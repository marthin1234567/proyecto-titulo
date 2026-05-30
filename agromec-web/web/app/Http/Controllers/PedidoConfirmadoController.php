<?php

namespace App\Http\Controllers;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Http\Request;
use Throwable;

class PedidoConfirmadoController extends Controller
{
    public function show(Request $request, string $id)
    {
        try {
            $pedido = app(FirestoreRepository::class)->getPedidoById($id);
        } catch (Throwable) {
            $pedido = null;
        }

        if (! $pedido) {
            abort(404);
        }

        return view('public.pedido-confirmado', compact('pedido'));
    }
}
