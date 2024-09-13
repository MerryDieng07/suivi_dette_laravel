<?php


namespace App\Services;

use Log;
use App\Models\User;
use App\Enums\EtatEnum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Validator;

class AuthentificationService implements AuthentificationServiceInterface
{
    public function login(Request $request): JsonResponse
    {
        // Validation des données de la requête
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'data' => $validator->errors(),
                'message' => 'Erreur de validation',
            ], 400);
        }

        // Tentative de connexion
        if (Auth::attempt(['login' => $request->login, 'password' => $request->password])) {
            $user = User::where('login', $request->login)->first();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->accessToken;

            return response()->json([
                'status' => 200,
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'expires_at' => $tokenResult->token->expires_at,
                    'user' => [
                        'nom' => $user->nom,
                        'prenom' => $user->prenom,
                        'login' => $user->login,
                        'role_id' => $user->role_id,
                    ],
                ],
                'message' => 'Connexion réussie',
            ]);
        }

        return response()->json([
            'status' => 401,
            'data' => null,
            'message' => 'Login ou mot de passe incorrect',
        ]);
    }

   public function register(Request $request): JsonResponse
{
    // Validation des données de la requête
    $validator = Validator::make($request->all(), [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'login' => 'required|string|max:255|unique:users,login',
        'password' => [
            'required',
            'string',
            'min:5',
            'regex:/[a-z]/', // Contient au moins une minuscule
            'regex:/[A-Z]/', // Contient au moins une majuscule
            'regex:/[0-9]/', // Contient au moins un chiffre
            'regex:/[@$!%*#?&]/' // Contient au moins un caractère spécial
        ],
        'role_id' => 'required|integer',
        'photo' => 'nullable|image|max:2048',
        'etat' => 'required|string|in:' . implode(',', array_map(fn($case) => $case->value, EtatEnum::cases())),
    ]);

    // Si la validation échoue
    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'data' => $validator->errors(),
            'message' => 'Erreur de validation',
        ], 400);
    }

    try {
        // Création de l'utilisateur
        $user = new User();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->login = $request->login;
        $user->role_id = $request->role_id;
        $user->password = bcrypt($request->password); // Hashage du mot de passe
        $user->etat = $request->etat ?? 'ACTIF';

        // Gestion de la photo (si fournie)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public'); // Stockage de la photo
            $user->photo = $path;
        }

        // Sauvegarde de l'utilisateur dans la base de données
        $user->save();

        // Retourner une réponse JSON avec succès
        return response()->json([
            'status' => 201,
            'data' => ['user' => $user],
            'message' => 'Utilisateur enregistré avec succès'
        ]);
    } catch (\Exception $e) {
        FacadesLog::error("Erreur lors de l'enregistrement de l'utilisateur : " . $e->getMessage());

        return response()->json([
            'status' => 500,
            'data' => null,
            'message' => 'Erreur lors de l\'enregistrement de l\'utilisateur',
        ], 500);
    }
}

    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json([
            'status' => 200,
            'data' => null,
            'message' => 'Déconnexion réussie'
        ]);
    }
}
