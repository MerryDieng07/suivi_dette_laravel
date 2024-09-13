<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleService;

    // Injection de dépendance du service dans le contrôleur
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Récupère et renvoie la liste des articles.
     * Si le paramètre 'disponible' est fourni, filtre en fonction de la disponibilité.
     */
    public function index(Request $request): JsonResponse
    {
        $disponible = $request->input('disponible');
        $articles = $this->articleService->getAllArticles($disponible);
        return response()->json(['data' => $articles], 200);
    }

    /**
     * Récupère et renvoie un article en fonction de son ID.
     */
    public function show(int $id): JsonResponse
    {
        $article = $this->articleService->getArticleById($id);
        return response()->json(['data' => $article], 200);
    }

    /**
     * Récupère et renvoie un article par son libellé.
     */
    public function getByLibelle(Request $request): JsonResponse
    {
        $libelle = $request->input('libelle');
        $article = $this->articleService->getByLibelle($libelle);
        return response()->json(['data' => $article], 200);
    }

    /**
     * Crée un nouvel article avec les données validées provenant de StoreArticleRequest.
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->createArticle($request->validated());
        return response()->json(['data' => $article], 201);
    }

    /**
     * Met à jour un article existant en fonction de son ID avec les données validées de UpdateArticleRequest.
     */
    public function update(UpdateArticleRequest $request, int $id): JsonResponse
    {
        $article = $this->articleService->updateArticle($id, $request->validated());
        return response()->json(['data' => $article], 200);
    }

    /**
     * Met à jour la quantité de stock d'un ensemble d'articles.
     */
    public function updateStock(Request $request): JsonResponse
    {
        $articles = $this->articleService->updateStock($request->all());
        return response()->json(['data' => $articles], 200);
    }


   // Méthode pour filtrer les articles par libelle
   public function filterByLibelle(string $libelle)
   {
       // Appel du service pour gérer la logique métier
       $articles = $this->articleService->filterByLibelle($libelle);

       // Retourner la réponse en JSON
       return response()->json(['status' => 200, 'data' => $articles, 'message' => 'Articles filtrés avec succès']);
   }
}
