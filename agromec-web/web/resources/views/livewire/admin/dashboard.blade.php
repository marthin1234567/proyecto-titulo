<div class="space-y-6">

    {{-- Bienvenida --}}
    <div class="ad-page-header">
        @php $hora = now()->hour; $saludo = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches'); @endphp
        <h1 class="text-xl font-semibold" style="color:var(--c-text);">
            {{ $saludo }}, {{ session('firebase.nombre', 'Admin') }} 👋
        </h1>
        <p class="text-sm mt-0.5" style="color:var(--c-text-3);">
            {{ now()->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }} · Backoffice AgroMecSmart
        </p>
    </div>

    {{-- KPI Grid --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

        @php
        $kpiItems = [
            [
                'label'   => 'Ventas del mes',
                'value'   => '$'.number_format($kpis['pedidos_mes'], 0, ',', '.'),
                'sub'     => 'Total pedidos confirmados',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
                'bg'      => 'var(--c-green-tint)',
                'color'   => 'var(--c-green-dark)',
            ],
            [
                'label'   => 'Cotizaciones',
                'value'   => '$'.number_format($kpis['cotizaciones_mes'], 0, ',', '.'),
                'sub'     => 'Monto cotizado este mes',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                'bg'      => '#ede9fe',
                'color'   => '#5b21b6',
            ],
            [
                'label'   => 'Órdenes de compra',
                'value'   => '$'.number_format($kpis['ordenes_mes'], 0, ',', '.'),
                'sub'     => 'Total comprado a proveedores',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
                'bg'      => '#fef3c7',
                'color'   => '#92400e',
            ],
            [
                'label'   => 'Clientes',
                'value'   => (string) $kpis['clientes_total'],
                'sub'     => 'Cuentas registradas',
                'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
                'bg'      => '#dbeafe',
                'color'   => '#1d4ed8',
            ],
        ];
        @endphp

        @foreach($kpiItems as $kpi)
        <article class="rounded-2xl bg-white p-5" style="border:1px solid var(--c-border); box-shadow:0 1px 4px rgba(0,0,0,.05);">
            <div class="flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl"
                     style="background:{{ $kpi['bg'] }};">
                    <svg class="h-5 w-5" style="color:{{ $kpi['color'] }};" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        {!! $kpi['icon'] !!}
                    </svg>
                </div>
            </div>
            <p class="mt-4 font-serif text-3xl font-semibold" style="color:var(--c-text);">{{ $kpi['value'] }}</p>
            <p class="mt-0.5 text-sm font-semibold" style="color:var(--c-text);">{{ $kpi['label'] }}</p>
            <p class="text-xs mt-0.5" style="color:var(--c-text-3);">{{ $kpi['sub'] }}</p>
        </article>
        @endforeach
    </div>

    {{-- Accesos rápidos --}}
    <div class="grid gap-3 sm:grid-cols-3">
        <a href="{{ route('admin.pedidos') }}"
           class="flex items-center gap-3 rounded-2xl bg-white p-4 transition-all hover:-translate-y-0.5"
           style="border:1px solid var(--c-border); box-shadow:0 1px 4px rgba(0,0,0,.05);">
            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl" style="background:var(--c-green-tint);">
                <svg class="h-4.5 w-4.5" style="color:var(--c-green-dark);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold" style="color:var(--c-text);">Ver pedidos</p>
                <p class="text-xs" style="color:var(--c-text-3);">Gestionar órdenes de clientes</p>
            </div>
        </a>
        <a href="{{ route('admin.productos') }}"
           class="flex items-center gap-3 rounded-2xl bg-white p-4 transition-all hover:-translate-y-0.5"
           style="border:1px solid var(--c-border); box-shadow:0 1px 4px rgba(0,0,0,.05);">
            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl" style="background:#fef3c7;">
                <svg class="h-4.5 w-4.5" style="color:#92400e;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold" style="color:var(--c-text);">Gestionar productos</p>
                <p class="text-xs" style="color:var(--c-text-3);">CRUD del catálogo</p>
            </div>
        </a>
        <a href="{{ route('admin.cotizaciones') }}"
           class="flex items-center gap-3 rounded-2xl bg-white p-4 transition-all hover:-translate-y-0.5"
           style="border:1px solid var(--c-border); box-shadow:0 1px 4px rgba(0,0,0,.05);">
            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl" style="background:#ede9fe;">
                <svg class="h-4.5 w-4.5" style="color:#5b21b6;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold" style="color:var(--c-text);">Cotizaciones</p>
                <p class="text-xs" style="color:var(--c-text-3);">Propuestas a clientes</p>
            </div>
        </a>
    </div>

    {{-- Últimos pedidos --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold" style="color:var(--c-text);">Pedidos recientes</h2>
            <a href="{{ route('admin.pedidos') }}" class="text-xs font-semibold" style="color:var(--c-green);">
                Ver todos →
            </a>
        </div>

        <div class="space-y-2">
            @forelse($ultimosPedidos as $pedido)
            @php
                $e = $pedido['estado'] ?? 'Pendiente';
                $bs = match($e) {
                    'Completado' => 'background:#dcfce7; color:#15803d;',
                    'Procesado'  => 'background:#dbeafe; color:#1d4ed8;',
                    default      => 'background:#fef9c3; color:#92400e;',
                };
            @endphp
            <div class="flex flex-wrap items-center gap-3 rounded-2xl bg-white px-5 py-3.5"
                 style="border:1px solid var(--c-border);">
                <span class="font-mono text-xs font-bold w-20" style="color:var(--c-text);">
                    #{{ strtoupper(substr($pedido['id'], 0, 8)) }}
                </span>
                <span class="flex-1 text-sm truncate" style="color:var(--c-text-2);">{{ $pedido['clienteEmail'] ?? '-' }}</span>
                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold" style="{{ $bs }}">{{ $e }}</span>
                <span class="text-xs" style="color:var(--c-text-3);">
                    {{ isset($pedido['fecha']) ? \Carbon\Carbon::parse($pedido['fecha'])->locale('es')->isoFormat('D MMM') : '-' }}
                </span>
                <span class="font-semibold text-sm w-24 text-right flex-shrink-0" style="color:var(--c-text);">
                    ${{ number_format((float)($pedido['total'] ?? 0), 0, ',', '.') }}
                </span>
            </div>
            @empty
            <div class="rounded-2xl bg-white py-8 text-center" style="border:1px solid var(--c-border);">
                <p class="text-sm" style="color:var(--c-text-3);">Sin pedidos registrados aún.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
