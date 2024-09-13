<?php

namespace App\Repositories;

use App\Models\Client;
use App\Enums\EtatEnum;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ClientRepository
{
    protected $model;

    public function __construct(Client $client)
    {
        $this->model = $client;
    }

    public function findAll()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $client = $this->findById($id);
        $client->update($data);
        return $client;
    }

    public function delete($id)
    {
        $client = $this->findById($id);
        return $client->delete();
    }

    public function findByTelephone($telephone)
    {
        return $this->model->where('telephone', $telephone)->first();
    }

    public function getClients(Request $request)
    {
        $comptes = $request->query('comptes');
        $active = $request->query('active');

        $query = QueryBuilder::for(Client::class)
            ->allowedFilters(['surname'])
            ->allowedIncludes(['user']);

        if ($comptes !== null) {
            $query = $comptes === 'oui' ? $query->whereHas('user') : $query->whereDoesntHave('user');
        }

        if ($active !== null) {
            $etat = $active === 'oui' ? EtatEnum::ACTIF->value : EtatEnum::INACTIF->value;
            $query->whereHas('user', function ($query) use ($etat) {
                $query->where('etat', $etat);
            });
        }

        return $query->get();
    }
}
