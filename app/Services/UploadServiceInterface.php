<?php

namespace App\Services;

interface UploadServiceInterface
{
    public function uploadPhoto($file, $userId): string;
    public function generatePhotoInBase64($userId): ?string;
}
