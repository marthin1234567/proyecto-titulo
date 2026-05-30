<?php

namespace App\Livewire\Shop;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class Carrito extends Component
{
    public array $items = [];

    public string $message = '';

    private string $uid = '';

    private string $email = '';

    public function mount(?string $add = null): void
    {
        $this->uid = (string) session('firebase.uid', '');
        $this->email = (string) session('firebase.email', '');

        $this->items = (array) session('cart.items', []);

        try {
            if ($this->uid !== '' && $this->items === []) {
                $this->items = app(FirestoreRepository::class)->getCart($this->uid);
            }

            if ($add) {
                $this->addProduct($add);
            }
        } catch (Throwable $exception) {
            Log::error('Error cargando carrito', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo cargar el carrito desde Firebase.';
        }

        $this->persistCart();
    }

    public function increment(string $productoId): void
    {
        foreach ($this->items as &$item) {
            if (($item['productoId'] ?? '') === $productoId) {
                $item['cantidad'] = (int) ($item['cantidad'] ?? 1) + 1;
                $item['subtotal'] = round((float) $item['cantidad'] * (float) ($item['precioUnitario'] ?? 0), 2);
                break;
            }
        }

        $this->persistCart();
    }

    public function decrement(string $productoId): void
    {
        foreach ($this->items as $index => &$item) {
            if (($item['productoId'] ?? '') !== $productoId) {
                continue;
            }

            $cantidad = (int) ($item['cantidad'] ?? 1) - 1;
            if ($cantidad <= 0) {
                unset($this->items[$index]);
            } else {
                $item['cantidad'] = $cantidad;
                $item['subtotal'] = round((float) $cantidad * (float) ($item['precioUnitario'] ?? 0), 2);
            }

            break;
        }

        $this->items = array_values($this->items);
        $this->persistCart();
    }

    public function remove(string $productoId): void
    {
        $this->items = array_values(array_filter(
            $this->items,
            fn (array $item): bool => (string) ($item['productoId'] ?? '') !== $productoId
        ));

        $this->persistCart();
    }

    public function clear(): void
    {
        $this->items = [];
        $this->persistCart();
    }

    public function checkout(): void
    {
        if ($this->uid === '' || $this->items === []) {
            $this->message = 'No hay productos para confirmar.';

            return;
        }

        try {
            app(FirestoreRepository::class)->createPedido($this->uid, $this->email, $this->items);
            $this->items = [];
            $this->persistCart();
            $this->message = 'Pedido creado correctamente.';
        } catch (Throwable $exception) {
            Log::error('Error creando pedido desde carrito', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo crear el pedido en este momento.';
        }
    }

    public function getTotalProperty(): float
    {
        return round(collect($this->items)->sum(fn (array $item) => (float) ($item['subtotal'] ?? 0)), 2);
    }

    private function addProduct(string $productId): void
    {
        $producto = app(FirestoreRepository::class)->getProductoById($productId);
        if (! $producto) {
            $this->message = 'Producto no encontrado.';

            return;
        }

        foreach ($this->items as &$item) {
            if (($item['productoId'] ?? '') === $productId) {
                $item['cantidad'] = (int) ($item['cantidad'] ?? 1) + 1;
                $item['subtotal'] = round((float) $item['cantidad'] * (float) ($item['precioUnitario'] ?? 0), 2);
                $this->message = 'Producto agregado al carrito.';

                return;
            }
        }

        $precio = (float) ($producto['precioUnitario'] ?? 0);

        $this->items[] = [
            'productoId' => (string) $productId,
            'productoNombre' => (string) ($producto['nombre'] ?? 'Producto'),
            'productoImagenUrl' => (string) ($producto['imagenUrl'] ?? ''),
            'cantidad' => 1,
            'precioUnitario' => $precio,
            'subtotal' => $precio,
        ];

        $this->message = 'Producto agregado al carrito.';
    }

    private function persistCart(): void
    {
        session(['cart.items' => array_values($this->items)]);

        if ($this->uid === '') {
            return;
        }

        try {
            $repo = app(FirestoreRepository::class);

            if ($this->items === []) {
                $repo->clearCart($this->uid);
            } else {
                $repo->saveCart($this->uid, $this->items);
            }
        } catch (Throwable $exception) {
            Log::error('Error persistiendo carrito', ['error' => $exception->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.shop.carrito')->layout('layouts.public');
    }
}
