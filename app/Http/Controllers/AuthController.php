<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Enums\EtatEnum;
use App\Services\AuthentificationServiceInterface;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthentificationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        // Validation des données de la requête
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'data' => $validator->errors(),
                'message' => 'Erreur de validation',
            ], 400);
        }

        // Tentative de connexion avec les identifiants fournis
        if (Auth::attempt(['login' => $request->login, 'password' => $request->password])) {
            $user = User::where('login', $request->login)->first();
            $tokenResult = $user->createToken('Personal Access Token', ['*']);
            $token = $tokenResult->accessToken;

            // Réponse en cas de succès
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
        } else {
            // Réponse en cas d'échec
            return response()->json([
                'status' => 401,
                'data' => null,
                'message' => 'Login ou mot de passe incorrect',
            ]);
        }
    }

    public function register(Request $request)
    {
        // Validation des données de la requête
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:255', 'unique:users,login'],
            'password' => [
                'required',
                'string',
                'min:5',
                'regex:/[a-z]/', // au moins une lettre minuscule
                'regex:/[A-Z]/', // au moins une lettre majuscule
                'regex:/[0-9]/', // au moins un chiffre
                'regex:/[@$!%*#?&]/' // au moins un caractère spécial
            ],
            'role_id' => ['required', 'integer'],
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
            // Création d'un nouvel utilisateur
            $user = new User();
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->login = $request->login;
            $user->role_id = $request->role_id;
            $user->password = bcrypt($request->password);
            $user->etat = $request->etat ?? 'ACTIF';

            // Enregistrement de la photo si elle est présente
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('photos', 'public');
                $user->photo = $path;
            }

            // Sauvegarde de l'utilisateur en base de données
            $user->save();

            return response()->json([
                'status' => 201,
                'data' => ['user' => $user],
                'message' => 'Utilisateur enregistré avec succès'
            ]);
        } catch (\Exception $e) {
            // Gestion des erreurs lors de l'enregistrement
            return response()->json([
                'status' => 500,
                'data' => null,
                'message' => 'Erreur lors de l\'enregistrement de l\'utilisateur',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        // Révocation du jeton d'accès de l'utilisateur
        $token = $request->user()->token();
        $token->revoke();

        return response()->json([
            'status' => 200,
            'data' => null,
            'message' => 'Déconnexion réussie'
        ]);
    }
}
