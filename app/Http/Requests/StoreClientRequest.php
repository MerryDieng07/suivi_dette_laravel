<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true; // Ou ajoute ta logique d'autorisation ici
    }

    /**
     * Règles de validation pour les requêtes.
     */
    public function rules(): array
    {
        return [
            'surname' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:10|unique:clients,telephone',
            'user' => ['sometimes', 'array'],
            'user.nom' => ['required_with:user', 'string'],
            'user.prenom' => ['required_with:user', 'string'],
            'user.login' => ['required_with:user', 'string'],
            'user.role_id' => ['required_with:user'], 
            'user.photo' => ['required_with:user'],
            'user.password' => ['required_with:user', 'string'],
            'comptes' => 'nullable|in:oui,non',
            'active' => 'nullable|in:oui,non',
        ];
    }
}
