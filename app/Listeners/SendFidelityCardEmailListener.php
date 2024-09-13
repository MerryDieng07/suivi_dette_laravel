<?php

namespace App\Listeners;

use App\Events\FidelityCardCreated;
use App\Jobs\SendFidelityCardEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFidelityCardEmailListener implements ShouldQueue
{
    /**
     * Gère l'événement FidelityCardCreated.
     *
     * @param FidelityCardCreated $event
     * @return void
     */
    public function handle(FidelityCardCreated $event)
    {
        // Dispatch du job pour envoyer l'e-mail de manière asynchrone
        SendFidelityCardEmailJob::dispatch($event->client, $event->qrCodePath);
    }
}
