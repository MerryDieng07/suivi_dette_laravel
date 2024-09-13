<?php

namespace App\Http\Controllers;

use App\Models\Dette;
use Illuminate\Http\Request;
use App\Services\DetteService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreDetteRequest;

class DetteController extends Controller
{
    protected $detteService;

    public function __construct(DetteService $detteService)
    {
        $this->detteService = $detteService;
    }

    public function store(StoreDetteRequest $request): JsonResponse
    {
        // Les données sont déjà validées par CreateDetteRequest
        $data = $request->validated();

        // Appeler le service pour créer la dette
        $dette = $this->detteService->createDette($data);

        return response()->json([
            'message' => 'Dette créée avec succès',
            'dette' => $dette
        ], 201);
    }

    public function effectuerPaiement(Request $request, $detteId)
{
    // Récupérer la dette depuis la base de données
    $dette = Dette::findOrFail($detteId);

    // Récupérer le montant restant à payer
    $montantRestant = $dette->montant_total - $dette->montant_paye;

    // Valider la requête
    $request->validate([
        'montant' => 'required|numeric|min:1',
    ]);

    // Vérifier si le montant donné est supérieur au montant restant
    if ($request->montant > $montantRestant) {
        return response()->json([
            'status' => 'error',
            'message' => 'La somme donnée ne doit jamais être supérieure au montant à payer.',
        ], 400);
    }

    // Effectuer le paiement
    $dette->montant_paye += $request->montant;
    $dette->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Le paiement a été effectué avec succès.',
    ]);
}

    public function show($id)
    {
        $dette = Dette::findOrFail($id);
        return response()->json($dette);
    }

    // Le contrôleur ne contient plus de logique de requête
    public function index(StoreDetteRequest $request)
    {
        $statut = $request->query('statut');
        $resultat = $this->detteService->listerDettes($statut);

        return response()->json($resultat, $resultat['status']);
    }

}
