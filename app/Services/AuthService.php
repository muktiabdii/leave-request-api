<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService {
    public function register(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role']
        ]);
    }

    public function login(array $data) {
        $user = User::firstWhere('email', $data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}