<?php

namespace App\Services;

use App\Repositories\ArticleRepository;

class ArticleService
{
    protected $articleRepository;

    // Injection de dépendance du repository dans le service
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Récupère tous les articles ou filtre par disponibilité.
     */
    public function getAllArticles($disponible = null)
    {
        return $this->articleRepository->getAll($disponible);
    }

    /**
     * Récupère un article par son ID.
     */
    public function getArticleById($id)
    {
        return $this->articleRepository->findById($id);
    }

    /**
     * Récupère un article par son libellé.
     */
    public function getByLibelle(string $libelle)
    {
        return $this->articleRepository->findByLibelle($libelle);
    }

    /**
     * Crée un nouvel article avec les données fournies.
     */
    public function createArticle(array $data)
    {
        return $this->articleRepository->create($data);
    }

    /**
     * Met à jour un article existant en fonction de son ID et des nouvelles données.
     */
    public function updateArticle($id, array $data)
    {
        return $this->articleRepository->update($id, $data);
    }

    /**
     * Met à jour la quantité de stock de plusieurs articles en une seule opération.
     */
    public function updateStock(array $data)
    {
        return $this->articleRepository->updateStock($data);
    }


     // Logique métier : filtrer les articles par libelle
     public function filterByLibelle(string $libelle)
     {
         // Appel du repository pour interagir avec la base de données
         return $this->articleRepository->filterByLibelle($libelle);
     }
}
