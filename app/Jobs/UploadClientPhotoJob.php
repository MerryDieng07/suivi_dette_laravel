<?php

namespace App\Jobs;

use App\Models\Client;
use App\Services\UploadServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadClientPhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clientId;
    protected $photoPath;

    /**
     * Create a new job instance.
     *
     * @param int $clientId
     * @param string $photoPath
     */
    public function __construct($clientId, $photoPath)
    {
        $this->clientId = $clientId;
        $this->photoPath = $photoPath;
    }

    /**
     * Execute the job.
     *
     * @param UploadServiceInterface $uploadService
     * @return void
     */
    public function handle(UploadServiceInterface $uploadService)
    {
        // Récupérer le client par ID
        $client = Client::findOrFail($this->clientId);

        // Lire le contenu du fichier
        $fileContent = Storage::get($this->photoPath);

        // Téléchargez la photo sur Cloudinary depuis le contenu du fichier
        $cloudinaryUrl = $uploadService->uploadFileFromContent($fileContent, 'client_photos');

        // Mettez à jour l'URL de la photo du client
        $client->update(['photo' => $cloudinaryUrl]);

        // Supprimez le fichier temporaire après l'upload
        Storage::delete($this->photoPath);
    }
}
