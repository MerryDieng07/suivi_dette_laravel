<?php
namespace App\Services;

use Laravel\Passport\Bridge\AccessToken as PassportAccessToken;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use Laravel\Passport\HasApiTokens;

class CustomUserClaims extends PassportAccessToken implements AccessTokenEntityInterface
{
    use EntityTrait, HasApiTokens;

    /**
     * Ajouter des informations personnalisées dans le token.
     *
     * @return array
     */
    // public function getClaims()
    // {
    //     // Ici, vous pouvez ajouter n'importe quelle information utilisateur dans le token
    //     return [
    //         'nom' => $this->user->nom,
    //         'prenom' => $this->user->prenom,
    //         'roleId' => $this->user->roleId,
    //         // Ajouter d'autres informations selon vos besoins
    //     ];
    // }
}
