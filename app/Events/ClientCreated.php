<?php

namespace App\Events;

use App\Models\Client;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientCreated
{ use Dispatchable, SerializesModels;

    public $client;
    public $photo;

    public function __construct(Client $client, $photo)
    {
        $this->client = $client;
        $this->photo = $photo;
    }
}
