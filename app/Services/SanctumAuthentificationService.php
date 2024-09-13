<?php


namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class SanctumAuthentificationService implements AuthentificationServiceInterface
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('login', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('login', $credentials['login'])->firstOrFail();
            $token = $user->createToken('SanctumToken')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->only('login', 'password');

        // Validation logic here (e.g., using Laravel's validator)

        $user = User::create([
            'login' => $data['login'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('SanctumToken')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();
            Auth::logout();

            return response()->json(['message' => 'Logged out successfully'], 200);
        }

        return response()->json(['error' => 'No user authenticated'], 401);
    }
}
