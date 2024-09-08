<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    public function generateQrCode(string $data, string $fileName): string
    {
        // Créer le QR code
        $qrCode = new QrCode($data);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);
        $qrCode->setForegroundColor(new Color(0, 0, 0)); // Couleur du QR code
        $qrCode->setBackgroundColor(new Color(255, 255, 255)); // Couleur de fond
        

        $writer = new PngWriter();
        $qrCodeResult = $writer->write($qrCode);

        // Définir le chemin de sauvegarde
        $filePath = storage_path('public/qrcodes/' . $fileName);
        file_put_contents($filePath, $qrCodeResult->getString());

        return $filePath;
    }
}