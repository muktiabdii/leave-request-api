<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Helper\ApiResponse;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateProfileRequest;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function profile()
    {
        $user = $this->userService->getCurrentUser();

        return ApiResponse::success(
            new UserResource($user),
            'User retrieved successfully'
        );
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->userService->updateProfile($request->validated());

        return ApiResponse::success(
            new UserResource($user),
            'Profile updated successfully'
        );
    }
}
