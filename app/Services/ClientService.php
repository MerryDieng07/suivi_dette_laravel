<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ClientRepository;
use App\Http\Requests\StoreClientRequest;
use Illuminate\Http\Request;
use App\Events\FidelityCardCreated;

class ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getAllClients()
    {
        return $this->clientRepository->findAll();
    }

    public function createClient(StoreClientRequest $request)
    {
        $validatedData = $request->validated();
        $qrCodePath = $validatedData['qr_code_path'] ?? null;

        $clientData = array_filter($validatedData, function ($key) {
            return $key !== 'qr_code_path';
        }, ARRAY_FILTER_USE_KEY);

        $client = $this->clientRepository->create($clientData);

        if (isset($validatedData['user'])) {
            $user = $this->createUser($validatedData['user']);
            $client->user()->associate($user);
            $client->save();
        }

        if ($qrCodePath) {
            // Traiter le QR code
        }

        event(new FidelityCardCreated($client, $validatedData));

        return $client;
    }

    protected function createUser(array $userData)
    {
        return User::create($userData);
    }

    public function getClientById(int $id)
    {
        return $this->clientRepository->findById($id);
    }

    public function updateClient(StoreClientRequest $request, int $id)
    {
        $data = $request->validated();
        return $this->clientRepository->update($id, $data);
    }

    public function deleteClient(int $id)
    {
        return $this->clientRepository->delete($id);
    }

    public function getClientByTelephone(string $telephone)
    {
        return $this->clientRepository->findByTelephone($telephone);
    }

    public function getClientsByStatus(StoreClientRequest $request)
    {
        return $this->clientRepository->getClients($request);
    }
}
