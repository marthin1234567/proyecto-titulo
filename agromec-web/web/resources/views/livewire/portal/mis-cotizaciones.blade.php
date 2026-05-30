<section class="space-y-4">
    <div class="ag-card rounded-2xl p-6">
        <h1 class="text-2xl font-bold">Mis Cotizaciones</h1>
        <p class="text-sm text-slate-600">Resumen de cotizaciones asociadas a tu usuario.</p>
    </div>

    @if (count($cotizaciones) === 0)
        <div class="ag-card rounded-2xl p-8 text-center">
            <p class="font-semibold">Aun no tienes cotizaciones.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($cotizaciones as $cotizacion)
                <article class="ag-card rounded-2xl p-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h2 class="font-semibold">Cotizacion {{ $cotizacion['id'] }}</h2>
                        <p class="text-sm text-slate-500">{{ $cotizacion['fecha'] ?? '-' }}</p>
                    </div>
                    <p class="mt-2 text-sm text-slate-600">Cliente: {{ $cotizacion['clienteNombre'] ?? '-' }}</p>
                    <p class="mt-1 text-sm text-slate-600">Total: ${{ number_format((float) ($cotizacion['total'] ?? 0), 2) }}</p>
                </article>
            @endforeach
        </div>
    @endif
</section>
