<?php

namespace App\Livewire\Admin;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class ReportesIndex extends Component
{
    public string $from = '';

    public string $to = '';

    public array $resumenEstado = [];

    public array $topProductos = [];

    public array $kpis = [
        'pedidos_total' => 0,
        'cotizaciones_total' => 0,
        'ordenes_total' => 0,
        'clientes_total' => 0,
    ];

    public function mount(): void
    {
        $this->from = now()->startOfMonth()->format('Y-m-d');
        $this->to = now()->format('Y-m-d');
        $this->buildReport();
    }

    public function updatedFrom(): void
    {
        $this->buildReport();
    }

    public function updatedTo(): void
    {
        $this->buildReport();
    }

    public function buildReport(): void
    {
        try {
            $repo = app(FirestoreRepository::class);

            $pedidos = collect($repo->getPedidos())->filter(fn (array $item): bool => $this->withinRange((string) ($item['fecha'] ?? '')));
            $cotizaciones = collect($repo->getCotizaciones())->filter(fn (array $item): bool => $this->withinRange((string) ($item['fecha'] ?? '')));
            $ordenes = collect($repo->getOrdenesCompra())->filter(fn (array $item): bool => $this->withinRange((string) ($item['fecha'] ?? '')));

            $this->kpis = [
                'pedidos_total' => (float) $pedidos->sum(fn (array $item) => (float) ($item['total'] ?? 0)),
                'cotizaciones_total' => (float) $cotizaciones->sum(fn (array $item) => (float) ($item['total'] ?? 0)),
                'ordenes_total' => (float) $ordenes->sum(fn (array $item) => (float) ($item['total'] ?? 0)),
                'clientes_total' => count($repo->getClientes()),
            ];

            $this->resumenEstado = $pedidos
                ->groupBy(fn (array $item) => (string) ($item['estado'] ?? 'Pendiente'))
                ->map(fn ($group, $estado) => [
                    'estado' => $estado,
                    'cantidad' => $group->count(),
                    'total' => (float) $group->sum(fn (array $item) => (float) ($item['total'] ?? 0)),
                ])
                ->values()
                ->all();

            $productos = [];
            foreach ($pedidos as $pedido) {
                foreach ((array) ($pedido['lineas'] ?? []) as $linea) {
                    $key = (string) ($linea['productoNombre'] ?? 'Producto');
                    $productos[$key] = ($productos[$key] ?? 0) + (int) ($linea['cantidad'] ?? 0);
                }
            }

            arsort($productos);
            $this->topProductos = collect($productos)
                ->map(fn (int $cantidad, string $nombre) => ['nombre' => $nombre, 'cantidad' => $cantidad])
                ->take(10)
                ->values()
                ->all();
        } catch (Throwable $exception) {
            Log::error('Error generando reporte admin', ['error' => $exception->getMessage()]);
            $this->resumenEstado = [];
            $this->topProductos = [];
        }
    }

    private function withinRange(string $fecha): bool
    {
        if ($fecha === '') {
            return false;
        }

        $date = substr($fecha, 0, 10);

        return $date >= $this->from && $date <= $this->to;
    }

    public function render()
    {
        return view('livewire.admin.reportes-index')->layout('layouts.admin');
    }
}
