<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Traits\RestResponseTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

class ArticleController extends Controller
{
    use RestResponseTrait;
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->has('disponible')) {
            $disponible = $request->input('disponible');
            if ($disponible === 'oui') {
                $query->where('qteStock', '>', 0);
            } elseif ($disponible === 'non') {
                $query->where('qteStock', '=', 0);
            }
        }

        $articles = $query->get();

        if ($articles->isEmpty()) {
            return response()->json([
                'status' => 200,
                'data' => null,
                'message' => 'Pas Articles',
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => ArticleResource::collection($articles),
            'message' => 'Liste des articles',
        ]);
    }
    public function updateStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'qteStock' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 411,
                'data' => null,
                'message' => 'Validation failed',
            ], 411);
        }

        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'status' => 411,
                'data' => null,
                'message' => 'Objet non trouvé',
            ], 411);
        }

        $article->qteStock = $request->qteStock;
        $article->save();

        return response()->json([
            'status' => 200,
            'data' => $article,
            'message' => 'Quantité de stock mise à jour',
        ]);
    }

    public function store(StoreArticleRequest $request)
    {
        $article = Article::create($request->validated());

        return response()->json([
            'status' => 201,
            'data' => new ArticleResource($article),
            'message' => 'Article créé avec succès',
        ], 201);
    }

    /**
     * Modifier un article existant.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->update($request->validated());

        return response()->json([
            'status' => 200,
            'data' => new ArticleResource($article),
            'message' => 'Article modifié avec succès',
        ]);
    }

    /**
     * Supprimer un article.
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json([
            'status' => 200,
            'data' => null,
            'message' => 'Article supprimé avec succès',
        ]);
    }
}
