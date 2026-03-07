<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AuthLoginRequest;
use App\Models\User;
use App\Permissions\Api\V1\Abilities;
use App\Traits\Api\V1\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(AuthLoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error(
                'Invalid credentials', 
                [],
                401
            );
        }

        $user = User::firstWhere('email', $request->email);
        $expiration = config('sanctum.expiration');
        $expiresAt = now()->addMinutes($expiration);
        $token = $user->createToken(
            'access-token',
            Abilities::get($user),
            $expiresAt
        );

        return $this->success(
            "Authenticated",
            [
                'token' => $token->plainTextToken,
                'expiresAt' => $expiresAt->toISOString(),
                'expiresIn' => $expiration * 60,
            ],
            200
        );
    }

    public function refresh()
    {
        // Generate and return new access token.
    }

    public function logout()
    {
        // Delete access token.
    }
}
