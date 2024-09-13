<?php

namespace App\Listeners;

use App\Events\ClientCreated;
use App\Jobs\UploadClientPhotoJob;

class UploadClientPhotoListener
{
    public function handle(ClientCreated $event)
    {
        // Dispatch le job de manière asynchrone
        UploadClientPhotoJob::dispatch($event->client, $event->photo);
    }
}
