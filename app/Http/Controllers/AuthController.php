<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return ApiResponse::success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ], 'User registered successfully', 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return ApiResponse::error('Invalid email or password', 401);
        }

        return ApiResponse::success([
            'user' => [
                'id' => $result['user']->id,
                'name' => $result['user']->name,
                'email' => $result['user']->email,
                'role' => $result['user']->role,
            ],
            'token' => $result['token'],
            'token_type' => 'Bearer'
        ], 'User logged in successfully');
    }

    public function redirectToGoogle()
    {
        return $this->authService->redirectToGoogle();
    }

    public function handleGoogleCallback()
    {
        $result = $this->authService->handleGoogleCallback();

        if (!$result) {
            return ApiResponse::error('Google authentication failed', 401);
        }

        return ApiResponse::success([
            'user' => [
                'id' => $result['user']->id,
                'name' => $result['user']->name,
                'email' => $result['user']->email,
                'role' => $result['user']->role,
            ],
            'token' => $result['token'],
            'token_type' => 'Bearer'
        ], 'User logged in with Google successfully');
    }
}
