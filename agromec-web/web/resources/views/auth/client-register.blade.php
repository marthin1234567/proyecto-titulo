@extends('layouts.public')

@section('content')
<div class="flex min-h-[calc(100vh-70px)]">

    {{-- Left: form --}}
    <div class="flex flex-1 flex-col justify-center px-6 py-16 md:px-16 lg:flex-none lg:w-[480px]">
        <div class="mx-auto w-full max-w-sm">

            <div class="mb-10">
                <h1 class="text-2xl font-semibold font-serif" style="color:var(--c-text);">Crea tu cuenta</h1>
                <p class="mt-1 text-sm" style="color:var(--c-text-3);">Accede al portal para gestionar pedidos y cotizaciones</p>
            </div>

            @if ($errors->any())
                <div class="ad-alert-error mb-5">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('auth.client.register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="ad-label" for="nombre">Nombre</label>
                    <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}"
                           class="ad-input" placeholder="Tu nombre" required autofocus>
                </div>

                <div>
                    <label class="ad-label" for="email">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="ad-input" placeholder="tu@correo.com" required>
                </div>

                <div>
                    <label class="ad-label" for="password">Contraseña</label>
                    <input id="password" type="password" name="password"
                           class="ad-input" placeholder="Mínimo 6 caracteres" required>
                </div>

                <button type="submit" class="ad-btn-primary w-full mt-2 text-center">
                    Crear cuenta
                </button>
            </form>

            {{-- Divisor --}}
            <div class="my-5 flex items-center gap-3">
                <div class="flex-1 h-px" style="background:var(--c-border);"></div>
                <span class="text-xs font-medium" style="color:var(--c-text-3);">o regístrate con</span>
                <div class="flex-1 h-px" style="background:var(--c-border);"></div>
            </div>

            {{-- Botón Google --}}
            <a href="{{ route('auth.google') }}"
               class="flex w-full items-center justify-center gap-3 rounded-xl border px-4 py-2.5 text-sm font-semibold transition-all hover:bg-gray-50 active:scale-95"
               style="border-color:var(--c-border-2); color:var(--c-text); background:white;">
                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continuar con Google
            </a>

            <p class="mt-5 text-center text-xs" style="color:var(--c-text-3);">
                ¿Ya tienes cuenta?
                <a href="{{ route('auth.client.show') }}" class="font-semibold hover:underline" style="color:var(--c-green);">
                    Inicia sesión
                </a>
            </p>
        </div>
    </div>

    {{-- Right: decorative --}}
    <div class="relative hidden flex-1 lg:block">
        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1400&q=85"
             alt="Campo de trigo" class="absolute inset-0 h-full w-full object-cover object-center">
        <div class="absolute inset-0" style="background:linear-gradient(to right, rgba(255,255,255,0.7), transparent 50%);"></div>
    </div>

</div>
@endsection

