<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Backoffice — AgroMec Smart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;0,600;0,700;1,500&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex" style="background:var(--c-bg-2);">

<div class="flex w-full min-h-screen">

    {{-- Left: form --}}
    <div class="flex flex-1 flex-col justify-center px-6 py-12 md:px-16 lg:flex-none lg:w-[480px]">
        <div class="mx-auto w-full max-w-sm">

            {{-- Logo --}}
            <div class="mb-10">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 text-sm font-bold"
                   style="color:var(--c-text);">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg text-white font-bold text-sm"
                         style="background:var(--c-green);">A</div>
                    Agro<span style="color:var(--c-green); font-style:italic; font-family:var(--font-serif);">Mec</span> Smart
                </a>
                <p class="mt-1 text-xs" style="color:var(--c-text-3);">Panel de Administración</p>
            </div>

            <h1 class="text-2xl font-semibold font-serif mb-1" style="color:var(--c-text);">Bienvenido de vuelta</h1>
            <p class="text-sm mb-8" style="color:var(--c-text-3);">Acceso exclusivo para personal AgroMec</p>

            @if ($errors->any())
                <div class="ad-alert-error mb-5">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="ad-label" for="email">Correo corporativo</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="ad-input" placeholder="nombre@agromec.com" required autofocus>
                </div>

                <div>
                    <label class="ad-label" for="password">Contraseña</label>
                    <input id="password" type="password" name="password"
                           class="ad-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="ad-btn-primary w-full mt-2 text-center">
                    Ingresar al backoffice
                </button>
            </form>

            <p class="mt-8 text-center text-xs" style="color:var(--c-text-3);">
                ¿Eres cliente?
                <a href="{{ route('auth.client.show') }}" class="font-semibold hover:underline" style="color:var(--c-green);">
                    Iniciar sesión aquí
                </a>
            </p>
        </div>
    </div>

    {{-- Right: field photo --}}
    <div class="relative hidden flex-1 lg:block">
        <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=1400&q=85"
             alt="Campo agrícola" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(to right, rgba(10,52,38,.5), rgba(10,52,38,.2));"></div>

        {{-- Quote overlay --}}
        <div class="absolute bottom-12 left-10 right-10 text-white">
            <p class="font-serif text-2xl font-medium italic leading-snug">"La tecnología al servicio del campo."</p>
            <p class="mt-2 text-sm opacity-70">AgroMec Smart — Plataforma de gestión agrícola</p>
        </div>
    </div>

</div>
</body>
</html>
