<?php

namespace App\Livewire\Admin;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class ClientesIndex extends Component
{
    public string $search = '';

    public array $clientes = [];

    public ?string $editingId = null;

    public string $editTelefono = '';

    public string $editDireccion = '';

    public string $message = '';

    public function mount(): void
    {
        $this->loadClientes();
    }

    public function updatedSearch(): void
    {
        $this->loadClientes();
    }

    public function edit(string $id): void
    {
        $cliente = collect($this->clientes)->firstWhere('id', $id);
        if (! $cliente) {
            return;
        }

        $this->editingId = $id;
        $this->editTelefono = (string) ($cliente['telefono'] ?? '');
        $this->editDireccion = (string) ($cliente['direccion'] ?? '');
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'editTelefono', 'editDireccion']);
    }

    public function saveEdit(): void
    {
        if (! $this->editingId) {
            return;
        }

        $this->validate([
            'editTelefono' => ['nullable', 'string', 'max:60'],
            'editDireccion' => ['nullable', 'string', 'max:250'],
        ]);

        try {
            app(FirestoreRepository::class)->updateClienteById($this->editingId, [
                'telefono' => $this->editTelefono,
                'direccion' => $this->editDireccion,
            ]);

            $this->message = 'Cliente actualizado.';
            $this->cancelEdit();
            $this->loadClientes();
        } catch (Throwable $exception) {
            Log::error('Error actualizando cliente admin', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo actualizar el cliente.';
        }
    }

    private function loadClientes(): void
    {
        try {
            $repo = app(FirestoreRepository::class);

            $clientes = $repo->getClientes();
            $pedidos = $repo->getPedidos();
            $cotizaciones = $repo->getCotizaciones();
            $term = strtolower(trim($this->search));

            $this->clientes = collect($clientes)
                ->filter(function (array $cliente) use ($term): bool {
                    if ($term === '') {
                        return true;
                    }

                    return str_contains(strtolower((string) ($cliente['nombre'] ?? '')), $term)
                        || str_contains(strtolower((string) ($cliente['email'] ?? '')), $term);
                })
                ->map(function (array $cliente) use ($pedidos, $cotizaciones): array {
                    $email = (string) ($cliente['email'] ?? '');
                    $clienteId = (string) ($cliente['id'] ?? '');

                    $cliente['pedidos_count'] = collect($pedidos)
                        ->where('clienteEmail', $email)
                        ->count();

                    $cliente['cotizaciones_count'] = collect($cotizaciones)
                        ->filter(fn (array $item): bool => (string) ($item['clienteId'] ?? '') === $clienteId)
                        ->count();

                    return $cliente;
                })
                ->sortBy('nombre')
                ->values()
                ->all();
        } catch (Throwable $exception) {
            Log::error('Error cargando clientes admin', ['error' => $exception->getMessage()]);
            $this->clientes = [];
        }
    }

    public function render()
    {
        return view('livewire.admin.clientes-index')->layout('layouts.admin');
    }
}
