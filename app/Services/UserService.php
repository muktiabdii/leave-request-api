<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UserService
{
    public function getCurrentUser()
    {
        return Auth::user();
    }

    public function updateProfile($data)
    {
        $user = $this->getCurrentUser();
        $user->update($data);
        return $user->fresh();
    }
}