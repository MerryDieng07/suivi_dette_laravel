<?php


namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CustumPasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Validator pour vérifier les critères de sécurité du mot de passe
        $validator = Validator::make(
            [$attribute => $value], // On vérifie seulement le champ actuel
            [
                $attribute => [
                    'required',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols() // pour inclure les caractères spéciaux
                        ->uncompromised(), // Vérifie que le mot de passe n'a pas été compromis dans des fuites de données
                ],
            ]
        );

        // Si la validation échoue, on retourne les messages d'erreur
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error); // On passe chaque erreur à la fonction de fail
            }
        }
    }
}
