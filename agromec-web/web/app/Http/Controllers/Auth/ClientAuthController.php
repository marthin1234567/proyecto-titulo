<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Firebase\FirebaseAuthService;
use App\Services\Firebase\FirestoreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ClientAuthController extends Controller
{
    public function __construct(
        private readonly FirebaseAuthService $firebaseAuth,
        private readonly FirestoreRepository $repo
    )
    {
    }

    public function show()
    {
        return view('auth.client-login');
    }

    public function showRegister()
    {
        return view('auth.client-register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        // Paso 1: verificar credenciales contra Firebase Auth (REST)
        try {
            $tokenData = $this->firebaseAuth->signInWithEmailPassword(
                $credentials['email'],
                $credentials['password']
            );
        } catch (Throwable $e) {
            Log::warning('Login fallido (credenciales)', ['email' => $credentials['email'], 'error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'Correo o contraseña incorrectos.'])->onlyInput('email');
        }

        $uid = (string) ($tokenData['localId'] ?? '');

        // Paso 2: obtener perfil desde Firestore (tolerante a fallos)
        $profile = null;
        $firestoreError = false;
        try {
            $profile = $this->firebaseAuth->getUserProfile($uid);
        } catch (Throwable $e) {
            Log::error('Error obteniendo perfil en login (Firestore)', ['uid' => $uid, 'error' => $e->getMessage()]);
            $firestoreError = true;
        }

        // Perfil no existe en Firestore (no es un error de servicio → ir a registro)
        if (! $firestoreError && ! is_array($profile)) {
            return redirect()->route('auth.client.register.show')
                ->withErrors(['email' => 'Tu cuenta no está registrada en el portal. Crea una cuenta para continuar.'])
                ->onlyInput('email');
        }

        $role   = (string) ($profile['rol']    ?? 'cliente');
        $nombre = (string) ($profile['nombre'] ?? $credentials['email']);

        // Usuarios de backoffice no pueden entrar por aquí
        if (in_array($role, ['compras', 'cotizaciones'], true)) {
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Este usuario pertenece al backoffice.']);
        }

        $request->session()->put('firebase', [
            'uid'      => $uid,
            'email'    => $credentials['email'],
            'nombre'   => $nombre,
            'role'     => $role,
            'id_token' => $tokenData['idToken'] ?? null,
        ]);
        $request->session()->regenerate();

        return $this->finalizeClientSessionAndRedirect($request, $uid, $credentials['email'], $tokenData['idToken'] ?? null);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:180'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        try {
            $user = $this->firebaseAuth->createUser($data['email'], $data['password'], $data['nombre']);

            $this->repo->upsertUsuario($user->uid, [
                'nombre' => $data['nombre'],
                'email' => $data['email'],
                'rol' => 'cliente',
            ]);

            $this->repo->upsertClienteByEmail([
                'nombre' => $data['nombre'],
                'empresa' => '',
                'email' => $data['email'],
                'telefono' => '',
                'direccion' => '',
            ]);

            $tokenData = $this->firebaseAuth->signInWithEmailPassword($data['email'], $data['password']);

            $request->session()->put('firebase', [
                'uid'    => (string) $user->uid,
                'email'  => $data['email'],
                'nombre' => $data['nombre'],
                'role'   => 'cliente',
                'id_token' => $tokenData['idToken'] ?? null,
            ]);
            $request->session()->regenerate();

            return $this->finalizeClientSessionAndRedirect($request, (string) $user->uid, $data['email'], $tokenData['idToken'] ?? null, 'Cuenta creada correctamente.');
        } catch (Throwable) {
            return back()
                ->withErrors(['email' => 'No se pudo crear la cuenta. Si el correo ya existe, intenta iniciar sesión.'])
                ->onlyInput('email');
        }
    }

    private function finalizeClientSessionAndRedirect(
        Request $request,
        string $uid,
        string $email,
        mixed $idToken = null,
        ?string $statusMessage = null
    ) {
        if ($statusMessage) {
            $request->session()->flash('status', $statusMessage);
        }

        $items = (array) session('cart.items', []);
        $pending = (bool) session('cart.checkout_pending', false);

        if ($pending && $uid !== '' && $items !== []) {
            try {
                $pedidoId = $this->repo->createPedido($uid, $email, $items);
                session()->forget('cart.checkout_pending');
                session(['cart.items' => []]);
                $this->repo->clearCart($uid);

                return redirect()->route('pedido.confirmado', ['id' => $pedidoId]);
            } catch (Throwable $exception) {
                Log::error('Error creando pedido post-login', ['error' => $exception->getMessage()]);
                session()->forget('cart.checkout_pending');
                $request->session()->flash('status', 'Ingresaste correctamente, pero no se pudo crear el pedido.');
            }
        }

        return redirect()->route('portal.pedidos');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('firebase');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Sesion cerrada correctamente.');
    }
}
