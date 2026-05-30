<?php

namespace App\Livewire\Portal;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class MisPedidos extends Component
{
    public array $pedidos = [];

    public function mount(): void
    {
        $uid = (string) session('firebase.uid', '');

        if ($uid === '') {
            return;
        }

        try {
            $this->pedidos = app(FirestoreRepository::class)->getPedidosByUser($uid);
        } catch (Throwable $exception) {
            Log::error('Error cargando pedidos del portal', ['error' => $exception->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.portal.mis-pedidos')->layout('layouts.portal');
    }
}
