<?php

namespace App\Console\Commands;

use App\Services\Firebase\FirebaseAuthService;
use App\Services\Firebase\FirebaseClientFactory;
use App\Services\Firebase\FirestoreRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Throwable;

class CreateAdminUser extends Command
{
    protected $signature   = 'admin:create {email} {password?} {nombre?} {rol=admin}';
    protected $description = 'Crea o actualiza un usuario administrador en Firebase + Firestore';

    public function handle(): int
    {
        $email    = $this->argument('email');
        $password = $this->argument('password');
        $nombre   = $this->argument('nombre');
        $rol      = $this->argument('rol');

        if (! in_array($rol, ['admin', 'compras', 'cotizaciones'])) {
            $this->error("Rol inválido. Usa 'admin', 'compras' o 'cotizaciones'.");
            return 1;
        }

        $this->info("Creando/actualizando usuario admin: {$email}");

        // Forzar credenciales para el contexto CLI
        $credentialsPath = config('services.firebase.credentials');
        if ($credentialsPath && file_exists($credentialsPath)) {
            putenv("GOOGLE_APPLICATION_CREDENTIALS={$credentialsPath}");
            $_ENV['GOOGLE_APPLICATION_CREDENTIALS'] = $credentialsPath;
        }

        $factory = app(FirebaseClientFactory::class);
        $auth    = $factory->auth();
        $db      = $factory->firestoreClient();

        // Crear o actualizar en Firebase Auth
        $uid = null;
        try {
            $user = $auth->getUserByEmail($email);
            $uid  = (string) $user->uid;

            if ($password) {
                $auth->changeUserPassword($uid, $password);
                $this->line("  ✓ Usuario existente en Firebase Auth (UID: {$uid}) — contraseña actualizada");
            } else {
                $this->line("  ✓ Usuario existente en Firebase Auth (UID: {$uid}) — contraseña sin cambios");
            }

            if (! $nombre) {
                $nombre = (string) ($user->displayName ?? $email);
            }
        } catch (Throwable) {
            if (! $password) {
                $this->error("  ✗ El usuario no existe en Firebase Auth y no se proporcionó contraseña.");
                return 1;
            }

            try {
                $user = $auth->createUser([
                    'email'       => $email,
                    'password'    => $password,
                    'displayName' => $nombre ?? $email,
                ]);
                $uid = (string) $user->uid;
                $this->line("  ✓ Usuario creado en Firebase Auth (UID: {$uid})");
            } catch (Throwable $e) {
                $this->error("  ✗ Error Firebase Auth: " . $e->getMessage());
                return 1;
            }
        }

        // Upsert perfil en Firestore → colección 'usuarios' (directo con firestoreClient)
        try {
            $existingDocs = $db->collection('usuarios')
                ->where('uid', '=', $uid)
                ->limit(1)
                ->documents();

            $docRef = null;
            foreach ($existingDocs as $doc) {
                if ($doc->exists()) {
                    $docRef = $db->collection('usuarios')->document($doc->id());
                    break;
                }
            }

            $data = ['uid' => $uid, 'nombre' => $nombre ?? $email, 'email' => $email, 'rol' => $rol];

            if ($docRef) {
                $docRef->set($data, ['merge' => true]);
            } else {
                $db->collection('usuarios')->add($data);
            }

            $this->line("  ✓ Perfil guardado en Firestore (rol: {$rol})");
        } catch (Throwable $e) {
            $this->error("  ✗ Error Firestore: " . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info("✅ Admin listo:");
        $this->table(['Campo', 'Valor'], [
            ['Email',      $email],
            ['Contraseña', $password],
            ['Nombre',     $nombre],
            ['Rol',        $rol],
            ['UID',        $uid],
        ]);

        return 0;
    }
}
