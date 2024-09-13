<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PassportAuthentificationService implements AuthentificationServiceInterface
{
    use HasApiTokens;

    public function login(Request $request): JsonResponse
    {
        // Valider les données
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Trouver l'utilisateur par son login (ou email si tu le veux)
        $user = User::where('login', $credentials['login'])->first();

        // Créer un token Passport pour l'utilisateur
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        // Valider les données d'enregistrement
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Créer un nouvel utilisateur
        $user = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'login' => $data['login'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);

        // Créer un token pour l'utilisateur enregistré
        $tokenResult = $user->createToken('Personal Access Token');

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        // Révoquer le token de l'utilisateur
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
