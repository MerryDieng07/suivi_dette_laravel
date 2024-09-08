<?php

namespace App\Listeners;

use App\Events\ClientRegistered;
use App\Services\UploadServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleClientPhotoUpload implements ShouldQueue
{
    use InteractsWithQueue;

    protected $uploadService;

    public function __construct(UploadServiceInterface $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function handle(ClientRegistered $event)
    {
        $clientData = $event->clientData;
        $photo = $event->photo;

        if ($photo) {
            $this->uploadService->uploadPhoto($photo, $clientData['user_id']);
        }
    }
}
