<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use App\Enums\StateEnum;
use App\Rules\CustumPasswordRule;
use App\Rules\TelephoneRule;
use App\Traits\RestResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreClientRequest extends FormRequest
{
    use RestResponseTrait;
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
    public function rules(): array
{
    $rules = [
        'surname' => ['required', 'string', 'max:255', 'unique:clients,surname'],
        'address' => ['string', 'max:255'],
        'telephone' => ['required', new TelephoneRule()],

        'user' => ['sometimes', 'array'],
        'user.nom' => ['required_with:user', 'string'],
        'user.prenom' => ['required_with:user', 'string'],
        'user.login' => ['required_with:user', 'string'],
        'user.roleId' => ['required_with:user'], // Assurez-vous que les rôles sont valides
        'user.password' => ['required_with:user', new CustumPasswordRule()],
        'user.etat' => ['required_with:user', 'string', 'in:ACTIF'], // Validation pour l'état
    ];

    return $rules;
}
/*
        if ($this->filled('user')) {
            $userRules = (new StoreUserRequest())->Rules();
            $rules = array_merge($rules, ['user' => 'array']);
            $rules = array_merge($rules, array_combine(
                array_map(fn($key) => "user.$key", array_keys($userRules)),
                $userRules
            ));
        }
*/
      //  dd($rules);

   
      function messages()
      {
          return [
              'surname.required' => "Le surnom est obligatoire.",
              'user.etat.required_with' => "L'état est obligatoire si l'utilisateur est fourni.",
              'user.etat.in' => "L'état de l'utilisateur doit être 'ACTIF'.",
          ];
      }
      

    function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendResponse($validator->errors(),StateEnum::ECHEC,404));
    }

    
}
