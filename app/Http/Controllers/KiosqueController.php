<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Scan;
use App\Models\Visitor;
use App\Services\BadgeImprimante;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KiosqueController extends Controller
{
    /**
     * Afficher l'interface du kiosque de scan plein écran.
     */
    public function index()
    {
        $evenement = Event::actifCourant();

        if (!$evenement) {
            return view('kiosque.aucun-evenement');
        }

        return view('kiosque.scanner', compact('evenement'));
    }

    /**
     * Traiter un scan de QR code (appelé en AJAX depuis le kiosque).
     * Retourne JSON : données visiteur + statut du scan.
     */
    public function scanner(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $token    = trim($request->input('token'));
        $visiteur = Visitor::trouverParToken($token);

        if (!$visiteur) {
            return response()->json([
                'succes'  => false,
                'statut'  => 'inconnu',
                'message' => 'QR code non reconnu. Visiteur introuvable.',
            ], 404);
        }

        $dejaPresent   = $visiteur->estPresent();
        $kiosqueId     = env('KIOSQUE_ID', 'kiosque-1');
        $ipAddress     = $request->ip();

        // Enregistrer le scan dans tous les cas (traçabilité)
        $scan = $visiteur->enregistrerScan($kiosqueId, $ipAddress);

        // Déclencher l'impression du badge
        $impressionReussie = false;
        if (!$dejaPresent) {
            // Premier scan → impression automatique
            try {
                $imprimante = session('imprimante');
                $service = new BadgeImprimante($imprimante ?: null);
                $service->imprimer($visiteur);
                $impressionReussie = true;
            } catch (\Exception $e) {
                logger()->error("Échec impression badge {$visiteur->token} : " . $e->getMessage());
            }
        }

        return response()->json([
            'succes'             => true,
            'statut'             => $dejaPresent ? 'deja_scanne' : 'nouveau',
            'impression'         => $impressionReussie,
            'visiteur'           => [
                'nom'              => $visiteur->nom,
                'prenom'           => $visiteur->prenom,
                'nom_complet'      => $visiteur->nom_complet,
                'entreprise'       => $visiteur->entreprise,
                'fonction'         => $visiteur->fonction,
                'email'            => $visiteur->email,
                'scan_count'       => $visiteur->fresh()->scan_count,
                'first_scanned_at' => $visiteur->first_scanned_at?->format('H:i:s'),
            ],
            'scan' => [
                'id'          => $scan->id,
                'scanned_at'  => $scan->scanned_at->format('H:i:s'),
                'kiosque_id'  => $scan->kiosque_id,
            ],
            'message' => $dejaPresent
                ? "ATTENTION : {$visiteur->nom_complet} a déjà été scanné à {$visiteur->first_scanned_at?->format('H:i')}."
                : "Bienvenue, {$visiteur->nom_complet} ! Badge en cours d'impression.",
        ]);
    }

    /**
     * Afficher la page d'impression du badge (window.print approach).
     */
    public function printBadge(string $token)
    {
        $visiteur = Visitor::trouverParToken($token);

        if (!$visiteur) {
            abort(404);
        }

        $qrUrl = route('visiteur.confirmation', $visiteur->token);

        return view('badge.print', compact('visiteur', 'qrUrl'));
    }

    /**
     * Afficher la liste des scans récents sur le kiosque (rafraîchissement AJAX).
     */
    public function scansRecents(): JsonResponse
    {
        $evenement = Event::actifCourant();

        if (!$evenement) {
            return response()->json(['scans' => []]);
        }

        $scans = Scan::whereHas('visiteur', fn ($q) => $q->where('event_id', $evenement->id))
            ->with('visiteur')
            ->orderByDesc('scanned_at')
            ->limit(10)
            ->get()
            ->map(fn ($scan) => [
                'nom_complet' => $scan->visiteur->nom_complet,
                'entreprise'  => $scan->visiteur->entreprise,
                'scanned_at'  => $scan->scanned_at->format('H:i:s'),
                'numero'      => $scan->visiteur->scan_count,
            ]);

        return response()->json(['scans' => $scans]);
    }

    public function setImprimante(Request $request): JsonResponse
    {
        $allowed = ['Rongta RP4xx Series', 'VRSTTI'];
        $imprimante = $request->input('imprimante');

        if (in_array($imprimante, $allowed)) {
            session(['imprimante' => $imprimante]);
            return response()->json(['succes' => true, 'imprimante' => $imprimante]);
        }

        return response()->json(['succes' => false], 422);
    }
}
