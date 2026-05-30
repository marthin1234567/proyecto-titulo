<?php

namespace App\Livewire\Admin;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

class PedidosIndex extends Component
{
    public array  $pedidos     = [];
    public string $message     = '';
    public string $filtroEstado = '';
    public array  $expandedIds = [];

    public function mount(): void
    {
        $this->loadPedidos();
    }

    public function toggleExpand(string $id): void
    {
        if (in_array($id, $this->expandedIds)) {
            $this->expandedIds = array_values(array_filter(
                $this->expandedIds, fn ($i) => $i !== $id
            ));
        } else {
            $this->expandedIds[] = $id;
        }
    }

    public function updateEstado(string $id, string $estado): void
    {
        if (! in_array($estado, ['Pendiente', 'Procesado', 'Completado'], true)) {
            return;
        }

        try {
            app(FirestoreRepository::class)->updatePedidoEstado($id, $estado);
            $this->message = "Estado actualizado a \"{$estado}\".";
            $this->loadPedidos();
        } catch (Throwable $e) {
            Log::error('Error actualizando estado de pedido', ['error' => $e->getMessage()]);
            $this->message = 'No se pudo actualizar el estado.';
        }
    }

    #[Computed]
    public function pedidosFiltrados(): array
    {
        if ($this->filtroEstado === '') {
            return $this->pedidos;
        }

        return array_values(array_filter(
            $this->pedidos,
            fn (array $p) => ($p['estado'] ?? 'Pendiente') === $this->filtroEstado
        ));
    }

    private function loadPedidos(): void
    {
        try {
            $this->pedidos = collect(app(FirestoreRepository::class)->getPedidos())
                ->sortByDesc(fn (array $item) => (string) ($item['fecha'] ?? ''))
                ->values()
                ->all();
        } catch (Throwable $e) {
            Log::error('Error cargando pedidos en admin', ['error' => $e->getMessage()]);
            $this->pedidos = [];
        }
    }

    public function render()
    {
        return view('livewire.admin.pedidos-index')->layout('layouts.admin');
    }
}
