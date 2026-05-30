<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Firebase\FirebaseAuthService;
use App\Services\Firebase\FirestoreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleClientAuthController extends Controller
{
    public function __construct(
        private readonly FirebaseAuthService $firebaseAuth,
        private readonly FirestoreRepository $repo,
    ) {}

    /**
     * Redirige al usuario a la pantalla de selección de cuenta Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Callback de Google: autentica o crea el usuario en Firebase/Firestore.
     */
    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            Log::warning('Google OAuth callback fallido', ['error' => $e->getMessage()]);
            return redirect()->route('auth.client.show')
                ->withErrors(['email' => 'No se pudo completar el inicio de sesión con Google.']);
        }

        $email  = (string) $googleUser->getEmail();
        $nombre = (string) ($googleUser->getName() ?: $email);

        // ── 1. Buscar o crear usuario en Firebase Auth ─────────────────────
        $uid = '';
        try {
            $existingUser = $this->firebaseAuth->getUserByEmail($email);

            if ($existingUser) {
                $uid = (string) $existingUser->uid;
            } else {
                $newUser = $this->firebaseAuth->createUser(
                    $email,
                    Str::random(32),
                    $nombre,
                );
                $uid = (string) $newUser->uid;
            }
        } catch (Throwable $e) {
            Log::error('Error Firebase Auth en Google login', ['email' => $email, 'error' => $e->getMessage()]);
            return redirect()->route('auth.client.show')
                ->withErrors(['email' => 'Error al conectar con el servicio de autenticación.']);
        }

        // ── 2. Asegurar perfil en Firestore ────────────────────────────────
        $role = 'cliente';
        try {
            $profile = $this->firebaseAuth->getUserProfile($uid);

            if (is_array($profile)) {
                $role   = (string) ($profile['rol']    ?? 'cliente');
                $nombre = (string) ($profile['nombre'] ?? $nombre);

                // Bloquear acceso de backoffice por este canal
                if (in_array($role, ['compras', 'cotizaciones'], true)) {
                    return redirect()->route('auth.client.show')
                        ->withErrors(['email' => 'Esta cuenta pertenece al backoffice. Usa el acceso de empleados.']);
                }
            } else {
                // Primera vez: crear perfil
                $this->repo->upsertUsuario($uid, [
                    'nombre' => $nombre,
                    'email'  => $email,
                    'rol'    => 'cliente',
                ]);
                $this->repo->upsertClienteByEmail([
                    'nombre'   => $nombre,
                    'empresa'  => '',
                    'email'    => $email,
                    'telefono' => '',
                    'direccion'=> '',
                ]);
            }
        } catch (Throwable $e) {
            Log::error('Error Firestore en Google login', ['uid' => $uid, 'error' => $e->getMessage()]);
            // Seguimos: perfil de Firestore no bloquea el login
        }

        // ── 3. Crear sesión ────────────────────────────────────────────────
        $request->session()->put('firebase', [
            'uid'      => $uid,
            'email'    => $email,
            'nombre'   => $nombre,
            'role'     => $role,
            'id_token' => null,
        ]);
        $request->session()->regenerate();

        // ── 4. Redirigir (con pedido pendiente si lo hay) ──────────────────
        $items   = (array) session('cart.items', []);
        $pending = (bool)  session('cart.checkout_pending', false);

        if ($pending && $items !== []) {
            try {
                $pedidoId = $this->repo->createPedido($uid, $email, $items);
                session()->forget('cart.checkout_pending');
                session(['cart.items' => []]);
                $this->repo->clearCart($uid);

                return redirect()->route('pedido.confirmado', ['id' => $pedidoId]);
            } catch (Throwable $e) {
                Log::error('Error creando pedido post Google login', ['error' => $e->getMessage()]);
                session()->forget('cart.checkout_pending');
            }
        }

        return redirect()->route('portal.pedidos')
            ->with('status', '¡Bienvenido! Has iniciado sesión con Google.');
    }
}
