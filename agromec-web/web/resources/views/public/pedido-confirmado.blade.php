@extends('layouts.public')

@section('title', 'Pedido confirmado — AgroMecSmart')

@section('content')
<div class="min-h-[calc(100vh-70px)] py-12 px-4" style="background:var(--c-bg-2);">
<div class="mx-auto max-w-2xl space-y-6">

    {{-- Hero de confirmación --}}
    <div class="rounded-2xl bg-white p-8 text-center shadow-sm" style="border:1px solid var(--c-border);">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full" style="background:#dcfce7;">
            <svg class="h-8 w-8" style="color:#16a34a;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold" style="color:var(--c-text);">¡Pedido recibido!</h1>
        <p class="mt-1 text-sm" style="color:var(--c-text-3);">
            Gracias por tu compra. Revisaremos tu pedido y nos pondremos en contacto contigo.
        </p>

        <div class="mt-5 inline-block rounded-xl px-5 py-3" style="background:var(--c-bg-2); border:1px solid var(--c-border);">
            <p class="text-xs uppercase tracking-widest font-semibold" style="color:var(--c-text-3);">Número de pedido</p>
            <p class="mt-0.5 font-mono text-lg font-bold" style="color:var(--c-green);">
                #{{ strtoupper(substr($pedido['id'], 0, 8)) }}
            </p>
        </div>
    </div>

    {{-- Detalle del pedido --}}
    <div class="rounded-2xl bg-white shadow-sm overflow-hidden" style="border:1px solid var(--c-border);">

        <div class="px-6 py-4" style="border-bottom:1px solid var(--c-border);">
            <h2 class="font-semibold text-base" style="color:var(--c-text);">Detalle del pedido</h2>
            <p class="text-xs" style="color:var(--c-text-3);">
                Realizado el
                {{ \Carbon\Carbon::parse($pedido['fecha'])->locale('es')->isoFormat('D [de] MMMM [de] YYYY, H:mm') }}
            </p>
        </div>

        {{-- Líneas --}}
        <div class="divide-y" style="border-color:var(--c-border);">
            @forelse ($pedido['lineas'] ?? [] as $linea)
                <div class="flex items-center gap-4 px-6 py-4">
                    @if (!empty($linea['productoImagenUrl']))
                        <img
                            src="{{ $linea['productoImagenUrl'] }}"
                            alt="{{ $linea['productoNombre'] ?? 'Producto' }}"
                            class="h-14 w-14 rounded-xl object-cover flex-shrink-0"
                            style="border:1px solid var(--c-border);"
                            onerror="this.style.display='none'"
                        >
                    @else
                        <div class="h-14 w-14 rounded-xl flex-shrink-0 flex items-center justify-center"
                             style="background:var(--c-bg-2); border:1px solid var(--c-border);">
                            <svg class="h-6 w-6 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7H4a1 1 0 00-1 1v10a1 1 0 001 1h16a1 1 0 001-1V8a1 1 0 00-1-1zM16 3H8l-1 4h10l-1-4z"/>
                            </svg>
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm truncate" style="color:var(--c-text);">
                            {{ $linea['productoNombre'] ?? 'Producto' }}
                        </p>
                        <p class="text-xs" style="color:var(--c-text-3);">
                            {{ (int) ($linea['cantidad'] ?? 1) }} unid. × ${{ number_format((float) ($linea['precioUnitario'] ?? 0), 2) }}
                        </p>
                    </div>

                    <p class="font-semibold text-sm flex-shrink-0" style="color:var(--c-text);">
                        ${{ number_format((float) ($linea['subtotal'] ?? 0), 2) }}
                    </p>
                </div>
            @empty
                <div class="px-6 py-4">
                    <p class="text-sm" style="color:var(--c-text-3);">Sin líneas de detalle.</p>
                </div>
            @endforelse
        </div>

        {{-- Nota --}}
        @if (!empty($pedido['nota']))
            <div class="px-6 py-4" style="border-top:1px solid var(--c-border); background:var(--c-bg-2);">
                <p class="text-xs font-semibold uppercase tracking-wide" style="color:var(--c-text-3);">Nota</p>
                <p class="mt-1 text-sm" style="color:var(--c-text);">{{ $pedido['nota'] }}</p>
            </div>
        @endif

        {{-- Totales --}}
        <div class="px-6 py-4 space-y-2" style="border-top:1px solid var(--c-border);">
            <div class="flex justify-between text-sm">
                <span style="color:var(--c-text-2);">Subtotal</span>
                <span style="color:var(--c-text);">${{ number_format((float) ($pedido['total'] ?? 0), 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span style="color:var(--c-text-2);">Envío</span>
                <span class="font-semibold" style="color:var(--c-green);">Por coordinar</span>
            </div>
            <div class="flex justify-between text-base font-bold pt-2" style="border-top:1px solid var(--c-border);">
                <span style="color:var(--c-text);">Total</span>
                <span style="color:var(--c-text);">${{ number_format((float) ($pedido['total'] ?? 0), 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Estado --}}
    <div class="rounded-2xl bg-white p-5 shadow-sm" style="border:1px solid var(--c-border);">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold" style="background:#fef9c3; color:#854d0e;">
                    {{ $pedido['estado'] ?? 'Pendiente' }}
                </span>
            </div>
            <p class="text-sm" style="color:var(--c-text-3);">
                Tu pedido está siendo revisado. Te notificaremos cuando sea confirmado.
            </p>
        </div>
    </div>

    {{-- Acciones --}}
    <div class="flex flex-col sm:flex-row gap-3 pb-4">
        <a href="{{ route('portal.pedidos') }}"
           class="flex-1 inline-flex items-center justify-center gap-2 rounded-full py-3 text-sm font-semibold text-white"
           style="background:var(--c-green);">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Ver mis pedidos
        </a>
        <a href="{{ route('catalogo') }}"
           class="flex-1 inline-flex items-center justify-center gap-2 rounded-full py-3 text-sm font-semibold"
           style="border:1px solid var(--c-border-2); color:var(--c-text-2);">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            Seguir comprando
        </a>
    </div>

</div>
</div>
@endsection
