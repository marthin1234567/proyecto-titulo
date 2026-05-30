<?php

namespace App\Livewire\Admin;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class CotizacionesIndex extends Component
{
    public array $cotizaciones = [];

    public ?string $editingId = null;
    public bool    $showForm  = false;

    public string $clienteNombre = '';
    public string $clienteId     = '';
    public float  $total         = 0;
    public string $lineasJson    = '[]';
    public string $notas         = '';
    public string $validaHasta   = '';
    public string $message       = '';

    public function mount(): void
    {
        $this->loadCotizaciones();
    }

    public function edit(string $id): void
    {
        $cotizacion = collect($this->cotizaciones)->firstWhere('id', $id);
        if (! $cotizacion) {
            return;
        }

        $this->editingId     = $id;
        $this->showForm      = true;
        $this->clienteNombre = (string) ($cotizacion['clienteNombre'] ?? '');
        $this->clienteId     = (string) ($cotizacion['clienteId']     ?? '');
        $this->total         = (float)  ($cotizacion['total']          ?? 0);
        $this->notas         = (string) ($cotizacion['notas']          ?? '');
        $this->validaHasta   = (string) ($cotizacion['validaHasta']    ?? '');
        $this->lineasJson    = json_encode((array) ($cotizacion['lineas'] ?? []), JSON_PRETTY_PRINT) ?: '[]';
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'clienteNombre', 'clienteId', 'total',
                      'lineasJson', 'notas', 'validaHasta', 'showForm']);
        $this->lineasJson = '[]';
    }

    public function save(): void
    {
        $data = $this->validate([
            'clienteNombre' => ['required', 'string', 'max:180'],
            'clienteId'     => ['nullable', 'string', 'max:180'],
            'total'         => ['required', 'numeric', 'min:0'],
            'notas'         => ['nullable', 'string', 'max:1000'],
            'validaHasta'   => ['nullable', 'string'],
        ]);

        $payload = [
            'userId'        => (string) session('firebase.uid', ''),
            'clienteId'     => $data['clienteId']     ?? '',
            'clienteNombre' => $data['clienteNombre'],
            'fecha'         => now()->toIso8601String(),
            'total'         => (float) $data['total'],
            'notas'         => $data['notas']       ?? '',
            'validaHasta'   => $data['validaHasta'] ?? '',
            'lineas'        => [],
        ];

        try {
            $repo = app(FirestoreRepository::class);

            if ($this->editingId) {
                $repo->updateCotizacion($this->editingId, $payload);
                $this->message = 'Cotizacion actualizada.';
            } else {
                $repo->createCotizacion($payload);
                $this->message = 'Cotizacion creada.';
            }

            $this->cancelEdit();
            $this->loadCotizaciones();
        } catch (Throwable $exception) {
            Log::error('Error guardando cotizacion', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo guardar la cotizacion.';
        }
    }

    public function delete(string $id): void
    {
        try {
            app(FirestoreRepository::class)->deleteCotizacion($id);
            $this->message = 'Cotizacion eliminada.';
            $this->loadCotizaciones();
        } catch (Throwable $exception) {
            Log::error('Error eliminando cotizacion', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo eliminar la cotizacion.';
        }
    }

    private function loadCotizaciones(): void
    {
        try {
            $this->cotizaciones = collect(app(FirestoreRepository::class)->getCotizaciones())
                ->sortByDesc(fn (array $item) => (string) ($item['fecha'] ?? ''))
                ->values()
                ->all();
        } catch (Throwable $exception) {
            Log::error('Error cargando cotizaciones admin', ['error' => $exception->getMessage()]);
            $this->cotizaciones = [];
        }
    }

    public function render()
    {
        return view('livewire.admin.cotizaciones-index')->layout('layouts.admin');
    }
}
