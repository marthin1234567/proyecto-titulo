@extends('layouts.public')

@section('content')

<div class="mx-auto max-w-7xl px-5 py-10 lg:px-8">
    <div class="shop-layout">

        {{-- Side menu (izquierda) reutilizable --}}
        <x-catalog-side-menu :categorias="$categorias" :counts="$categoriaCounts ?? []" :action="route('catalogo')" />

        <div class="shop-content">
            <div class="mb-6">
                <p class="ag-label mb-1">Nuestro catálogo</p>
                <h1 class="font-serif text-3xl font-semibold" style="color:var(--c-text);">
                    Productos AgroMec
                </h1>

                {{-- Active filter badge --}}
                @if(request('categoria') || request('q'))
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-sm" style="color:var(--c-text-3);">
                        <span>Mostrando:</span>
                        @if(request('q'))
                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-0.5 text-xs font-semibold"
                                  style="background:var(--c-bg-2); color:var(--c-text-2); border:1px solid var(--c-border);">
                                “{{ request('q') }}”
                            </span>
                        @endif
                        @if(request('categoria'))
                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-0.5 text-xs font-semibold"
                                  style="background:var(--c-green-tint); color:var(--c-green-dark);">
                                {{ request('categoria') }}
                            </span>
                        @endif
                        <span>· {{ count($items) }} resultado{{ count($items) !== 1 ? 's' : '' }}</span>
                    </div>
                @endif
            </div>

            @if(count($items) === 0)
                <div class="flex flex-col items-center justify-center rounded-2xl border py-20 text-center"
                     style="border-color:var(--c-border); background:var(--c-bg-2);">
                    <span class="text-5xl mb-4">🔍</span>
                    <h2 class="text-lg font-semibold" style="color:var(--c-text);">Sin resultados</h2>
                    <p class="mt-1 text-sm" style="color:var(--c-text-3);">No hay productos para los filtros seleccionados.</p>
                    <a href="{{ route('catalogo') }}"
                       class="mt-5 rounded-full px-5 py-2 text-sm font-semibold text-white"
                       style="background:var(--c-green);">
                        Ver todos los productos
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($items as $item)
                        <x-product-card :producto="$item" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
