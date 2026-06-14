<?php

namespace App\Mail;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationInscription extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Visitor $visiteur
    ) {}

    public function envelope(): Envelope
    {
        $nomEvenement = $this->visiteur->event->nom ?? env('EVENT_NOM', 'ITAF');

        return new Envelope(
            subject: "Bienvenue à {$nomEvenement} — Votre badge d'accès",
        );
    }

    public function build(): static
    {
        $cheminQr    = storage_path('app/public/' . $this->visiteur->qr_code_path);
        $qrCodeInline = null;

        if ($this->visiteur->qr_code_path && file_exists($cheminQr)) {
            $contenu  = file_get_contents($cheminQr);
            $estSvg   = str_ends_with($this->visiteur->qr_code_path, '.svg');
            $mimeType = $estSvg ? 'image/svg+xml' : 'image/png';
            $nomFic   = $estSvg ? 'qr-code.svg' : 'qr-code.png';

            // Embed inline (visible dans Gmail, Outlook, Apple Mail)
            $qrCodeInline = $this->embedData($contenu, $nomFic, $mimeType);
        }

        return $this->view('emails.confirmation')->with([
            'visiteur'    => $this->visiteur,
            'evenement'   => $this->visiteur->event,
            'qrCodeCid'   => $qrCodeInline,
        ]);
    }
}
