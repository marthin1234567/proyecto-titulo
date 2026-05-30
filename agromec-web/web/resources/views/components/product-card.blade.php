@props(['producto'])

@php
    $id     = $producto['id'] ?? '';
    $nombre = $producto['nombre'] ?? 'Producto';
    $precio = (float) ($producto['precioUnitario'] ?? 0);
    $cat    = $producto['categoria'] ?? '';
    $desc   = $producto['descripcion'] ?? '';
    $img    = $producto['imagenUrl'] ?? '';
@endphp

<div class="ag-product group flex flex-col">

    {{-- Imagen (link a detalle) --}}
    <a href="{{ route('producto.detalle', $id) }}" class="block ag-product-thumb" tabindex="-1" aria-hidden="true">
        @if($img)
            <img src="{{ $img }}" alt="{{ $nombre }}" loading="lazy"
                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-4xl\' style=\'background:var(--c-bg-2)\'>🌿</div>'">
        @else
            <div class="w-full h-full flex items-center justify-center text-4xl select-none"
                 style="background:var(--c-bg-2);">🌿</div>
        @endif
    </a>

    {{-- Info --}}
    <div class="p-4 flex flex-col flex-1">
        <span class="ag-badge ag-badge-green text-[10px]">{{ $cat }}</span>

        <a href="{{ route('producto.detalle', $id) }}"
           class="mt-2 text-sm font-semibold leading-snug line-clamp-2 flex-1 hover:underline"
           style="color:var(--c-text); text-decoration-color:var(--c-border-2);">
            {{ $nombre }}
        </a>

        @if($desc)
            <p class="mt-1 text-xs leading-relaxed line-clamp-2" style="color:var(--c-text-3);">
                {{ $desc }}
            </p>
        @endif

        {{-- Precio + botón agregar --}}
        <div class="mt-3 pt-3" style="border-top:1px solid var(--c-border);">
            <p class="text-lg font-bold mb-2" style="color:var(--c-text);">
                ${{ number_format($precio, 0, ',', '.') }}
                <span class="text-xs font-normal" style="color:var(--c-text-3);">/u</span>
            </p>
            <button
                type="button"
                onclick="window.agAddToCart('{{ $id }}')"
                class="w-full flex items-center justify-center gap-2 rounded-xl py-2 text-sm font-semibold transition-all"
                style="background:var(--c-green-tint); color:var(--c-green-dark); border:1.5px solid transparent;"
                onmouseover="this.style.background='var(--c-green)'; this.style.color='#fff'; this.style.borderColor='var(--c-green)';"
                onmouseout="this.style.background='var(--c-green-tint)'; this.style.color='var(--c-green-dark)'; this.style.borderColor='transparent';">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.4 7h11.4M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/>
                </svg>
                Agregar al carrito
            </button>
        </div>
    </div>

</div>
