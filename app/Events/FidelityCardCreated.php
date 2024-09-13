<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FidelityCardCreated
{
    use Dispatchable, SerializesModels;

    public $client;
    public $qrCodePath;

    /**
     * Crée un nouvel événement.
     *
     * @param $client
     * @param $qrCodePath
     */
    public function __construct($client, $qrCodePath)

    
    {
        $this->client = $client;
        $this->qrCodePath = $qrCodePath;
    }

    
}
