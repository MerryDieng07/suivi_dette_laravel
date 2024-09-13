<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreClientRequest;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(): JsonResponse
    {
        $clients = $this->clientService->getAllClients();
        return response()->json(['data' => $clients], 200);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = $this->clientService->createClient($request);
        return response()->json(['data' => $client], 201);
    }

    public function clientByTelephone(Request $request): JsonResponse
    {
        $telephone = $request->input('telephone');
        // dd($telephone);
        $client = $this->clientService->getClientByTelephone($telephone);
        return response()->json(['data' => $client], 200);
    }
    public function getClients(Request $request)
    {
        // Récupération des paramètres de la requête
        $comptes = $request->query('comptes');
        $active = $request->query('active');

        // Construction de la requête avec QueryBuilder
        $query = QueryBuilder::for(Client::class)
            ->allowedFilters(['surname']) // Ajoute le filtre sur le surname (prénom ou nom de famille)
            ->allowedIncludes(['user']); // Permet d'inclure la relation user

        // Filtrage basé sur la présence de comptes
        if ($comptes !== null) {
            // Si 'comptes' est 'oui', on vérifie la présence de la relation 'user', sinon l'absence
            $query = $comptes === 'oui' ? $query->whereHas('user') : $query->whereDoesntHave('user');
        }

        // Filtrage basé sur l'état actif ou inactif des comptes
        if ($active !== null) {
            // Définition de l'état en fonction de la valeur passée (oui -> ACTIF, non -> INACTIF)
            $etat = $active === 'oui' ? 'ACTIF' : 'INACTIF';

            // Ajout de la condition whereHas sur l'état du compte utilisateur
            $query->whereHas('user', function ($query) use ($etat) {
                $query->where('etat', $etat);
            });
        }

        // Exécution de la requête et retour des résultats
        $clients = $query->get();

        return response()->json([
            'data' => $clients->isEmpty() ? null : $clients
        ], 200);
    }
    public function show($id): JsonResponse
    {
        $client = $this->clientService->getClientById($id);
        if ($client) {
            return response()->json($client);
        }
        return response()->json(['message' => 'Client not found'], 404);
    }

    public function update(StoreClientRequest $request, int $id): JsonResponse
    {
        $client = $this->clientService->updateClient($request, $id);
        return response()->json(['data' => $client], 200);
    }

    // app/Http/Controllers/ClientController.php
    public function destroy($id)
    {
        $client = Client::find($id);
        
        if ($client) {
            $client->delete();
            return response()->json(['message' => 'Client supprimé avec succès.'], 200);
        }

        return response()->json(['message' => 'Client non trouvé.'], 404);
    }

}
