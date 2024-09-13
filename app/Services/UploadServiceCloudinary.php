<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;
use Cloudinary\Upload\UploadApi;

class UploadServiceCloudinary implements UploadServiceInterface
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);
    }

    /**
     * Upload a file to Cloudinary from a file path.
     *
     * @param string $filePath
     * @param string $directory
     * @return string
     * @throws \Exception
     */
    public function uploadFile(string $filePath, string $directory): string
    {
        try {
            $result = $this->cloudinary->uploadApi()->upload($filePath, [
                'folder' => $directory,
                'public_id' => uniqid(),
                'overwrite' => false,
                'resource_type' => 'auto',
            ]);

            Log::info('Upload result: ' . json_encode($result));

            return $result['secure_url'];
        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            throw new \Exception('Erreur lors de l\'upload : ' . $e->getMessage());
        }
    }

    /**
     * Upload a file to Cloudinary from file content.
     *
     * @param string $fileContent
     * @param string $directory
     * @return string
     * @throws \Exception
     */
    public function uploadFileFromContent(string $fileContent, string $directory): string
    {
        try {
            // Créez un fichier temporaire avec le contenu
            $tempFilePath = tempnam(sys_get_temp_dir(), 'upload');
            file_put_contents($tempFilePath, $fileContent);

            $result = $this->cloudinary->uploadApi()->upload($tempFilePath, [
                'folder' => $directory,
                'public_id' => uniqid(),
                'overwrite' => false,
                'resource_type' => 'auto',
            ]);

            Log::info('Upload result: ' . json_encode($result));

            // Supprimez le fichier temporaire
            unlink($tempFilePath);

            return $result['secure_url'];
        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            throw new \Exception('Erreur lors de l\'upload : ' . $e->getMessage());
        }
    }
}
