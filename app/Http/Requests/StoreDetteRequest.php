<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDetteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;  // Ajuste selon les autorisations de ton application
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'montant' => 'required|numeric|min:0',
            'articles' => 'required|array|min:1',
            'articles.*.article_id' => 'required|exists:articles,id',
            'articles.*.quantite' => 'required|integer|min:1',
            'articles.*.prix' => 'required|numeric|min:0',
            'paiement.montant' => 'nullable|numeric|lte:montant',

            'statut' => 'sometimes|in:Solde,NonSolde',
        ];
    }
}

