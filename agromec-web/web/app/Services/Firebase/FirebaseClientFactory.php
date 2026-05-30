<?php

namespace App\Services\Firebase;

use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Factory;

class FirebaseClientFactory
{
    private ?Factory $factory = null;
    private ?FirestoreClient $firestoreClient = null;

    public function auth(): Auth
    {
        return $this->factory()->createAuth();
    }

    public function firestore(): Firestore
    {
        return $this->factory()->createFirestore();
    }

    public function firestoreClient(): FirestoreClient
    {
        if ($this->firestoreClient instanceof FirestoreClient) {
            return $this->firestoreClient;
        }

        $credentials = $this->resolveCredentialsPath(config('services.firebase.credentials'));
        if ($credentials && file_exists($credentials)) {
            putenv("GOOGLE_APPLICATION_CREDENTIALS={$credentials}");
        }

        return $this->firestoreClient = new FirestoreClient([
            'projectId' => config('services.firebase.project_id'),
            'transport' => 'rest',
        ]);
    }

    private function factory(): Factory
    {
        if ($this->factory instanceof Factory) {
            return $this->factory;
        }

        $factory = new Factory();

        $credentials = $this->resolveCredentialsPath(config('services.firebase.credentials'));
        if ($credentials && file_exists($credentials)) {
            // Requerido por google/cloud-firestore para ApplicationDefaultCredentials
            putenv("GOOGLE_APPLICATION_CREDENTIALS={$credentials}");
            $_ENV['GOOGLE_APPLICATION_CREDENTIALS'] = $credentials;

            $factory = $factory->withServiceAccount($credentials);
        }

        $projectId = config('services.firebase.project_id');
        if ($projectId) {
            $factory = $factory->withProjectId($projectId);
        }

        return $this->factory = $factory;
    }

    /**
     * Resuelve la ruta de credenciales: si es una ruta absoluta existente la
     * devuelve tal cual; si no, la busca relativa a la raíz del proyecto.
     * Así el .env puede contener solo "sdk.json" y funciona sin importar
     * dónde esté la carpeta del proyecto.
     */
    private function resolveCredentialsPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (file_exists($path)) {
            return $path;
        }

        $relative = base_path($path);

        return file_exists($relative) ? $relative : $path;
    }
}
