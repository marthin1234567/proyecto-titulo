@extends('layouts.public')

@section('content')
<div class="mx-auto max-w-7xl px-5 py-10 lg:px-8">

    <div class="shop-layout">

        {{-- Side menu: búsqueda + filtros (siempre visible en desktop) --}}
        <x-catalog-side-menu :categorias="($categorias ?? [])" :counts="($categoriaCounts ?? [])" :action="route('home')" />

        <div class="shop-content">
            {{-- Breadcrumb --}}
            <nav class="mb-6 flex items-center gap-2 text-xs" style="color:var(--c-text-3);">
                <a href="{{ route('home') }}" class="hover:underline">Inicio</a>
                <span>/</span>
                <a href="{{ route('catalogo') }}" class="hover:underline">Catálogo</a>
                <span>/</span>
                <span style="color:var(--c-text);">{{ $producto['nombre'] }}</span>
            </nav>

            <div class="grid gap-8 md:grid-cols-2">

                {{-- Imagen --}}
                <div class="overflow-hidden rounded-2xl border" style="border-color:var(--c-border); background:var(--c-bg-2); aspect-ratio:1/1;">
                    @if(!empty($producto['imagenUrl']))
                        <img src="{{ $producto['imagenUrl'] }}"
                             alt="{{ $producto['nombre'] }}"
                             class="w-full h-full object-cover"
                             onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-6xl\'>🌿</div>'">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-6xl">🌿</div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex flex-col justify-center">
                    <span class="ag-badge ag-badge-green mb-3">{{ $producto['categoria'] ?? '' }}</span>

                    <h1 class="font-serif text-3xl font-semibold leading-snug" style="color:var(--c-text);">
                        {{ $producto['nombre'] }}
                    </h1>

                    <p class="mt-4 leading-relaxed" style="color:var(--c-text-2); font-size:.95rem;">
                        {{ $producto['descripcion'] ?? 'Sin descripción disponible.' }}
                    </p>

                    <div class="my-6 flex items-baseline gap-1">
                        <span class="font-serif text-4xl font-semibold" style="color:var(--c-text);">
                            ${{ number_format((float)($producto['precioUnitario'] ?? 0), 0, ',', '.') }}
                        </span>
                        <span class="text-sm" style="color:var(--c-text-3);">/ unidad</span>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('shop.carrito', ['add' => $producto['id']]) }}"
                           onclick="event.preventDefault(); window.agAddToCart('{{ $producto['id'] }}')"
                           class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Agregar al carrito
                        </a>
                        <a href="{{ route('catalogo') }}"
                           class="btn btn-outline">
                            Ver más productos
                        </a>
                    </div>

                    @if(!empty($producto['proveedorId']))
                    <p class="mt-6 text-xs" style="color:var(--c-text-3);">
                        Proveedor ID: <span class="font-mono">{{ $producto['proveedorId'] }}</span>
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
