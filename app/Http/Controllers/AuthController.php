<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use App\Enums\EtatEnum;


class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(),[
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
        

        

        if (Auth::attempt(['login' => $request->login, 'password' => $request->password])) {
            
$user = User::find(1);

$token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json([
                'status' => 200,
                'data' => ['token' => $token],
                'message' => 'Connexion réussie'
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'data' => null,
                'message' => 'Login ou mot de passe incorrect'
            ]);
        }
    }

    

    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nom' => ['required', 'string', 'max:255'],
        'prenom' => ['required', 'string', 'max:255'],
        'login' => ['required', 'string', 'max:255', 'unique:users,login'],
        'password' => ['required', 'string', 'min:5',
            'regex:/[a-z]/',              // au moins une lettre minuscule
            'regex:/[A-Z]/',              // au moins une lettre majuscule
            'regex:/[0-9]/',              // au moins un chiffre
            'regex:/[@$!%*#?&]/'          // au moins un caractère spécial
        ],
        'roleId' => ['required', 'integer'],
        'photo' => 'nullable|image|max:2048',  // Modifié de required à nullable
        'etat' => 'required|string|in:' . implode(',', array_map(fn($case) => $case->value, EtatEnum::cases())),
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'data' => $validator->errors(),
            'message' => 'Erreur de validation',
        ], 400);
    }

    $user = new User();
    $user->nom = $request->nom;
    $user->prenom = $request->prenom;
    $user->login = $request->login;
    $user->roleId = $request->roleId;
    $user->password = bcrypt($request->password);
    $user->etat = $request->etat ?? 'ACTIF';
    // Upload image
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('photos', 'public');
        $user->photo = $path;
    }

    $user->save();

   
    return response()->json([
        'status' => 201,
        'data' => [
            'user' => $user,
             
        ],
        'message' => ' utilisateur enregistrés avec succès'
    ]);
}

public function logout (Request $request) {
    $token = $request->user()->token();
    $token->revoke();
    return response()->json([
        'status' => 200,
        'data' => null,
        'message' => 'Déconnexion réussie'
    ]);
}

}

