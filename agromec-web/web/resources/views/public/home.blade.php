@extends('layouts.public')

@section('content')

{{-- ════════════════════════════════════════════════════════════════════════
     HERO — compacto, enfocado en la tienda
═════════════════════════════════════════════════════════════════════════ --}}
<section class="ag-hero" style="min-height: 38vh;">

    <img class="ag-hero-img"
         src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1800&q=85"
         alt="Campo de trigo al atardecer">
    <div class="ag-hero-overlay"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
        <div class="max-w-2xl">
            <span class="ag-badge ag-badge-white mb-4">
                <span class="inline-block w-1.5 h-1.5 rounded-full" style="background:var(--c-green);"></span>
                Smart Farming · Plataforma B2B
            </span>

            <h1 class="font-serif font-semibold leading-tight text-white"
                style="font-size: clamp(2rem, 4.5vw, 3.5rem);">
                El mejor catálogo agrícola, <em style="color:#6EE7B7;">en línea.</em>
            </h1>

            <p class="mt-4 text-base leading-relaxed max-w-lg"
               style="color:rgba(255,255,255,.72);">
                Explora productos, agrega al carrito y genera tus pedidos directamente desde aquí.
            </p>

            <div class="mt-7 flex flex-wrap gap-3">
                @if(session('firebase.uid'))
                    <a href="{{ route('portal.pedidos') }}" class="btn btn-white">
                        Mis pedidos
                    </a>
                @else
                    <a href="{{ route('auth.client.show') }}" class="btn btn-white">
                        Iniciar sesión
                    </a>
                    <a href="{{ route('auth.client.register.show') }}" class="btn btn-outline-white">
                        Crear cuenta
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>


{{-- ════════════════════════════════════════════════════════════════════════
     TRUST BAR
═════════════════════════════════════════════════════════════════════════ --}}
<div class="ag-trust-bar">
    <div class="mx-auto max-w-7xl px-5 py-3 lg:px-8">
        <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-2">
            @foreach([
                ['🌿', 'Catálogo actualizado en tiempo real'],
                ['🔒', 'Acceso seguro por roles'],
                ['📦', 'Seguimiento de pedidos completo'],
                ['📊', 'Reportes y estadísticas'],
            ] as [$icon, $text])
            <span class="flex items-center gap-2 text-xs font-medium">
                <span>{{ $icon }}</span>
                <span>{{ $text }}</span>
            </span>
            @endforeach
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════════════════════════════════
     TIENDA — sidemenu + productos
═════════════════════════════════════════════════════════════════════════ --}}
<div class="mx-auto max-w-7xl px-5 py-10 lg:px-8">
    <div class="shop-layout">

        {{-- Side menu reutilizable --}}
        <x-catalog-side-menu :categorias="$categorias" :counts="$categoriaCounts" :action="route('home')" />

        {{-- Productos --}}
        <div class="shop-content">
            <div class="mb-6 flex items-end justify-between">
                <div>
                    <p class="ag-label mb-1">Nuestro catálogo</p>
                    <h2 class="font-serif text-3xl font-semibold" style="color:var(--c-text);">
                        Todos los productos
                    </h2>

                    @if(request('categoria') || request('q'))
                        <div class="mt-3 flex flex-wrap items-center gap-2 text-sm" style="color:var(--c-text-3);">
                            <span>Mostrando:</span>
                            @if(request('q'))
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-0.5 text-xs font-semibold"
                                      style="background:var(--c-bg-2); color:var(--c-text-2); border:1px solid var(--c-border);">
                                    "{{ request('q') }}"
                                </span>
                            @endif
                            @if(request('categoria'))
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-0.5 text-xs font-semibold"
                                      style="background:var(--c-green-tint); color:var(--c-green-dark);">
                                    {{ request('categoria') }}
                                </span>
                            @endif
                            <span>· {{ count($productos) }} resultado{{ count($productos) !== 1 ? 's' : '' }}</span>
                        </div>
                    @else
                        <p class="mt-1 text-sm" style="color:var(--c-text-3);">
                            {{ count($productos) }} producto{{ count($productos) !== 1 ? 's' : '' }} disponibles
                        </p>
                    @endif
                </div>
            </div>

            @if(count($productos) === 0)
                <div class="flex flex-col items-center justify-center rounded-2xl border py-20 text-center"
                     style="border-color:var(--c-border); background:var(--c-bg-2);">
                    <span class="text-5xl mb-4">🔍</span>
                    <h3 class="text-lg font-semibold" style="color:var(--c-text);">Sin resultados</h3>
                    <p class="mt-1 text-sm" style="color:var(--c-text-3);">No hay productos para los filtros seleccionados.</p>
                    <a href="{{ route('home') }}"
                       class="mt-5 rounded-full px-5 py-2 text-sm font-semibold text-white"
                       style="background:var(--c-green);">
                        Ver todos los productos
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
                    @foreach($productos as $prod)
                        <x-product-card :producto="$prod" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════════════════════════════════
     CTA FINAL
═════════════════════════════════════════════════════════════════════════ --}}
@guest
<section class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
    <div class="relative overflow-hidden rounded-3xl" style="background:var(--c-amber);">
        <div class="absolute inset-y-0 right-0 w-2/5 hidden md:block overflow-hidden">
            <img src="https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?w=800&q=80"
                 alt="" class="w-full h-full object-cover opacity-50">
            <div class="absolute inset-0"
                 style="background: linear-gradient(to right, var(--c-amber) 0%, transparent 60%);"></div>
        </div>

        <div class="relative z-10 px-10 py-14 md:px-14 max-w-xl">
            <p class="ag-label" style="color:rgba(0,0,0,.45); letter-spacing:.12em;">Portal de clientes</p>
            <h2 class="font-serif text-3xl md:text-4xl font-semibold mt-3 leading-tight" style="color:var(--c-text);">
                ¿Listo para generar<br>tu pedido?
            </h2>
            <p class="mt-4 text-base leading-relaxed" style="color:rgba(15,14,12,.6);">
                Crea tu cuenta o inicia sesión para confirmar pedidos y seguir su estado en tiempo real.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('auth.client.register.show') }}" class="btn btn-dark">
                    Crear cuenta gratis
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="{{ route('auth.client.show') }}" class="btn btn-outline">Ya tengo cuenta</a>
            </div>
        </div>
    </div>
</section>
@endguest

@endsection
