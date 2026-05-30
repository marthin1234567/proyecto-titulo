<?php

namespace App\Livewire\Shop;

use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class CartSidepanel extends Component
{
    public array $items = [];

    public string $message = '';

    public bool $confirming = false;

    public string $nota = '';

    private string $uid = '';

    private string $email = '';

    public function mount(): void
    {
        $this->uid = (string) session('firebase.uid', '');
        $this->email = (string) session('firebase.email', '');

        $this->items = (array) session('cart.items', []);

        try {
            if ($this->uid !== '' && $this->items === []) {
                $this->items = app(FirestoreRepository::class)->getCart($this->uid);
            }
        } catch (Throwable $exception) {
            Log::error('Error cargando carrito (sidepanel)', ['error' => $exception->getMessage()]);
        }

        $this->persistCart();
    }

    #[On('cart-add')]
    public function onCartAdd(string $id = ''): void
    {
        if ($id === '') {
            return;
        }
        $this->addProduct($id);
        $this->persistCart();
        $this->dispatch('cart-open');
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

    public function startCheckout()
    {
        $this->uid = (string) session('firebase.uid', '');

        if ($this->items === []) {
            $this->message = 'Tu carrito está vacío.';
            return null;
        }

        if ($this->uid === '') {
            session(['cart.checkout_pending' => true]);
            return redirect()->route('auth.client.show');
        }

        $this->confirming = true;
        $this->message = '';

        return null;
    }

    public function cancelConfirm(): void
    {
        $this->confirming = false;
        $this->nota = '';
    }

    public function placeOrder()
    {
        $this->uid   = (string) session('firebase.uid', '');
        $this->email = (string) session('firebase.email', '');

        if ($this->uid === '' || $this->items === []) {
            $this->confirming = false;
            return null;
        }

        try {
            $pedidoId = app(FirestoreRepository::class)->createPedido(
                $this->uid,
                $this->email,
                $this->items,
                $this->nota,
            );

            $this->items     = [];
            $this->confirming = false;
            $this->nota      = '';
            $this->persistCart();

            return redirect()->route('pedido.confirmado', ['id' => $pedidoId]);
        } catch (Throwable $exception) {
            Log::error('Error creando pedido desde sidepanel', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo crear el pedido en este momento.';
        }

        return null;
    }

    public function getCountProperty(): int
    {
        return (int) collect($this->items)->sum(fn (array $item) => (int) ($item['cantidad'] ?? 0));
    }

    public function getTotalProperty(): float
    {
        return round(collect($this->items)->sum(fn (array $item) => (float) ($item['subtotal'] ?? 0)), 2);
    }

    private function addProduct(string $productId): void
    {
        try {
            $producto = app(FirestoreRepository::class)->getProductoById($productId);
        } catch (Throwable $exception) {
            Log::error('Error cargando producto para carrito', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo cargar el producto.';

            return;
        }

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

        $uid = (string) session('firebase.uid', '');
        if ($uid === '') {
            return;
        }

        try {
            $repo = app(FirestoreRepository::class);

            if ($this->items === []) {
                $repo->clearCart($uid);
            } else {
                $repo->saveCart($uid, $this->items);
            }
        } catch (Throwable $exception) {
            Log::error('Error persistiendo carrito (sidepanel)', ['error' => $exception->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.shop.cart-sidepanel');
    }
}

