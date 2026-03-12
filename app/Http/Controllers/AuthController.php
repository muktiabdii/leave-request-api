<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return ApiResponse::success(
            new UserResource($user),
            'User registered successfully',
            201
        );
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return ApiResponse::error('Invalid email or password', 401);
        }

        return ApiResponse::success([
            'user' => new UserResource($result->user),
            'token' => $result->token,
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
            'user' => new UserResource($result->user),
            'token' => $result->token,
            'token_type' => 'Bearer'
        ], 'User logged in with Google successfully');
    }

    public function logout()
    {
        $this->authService->logout();

        return ApiResponse::success(
            null,
            'User logged out successfully'
        );
    }
}
