<?php

namespace App\Services\Firebase;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Auth\UserRecord;
use RuntimeException;
use Throwable;

class FirebaseAuthService
{
    public function __construct(private readonly FirebaseClientFactory $factory)
    {
    }

    public function signInWithEmailPassword(string $email, string $password): array
    {
        $apiKey = config('services.firebase.api_key');

        if (! $apiKey) {
            throw new RuntimeException('FIREBASE_API_KEY is not configured.');
        }

        $response = Http::asJson()->post(
            'https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key='.$apiKey,
            [
                'email' => $email,
                'password' => $password,
                'returnSecureToken' => true,
            ]
        );

        if ($response->failed()) {
            throw new RuntimeException('Unable to sign in with Firebase credentials.');
        }

        return $response->json();
    }

    public function getUserProfile(string $uid): ?array
    {
        $documents = $this->factory
            ->firestore()
            ->database()
            ->collection('usuarios')
            ->where('uid', '=', $uid)
            ->limit(1)
            ->documents();

        foreach ($documents as $document) {
            if (! $document->exists()) {
                continue;
            }

            return $this->sanitizeDoc(Arr::add($document->data(), 'id', $document->id()));
        }

        return null;
    }

    public function getUserByEmail(string $email): ?UserRecord
    {
        try {
            return $this->factory->auth()->getUserByEmail($email);
        } catch (Throwable) {
            return null;
        }
    }

    public function createUser(string $email, string $password, string $displayName): UserRecord
    {
        return $this->factory->auth()->createUser([
            'email' => $email,
            'password' => $password,
            'displayName' => $displayName,
        ]);
    }

    public function updateUser(string $uid, array $attributes): void
    {
        $this->factory->auth()->updateUser($uid, $attributes);
    }

    public function deleteUser(string $uid): void
    {
        $this->factory->auth()->deleteUser($uid);
    }

    private function sanitizeDoc(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(fn ($v) => $this->sanitizeDoc($v), $value);
        }
        if ($value instanceof \Google\Cloud\Core\Timestamp) {
            return $value->get()->format(DATE_ATOM);
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }
        if (is_object($value)) {
            return method_exists($value, '__toString') ? (string) $value : '';
        }
        return $value;
    }
}
