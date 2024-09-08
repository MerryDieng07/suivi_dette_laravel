<?php

namespace App\Services;

interface AuthentificationServiceInterface
{
    /**
     * Authentifie un utilisateur et renvoie un token.
     *
     * @param array $credentials
     * @return mixed
     */
    public function authenticate(array $credentials);

    /**
     * Déconnecte un utilisateur.
     *
     * @return void
     */
    public function logout();
}
