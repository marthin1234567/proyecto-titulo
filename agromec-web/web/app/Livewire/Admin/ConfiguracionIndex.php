<?php

namespace App\Livewire\Admin;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class ConfiguracionIndex extends Component
{
    public string $nombreEmpresa = '';

    public string $direccionEmpresa = '';

    public string $telefonoEmpresa = '';

    public string $emailContacto = '';

    public string $categorias = '';

    public string $message = '';

    public function mount(): void
    {
        try {
            $config = app(FirestoreRepository::class)->getConfiguracionGeneral();
            $this->nombreEmpresa = (string) ($config['nombreEmpresa'] ?? '');
            $this->direccionEmpresa = (string) ($config['direccionEmpresa'] ?? '');
            $this->telefonoEmpresa = (string) ($config['telefonoEmpresa'] ?? '');
            $this->emailContacto = (string) ($config['emailContacto'] ?? '');
            $this->categorias = implode(', ', (array) ($config['categorias'] ?? []));
        } catch (Throwable $exception) {
            Log::error('Error cargando configuracion general', ['error' => $exception->getMessage()]);
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'nombreEmpresa' => ['required', 'string', 'max:180'],
            'direccionEmpresa' => ['nullable', 'string', 'max:250'],
            'telefonoEmpresa' => ['nullable', 'string', 'max:60'],
            'emailContacto' => ['nullable', 'email', 'max:180'],
            'categorias' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $categories = collect(explode(',', $data['categorias']))
                ->map(fn (string $item): string => trim($item))
                ->filter(fn (string $item): bool => $item !== '')
                ->values()
                ->all();

            app(FirestoreRepository::class)->saveConfiguracionGeneral([
                'nombreEmpresa' => $data['nombreEmpresa'],
                'direccionEmpresa' => $data['direccionEmpresa'],
                'telefonoEmpresa' => $data['telefonoEmpresa'],
                'emailContacto' => $data['emailContacto'],
                'categorias' => $categories,
            ]);

            $this->message = 'Configuracion guardada correctamente.';
        } catch (Throwable $exception) {
            Log::error('Error guardando configuracion', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo guardar la configuracion.';
        }
    }

    public function render()
    {
        return view('livewire.admin.configuracion-index')->layout('layouts.admin');
    }
}
