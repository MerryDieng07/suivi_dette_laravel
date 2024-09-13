<?php


namespace App\Repositories;

interface DetteRepositoryInterface
{
    public function getAllDettes($statut = null);
}
