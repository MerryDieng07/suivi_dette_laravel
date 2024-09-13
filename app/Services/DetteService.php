<?php


namespace App\Services;

use App\Repositories\DetteRepository;
use App\Repositories\ArticleRepository;
use Illuminate\Support\Facades\DB;

class DetteService
{
    protected $detteRepository;
    protected $articleRepository;

    public function __construct(DetteRepository $detteRepository, ArticleRepository $articleRepository)
    {
        $this->detteRepository = $detteRepository;
        $this->articleRepository = $articleRepository;
    }

    public function createDette(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Créer la dette
            $dette = $this->detteRepository->create([
                'client_id' => $data['client_id'],
                'montant' => $data['montant'],
            ]);

            // Ajouter les articles à la dette
            foreach ($data['articles'] as $articleData) {
                $article = $this->articleRepository->findById($articleData['article_id']);
                $this->articleRepository->decreaseStock($article, $articleData['quantite']);
                
                $dette->articles()->attach($article, [
                    'quantite' => $articleData['quantite'],
                    'prix' => $articleData['prix'],
                ]);
            }

            // Si un paiement est fourni, l'ajouter
            if (isset($data['paiement'])) {
                $this->detteRepository->addPaiement($dette, $data['paiement']['montant']);
            }

            return $dette->load('articles', 'client');
        });
    }

    // Logique métier pour récupérer les dettes
    public function listerDettes($statut = null)
    {
        $dettes = $this->detteRepository->getDettesParStatut($statut);

        if ($dettes->isEmpty()) {
            return [
                'status' => 200,
                'data' => null,
                'message' => 'Pas de dettes disponibles',
            ];
        }

        return [
            'status' => 200,
            'data' => $dettes,
            'message' => 'Liste des dettes',
        ];
    }
}
