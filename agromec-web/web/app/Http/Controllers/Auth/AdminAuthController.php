<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Firebase\FirebaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminAuthController extends Controller
{
    public function __construct(private readonly FirebaseAuthService $firebaseAuth)
    {
    }

    public function show()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        // Paso 1: verificar credenciales en Firebase Auth
        try {
            $tokenData = $this->firebaseAuth->signInWithEmailPassword(
                $credentials['email'],
                $credentials['password']
            );
        } catch (Throwable $e) {
            Log::warning('Admin login fallido (credenciales)', ['email' => $credentials['email'], 'error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'Correo o contraseña incorrectos.'])->onlyInput('email');
        }

        $uid = (string) ($tokenData['localId'] ?? '');

        // Paso 2: obtener perfil y verificar rol backoffice
        $profile = null;
        try {
            $profile = $this->firebaseAuth->getUserProfile($uid);
        } catch (Throwable $e) {
            Log::error('Error Firestore en admin login', ['uid' => $uid, 'error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'No se pudo verificar los permisos. Intenta nuevamente.'])->onlyInput('email');
        }

        $role = (string) ($profile['rol'] ?? '');

        if (! in_array($role, ['admin', 'compras', 'cotizaciones'], true)) {
            return back()->withErrors(['email' => 'No tienes permisos de backoffice.'])->onlyInput('email');
        }

        $request->session()->put('firebase', [
            'uid'      => $uid,
            'email'    => $credentials['email'],
            'nombre'   => (string) ($profile['nombre'] ?? $credentials['email']),
            'role'     => $role,
            'id_token' => $tokenData['idToken'] ?? null,
        ]);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('firebase');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'Sesion cerrada correctamente.');
    }
}
