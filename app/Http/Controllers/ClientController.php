<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Resources\ClientCollection;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\User;
use App\Traits\RestResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Spatie\QueryBuilder\QueryBuilder;

class ClientController extends Controller
{
    use RestResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
{
    $include = $request->has('include') ? [$request->input('include')] : [];

    // Initialisation de la requête
    $query = QueryBuilder::for(Client::class)
        ->allowedFilters(['surname'])
        ->allowedIncludes($include)
        ->whereNotNull('user_id'); // Seuls les clients avec un compte lié

    // Ajout du filtre 'active' si présent dans la requête
    if ($request->has('active')) {
        $active = $request->input('active');

        if ($active === 'oui') {
            $query->whereHas('user', function($q) {
                $q->where('active', true); // Filtrer les clients avec des comptes actifs
            });
        } elseif ($active === 'non') {
            $query->whereHas('user', function($q) {
                $q->where('active', false); // Filtrer les clients avec des comptes désactivés
            });
        }
    }

    $clients = $query->get();

    return response()->json(new ClientCollection($clients));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $clientRequest = $request->only('surname', 'adresse', 'telephone');
            $client = Client::create($clientRequest);

            if ($request->has('user')) {
                $user = User::create([
                    'nom' => $request->input('user.nom'),
                    'prenom' => $request->input('user.prenom'),
                    'login' => $request->input('user.login'),
                    'password' => $request->input('user.password'),
                    'roleId' => $request->input('user.roleId'),
                    'etat' => $request->input('user.etat')?? 'ACTIF', 
                    'photo' => $request->input('user.photo'),
                ]);

                $user->client()->save($client);
            }

            DB::commit();

            return $this->sendResponse(new ClientResource($client));
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->sendResponse('Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendResponse('An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $client = Client::find($id);

        if (!$client) {
            return $this->sendResponse('Client not found');
        }

        return $this->sendResponse(new ClientResource($client),);
    }

    /**
 * Update the specified resource in storage.
 *
 * @param  \App\Http\Requests\StoreClientRequest  $request
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function update(StoreClientRequest $request, int $id)
{
    try {
        DB::beginTransaction();
        
        $client = Client::findOrFail($id);
        
        // Mettre à jour les informations du client
        $clientData = $request->only('surname', 'address', 'telephone');
        $client->update($clientData);

        // Si un utilisateur est fourni, mettre à jour les informations de l'utilisateur associé
        if ($request->has('user')) {
            $user = $client->user;  // Supposons que vous avez une relation définie
            if ($user) {
                $userData = $request->input('user');
                $user->update([
                    'nom' => $userData['nom'] ?? $user->nom,
                    'prenom' => $userData['prenom'] ?? $user->prenom,
                    'login' => $userData['login'] ?? $user->login,
                    'password' => $userData['password'] ?? $user->password,
                    'role' => $userData['role'] ?? $user->role,
                ]);
            }
        }

        DB::commit();
        return $this->sendResponse(new ClientResource($client));
    } catch (\Exception $e) {
        DB::rollBack();
        return $this->sendResponse(['error' => $e->getMessage()]);
    }
}

/**
 * Remove the specified resource from storage.
 *
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function destroy(int $id)
{
    try {
        DB::beginTransaction();

        $client = Client::findOrFail($id);
        
        // Supprimer l'utilisateur associé, si nécessaire
        if ($client->user) {
            $client->user->delete();
        }
        
        // Supprimer le client
        $client->delete();

        DB::commit();
        return $this->sendResponse([]);
    } catch (\Exception $e) {
        DB::rollBack();
        return $this->sendResponse(['error' => $e->getMessage()]);
    }
}


}
