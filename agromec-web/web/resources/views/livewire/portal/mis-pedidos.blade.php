<section class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold" style="color:var(--c-text);">Mis pedidos</h1>
            <p class="mt-0.5 text-sm" style="color:var(--c-text-3);">
                {{ count($pedidos) }} {{ count($pedidos) === 1 ? 'pedido realizado' : 'pedidos realizados' }}
            </p>
        </div>
        <a href="{{ route('catalogo') }}"
           class="hidden sm:inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-white"
           style="background:var(--c-green);">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo pedido
        </a>
    </div>

    @if (count($pedidos) === 0)
        {{-- Estado vacío --}}
        <div class="rounded-2xl bg-white py-14 text-center"
             style="border:1px solid var(--c-border); box-shadow:0 1px 4px rgba(0,0,0,.05);">
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl"
                 style="background:var(--c-bg-2); border:1px solid var(--c-border);">
                <svg class="h-7 w-7" style="color:var(--c-text-3);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="font-semibold text-base" style="color:var(--c-text);">Aún no tienes pedidos</p>
            <p class="mt-1 text-sm" style="color:var(--c-text-3);">Cuando realices un pedido, aparecerá aquí.</p>
            <a href="{{ route('catalogo') }}"
               class="mt-5 inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold text-white"
               style="background:var(--c-green);">
                Explorar catálogo
            </a>
        </div>

    @else
        <div class="space-y-3">
            @foreach($pedidos as $pedido)
                @php
                    $estado = strtolower($pedido['estado'] ?? 'pendiente');
                    $badge = match(true) {
                        in_array($estado, ['confirmado', 'aprobado']) => ['bg' => '#dcfce7', 'color' => '#15803d', 'dot' => '#22c55e'],
                        in_array($estado, ['enviado', 'en camino'])   => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'dot' => '#3b82f6'],
                        $estado === 'entregado'                       => ['bg' => '#f0fdf4', 'color' => '#166534', 'dot' => '#16a34a'],
                        $estado === 'cancelado'                       => ['bg' => '#fee2e2', 'color' => '#dc2626', 'dot' => '#ef4444'],
                        default                                       => ['bg' => '#fef9c3', 'color' => '#92400e', 'dot' => '#f59e0b'],
                    };
                    $lineas = $pedido['lineas'] ?? [];
                    $totalLineas = count($lineas);
                    $fecha = \Carbon\Carbon::parse($pedido['fecha'] ?? now());
                @endphp

                <article class="rounded-2xl bg-white overflow-hidden transition-all hover:-translate-y-0.5"
                         style="border:1px solid var(--c-border); box-shadow:0 1px 4px rgba(0,0,0,.05);">

                    {{-- Franja superior de estado --}}
                    <div class="h-1 w-full" style="background:{{ $badge['dot'] }};"></div>

                    {{-- Header del pedido --}}
                    <div class="flex flex-wrap items-start justify-between gap-3 px-5 pt-4 pb-4">

                        <div class="flex items-start gap-3.5">
                            {{-- Ícono de pedido --}}
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl mt-0.5"
                                 style="background:{{ $badge['bg'] }};">
                                <svg class="h-4.5 w-4.5" style="color:{{ $badge['color'] }};" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-mono text-sm font-bold tracking-wide" style="color:var(--c-text);">
                                        #{{ strtoupper(substr($pedido['id'], 0, 8)) }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                          style="background:{{ $badge['bg'] }}; color:{{ $badge['color'] }};">
                                        <span class="h-1.5 w-1.5 rounded-full flex-shrink-0" style="background:{{ $badge['dot'] }};"></span>
                                        {{ ucfirst($pedido['estado'] ?? 'Pendiente') }}
                                    </span>
                                </div>
                                <p class="mt-0.5 text-xs" style="color:var(--c-text-3);">
                                    {{ $fecha->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                                    · {{ $totalLineas }} {{ $totalLineas === 1 ? 'producto' : 'productos' }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <p class="text-xs" style="color:var(--c-text-3);">Total</p>
                            <p class="text-xl font-bold mt-0.5" style="color:var(--c-text);">
                                ${{ number_format((float) ($pedido['total'] ?? 0), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Líneas de productos --}}
                    @if ($totalLineas > 0)
                        <div style="border-top:1px solid var(--c-border);">
                            @foreach(array_slice($lineas, 0, 3) as $i => $linea)
                                <div class="flex items-center gap-3 px-5 py-3"
                                     style="{{ $i > 0 ? 'border-top:1px solid var(--c-border);' : '' }} background:var(--c-bg-2);">

                                    @if (!empty($linea['productoImagenUrl']))
                                        <img src="{{ $linea['productoImagenUrl'] }}"
                                             alt="{{ $linea['productoNombre'] ?? '' }}"
                                             class="h-11 w-11 rounded-xl object-cover flex-shrink-0"
                                             style="border:1px solid var(--c-border);"
                                             onerror="this.src=''; this.style.display='none'">
                                    @else
                                        <div class="h-11 w-11 rounded-xl flex-shrink-0 flex items-center justify-center"
                                             style="background:var(--c-bg-3); border:1px solid var(--c-border);">
                                            <svg class="h-5 w-5 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7H4a1 1 0 00-1 1v10a1 1 0 001 1h16a1 1 0 001-1V8a1 1 0 00-1-1zM16 3H8l-1 4h10l-1-4z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium truncate" style="color:var(--c-text);">
                                            {{ $linea['productoNombre'] ?? 'Producto' }}
                                        </p>
                                        <p class="text-xs" style="color:var(--c-text-3);">
                                            {{ (int) ($linea['cantidad'] ?? 1) }} {{ (int)($linea['cantidad']??1) === 1 ? 'unidad' : 'unidades' }}
                                            · ${{ number_format((float) ($linea['precioUnitario'] ?? 0), 0, ',', '.') }} c/u
                                        </p>
                                    </div>

                                    <p class="text-sm font-semibold flex-shrink-0" style="color:var(--c-text);">
                                        ${{ number_format((float) ($linea['subtotal'] ?? 0), 0, ',', '.') }}
                                    </p>
                                </div>
                            @endforeach

                            @if ($totalLineas > 3)
                                <div class="flex items-center gap-2 px-5 py-2.5" style="background:var(--c-bg-2); border-top:1px solid var(--c-border);">
                                    <span class="text-xs font-medium" style="color:var(--c-text-3);">
                                        + {{ $totalLineas - 3 }} producto(s) más en este pedido
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Footer --}}
                    <div class="flex items-center justify-between px-5 py-3"
                         style="border-top:1px solid var(--c-border);">
                        @if (!empty($pedido['nota']))
                            <p class="text-xs truncate max-w-xs" style="color:var(--c-text-3);">
                                <span class="font-semibold">Nota:</span> {{ $pedido['nota'] }}
                            </p>
                        @else
                            <span></span>
                        @endif
                        <a href="{{ route('pedido.confirmado', ['id' => $pedido['id']]) }}"
                           class="inline-flex items-center gap-1 text-xs font-semibold transition-colors"
                           style="color:var(--c-green);">
                            Ver detalle
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                </article>
            @endforeach
        </div>
    @endif

</section>
