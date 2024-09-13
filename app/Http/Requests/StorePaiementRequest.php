<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StorePaiementRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Assure-toi de gérer les autorisations selon tes besoins
    }

    public function rules()
    {
        return [
            'dette_id' => 'required|exists:dettes,id',
            'montant' => 'required|numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $detteId = $this->input('dette_id');
            $montant = $this->input('montant');

            $dette = DB::table('dettes')->where('id', $detteId)->first();

            if ($dette) {
                $montantRestant = $dette->montant - DB::table('paiements')
                    ->where('dette_id', $detteId)
                    ->sum('montant');

                if ($montant > $montantRestant) {
                    $validator->errors()->add('montant', 'Le montant du paiement ne peut pas dépasser le montant restant à payer.');
                }
            } else {
                $validator->errors()->add('dette_id', 'La dette spécifiée est invalide.');
            }
        });
    }
}
