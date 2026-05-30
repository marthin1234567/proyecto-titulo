<?php

namespace App\Livewire\Admin;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Throwable;

class ProductosIndex extends Component
{
    public array   $productos          = [];
    public ?string $editingId          = null;
    public ?string $confirmingDeleteId = null;
    public bool    $showForm           = false;

    public string $nombre        = '';
    public string $descripcion   = '';
    public string $categoria     = '';
    public float  $precioUnitario = 0;
    public int    $stock          = 0;
    public string $proveedorId   = '';
    public string $imagenUrl     = '';
    public string $message       = '';

    public function mount(): void
    {
        $this->loadProductos();
    }

    public function edit(string $id): void
    {
        $producto = collect($this->productos)->firstWhere('id', $id);
        if (! $producto) {
            return;
        }

        $this->editingId      = $id;
        $this->showForm       = true;
        $this->nombre         = (string) ($producto['nombre']        ?? '');
        $this->descripcion    = (string) ($producto['descripcion']   ?? '');
        $this->categoria      = (string) ($producto['categoria']     ?? '');
        $this->precioUnitario = (float)  ($producto['precioUnitario'] ?? 0);
        $this->stock          = (int)    ($producto['stock']          ?? 0);
        $this->proveedorId    = (string) ($producto['proveedorId']   ?? '');
        $this->imagenUrl      = (string) ($producto['imagenUrl']     ?? '');
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'nombre', 'descripcion', 'categoria',
                      'precioUnitario', 'stock', 'proveedorId', 'imagenUrl', 'showForm']);
    }

    public function save(): void
    {
        $data = $this->validate([
            'nombre'        => ['required', 'string', 'max:180'],
            'descripcion'   => ['nullable', 'string', 'max:1000'],
            'categoria'     => ['required', 'string', 'max:120'],
            'precioUnitario'=> ['required', 'numeric', 'min:0'],
            'stock'         => ['nullable', 'integer', 'min:0'],
            'proveedorId'   => ['nullable', 'string', 'max:180'],
            'imagenUrl'     => ['nullable', 'url', 'max:500'],
        ]);

        try {
            $repo = app(FirestoreRepository::class);

            if ($this->editingId) {
                $repo->updateProducto($this->editingId, $data);
                $this->message = "Producto \"{$this->nombre}\" actualizado.";
            } else {
                $repo->createProducto($data);
                $this->message = "Producto \"{$this->nombre}\" creado.";
            }

            $this->cancelEdit();
            $this->loadProductos();
        } catch (Throwable $e) {
            Log::error('Error guardando producto', ['error' => $e->getMessage()]);
            $this->message = 'No se pudo guardar el producto.';
        }
    }

    public function confirmDelete(string $id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function delete(): void
    {
        if (! $this->confirmingDeleteId) {
            return;
        }

        try {
            app(FirestoreRepository::class)->deleteProducto($this->confirmingDeleteId);
            $this->message = 'Producto eliminado.';
            $this->confirmingDeleteId = null;
            $this->loadProductos();
        } catch (Throwable $e) {
            Log::error('Error eliminando producto', ['error' => $e->getMessage()]);
            $this->message = 'No se pudo eliminar el producto.';
        }
    }

    private function loadProductos(): void
    {
        try {
            $this->productos = collect(app(FirestoreRepository::class)->getProductos())
                ->sortBy('nombre')
                ->values()
                ->all();
        } catch (Throwable $e) {
            Log::error('Error cargando productos en admin', ['error' => $e->getMessage()]);
            $this->productos = [];
        }
    }

    public function render()
    {
        return view('livewire.admin.productos-index')->layout('layouts.admin');
    }
}
