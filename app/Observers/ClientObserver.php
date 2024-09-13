<?php

namespace App\Observers;

use App\Events\ClientCreated;
use App\Models\Client;

class ClientObserver
{
    public function created(Client $client)
    {
        if (request()->hasFile('user.photo')) {
            $photo = request()->file('user.photo');
            // Déclencher l'événement lorsqu'un client est créé
            event(new ClientCreated($client, $photo));
        }
    }
}
