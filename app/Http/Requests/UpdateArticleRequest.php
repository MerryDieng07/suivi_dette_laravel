<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'libelle' => 'sometimes|required|string|max:255',
            'prix' => 'sometimes|required|numeric|min:0',
            'qteStock' => 'sometimes|required|integer|min:0',
        ];
    }
}
