<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    /**
     * Récupère tous les articles, avec possibilité de filtrer selon la disponibilité.
     */
    public function getAll($disponible = null)
    {
        $query = Article::query();

        // Filtrer selon la disponibilité
        if ($disponible === 'oui') {
            $query->where('qteStock', '>', 0);
        } elseif ($disponible === 'non') {
            $query->where('qteStock', '=', 0);
        }

        return $query->get();
    }

    /**
     * Récupère un article par son ID.
     */
    public function findById($id)
    {
        return Article::findOrFail($id);
    }

    /**
     * Récupère un article par son libellé.
     */
    public function findByLibelle(string $libelle)
    {
        return Article::where('libelle', $libelle)->first();
    }

    /**
     * Crée un nouvel article avec les données fournies.
     */
    public function create(array $data)
    {
        return Article::create($data);
    }

    /**
     * Met à jour un article existant en fonction de son ID.
     */
    public function update($id, array $data)
    {
        
        $article = Article::findOrFail($id);
        if (isset($data['qteStock'])) {
            $article->qteStock += $data['qteStock'];
        }
        $article->save();
    
        return $article;
    }

    /**
     * Met à jour la quantité de stock pour plusieurs articles.
     */
    public function updateStock(array $data)
{
    foreach ($data as $articleData) {
        // Vérifier que $articleData est un tableau et qu'il contient bien 'id' et 'qteStock'
        if (is_array($articleData) && isset($articleData['id']) && isset($articleData['qteStock'])) {
            $article = Article::findOrFail($articleData['id']);
            $article->qteStock += $articleData['qteStock'];
            $article->save();
        } else {
            throw new \InvalidArgumentException("Données incorrectes pour l'article : " . json_encode($articleData));
        }
    }

    // Retourner les articles mis à jour
    return Article::whereIn('id', array_column($data, 'id'))->get();
}

    // Méthode pour filtrer les articles par libelle
    public function filterByLibelle(string $libelle)
    {
        return Article::where('libelle', 'like', '%' . $libelle . '%')->get();
    }

    
    public function decreaseStock(Article $article, $quantity)
    {
        if ($article->qteStock < $quantity) {
            throw new \Exception("Quantité insuffisante en stock");
        }

        $article->qteStock -= $quantity;
        $article->save();
    }
}
