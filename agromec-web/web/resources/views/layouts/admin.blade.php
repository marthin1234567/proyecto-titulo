<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Backoffice — AgroMec Smart' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;0,600;0,700;1,500&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased" style="background:var(--c-bg-2); color:var(--c-text);" x-data="{ sidebarOpen: false }">

<div class="flex h-screen overflow-hidden">

    {{-- ── SIDEBAR ────────────────────────────────────────────────────── --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 flex w-60 flex-col transition-transform duration-200 md:static md:translate-x-0"
        style="background:#fff; border-right:1px solid var(--c-border); box-shadow:2px 0 20px rgba(15,14,12,.06);">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-4" style="border-bottom:1px solid var(--c-border);">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl text-white font-bold shrink-0"
                 style="background:linear-gradient(135deg,var(--c-green),var(--c-green-dark)); font-family:var(--font-serif); font-style:italic; font-size:1.1rem; letter-spacing:-.02em;">A</div>
            <div>
                <a href="{{ route('admin.dashboard') }}"
                   class="text-sm font-bold tracking-tight leading-none" style="color:var(--c-text);">
                    Agro<span style="color:var(--c-green); font-style:italic; font-family:var(--font-serif);">Mec</span>
                </a>
                <p class="text-[10px] font-medium mt-0.5" style="color:var(--c-text-3); letter-spacing:.06em; text-transform:uppercase;">Backoffice</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-3">
            @php
                $current  = request()->route()->getName();
                $userRole = session('firebase.role', '');

                $sections = [
                    '' => [
                        ['admin.dashboard', 'admin.dashboard', 'Dashboard', '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>', ['admin','compras','cotizaciones']],
                    ],
                    'Gestión' => [
                        ['admin.usuarios',     'admin.usuarios',     'Usuarios',          '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',  ['admin']],
                        ['admin.productos',    'admin.productos',    'Productos',         '<path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>',   ['admin','compras']],
                        ['admin.pedidos',      'admin.pedidos',      'Pedidos',           '<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',   ['admin','cotizaciones']],
                        ['admin.cotizaciones', 'admin.cotizaciones', 'Cotizaciones',      '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',   ['admin','cotizaciones']],
                        ['admin.ordenes',      'admin.ordenes',      'Órdenes de Compra', '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',  ['admin']],
                    ],
                    'Empresas' => [
                        ['admin.proveedores',  'admin.proveedores',  'Proveedores',       '<path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',   ['admin','compras']],
                        ['admin.clientes',     'admin.clientes',     'Clientes',          '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',   ['admin','compras']],
                    ],
                    'Análisis' => [
                        ['admin.reportes',     'admin.reportes',     'Reportes',          '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',   ['admin','compras']],
                        ['admin.configuracion','admin.configuracion','Configuración',     '<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',  ['admin','compras']],
                    ],
                ];
            @endphp

            @foreach($sections as $sectionLabel => $items)
                @php
                    $visibleItems = array_filter($items, fn($item) => in_array($userRole, $item[4], true));
                @endphp
                @if(count($visibleItems))
                    @if($sectionLabel !== '')
                        <span class="ad-nav-label">{{ $sectionLabel }}</span>
                    @endif
                    @foreach($visibleItems as [$route, $routeName, $label, $svgPath, $allowedRoles])
                    <a href="{{ route($route) }}"
                       class="ad-nav-item {{ str_starts_with($current ?? '', $routeName) ? 'active' : '' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            {!! $svgPath !!}
                        </svg>
                        <span>{{ $label }}</span>
                    </a>
                    @endforeach
                @endif
            @endforeach
        </nav>

        {{-- Footer: usuario + logout --}}
        <div class="px-3 py-4 space-y-2" style="border-top:1px solid var(--c-border);">
            @php
                $adminNombre = session('firebase.nombre', session('firebase.email', 'Admin'));
                $adminEmail  = session('firebase.email', '');
                $adminRol    = session('firebase.role', 'admin');
                $adminInitial = strtoupper(mb_substr($adminNombre, 0, 1));
            @endphp
            <div class="px-3 py-2.5 rounded-xl flex items-center gap-2.5" style="background:var(--c-bg-2);">
                <div class="h-8 w-8 flex-shrink-0 rounded-full flex items-center justify-center text-xs font-bold text-white"
                     style="background:linear-gradient(135deg,var(--c-green),var(--c-green-dark));">
                    {{ $adminInitial }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold truncate" style="color:var(--c-text);">{{ $adminNombre }}</p>
                    <p class="text-[10px] truncate" style="color:var(--c-text-3);">{{ $adminEmail }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('auth.admin.logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors hover:bg-red-50"
                        style="color:#dc2626;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- ── MOBILE OVERLAY ──────────────────────────────────────────────── --}}
    <div x-show="sidebarOpen" @click="sidebarOpen=false"
         class="fixed inset-0 z-40 bg-black/30 md:hidden" style="display:none;"></div>

    {{-- ── MAIN AREA ───────────────────────────────────────────────────── --}}
    <div class="flex flex-1 flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="flex shrink-0 items-center justify-between px-5 py-3 md:px-7"
                style="background:#fff; border-bottom:1px solid var(--c-border);">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen=!sidebarOpen"
                        class="inline-flex items-center justify-center rounded-lg p-1.5 transition-colors hover:bg-black/5 md:hidden"
                        style="color:var(--c-text-2);">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                {{-- Page title via @section --}}
                <div>
                    @hasSection('page-title')
                        <h1 class="text-base font-semibold" style="color:var(--c-text);">@yield('page-title')</h1>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="ad-badge ad-badge-green text-[10px]">
                    {{ session('firebase.role', 'admin') }}
                </span>
                <a href="{{ route('home') }}"
                   class="text-xs font-medium transition-colors hover:underline"
                   style="color:var(--c-text-3);" target="_blank">
                    Ver sitio →
                </a>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto px-5 py-6 md:px-7 md:py-7">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
