<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationInscription;
use App\Models\Event;
use App\Models\Visitor;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VisitorController extends Controller
{
    /**
     * Afficher le formulaire d'inscription pour un événement donné.
     */
    public function formulaire(string $slug)
    {
        $evenement = Event::where('slug', $slug)->where('actif', true)->firstOrFail();

        return view('visitor.formulaire', compact('evenement'));
    }

    /**
     * Enregistrer un nouveau visiteur, générer son QR code et envoyer l'email.
     */
    public function store(Request $request, string $slug)
    {
        $evenement = Event::where('slug', $slug)->where('actif', true)->firstOrFail();

        // Validation des champs du formulaire
        $donnees = $request->validate([
            'nom'        => 'required|string|max:100',
            'prenom'     => 'required|string|max:100',
            'entreprise' => 'nullable|string|max:150',
            'fonction'   => 'nullable|string|max:100',
            'email'      => 'required|email|unique:visitors,email',
            'telephone'  => 'nullable|digits_between:6,15',
            'pays'         => 'nullable|string|max:80',
            'cndp_consent' => 'required|accepted',
        ], [
            'nom.required'          => 'Le nom est obligatoire.',
            'prenom.required'       => 'Le prénom est obligatoire.',
            'email.required'        => 'L\'adresse email est obligatoire.',
            'email.email'           => 'L\'adresse email n\'est pas valide.',
            'email.unique'          => 'Cette adresse email est déjà inscrite.',
            'cndp_consent.required' => 'Vous devez accepter la politique de confidentialité (CNDP).',
            'cndp_consent.accepted' => 'Vous devez accepter la politique de confidentialité (CNDP).',
        ]);

        // Générer un token UUID unique pour le QR code
        $token = Str::uuid()->toString();

        // Créer le visiteur en base
        $visiteur = Visitor::create([
            ...$donnees,
            'event_id'      => $evenement->id,
            'token'         => $token,
            'registered_at' => now(),
            'cndp_consent'  => 'accepte',
        ]);

        // Générer et sauvegarder le QR code
        $cheminQr = $this->genererQrCode($visiteur);
        $visiteur->update(['qr_code_path' => $cheminQr]);

        // Envoyer l'email de confirmation avec le QR code
        try {
            Mail::to($visiteur->email)->send(new ConfirmationInscription($visiteur));
        } catch (\Exception $e) {
            logger()->error("Email non envoyé à {$visiteur->email} : " . $e->getMessage());
        }

        return redirect()->route('visiteur.confirmation', $visiteur->token)
            ->with('succes', 'Inscription réussie ! Consultez votre email.');
    }

    /**
     * Afficher la page de confirmation avec le QR code du visiteur.
     */
    public function confirmation(string $token)
    {
        $visiteur = Visitor::trouverParToken($token);

        if (!$visiteur) {
            abort(404, 'Inscription introuvable.');
        }

        return view('visitor.confirmation', compact('visiteur'));
    }

    /**
     * Retourner le QR code en réponse directe (SVG ou PNG selon ce qui existe).
     */
    public function afficherQr(string $token)
    {
        $visiteur = Visitor::trouverParToken($token);

        if (!$visiteur || !$visiteur->qr_code_path) {
            abort(404);
        }

        $chemin = storage_path('app/public/' . $visiteur->qr_code_path);

        if (!file_exists($chemin)) {
            abort(404);
        }

        $estSvg = str_ends_with($visiteur->qr_code_path, '.svg');

        return response()->file($chemin, [
            'Content-Type'        => $estSvg ? 'image/svg+xml' : 'image/png',
            'Content-Disposition' => 'inline; filename="badge-qr.' . ($estSvg ? 'svg' : 'png') . '"',
        ]);
    }

    /**
     * Télécharger le QR code du visiteur.
     */
    public function telechargerQr(string $token)
    {
        $visiteur = Visitor::trouverParToken($token);

        if (!$visiteur || !$visiteur->qr_code_path) {
            abort(404);
        }

        $chemin    = storage_path('app/public/' . $visiteur->qr_code_path);
        $extension = str_ends_with($visiteur->qr_code_path, '.svg') ? 'svg' : 'png';

        return response()->download($chemin, "badge-{$visiteur->nom}-{$visiteur->prenom}.{$extension}");
    }

    /**
     * Générer le QR code SVG (aucune extension PHP requise) et le sauvegarder.
     * Retourne le chemin relatif depuis storage/app/public/.
     */
    private function genererQrCode(Visitor $visiteur): string
    {
        $urlQr       = route('visiteur.confirmation', $visiteur->token);
        $dossier     = storage_path('app/public/qrcodes');
        $nomFichier  = "qrcodes/qr-{$visiteur->token}.svg";
        $cheminSvg   = storage_path("app/public/{$nomFichier}");

        if (!is_dir($dossier)) {
            mkdir($dossier, 0755, true);
        }

        // SVG : aucune extension Imagick requise, fonctionne avec GD uniquement
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer  = new Writer($renderer);
        $svgData = $writer->writeString($urlQr);

        file_put_contents($cheminSvg, $svgData);

        return $nomFichier;
    }
}
