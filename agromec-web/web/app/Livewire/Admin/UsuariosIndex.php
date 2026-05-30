<?php

namespace App\Livewire\Admin;

use App\Services\Firebase\FirebaseAuthService;
use App\Services\Firebase\FirestoreRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class UsuariosIndex extends Component
{
    public array $usuarios = [];

    public string $nombre = '';

    public string $email = '';

    public string $password = '';

    public string $rol = 'cliente';

    public string $message = '';

    public function mount(): void
    {
        $this->loadUsuarios();
    }

    public function createUser(): void
    {
        $data = $this->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:180'],
            'password' => ['required', 'string', 'min:6'],
            'rol' => ['required', 'in:cliente,compras,cotizaciones,admin'],
        ]);

        try {
            $auth = app(FirebaseAuthService::class);
            $repo = app(FirestoreRepository::class);

            $user = $auth->createUser($data['email'], $data['password'], $data['nombre']);

            $repo->upsertUsuario($user->uid, [
                'nombre' => $data['nombre'],
                'email' => $data['email'],
                'rol' => $data['rol'],
            ]);

            if ($data['rol'] === 'cliente') {
                $repo->upsertClienteByEmail([
                    'nombre' => $data['nombre'],
                    'empresa' => '',
                    'email' => $data['email'],
                    'telefono' => '',
                    'direccion' => '',
                ]);
            }

            $this->reset(['nombre', 'email', 'password']);
            $this->rol = 'cliente';
            $this->message = 'Usuario creado correctamente.';
            $this->loadUsuarios();
        } catch (Throwable $exception) {
            Log::error('Error creando usuario admin', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo crear el usuario.';
        }
    }

    public function updateRole(string $uid, string $rol): void
    {
        if (! in_array($rol, ['cliente', 'compras', 'cotizaciones', 'admin'], true)) {
            return;
        }

        try {
            $repo = app(FirestoreRepository::class);
            $user = collect($this->usuarios)->firstWhere('uid', $uid);

            if (! $user) {
                return;
            }

            $repo->upsertUsuario($uid, [
                'nombre' => (string) ($user['nombre'] ?? ''),
                'email' => (string) ($user['email'] ?? ''),
                'rol' => $rol,
            ]);

            if ($rol === 'cliente') {
                $repo->upsertClienteByEmail([
                    'nombre' => (string) ($user['nombre'] ?? ''),
                    'empresa' => '',
                    'email' => (string) ($user['email'] ?? ''),
                    'telefono' => '',
                    'direccion' => '',
                ]);
            }

            $this->message = 'Rol actualizado.';
            $this->loadUsuarios();
        } catch (Throwable $exception) {
            Log::error('Error actualizando rol usuario', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo actualizar el rol.';
        }
    }

    public function deleteUser(string $uid): void
    {
        try {
            $repo = app(FirestoreRepository::class);
            $auth = app(FirebaseAuthService::class);

            $user = collect($this->usuarios)->firstWhere('uid', $uid);

            $auth->deleteUser($uid);
            $repo->deleteUsuarioByUid($uid);

            if ($user && isset($user['email'])) {
                $repo->deleteClienteByEmail((string) $user['email']);
            }

            $this->message = 'Usuario eliminado.';
            $this->loadUsuarios();
        } catch (Throwable $exception) {
            Log::error('Error eliminando usuario', ['error' => $exception->getMessage()]);
            $this->message = 'No se pudo eliminar el usuario.';
        }
    }

    private function loadUsuarios(): void
    {
        try {
            $this->usuarios = collect(app(FirestoreRepository::class)->getUsuarios())
                ->map(function (array $user): array {
                    $user['uid'] = (string) ($user['uid'] ?? $user['id'] ?? '');

                    return $user;
                })
                ->sortBy('nombre')
                ->values()
                ->all();
        } catch (Throwable $exception) {
            Log::error('Error cargando usuarios admin', ['error' => $exception->getMessage()]);
            $this->usuarios = [];
        }
    }

    public function render()
    {
        return view('livewire.admin.usuarios-index')->layout('layouts.admin');
    }
}
