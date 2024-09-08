<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use App\Enums\StateEnum;
use App\Enums\UserRole;
use App\Rules\CustumPasswordRule;
use App\Rules\PasswordRules;
use App\Enums\EtatEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function Rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'login' => 'required|string|unique:users|max:255',
            'role_id' => 'required|numeric|exists:roles,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Re-décommente si nécessaire
            'etat' => 'required|string|in:' . implode(',', array_map(fn($case) => $case->value, EtatEnum::cases())),
            'password' => ['required', 'confirmed', new CustumPasswordRule()],
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
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
    

  
}
