<?php

namespace App\Livewire\Admin;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class Dashboard extends Component
{
    public array $kpis = [
        'pedidos_mes' => 0,
        'cotizaciones_mes' => 0,
        'ordenes_mes' => 0,
        'clientes_total' => 0,
    ];

    public array $ultimosPedidos = [];

    public function mount(): void
    {
        try {
            $repo = app(FirestoreRepository::class);

            $pedidos = $repo->getPedidos();
            $cotizaciones = $repo->getCotizaciones();
            $ordenes = $repo->getOrdenesCompra();
            $clientes = $repo->getClientes();

            $this->kpis['pedidos_mes'] = (float) collect($pedidos)
                ->filter(fn (array $item) => $this->isCurrentMonth((string) ($item['fecha'] ?? '')))
                ->sum(fn (array $item) => (float) ($item['total'] ?? 0));

            $this->kpis['cotizaciones_mes'] = (float) collect($cotizaciones)
                ->filter(fn (array $item) => $this->isCurrentMonth((string) ($item['fecha'] ?? '')))
                ->sum(fn (array $item) => (float) ($item['total'] ?? 0));

            $this->kpis['ordenes_mes'] = (float) collect($ordenes)
                ->filter(fn (array $item) => $this->isCurrentMonth((string) ($item['fecha'] ?? '')))
                ->sum(fn (array $item) => (float) ($item['total'] ?? 0));

            $this->kpis['clientes_total'] = count($clientes);

            $this->ultimosPedidos = collect($pedidos)
                ->sortByDesc(fn (array $item) => (string) ($item['fecha'] ?? ''))
                ->take(8)
                ->values()
                ->all();
        } catch (Throwable $exception) {
            Log::error('Error cargando dashboard de admin', ['error' => $exception->getMessage()]);
        }
    }

    private function isCurrentMonth(string $fecha): bool
    {
        if ($fecha === '') {
            return false;
        }

        return str_starts_with($fecha, now()->format('Y-m'));
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.admin');
    }
}
