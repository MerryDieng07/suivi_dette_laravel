<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;
use App\Services\AuthentificationServiceInterface;

class PassportAuthentificationService implements AuthentificationServiceInterface
{
    protected $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function authenticate(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 401,
                'message' => 'Identifiants incorrects',
            ], 401);
        }
        
        $user = User::find(Auth::user()->id);
        $tokenResult = $user->createToken('Personal Access Token')->accessToken;

        return response()->json([
            'status' => 200,
            'data' => ['token' => $tokenResult],
            'message' => 'Connexion réussie',
        ], 200);
    }

    public function logout()
    {
        $user = User::find(Auth::user()->id);
        $this->$user->revokeAll($user->id);
        Auth::logout();
    }
}
