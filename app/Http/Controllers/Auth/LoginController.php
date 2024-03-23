<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $login = $request->validated();

        if (!Auth::attempt($login)) {
            return response()->json(
                [
                    'error' => [
                        'message' => 'Invalid credentials',
                    ],
                ],
                401
            );
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(
            [
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ]
        );
    }
}
