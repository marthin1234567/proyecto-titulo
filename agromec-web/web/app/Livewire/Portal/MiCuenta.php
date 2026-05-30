<?php

namespace App\Livewire\Portal;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class MiCuenta extends Component
{
    public ?array $cliente = null;

    public string $telefono = '';

    public string $direccion = '';

    public string $message = '';

    public function mount(): void
    {
        $email = (string) session('firebase.email', '');

        if ($email === '') {
            return;
        }

        try {
            $this->cliente = app(FirestoreRepository::class)->getClienteByEmail($email);
            $this->telefono = (string) ($this->cliente['telefono'] ?? '');
            $this->direccion = (string) ($this->cliente['direccion'] ?? '');
        } catch (Throwable $exception) {
            Log::error('Error cargando cuenta del cliente', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo cargar la cuenta.';
        }
    }

    public function save(): void
    {
        if (! $this->cliente || ! isset($this->cliente['id'])) {
            $this->message = 'No hay perfil de cliente para actualizar.';

            return;
        }

        $this->validate([
            'telefono' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:250'],
        ]);

        try {
            app(FirestoreRepository::class)->updateCliente((string) $this->cliente['id'], [
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
            ]);

            $this->cliente['telefono'] = $this->telefono;
            $this->cliente['direccion'] = $this->direccion;
            $this->message = 'Cuenta actualizada correctamente.';
        } catch (Throwable $exception) {
            Log::error('Error actualizando cuenta del cliente', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo actualizar la cuenta.';
        }
    }

    public function render()
    {
        return view('livewire.portal.mi-cuenta')->layout('layouts.portal');
    }
}
