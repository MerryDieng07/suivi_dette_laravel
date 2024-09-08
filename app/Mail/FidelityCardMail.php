<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf; // Assurez-vous d'inclure la classe Pdf
use Illuminate\Support\Facades\Storage;

class FidelityCardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $qrCodePath;

    /**
     * Crée une nouvelle instance de message.
     *
     * @param  $client
     * @param  $qrCodePath
     * @return void
     */
    public function __construct($client, $qrCodePath)
    {
        $this->client = $client;
        $this->qrCodePath = $qrCodePath;
    }

    /**
     * Construisez le message.
     *
     * @return $this
     */
    public function build()
    {
        // Vérifiez que le login est bien une adresse email valide
        $recipientEmail = $this->client->user->email ?? $this->client->user->login;

        // Générer le PDF à partir de la vue 'emails.loyalty_card'
        $pdf = Pdf::loadView('Pdf.client', [
            'client' => $this->client->user->nom,
            'photo' => $this->client->user->photo,
            'qrCodePath' => $this->qrCodePath,
        ]);


        // Définir le chemin du fichier PDF à enregistrer
        $pdfFileName = 'client_' . $this->client->id . '.pdf';
        $pdfPath = storage_path('app/public/' . $pdfFileName);

        // Enregistrer le PDF sur le disque
        $pdf->save($pdfPath);

        return $this->view('Pdf.client')
                    ->with([
                        'clientName' => $this->client->user->nom,
                        'qrCodePath' => $this->qrCodePath,
                    ])
                    ->to($recipientEmail) // Assurez-vous que le destinataire est défini
                    ->subject('Votre Carte de Fidélité')
                    ->attach($pdfPath, [
                        'as' => 'fidelity_card.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->withSwiftMessage(function ($message) use ($pdfPath) {
                        $message->getHeaders()
                                ->addTextHeader('X-Mailer', 'Laravel');
                        // Supprimer le fichier après l'envoi
                        Storage::delete($pdfPath);
                    });
    }
}