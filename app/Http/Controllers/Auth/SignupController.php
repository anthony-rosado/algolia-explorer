<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SignupController extends Controller
{
    public function __invoke(SignupRequest $request): JsonResponse
    {
        $signup = $request->validated();

        $userExists = User::query()->where('email', $signup['email'])->exists();

        if ($userExists) {
            return response()->json(
                [
                    'error' => [
                        'message' => 'User already exists',
                    ],
                ],
                400
            );
        }

        /** @var User $user */
        $user = User::query()->create($signup);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(
                [
                    'error' => [
                        'message' => 'Invalid credentials',
                    ],
                ],
                400
            );
        }

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
