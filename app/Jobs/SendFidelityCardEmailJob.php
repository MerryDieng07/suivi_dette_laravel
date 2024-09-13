<?php

namespace App\Jobs;

use App\Mail\FidelityCardMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Ajoutez cette ligne
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendFidelityCardEmailJob implements ShouldQueue // Implémente ShouldQueue ici
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    protected $qrCodePath;

    public function __construct($client, $qrCodePath)
    {
        $this->client = $client;
        $this->qrCodePath = $qrCodePath;
    }

    public function handle()
    {
        // Envoyer l'e-mail
        Mail::to($this->client->user->email ?? $this->client->user->login)
            ->send(new FidelityCardMail($this->client, $this->qrCodePath));
    }
}
