<?php

namespace App\Livewire\Admin;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class ProveedoresIndex extends Component
{
    public array $proveedores = [];

    public ?string $editingId = null;

    public string $nombre = '';

    public string $contacto = '';

    public string $telefono = '';

    public string $direccion = '';

    public string $message = '';

    public function mount(): void
    {
        $this->loadProveedores();
    }

    public function edit(string $id): void
    {
        $proveedor = collect($this->proveedores)->firstWhere('id', $id);
        if (! $proveedor) {
            return;
        }

        $this->editingId = $id;
        $this->nombre = (string) ($proveedor['nombre'] ?? '');
        $this->contacto = (string) ($proveedor['contacto'] ?? '');
        $this->telefono = (string) ($proveedor['telefono'] ?? '');
        $this->direccion = (string) ($proveedor['direccion'] ?? '');
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'nombre', 'contacto', 'telefono', 'direccion']);
    }

    public function save(): void
    {
        $data = $this->validate([
            'nombre' => ['required', 'string', 'max:180'],
            'contacto' => ['nullable', 'string', 'max:180'],
            'telefono' => ['nullable', 'string', 'max:60'],
            'direccion' => ['nullable', 'string', 'max:250'],
        ]);

        try {
            $repo = app(FirestoreRepository::class);

            if ($this->editingId) {
                $repo->updateProveedor($this->editingId, $data);
                $this->message = 'Proveedor actualizado.';
            } else {
                $repo->createProveedor($data);
                $this->message = 'Proveedor creado.';
            }

            $this->cancelEdit();
            $this->loadProveedores();
        } catch (Throwable $exception) {
            Log::error('Error guardando proveedor', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo guardar el proveedor.';
        }
    }

    public function delete(string $id): void
    {
        try {
            app(FirestoreRepository::class)->deleteProveedor($id);
            $this->message = 'Proveedor eliminado.';
            $this->loadProveedores();
        } catch (Throwable $exception) {
            Log::error('Error eliminando proveedor', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo eliminar el proveedor.';
        }
    }

    private function loadProveedores(): void
    {
        try {
            $this->proveedores = collect(app(FirestoreRepository::class)->getProveedores())
                ->sortBy('nombre')
                ->values()
                ->all();
        } catch (Throwable $exception) {
            Log::error('Error cargando proveedores admin', ['error' => $exception->getMessage()]);
            $this->proveedores = [];
        }
    }

    public function render()
    {
        return view('livewire.admin.proveedores-index')->layout('layouts.admin');
    }
}
