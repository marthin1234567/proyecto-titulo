<?php

namespace App\Services\Firebase;

use DateTimeImmutable;
use Illuminate\Support\Arr;

class FirestoreRepository
{
    public function __construct(private readonly FirebaseClientFactory $factory)
    {
    }

    private function db(): \Google\Cloud\Firestore\FirestoreClient
    {
        return $this->factory->firestoreClient();
    }

    public function getProductos(): array
    {
        return $this->documentsToArray($this->db()->collection('productos')->documents());
    }

    public function getProductoById(string $id): ?array
    {
        $snapshot = $this->db()->collection('productos')->document($id)->snapshot();

        if (! $snapshot->exists()) {
            return null;
        }

        return $this->sanitize(Arr::add($snapshot->data(), 'id', $snapshot->id()));
    }

    public function createProducto(array $data): void
    {
        $this->db()->collection('productos')->add($data);
    }

    public function updateProducto(string $id, array $data): void
    {
        $this->db()->collection('productos')->document($id)->set($data, ['merge' => true]);
    }

    public function deleteProducto(string $id): void
    {
        $this->db()->collection('productos')->document($id)->delete();
    }

    public function storeContacto(array $data): void
    {
        $this->db()->collection('contactos')->add(array_merge($data, [
            'fecha' => (new DateTimeImmutable())->format(DATE_ATOM),
        ]));
    }

    public function getPedidosByUser(string $uid): array
    {
        $items = $this->documentsToArray(
            $this->db()->collection('pedidos')->where('userId', '=', $uid)->documents()
        );
        usort($items, fn (array $a, array $b) => strcmp((string) ($b['fecha'] ?? ''), (string) ($a['fecha'] ?? '')));

        return $items;
    }

    public function getPedidos(): array
    {
        return $this->documentsToArray($this->db()->collection('pedidos')->documents());
    }

    public function updatePedidoEstado(string $id, string $estado): void
    {
        $this->db()->collection('pedidos')->document($id)->set(['estado' => $estado], ['merge' => true]);
    }

    public function getCotizacionesByUser(string $uid): array
    {
        $items = $this->documentsToArray(
            $this->db()->collection('cotizaciones')->where('userId', '=', $uid)->documents()
        );
        usort($items, fn (array $a, array $b) => strcmp((string) ($b['fecha'] ?? ''), (string) ($a['fecha'] ?? '')));

        return $items;
    }

    public function getCotizaciones(): array
    {
        return $this->documentsToArray($this->db()->collection('cotizaciones')->documents());
    }

    public function createCotizacion(array $data): void
    {
        $this->db()->collection('cotizaciones')->add($data);
    }

    public function updateCotizacion(string $id, array $data): void
    {
        $this->db()->collection('cotizaciones')->document($id)->set($data, ['merge' => true]);
    }

    public function deleteCotizacion(string $id): void
    {
        $this->db()->collection('cotizaciones')->document($id)->delete();
    }

    public function getOrdenesCompra(): array
    {
        return $this->documentsToArray($this->db()->collection('ordenes_compra')->documents());
    }

    public function createOrdenCompra(array $data): void
    {
        $this->db()->collection('ordenes_compra')->add($data);
    }

    public function updateOrdenCompra(string $id, array $data): void
    {
        $this->db()->collection('ordenes_compra')->document($id)->set($data, ['merge' => true]);
    }

    public function deleteOrdenCompra(string $id): void
    {
        $this->db()->collection('ordenes_compra')->document($id)->delete();
    }

    public function updateOrdenCompraEstado(string $id, string $estado): void
    {
        $this->db()->collection('ordenes_compra')->document($id)->set(['estado' => $estado], ['merge' => true]);
    }

    public function getProveedores(): array
    {
        return $this->documentsToArray($this->db()->collection('proveedores')->documents());
    }

    public function createProveedor(array $data): void
    {
        $this->db()->collection('proveedores')->add($data);
    }

    public function updateProveedor(string $id, array $data): void
    {
        $this->db()->collection('proveedores')->document($id)->set($data, ['merge' => true]);
    }

    public function deleteProveedor(string $id): void
    {
        $this->db()->collection('proveedores')->document($id)->delete();
    }

    public function getUsuarios(): array
    {
        return $this->documentsToArray($this->db()->collection('usuarios')->documents());
    }

    public function upsertUsuario(string $uid, array $data): void
    {
        $this->db()->collection('usuarios')->document($uid)->set(
            array_merge($data, ['uid' => $uid]),
            ['merge' => true]
        );
    }

    public function deleteUsuarioByUid(string $uid): void
    {
        $this->db()->collection('usuarios')->document($uid)->delete();
    }

