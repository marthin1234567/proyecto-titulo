<div class="space-y-5">

    {{-- Header --}}
    <div class="ad-page-header flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--c-text);">Pedidos de clientes</h1>
            <p class="text-sm mt-0.5" style="color:var(--c-text-3);">
                {{ count($pedidos) }} {{ count($pedidos) === 1 ? 'pedido' : 'pedidos' }} en total
            </p>
        </div>
        {{-- Filtro de estado --}}
        <div class="flex items-center gap-2">
            @foreach(['', 'Pendiente', 'Procesado', 'Completado'] as $f)
                <button
                    wire:click="$set('filtroEstado', '{{ $f }}')"
                    class="rounded-full px-3 py-1.5 text-xs font-semibold transition-all"
                    style="{{ $filtroEstado === $f
                        ? 'background:var(--c-green); color:white;'
                        : 'background:var(--c-bg-2); color:var(--c-text-2); border:1px solid var(--c-border);' }}">
                    {{ $f === '' ? 'Todos' : $f }}
                </button>
            @endforeach
        </div>
    </div>

    @if($message !== '')
        <div class="ad-alert-success">{{ $message }}</div>
    @endif

    {{-- Lista de pedidos --}}
    <div class="space-y-3">
        @forelse($this->pedidosFiltrados as $p)
            @php
                $estado = $p['estado'] ?? 'Pendiente';
                $badgeStyle = match($estado) {
                    'Completado' => 'background:#dcfce7; color:#15803d;',
                    'Procesado'  => 'background:#dbeafe; color:#1d4ed8;',
                    default      => 'background:#fef9c3; color:#92400e;',
                };
                $dotColor = match($estado) {
                    'Completado' => '#22c55e',
                    'Procesado'  => '#3b82f6',
                    default      => '#f59e0b',
                };
                $lineas = $p['lineas'] ?? [];
                $isOpen = in_array($p['id'], $expandedIds);
            @endphp

            <article class="ad-card overflow-hidden" style="padding:0;">

                {{-- Fila principal --}}
                <div class="flex flex-wrap items-center gap-3 px-5 py-4 cursor-pointer select-none"
                     wire:click="toggleExpand('{{ $p['id'] }}')"
                     style="border-bottom: {{ $isOpen ? '1px solid var(--c-border)' : 'none' }};">

                    {{-- Chevron --}}
                    <svg class="h-4 w-4 flex-shrink-0 transition-transform {{ $isOpen ? 'rotate-90' : '' }}"
                         style="color:var(--c-text-3);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>

                    {{-- ID --}}
                    <span class="font-mono text-xs font-bold w-24" style="color:var(--c-text);">
                        #{{ strtoupper(substr($p['id'], 0, 8)) }}
                    </span>

                    {{-- Cliente --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate" style="color:var(--c-text);">{{ $p['clienteEmail'] ?? '-' }}</p>
                        <p class="text-xs" style="color:var(--c-text-3);">
                            {{ isset($p['fecha']) ? \Carbon\Carbon::parse($p['fecha'])->locale('es')->isoFormat('D MMM YYYY · H:mm') : '-' }}
                            · {{ count($lineas) }} {{ count($lineas) === 1 ? 'producto' : 'productos' }}
                        </p>
                    </div>

                    {{-- Estado selector --}}
                    <div wire:click.stop>
                        <select class="ad-select text-xs py-1 rounded-lg"
                                style="width:auto; {{ $badgeStyle }}"
                                wire:change="updateEstado('{{ $p['id'] }}', $event.target.value)">
                            @foreach(['Pendiente', 'Procesado', 'Completado'] as $opt)
                                <option value="{{ $opt }}" @selected($estado === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Total --}}
                    <span class="text-base font-bold w-28 text-right flex-shrink-0" style="color:var(--c-text);">
                        ${{ number_format((float)($p['total'] ?? 0), 0, ',', '.') }}
                    </span>
                </div>

                {{-- Detalle de ítems (expandible) --}}
                @if($isOpen)
                    <div style="background:var(--c-bg-2);">
                        @if(count($lineas) > 0)
                            @foreach($lineas as $i => $linea)
                                <div class="flex items-center gap-4 px-6 py-3"
                                     style="{{ $i > 0 ? 'border-top:1px solid var(--c-border);' : '' }}">
                                    @if(!empty($linea['productoImagenUrl']))
                                        <img src="{{ $linea['productoImagenUrl'] }}"
                                             alt="{{ $linea['productoNombre'] ?? '' }}"
                                             class="h-10 w-10 rounded-lg object-cover flex-shrink-0"
                                             style="border:1px solid var(--c-border);"
                                             onerror="this.style.display='none'">
                                    @else
                                        <div class="h-10 w-10 rounded-lg flex-shrink-0"
                                             style="background:var(--c-bg-3,#efece5); border:1px solid var(--c-border);"></div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium" style="color:var(--c-text);">
                                            {{ $linea['productoNombre'] ?? 'Producto' }}
                                        </p>
                                        <p class="text-xs" style="color:var(--c-text-3);">
                                            {{ (int)($linea['cantidad'] ?? 1) }} unid. × ${{ number_format((float)($linea['precioUnitario'] ?? 0), 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <span class="text-sm font-semibold flex-shrink-0" style="color:var(--c-text);">
                                        ${{ number_format((float)($linea['subtotal'] ?? 0), 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach

                            {{-- Nota si existe --}}
                            @if(!empty($p['nota']))
                                <div class="px-6 py-3" style="border-top:1px solid var(--c-border);">
                                    <p class="text-xs" style="color:var(--c-text-3);">
                                        <span class="font-semibold">Nota:</span> {{ $p['nota'] }}
                                    </p>
                                </div>
                            @endif

                            {{-- Total en detalle --}}
                            <div class="flex justify-end px-6 py-3" style="border-top:1px solid var(--c-border);">
                                <p class="text-sm font-bold" style="color:var(--c-text);">
                                    Total: ${{ number_format((float)($p['total'] ?? 0), 0, ',', '.') }}
                                </p>
                            </div>
                        @else
                            <div class="px-6 py-4">
                                <p class="text-sm" style="color:var(--c-text-3);">Sin detalle de ítems.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </article>
        @empty
            <div class="ad-card py-12 text-center">
                <p class="font-semibold" style="color:var(--c-text);">
                    {{ $filtroEstado !== '' ? "No hay pedidos con estado \"{$filtroEstado}\"." : 'No hay pedidos registrados.' }}
                </p>
            </div>
        @endforelse
    </div>

</div>
