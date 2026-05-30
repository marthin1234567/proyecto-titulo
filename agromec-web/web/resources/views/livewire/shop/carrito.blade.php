<section class="space-y-4">
    <div class="ag-card rounded-2xl p-6">
        <h1 class="text-2xl font-bold">Carrito</h1>
        <p class="text-sm text-slate-600">Gestiona tus productos y confirma tu pedido.</p>
    </div>

    @if ($message !== '')
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ $message }}</div>
    @endif

    @if (count($items) === 0)
        <div class="ag-card rounded-2xl p-10 text-center">
            <h2 class="text-lg font-semibold">Tu carrito esta vacio</h2>
            <a href="{{ route('catalogo') }}" class="mt-4 inline-block rounded-full ag-btn-primary px-4 py-2 text-sm font-semibold">Explorar catalogo</a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($items as $item)
                <article class="ag-card flex flex-wrap items-center justify-between gap-3 rounded-2xl p-4">
                    <div>
                        <h3 class="font-semibold">{{ $item['productoNombre'] }}</h3>
                        <p class="text-sm text-slate-600">${{ number_format((float) ($item['precioUnitario'] ?? 0), 2) }} c/u</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="decrement('{{ $item['productoId'] }}')" class="h-8 w-8 rounded-full border">-</button>
                        <span class="min-w-8 text-center">{{ $item['cantidad'] }}</span>
                        <button wire:click="increment('{{ $item['productoId'] }}')" class="h-8 w-8 rounded-full border">+</button>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">${{ number_format((float) ($item['subtotal'] ?? 0), 2) }}</p>
                        <button wire:click="remove('{{ $item['productoId'] }}')" class="text-sm text-rose-600">Eliminar</button>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="ag-card rounded-2xl p-4 text-right">
            <p class="text-sm text-slate-600">Total</p>
            <p class="text-2xl font-bold" style="color: var(--ag-accent-strong);">${{ number_format($this->total, 2) }}</p>
            <div class="mt-3 flex justify-end gap-2">
                <button wire:click="clear" class="rounded-full border px-4 py-2 text-sm font-semibold">Vaciar</button>
                <button wire:click="checkout" class="rounded-full ag-btn-primary px-4 py-2 text-sm font-semibold">Confirmar pedido</button>
            </div>
        </div>
    @endif
</section>
