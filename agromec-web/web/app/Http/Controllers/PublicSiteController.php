<?php

namespace App\Http\Controllers;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class PublicSiteController extends Controller
{
    public function __construct(private readonly FirestoreRepository $firestore)
    {
    }

    public function home(Request $request)
    {
        $categorias = ['Semillas', 'Cereales', 'Leguminosas', 'Frutos Secos', 'Oleaginosas'];
        $categoriaCounts = array_fill_keys($categorias, 0);
        $productos = [];

        $q          = strtolower((string) $request->string('q'));
        $categoria  = (string) $request->string('categoria');
        $precioMin  = $request->filled('precio_min') ? (float) $request->input('precio_min') : null;
        $precioMax  = $request->filled('precio_max') ? (float) $request->input('precio_max') : null;

        try {
            $todos = collect($this->firestore->getProductos());

            foreach ($categorias as $cat) {
                $categoriaCounts[$cat] = $todos->where('categoria', $cat)->count();
            }

            $productos = $todos->filter(function (array $p) use ($q, $categoria, $precioMin, $precioMax): bool {
                $precio = (float) ($p['precioUnitario'] ?? 0);

                return ($q === '' || str_contains(strtolower((string) ($p['nombre'] ?? '')), $q))
                    && ($categoria === '' || (string) ($p['categoria'] ?? '') === $categoria)
                    && ($precioMin === null || $precio >= $precioMin)
                    && ($precioMax === null || $precio <= $precioMax);
            })->values()->all();
        } catch (Throwable) {
        }

        return view('public.home', compact('categorias', 'categoriaCounts', 'productos'));
    }

    public function catalogo(Request $request)
    {
        $q         = strtolower((string) $request->string('q'));
        $categoria = (string) $request->string('categoria');
        $precioMin = $request->filled('precio_min') ? (float) $request->input('precio_min') : null;
        $precioMax = $request->filled('precio_max') ? (float) $request->input('precio_max') : null;

        $categorias      = ['Semillas', 'Cereales', 'Leguminosas', 'Frutos Secos', 'Oleaginosas'];
        $categoriaCounts = array_fill_keys($categorias, 0);
        $items           = [];

        try {
            $todos = collect($this->firestore->getProductos());

            foreach ($categorias as $cat) {
                $categoriaCounts[$cat] = $todos->where('categoria', $cat)->count();
            }

            $items = $todos->filter(function (array $item) use ($q, $categoria, $precioMin, $precioMax): bool {
                $precio = (float) ($item['precioUnitario'] ?? 0);

                return ($q === '' || str_contains(strtolower((string) ($item['nombre'] ?? '')), $q))
                    && ($categoria === '' || (string) ($item['categoria'] ?? '') === $categoria)
                    && ($precioMin === null || $precio >= $precioMin)
                    && ($precioMax === null || $precio <= $precioMax);
            })->values()->all();
        } catch (Throwable $exception) {
            Log::error('No se pudo cargar Productos desde Firestore', ['error' => $exception->getMessage()]);
        }

        return view('public.catalogo', compact('items', 'categorias', 'categoriaCounts'));
    }

    public function productoDetalle(string $id)
    {
        try {
            $producto = $this->firestore->getProductoById($id);
        } catch (Throwable $exception) {
            Log::error('No se pudo cargar detalle de Producto', ['id' => $id, 'error' => $exception->getMessage()]);
            $producto = null;
        }

        abort_if(! $producto, 404);

        $categorias = ['Semillas', 'Cereales', 'Leguminosas', 'Frutos Secos', 'Oleaginosas'];
        $categoriaCounts = array_fill_keys($categorias, 0);
        try {
            $todos = collect($this->firestore->getProductos());
            foreach ($categorias as $cat) {
                $categoriaCounts[$cat] = $todos->where('categoria', $cat)->count();
            }
        } catch (Throwable) {
        }

        return view('public.producto-detalle', compact('producto', 'categorias', 'categoriaCounts'));
    }

    public function sobre()
    {
        return view('public.sobre', [
            'title' => 'Sobre nosotros — AgroMec Smart',
        ]);
    }

    public function contacto()
    {
        return view('public.contacto');
    }

    public function storeContacto(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:180'],
            'asunto' => ['required', 'string', 'max:150'],
            'mensaje' => ['required', 'string', 'max:3000'],
        ]);

        try {
            $this->firestore->storeContacto($validated);
        } catch (Throwable $exception) {
            Log::error('No se pudo guardar contacto', ['error' => $exception->getMessage()]);

            return redirect()
                ->route('contacto')
                ->with('status', 'No se pudo enviar el mensaje en este momento.');
        }

        return redirect()
            ->route('contacto')
            ->with('status', 'Mensaje enviado correctamente.');
    }
}
