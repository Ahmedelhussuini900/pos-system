<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function loginByEmail(string $email, string $password): ?array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        return $this->createTokenResponse($user);
    }

    public function loginByPin(string $pinCode): ?array
    {
        $user = User::where('pin_code', $pinCode)->first();

        if (! $user) {
            return null;
        }

        return $this->createTokenResponse($user);
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    private function createTokenResponse(User $user): array
    {
        $token = $user->createToken('pos-token')->plainTextToken;

        return [
            'token' => $token,
            'user'  => new UserResource($user->load('role.abilities')),
        ];
    }
}
