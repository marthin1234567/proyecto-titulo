<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Mi Portal — AgroMecSmart' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;0,600;0,700;1,500;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased" style="background:var(--c-bg-2);">

    {{-- ── NAVBAR ─────────────────────────────────────────────────────── --}}
    <header class="ag-nav sticky top-0 z-50">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-3.5 lg:px-8">
            <a href="{{ route('home') }}"
               class="text-[1.1rem] font-bold tracking-tight select-none flex items-center gap-0"
               style="font-family: var(--font-sans);">
                <span style="color:var(--c-text);">Agro</span><span style="color:var(--c-green); font-style:italic; font-family:var(--font-serif);">Mec</span><span style="color:var(--c-text);"> Smart</span>
            </a>

            <div class="flex items-center gap-1">
                <a href="{{ route('home') }}"     class="hidden md:inline px-4 py-2 text-sm font-medium rounded-full transition-colors hover:bg-black/5" style="color:var(--c-text-2);">Inicio</a>
                <a href="{{ route('catalogo') }}" class="hidden md:inline px-4 py-2 text-sm font-medium rounded-full transition-colors hover:bg-black/5" style="color:var(--c-text-2);">Catálogo</a>
                <span class="hidden md:block ml-2 h-4 w-px" style="background:var(--c-border-2);"></span>
                <a href="{{ route('portal.pedidos') }}"
                   class="ml-2 inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold text-white"
                   style="background:var(--c-green);">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Mi portal
                </a>
            </div>
        </div>
    </header>

    {{-- ── PORTAL LAYOUT ──────────────────────────────────────────────── --}}
    <div class="mx-auto max-w-7xl px-4 py-8 lg:px-8" style="min-height:calc(100vh - 62px);">
        <div class="portal-layout">

            {{-- ── SIDEBAR ──────────────────────────────────────────── --}}
            <aside class="portal-sidebar space-y-3">

                @php
                    $userName  = session('firebase.nombre', session('firebase.email', 'Usuario'));
                    $userEmail = session('firebase.email', '');
                    $initial   = strtoupper(mb_substr($userName, 0, 1));
                    $currentRoute = request()->route()?->getName() ?? '';
                @endphp

                {{-- Perfil compacto --}}
                <div class="rounded-2xl bg-white p-5" style="border:1px solid var(--c-border); box-shadow:0 1px 3px rgba(0,0,0,.06);">
                    <div class="flex items-center gap-3">
                        <div class="relative flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full text-base font-bold text-white select-none"
                                 style="background: linear-gradient(135deg, var(--c-green) 0%, var(--c-green-dark) 100%); box-shadow: 0 2px 8px rgba(29,158,117,.35);">
                                {{ $initial }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white"
                                  style="background:#22c55e;"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-sm leading-tight" style="color:var(--c-text);">{{ $userName }}</p>
                            <p class="truncate text-xs leading-snug mt-0.5" style="color:var(--c-text-3);">{{ $userEmail }}</p>
                        </div>
                    </div>
                </div>

                {{-- Navegación --}}
                <nav class="rounded-2xl bg-white overflow-hidden" style="border:1px solid var(--c-border); box-shadow:0 1px 3px rgba(0,0,0,.06);">
                    <p class="px-4 pt-3.5 pb-1 text-[10px] font-bold uppercase tracking-widest" style="color:var(--c-text-3);">
                        Mi cuenta
                    </p>

                    <a href="{{ route('portal.pedidos') }}"
                       class="portal-nav-link {{ str_starts_with($currentRoute, 'portal.pedidos') || str_starts_with($currentRoute, 'pedido.') ? 'portal-nav-link--active' : '' }}">
                        <span class="portal-nav-icon">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </span>
                        <span>Mis pedidos</span>
                    </a>

                    <a href="{{ route('portal.cuenta') }}"
                       class="portal-nav-link {{ $currentRoute === 'portal.cuenta' ? 'portal-nav-link--active' : '' }}">
                        <span class="portal-nav-icon">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <span>Mi perfil</span>
                    </a>

                    <div class="mx-4 my-2" style="height:1px; background:var(--c-border);"></div>

                    <form method="POST" action="{{ route('auth.client.logout') }}" class="px-3 pb-3">
                        @csrf
                        <button type="submit" class="portal-nav-link portal-nav-link--danger w-full rounded-xl text-left">
                            <span class="portal-nav-icon">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </span>
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                </nav>

                {{-- Acceso rápido al catálogo --}}
                <a href="{{ route('catalogo') }}"
                   class="flex items-center gap-3 rounded-2xl bg-white px-4 py-3.5 text-sm font-medium transition-all hover:-translate-y-0.5"
                   style="border:1px solid var(--c-border); color:var(--c-text-2); box-shadow:0 1px 3px rgba(0,0,0,.06);">
                    <svg class="h-4 w-4 flex-shrink-0" style="color:var(--c-green);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Ir al catálogo</span>
                    <svg class="ml-auto h-3.5 w-3.5 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

            </aside>

            {{-- ── MAIN CONTENT ─────────────────────────────────────── --}}
            <main class="portal-content min-w-0">

                @if (session('status'))
                    <div class="mb-5 flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-medium"
                         style="background:var(--c-green-tint); color:var(--c-green-dark); border:1px solid rgba(29,158,117,.2);">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}

            </main>

        </div>
    </div>

    <livewire:shop.cart-sidepanel />
    @livewireScripts
</body>
</html>