    public function upsertClienteByEmail(array $data): void
    {
        $email = (string) ($data['email'] ?? '');
        if ($email === '') {
            return;
        }

        $existing = $this->getClienteByEmail($email);
        if ($existing && isset($existing['id'])) {
            $this->updateCliente((string) $existing['id'], $data);

            return;
        }

        $this->db()->collection('clientes')->add($data);
    }

    public function deleteClienteByEmail(string $email): void
    {
        $documents = $this->db()->collection('clientes')->where('email', '=', $email)->documents();

        foreach ($documents as $document) {
            if ($document->exists()) {
                $this->db()->collection('clientes')->document($document->id())->delete();
            }
        }
    }

    public function getConfiguracionGeneral(): array
    {
        $snapshot = $this->db()->collection('configuracion')->document('general')->snapshot();

        if (! $snapshot->exists()) {
            return [
                'nombreEmpresa' => 'AgroMec Smart',
                'direccionEmpresa' => '',
                'telefonoEmpresa' => '',
                'emailContacto' => '',
                'categorias' => ['Semillas', 'Cereales', 'Leguminosas', 'Frutos Secos', 'Oleaginosas'],
            ];
        }

        return $snapshot->data();
    }

    public function saveConfiguracionGeneral(array $data): void
    {
        $this->db()->collection('configuracion')->document('general')->set($data, ['merge' => true]);
    }

    public function getClientes(): array
    {
        return $this->documentsToArray($this->db()->collection('clientes')->documents());
    }

    public function updateClienteById(string $id, array $data): void
    {
        $this->db()->collection('clientes')->document($id)->set($data, ['merge' => true]);
    }

    public function getClienteByEmail(string $email): ?array
    {
        $documents = $this->db()->collection('clientes')->where('email', '=', $email)->limit(1)->documents();

        foreach ($documents as $document) {
            if (! $document->exists()) {
                continue;
            }

            return Arr::add($document->data(), 'id', $document->id());
        }

        return null;
    }

    public function updateCliente(string $id, array $data): void
    {
        $this->db()->collection('clientes')->document($id)->set($data, ['merge' => true]);
    }

    public function getCart(string $uid): array
    {
        $snapshot = $this->db()->collection('carts')->document($uid)->snapshot();

        if (! $snapshot->exists()) {
            return [];
        }

        return (array) ($snapshot->data()['items'] ?? []);
    }

    public function saveCart(string $uid, array $items): void
    {
        $this->db()->collection('carts')->document($uid)->set([
            'items' => array_values($items),
            'updatedAt' => (new DateTimeImmutable())->format(DATE_ATOM),
        ], ['merge' => true]);
    }

    public function clearCart(string $uid): void
    {
        $this->db()->collection('carts')->document($uid)->delete();
    }

    public function createPedido(string $uid, string $email, array $lineas, string $nota = ''): string
    {
        $total = collect($lineas)->sum(fn (array $linea) => (float) ($linea['subtotal'] ?? 0));

        $ref = $this->db()->collection('pedidos')->add([
            'userId'        => $uid,
            'clienteEmail'  => $email,
            'fecha'         => (new DateTimeImmutable())->format(DATE_ATOM),
            'estado'        => 'Pendiente',
            'total'         => round($total, 2),
            'lineas'        => array_values($lineas),
            'nota'          => $nota,
        ]);

        return $ref->id();
    }

    public function getPedidoById(string $id): ?array
    {
        $snapshot = $this->db()->collection('pedidos')->document($id)->snapshot();

        if (! $snapshot->exists()) {
            return null;
        }

        return $this->sanitize(Arr::add($snapshot->data(), 'id', $snapshot->id()));
    }

    private function documentsToArray(iterable $documents): array
    {
        $items = [];

        foreach ($documents as $document) {
            if (! $document->exists()) {
                continue;
            }

            $items[] = $this->sanitize(Arr::add($document->data(), 'id', $document->id()));
        }

        return $items;
    }

    /**
     * Convierte recursivamente valores no serializables de Firestore
     * (Google\Cloud\Core\Timestamp, DateTimeImmutable, etc.) a tipos
     * primitivos que Livewire pueda manejar.
     */
    private function sanitize(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(fn ($v) => $this->sanitize($v), $value);
        }

        // Timestamps de Firestore (Google\Cloud\Core\Timestamp)
        if ($value instanceof \Google\Cloud\Core\Timestamp) {
            return $value->get()->format(DATE_ATOM);
        }

        // Cualquier DateTimeInterface
        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        // Otros objetos: intentar convertir a string
        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return (string) $value;
            }
            // Fallback: serializar como JSON
            return json_encode($value) ?: '';
        }

        return $value;
    }
}
