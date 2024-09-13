<?php


namespace App\Repositories;

use App\Models\Dette;

class DetteRepository
{
    protected $model;

    public function __construct(Dette $dette)
    {
        $this->model = $dette; // Initialisation de la propriété $model avec une instance de Dette
    }

    public function create(array $data)
    {
        return $this->model->create($data); // Utilisation de $this->model
    }

    public function addPaiement(Dette $dette, $montant)
    {
        return $dette->paiements()->create([
            'montant' => $montant,
        ]);
    }

    // Méthode pour récupérer les dettes en fonction du statut
    public function getDettesParStatut($statut = null)
    {
        if ($statut === 'Solde') {
            return $this->model->solde()->get();
        } elseif ($statut === 'NonSolde') {
            return $this->model->nonSolde()->get();
        }

        // Si aucun statut n'est spécifié, on retourne toutes les dettes
        return $this->model->all();
    }
}
