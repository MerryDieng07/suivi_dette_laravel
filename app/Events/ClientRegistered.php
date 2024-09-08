<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientRegistered
{
    use Dispatchable, SerializesModels;

    public $clientData;
    public $photo;

    public function __construct(array $clientData, $photo)
    {
        $this->clientData = $clientData;
        $this->photo = $photo;
    }

    public function broadcastOn()
    {
        return new Channel('client-registered');
    }
}
