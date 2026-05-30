<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFirebaseRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $role = (string) $request->session()->get('firebase.role', '');

        if ($role === '') {
            if ($request->is('admin/*')) {
                return redirect()->route('admin.login');
            }

            return redirect()->route('auth.client.show');
        }

        if ($role === 'admin') {
            return $next($request);
        }

        if (! in_array($role, $roles, true)) {
            if (in_array($role, ['admin', 'compras', 'cotizaciones'], true)) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        return $next($request);
    }
}
