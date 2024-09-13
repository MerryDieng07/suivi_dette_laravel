<?php

namespace App\Services;

interface UploadServiceInterface 
{
    /**
     * Upload a file to the cloud.
     *
     * @param string $filePath
     * @param string $directory
     * @return string
     */
    public function uploadFile(string $filePath, string $directory): string;
    public function uploadFileFromContent(string $fileContent, string $directory): string;
}
