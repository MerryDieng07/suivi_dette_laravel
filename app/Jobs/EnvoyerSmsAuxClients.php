<?php

namespace App\Jobs;
use Twilio\Rest\Client;

use App\Models\Dette;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EnvoyerSmsAuxClients implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Logique du job pour envoyer les SMS.
     */
    public function handle()
    {
        // Récupérer tous les clients ayant des dettes
        $clients = Client::whereHas('dettes', function ($query) {
            $query->where('montant_restant', '>', 0); // Clients ayant des dettes non payées
        })->get();

        foreach ($clients as $client) {
            // Calculer le montant total dû pour chaque client
            $totalDue = $client->dettes()->where('montant_restant', '>', 0)->sum('montant_restant');

            // Créer le message à envoyer par SMS
            $message = "Bonjour {$client->nom}, vous avez un total de {$totalDue} à régler pour vos dettes. Merci de passer à la boutique.";

            // Appeler ici une fonction pour envoyer le SMS (en utilisant une API comme Twilio, etc.)
            // sendSms($client->telephone, $message);

            // Pour cet exemple, nous allons simplement logger les messages
            Log::info("Message envoyé à {$client->telephone}: $message");
        }
    }

    function sendSms($numero, $message)
{
    $sid = config('services.twilio.sid');
    $token = config('services.twilio.token');
    $from = config('services.twilio.from'); // Votre numéro Twilio

    $client = new Client($sid, $token);

    $client->messages->create(
        $numero,
        [
            'from' => $from,
            'body' => $message,
        ]
    );
}
}
