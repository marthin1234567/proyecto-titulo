<?php

namespace App\Livewire\Admin;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class OrdenesIndex extends Component
{
    public array $ordenes = [];

    public ?string $editingId = null;

    public string $proveedorNombre = '';

    public string $proveedorId = '';

    public float $total = 0;

    public string $fechaEntregaEsperada = '';

    public string $condicionPago = '';

    public string $observaciones = '';

    public string $message = '';

    public function mount(): void
    {
        $this->loadOrdenes();
    }

    public function updateEstado(string $id, string $estado): void
    {
        if (! in_array($estado, ['Pendiente', 'Procesada', 'Completada'], true)) {
            return;
        }

        try {
            app(FirestoreRepository::class)->updateOrdenCompraEstado($id, $estado);
            $this->message = 'Estado de orden actualizado.';
            $this->loadOrdenes();
        } catch (Throwable $exception) {
            Log::error('Error actualizando estado de orden', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo actualizar el estado.';
        }
    }

    public function edit(string $id): void
    {
        $orden = collect($this->ordenes)->firstWhere('id', $id);
        if (! $orden) {
            return;
        }

        $this->editingId = $id;
        $this->proveedorNombre = (string) ($orden['proveedorNombre'] ?? '');
        $this->proveedorId = (string) ($orden['proveedorId'] ?? '');
        $this->total = (float) ($orden['total'] ?? 0);
        $this->fechaEntregaEsperada = (string) ($orden['fechaEntregaEsperada'] ?? '');
        $this->condicionPago = (string) ($orden['condicionPago'] ?? '');
        $this->observaciones = (string) ($orden['observaciones'] ?? '');
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'proveedorNombre', 'proveedorId', 'total', 'fechaEntregaEsperada', 'condicionPago', 'observaciones']);
        $this->total = 0;
    }

    public function save(): void
    {
        $data = $this->validate([
            'proveedorNombre' => ['required', 'string', 'max:180'],
            'proveedorId' => ['nullable', 'string', 'max:180'],
            'total' => ['required', 'numeric', 'min:0'],
            'fechaEntregaEsperada' => ['nullable', 'string', 'max:30'],
            'condicionPago' => ['nullable', 'string', 'max:180'],
            'observaciones' => ['nullable', 'string', 'max:800'],
        ]);

        $payload = [
            'userId' => (string) session('firebase.uid', ''),
            'proveedorId' => $data['proveedorId'],
            'proveedorNombre' => $data['proveedorNombre'],
            'fecha' => now()->toIso8601String(),
            'estado' => 'Pendiente',
            'total' => (float) $data['total'],
            'lineas' => [],
            'fechaEntregaEsperada' => $data['fechaEntregaEsperada'],
            'condicionPago' => $data['condicionPago'],
            'observaciones' => $data['observaciones'],
        ];

        try {
            $repo = app(FirestoreRepository::class);

            if ($this->editingId) {
                $repo->updateOrdenCompra($this->editingId, $payload);
                $this->message = 'Orden actualizada.';
            } else {
                $repo->createOrdenCompra($payload);
                $this->message = 'Orden creada.';
            }

            $this->cancelEdit();
            $this->loadOrdenes();
        } catch (Throwable $exception) {
            Log::error('Error guardando orden', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo guardar la orden.';
        }
    }

    public function delete(string $id): void
    {
        try {
            app(FirestoreRepository::class)->deleteOrdenCompra($id);
            $this->message = 'Orden eliminada.';
            $this->loadOrdenes();
        } catch (Throwable $exception) {
            Log::error('Error eliminando orden', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo eliminar la orden.';
        }
    }

    private function loadOrdenes(): void
    {
        try {
            $this->ordenes = collect(app(FirestoreRepository::class)->getOrdenesCompra())
                ->sortByDesc(fn (array $item) => (string) ($item['fecha'] ?? ''))
                ->values()
                ->all();
        } catch (Throwable $exception) {
            Log::error('Error cargando ordenes admin', ['error' => $exception->getMessage()]);
            $this->ordenes = [];
        }
    }

    public function render()
    {
        return view('livewire.admin.ordenes-index')->layout('layouts.admin');
    }
}
