<?php


namespace App\Http\Requests;

use App\Models\User;
use App\Enums\EtatEnum;
use App\Rules\CustumPasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest

    // public function authorize()
{
//     return $this->user()->can('create', ['client']);
// }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'login' => 'required|string|unique:users|max:255',
            'role_id' => 'required|numeric|exists:roles,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'etat' => 'required|string|in:' . implode(',', array_map(fn($case) => $case->value, EtatEnum::cases())),
            'password' => ['required', 'string', 'min:6'], // Ajout de "confirmed" et la règle personnalisée
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'login.required' => 'Le login est obligatoire.',
            'login.unique' => "Cet login est déjà utilisé.",
            'role_id.required' => 'Le rôle est obligatoire.',
            'role_id.exists' => 'Le rôle spécifié n\'existe pas.',
            'photo.image' => 'Le fichier photo doit être une image.',
            'photo.mimes' => 'La photo doit être au format jpeg, png, jpg ou gif.',
            'photo.max' => 'La photo ne doit pas dépasser 2 Mo.',
            'etat.required' => 'L\'état est obligatoire.',
            'etat.in' => 'L\'état doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            // 'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                ['message' => 'Erreur de validation', 'errors' => $validator->errors()],
                422
            )
        );
    }
}
