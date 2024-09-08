<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class SanctumAuthentificationService implements AuthentificationServiceInterface
{
    public function authenticate(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = User::where('login',$credentials['login'])->firstOrFail();
            $token = $user->createToken('SanctumToken')->plainTextToken;
            return ['token' => $token];
        }
        
        return ['error' => 'Unauthorized'];
    }

    public function logout()
    {
        $user = User::find(Auth::user()->id);
        $user->tokens()->delete();
        Auth::logout();
    }
}
