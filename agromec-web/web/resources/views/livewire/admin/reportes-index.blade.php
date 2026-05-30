<div class="space-y-5">
    <div class="ad-page-header">
        <h1 class="text-xl font-semibold" style="color:var(--c-text);">Reportes</h1>
        <p class="text-sm mt-0.5" style="color:var(--c-text-3);">Indicadores y consolidado por rango de fechas</p>
    </div>

    {{-- Date filter --}}
    <div class="ad-card">
        <p class="ad-section-title">Filtrar por período</p>
        <div class="mt-3 flex flex-wrap gap-4">
            <div><label class="ad-label">Desde</label><input type="date" wire:model="from" class="ad-input" style="width:auto;"></div>
            <div><label class="ad-label">Hasta</label><input type="date" wire:model="to" class="ad-input" style="width:auto;"></div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['Pedidos',      '$'.number_format($kpis['pedidos_total'],0,',','.'),      '📦', 'ad-badge-blue'],
            ['Cotizaciones', '$'.number_format($kpis['cotizaciones_total'],0,',','.'), '📋', 'ad-badge-purple'],
            ['Órdenes',      '$'.number_format($kpis['ordenes_total'],0,',','.'),      '🛒', 'ad-badge-yellow'],
            ['Clientes',     $kpis['clientes_total'],                                  '👥', 'ad-badge-green'],
        ] as [$label, $val, $icon, $badge])
        <article class="ad-kpi">
            <div class="flex items-start justify-between">
                <span class="text-2xl">{{ $icon }}</span>
                <span class="ad-badge {{ $badge }}">Período</span>
            </div>
            <p class="mt-4 font-serif text-3xl font-semibold" style="color:var(--c-text);">{{ $val }}</p>
            <p class="mt-1 text-xs" style="color:var(--c-text-3);">{{ $label }}</p>
        </article>
        @endforeach
    </div>

    <div class="grid gap-5 lg:grid-cols-2">
        {{-- Pedidos por estado --}}
        <div class="ad-card">
            <p class="ad-section-title">Pedidos por estado</p>
            <div class="mt-3 space-y-2">
                @forelse($resumenEstado as $row)
                @php $e = $row['estado']; @endphp
                <div class="flex items-center justify-between rounded-lg px-3 py-2.5" style="background:var(--c-bg-2);">
                    <span class="ad-badge {{ $e==='Completado'?'ad-badge-green':($e==='Procesado'?'ad-badge-blue':'ad-badge-yellow') }}">{{ $e }}</span>
                    <div class="text-right">
                        <span class="text-sm font-semibold" style="color:var(--c-text);">{{ $row['cantidad'] }} pedidos</span>
                        <span class="ml-3 text-sm" style="color:var(--c-text-3);">${{ number_format((float)$row['total'],0,',','.') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm py-3" style="color:var(--c-text-3);">Sin datos en el rango.</p>
                @endforelse
            </div>
        </div>

        {{-- Top productos --}}
        <div class="ad-card">
            <p class="ad-section-title">Productos más solicitados</p>
            <div class="mt-3 space-y-2">
                @forelse($topProductos as $i => $item)
                <div class="flex items-center justify-between rounded-lg px-3 py-2.5" style="background:var(--c-bg-2);">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                              style="background:var(--c-green-dark);">{{ $i+1 }}</span>
                        <span class="text-sm font-medium" style="color:var(--c-text);">{{ $item['nombre'] }}</span>
                    </div>
                    <span class="ad-badge ad-badge-gray">{{ $item['cantidad'] }} uds</span>
                </div>
                @empty
                <p class="text-sm py-3" style="color:var(--c-text-3);">Sin datos en el rango.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
