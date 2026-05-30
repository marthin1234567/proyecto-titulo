<?php

namespace App\Livewire\Portal;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class MisCotizaciones extends Component
{
    public array $cotizaciones = [];

    public function mount(): void
    {
        $uid = (string) session('firebase.uid', '');

        if ($uid === '') {
            return;
        }

        try {
            $this->cotizaciones = app(FirestoreRepository::class)->getCotizacionesByUser($uid);
        } catch (Throwable $exception) {
            Log::error('Error cargando cotizaciones del portal', ['error' => $exception->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.portal.mis-cotizaciones')->layout('layouts.portal');
    }
}
