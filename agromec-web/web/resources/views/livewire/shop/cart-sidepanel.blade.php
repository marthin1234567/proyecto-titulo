<div
    x-data="{ open: false }"
    @cart-open.window="open = true"
    @add-to-cart.window="$wire.onCartAdd($event.detail.id); open = true"
    class="fixed bottom-5 right-5 z-[60]"
>
    {{-- Floating button --}}
    <button
        type="button"
        class="relative inline-flex h-12 w-12 items-center justify-center rounded-full shadow-lg transition-all"
        style="background:var(--c-green); color:white;"
        @click="open = true"
        aria-label="Abrir carrito"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>

        @if($this->count > 0)
            <span
                class="absolute -top-2 -right-2 min-w-6 rounded-full px-1.5 py-0.5 text-[11px] font-bold text-white text-center"
                style="background:rgba(15, 23, 42, .95);"
            >
                {{ $this->count }}
            </span>
        @endif
    </button>

    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-[59]"
        style="background:rgba(0,0,0,.35);"
        @click="open = false"
    ></div>

    {{-- Panel --}}
    <aside
        x-show="open"
        x-transition:enter="transition transform ease-out duration-200"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed right-0 top-0 z-[60] h-full w-full max-w-md"
        style="background:var(--c-bg); border-left:1px solid var(--c-border);"
        @keydown.escape.window="open = false"
    >
        <div class="flex h-full flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid var(--c-border);">
                <div class="flex items-center gap-3">
                    @if($confirming)
                        <button wire:click="cancelConfirm" class="h-8 w-8 rounded-full border flex items-center justify-center"
                                style="border-color:var(--c-border-2); color:var(--c-text-2);">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                    @endif
                    <div>
                        <h2 class="text-lg font-semibold" style="color:var(--c-text);">
                            {{ $confirming ? 'Confirmar pedido' : 'Carrito' }}
                        </h2>
                        <p class="text-xs" style="color:var(--c-text-3);">
                            {{ $confirming ? 'Revisa tu pedido antes de confirmar' : 'Agrega productos y genera tu pedido' }}
                        </p>
                    </div>
                </div>
                <button type="button" class="h-9 w-9 rounded-full border"
                        style="border-color:var(--c-border-2); color:var(--c-text-2);"
                        @click="open = false" aria-label="Cerrar">
                    ✕
                </button>
            </div>

            {{-- Error / info --}}
            @if ($message !== '')
                <div class="px-5 pt-4">
                    <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        {{ $message }}
                    </div>
                </div>
            @endif

            {{-- ====== VISTA: CARRITO ====== --}}
            @if (!$confirming)

                <div class="flex-1 overflow-auto px-5 py-4 space-y-3">
                    @if (count($items) === 0)
                        <div class="rounded-2xl p-8 text-center" style="background:var(--c-bg-2); border:1px solid var(--c-border);">
                            <h3 class="text-base font-semibold" style="color:var(--c-text);">Tu carrito está vacío</h3>
                            <p class="mt-1 text-sm" style="color:var(--c-text-3);">Agrega productos desde el catálogo.</p>
                            <a href="{{ route('catalogo') }}" class="mt-4 inline-flex rounded-full px-4 py-2 text-sm font-semibold text-white"
                               style="background:var(--c-green);"
                               @click="open = false">
                                Ir al catálogo
                            </a>
                        </div>
                    @else
                        @foreach($items as $item)
                            <article class="rounded-2xl p-4 flex items-center justify-between gap-3"
                                     style="background:var(--c-bg-2); border:1px solid var(--c-border);">
                                <div class="min-w-0">
                                    <p class="font-semibold truncate" style="color:var(--c-text);">
                                        {{ $item['productoNombre'] ?? 'Producto' }}
                                    </p>
                                    <p class="text-xs" style="color:var(--c-text-3);">
                                        ${{ number_format((float) ($item['precioUnitario'] ?? 0), 2) }} c/u
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button wire:click="decrement('{{ $item['productoId'] }}')" class="h-8 w-8 rounded-full border"
                                            style="border-color:var(--c-border-2);">-</button>
                                    <span class="min-w-8 text-center text-sm" style="color:var(--c-text);">
                                        {{ (int) ($item['cantidad'] ?? 1) }}
                                    </span>
                                    <button wire:click="increment('{{ $item['productoId'] }}')" class="h-8 w-8 rounded-full border"
                                            style="border-color:var(--c-border-2);">+</button>
                                </div>

                                <div class="text-right">
                                    <p class="text-sm font-semibold" style="color:var(--c-text);">
                                        ${{ number_format((float) ($item['subtotal'] ?? 0), 2) }}
                                    </p>
                                    <button wire:click="remove('{{ $item['productoId'] }}')" class="text-xs" style="color:#e11d48;">
                                        Eliminar
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    @endif
                </div>

                <div class="px-5 py-4" style="border-top:1px solid var(--c-border); background:var(--c-bg);">
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-xs" style="color:var(--c-text-3);">Total</p>
                            <p class="text-2xl font-bold" style="color:var(--c-text);">
                                ${{ number_format($this->total, 2) }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                type="button"
                                wire:click="clear"
                                class="rounded-full border px-4 py-2 text-sm font-semibold"
                                style="border-color:var(--c-border-2); color:var(--c-text-2);"
                            >
                                Vaciar
                            </button>
                            <button
                                type="button"
                                wire:click="startCheckout"
                                class="rounded-full px-4 py-2 text-sm font-semibold text-white"
                                style="background:var(--c-green);"
                            >
                                Continuar
                            </button>
                        </div>
                    </div>

                    <p class="mt-2 text-[11px]" style="color:var(--c-text-3);">
                        Si no has iniciado sesión, te pediremos ingresar antes de generar el pedido.
                    </p>
                </div>

            {{-- ====== VISTA: CONFIRMACIÓN ====== --}}
            @else

                <div class="flex-1 overflow-auto px-5 py-4 space-y-4">

                    {{-- Resumen items --}}
                    <div class="rounded-2xl overflow-hidden" style="border:1px solid var(--c-border);">
                        @foreach($items as $index => $item)
                            <div class="flex items-center gap-3 px-4 py-3 {{ $index > 0 ? '' : '' }}"
                                 style="{{ $index > 0 ? 'border-top:1px solid var(--c-border);' : '' }} background:var(--c-bg-2);">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold truncate" style="color:var(--c-text);">
                                        {{ $item['productoNombre'] ?? 'Producto' }}
                                    </p>
                                    <p class="text-xs" style="color:var(--c-text-3);">
                                        {{ (int) ($item['cantidad'] ?? 1) }} unid. × ${{ number_format((float) ($item['precioUnitario'] ?? 0), 2) }}
                                    </p>
                                </div>
                                <p class="text-sm font-semibold flex-shrink-0" style="color:var(--c-text);">
                                    ${{ number_format((float) ($item['subtotal'] ?? 0), 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-between items-center rounded-xl px-4 py-3"
                         style="background:var(--c-bg-2); border:1px solid var(--c-border);">
                        <span class="text-sm font-semibold" style="color:var(--c-text-2);">Total del pedido</span>
                        <span class="text-xl font-bold" style="color:var(--c-text);">${{ number_format($this->total, 2) }}</span>
                    </div>

                    {{-- Nota opcional --}}
                    <div>
                        <label class="ag-label block mb-1">Nota para el pedido <span class="font-normal opacity-60">(opcional)</span></label>
                        <textarea
                            wire:model="nota"
                            rows="3"
                            placeholder="Ej: entregar en turno mañana, consultar disponibilidad..."
                            class="ad-input w-full resize-none rounded-xl text-sm"
                            style="min-height:72px;"
                        ></textarea>
                    </div>

                    {{-- Info estado --}}
                    <div class="rounded-xl p-3 text-xs" style="background:#fef9c3; border:1px solid #fde68a; color:#854d0e;">
                        Al confirmar, tu pedido quedará en estado <strong>Pendiente</strong> y un representante se pondrá en contacto contigo.
                    </div>
                </div>

                <div class="px-5 py-4 flex gap-3" style="border-top:1px solid var(--c-border); background:var(--c-bg);">
                    <button
                        type="button"
                        wire:click="cancelConfirm"
                        class="flex-1 rounded-full border py-3 text-sm font-semibold"
                        style="border-color:var(--c-border-2); color:var(--c-text-2);"
                    >
                        Volver
                    </button>
                    <button
                        type="button"
                        wire:click="placeOrder"
                        class="flex-1 rounded-full py-3 text-sm font-bold text-white"
                        style="background:var(--c-green);"
                    >
                        Confirmar pedido
                    </button>
                </div>

            @endif

        </div>
    </aside>
</div>
