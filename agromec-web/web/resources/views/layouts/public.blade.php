<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'AgroMec Smart — Plataforma Agrícola' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;0,600;0,700;1,500;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">

    {{-- ── NAVBAR ─────────────────────────────────────────────────────── --}}
    <header class="ag-nav sticky top-0 z-50">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">

            {{-- Logo --}}
            <a href="{{ route('home') }}"
               class="text-[1.1rem] font-bold tracking-tight select-none flex items-center gap-0"
               style="font-family: var(--font-sans);">
                <span style="color:var(--c-text);">Agro</span><span style="color:var(--c-green); font-style:italic; font-family:var(--font-serif);">Mec</span><span style="color:var(--c-text);"> Smart</span>
            </a>

            {{-- Mobile hamburger --}}
            <button data-collapse-toggle="public-nav" type="button"
                    class="inline-flex items-center rounded-lg p-2 md:hidden"
                    style="color:var(--c-text-2);" aria-controls="public-nav">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Nav links --}}
            <div class="hidden md:flex items-center gap-1" id="public-nav">
                <a href="{{ route('home') }}"     class="px-4 py-2 text-sm font-medium rounded-full transition-colors hover:bg-black/5" style="color:var(--c-text-2);">Inicio</a>
                <a href="{{ route('catalogo') }}" class="px-4 py-2 text-sm font-medium rounded-full transition-colors hover:bg-black/5" style="color:var(--c-text-2);">Catálogo</a>
                <a href="{{ route('sobre') }}"    class="px-4 py-2 text-sm font-medium rounded-full transition-colors hover:bg-black/5" style="color:var(--c-text-2);">Sobre Nosotros</a>
                <a href="{{ route('contacto') }}" class="px-4 py-2 text-sm font-medium rounded-full transition-colors hover:bg-black/5" style="color:var(--c-text-2);">Contacto</a>

                <span class="ml-3 h-4 w-px" style="background:var(--c-border-2);"></span>

                @if(session('firebase.uid'))
                    <a href="{{ route('portal.pedidos') }}"
                       class="ml-2 btn btn-green text-sm py-[.55rem] px-5">
                        Mi portal
                    </a>
                @else
                    <a href="{{ route('auth.client.show') }}"
                       class="ml-2 btn btn-outline text-sm py-[.55rem] px-5">
                        Iniciar sesión
                    </a>
                @endif
            </div>
        </div>
    </header>

    {{-- ── CONTENT ─────────────────────────────────────────────────────── --}}
    @if (session('status'))
        <div class="mx-auto max-w-7xl px-5 pt-4 lg:px-8">
            <div class="rounded-xl px-4 py-3 text-sm font-medium"
                 style="background:var(--c-green-tint); color:var(--c-green-dark); border:1px solid rgba(29,158,117,.2);">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @yield('content')

    {{-- Carrito sidepanel global (debe ir antes de @livewireScripts) --}}
    <livewire:shop.cart-sidepanel />

    @livewireScripts
</body>
</html>
