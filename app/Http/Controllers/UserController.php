<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        // Récupérer le paramètre 'active' de la requête
        $active = $request->query('active');

        // Construire la requête de filtrage
        $query = User::query();

        // Appliquer le filtrage par statut actif/inactif si le paramètre est fourni
        if ($active !== null) {
            $etat = $active === 'oui' ? 'ACTIF' : 'INACTIF';
            $query->where('etat', $etat);
        }

        // Récupérer les utilisateurs filtrés
        $users = $query->get();

        // Retourner la réponse en JSON avec la liste des utilisateurs ou null si vide
        return response()->json([
            'data' => $users->isEmpty() ? null : $users
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        //dd($request);
        $user = $this->userService->createUser($request->validated());
        return response()->json(['data' => new UserResource($user)], 201);
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
        }

        return response()->json(['data' => new UserResource($user)], 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated());
        return response()->json(['data' => new UserResource($user)], 200);
    }

    public function destroy($id)
{
    $result = $this->userService->deleteUser($id);

    if ($result) {
        return response()->json(['message' => 'User deleted successfully'], 200);
    } else {
        return response()->json(['message' => 'User not found'], 404);
    }
}

}