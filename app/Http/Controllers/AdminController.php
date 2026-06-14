<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationInscription;
use App\Models\Event;
use App\Models\Scan;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Tableau de bord principal avec statistiques en temps réel.
     */
    public function dashboard()
    {
        $evenement = Event::actifCourant();

        if (!$evenement) {
            return view('admin.aucun-evenement');
        }

        $totalInscrits  = $evenement->visiteurs()->count();
        $totalPresents  = $evenement->visiteurs()->where('scan_count', '>', 0)->count();
        $totalAbsents   = $totalInscrits - $totalPresents;
        $tauxPresence   = $evenement->tauxPresence();

        // Scans par heure (pour le graphique)
        $scansParHeure = Scan::whereHas('visiteur', fn ($q) => $q->where('event_id', $evenement->id))
            ->selectRaw('HOUR(scanned_at) as heure, COUNT(*) as nombre')
            ->groupBy('heure')
            ->orderBy('heure')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->heure . 'h' => $row->nombre]);

        // Dernières inscriptions
        $derniersInscrits = $evenement->visiteurs()
            ->orderByDesc('registered_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'evenement',
            'totalInscrits',
            'totalPresents',
            'totalAbsents',
            'tauxPresence',
            'scansParHeure',
            'derniersInscrits',
        ));
    }

    /**
     * Liste paginée de tous les visiteurs inscrits avec filtres.
     */
    public function visiteurs(Request $request)
    {
        $evenement = Event::actifCourant();

        $requete = $evenement
            ? $evenement->visiteurs()
            : Visitor::query();

        // Filtre par recherche texte
        if ($recherche = $request->input('q')) {
            $requete->where(fn ($q) =>
                $q->where('nom', 'like', "%{$recherche}%")
                  ->orWhere('prenom', 'like', "%{$recherche}%")
                  ->orWhere('email', 'like', "%{$recherche}%")
                  ->orWhere('entreprise', 'like', "%{$recherche}%")
            );
        }

        // Filtre par statut
        if ($statut = $request->input('statut')) {
            if ($statut === 'present') {
                $requete->where('scan_count', '>', 0);
            } elseif ($statut === 'absent') {
                $requete->where('scan_count', 0);
            }
        }

        $visiteurs = $requete->orderByDesc('registered_at')->paginate(20)->withQueryString();

        return view('admin.visiteurs', compact('visiteurs', 'evenement', 'recherche'));
    }

    /**
     * Afficher le détail d'un visiteur.
     */
    public function voirVisiteur(Visitor $visiteur)
    {
        $visiteur->load(['event', 'scans' => fn ($q) => $q->orderByDesc('scanned_at')]);

        return view('admin.visiteur-detail', compact('visiteur'));
    }

    /**
     * Renvoyer l'email de confirmation avec le QR code.
     */
    public function renvoyerEmail(Visitor $visiteur)
    {
        try {
            Mail::to($visiteur->email)->send(new ConfirmationInscription($visiteur));
            return back()->with('succes', "Email renvoyé à {$visiteur->email}.");
        } catch (\Exception $e) {
            return back()->with('erreur', "Échec de l'envoi : " . $e->getMessage());
        }
    }

    /**
     * Supprimer un visiteur et ses scans associés.
     */
    public function supprimerVisiteur(Visitor $visiteur)
    {
        $nom = $visiteur->nom_complet;

        // Supprimer le fichier QR code s'il existe
        if ($visiteur->qr_code_path) {
            $chemin = storage_path('app/public/' . $visiteur->qr_code_path);
            if (file_exists($chemin)) {
                unlink($chemin);
            }
        }

        $visiteur->delete();

        return redirect()->route('admin.visiteurs')
            ->with('succes', "{$nom} a été supprimé avec ses scans.");
    }

    /**
     * Liste des scans avec détails (journal de présence).
     */
    public function scans(Request $request)
    {
        $evenement = Event::actifCourant();

        $scans = Scan::with('visiteur.event')
            ->when($evenement, fn ($q) =>
                $q->whereHas('visiteur', fn ($v) => $v->where('event_id', $evenement->id))
            )
            ->orderByDesc('scanned_at')
            ->paginate(30);

        return view('admin.scans', compact('scans', 'evenement'));
    }

    /**
     * Statistiques JSON pour rafraîchissement AJAX du tableau de bord.
     */
    public function statsJson()
    {
        $evenement = Event::actifCourant();

        if (!$evenement) {
            return response()->json(['erreur' => 'Aucun événement actif']);
        }

        $scansParHeure = Scan::whereHas('visiteur', fn ($q) => $q->where('event_id', $evenement->id))
            ->selectRaw('HOUR(scanned_at) as heure, COUNT(*) as nombre')
            ->groupBy('heure')
            ->orderBy('heure')
            ->get();

        return response()->json([
            'total_inscrits' => $evenement->visiteurs()->count(),
            'total_presents' => $evenement->visiteurs()->where('scan_count', '>', 0)->count(),
            'taux_presence'  => $evenement->tauxPresence(),
            'scans_par_heure' => $scansParHeure,
        ]);
    }

    /**
     * Exporter la liste des visiteurs en CSV.
     */
    public function exporterCsv()
    {
        $evenement = Event::actifCourant();

        $visiteurs = $evenement
            ? $evenement->visiteurs()->orderBy('nom')->get()
            : Visitor::orderBy('nom')->get();

        $nomFichier = 'visiteurs-' . now()->format('Y-m-d') . '.csv';

        $entetes = ['ID', 'Nom', 'Prénom', 'Entreprise', 'Fonction', 'Email', 'Téléphone', 'Pays', 'Date inscription', 'Présent', 'Heure 1er scan'];

        $lignes = $visiteurs->map(fn ($v) => [
            $v->id,
            $v->nom,
            $v->prenom,
            $v->entreprise,
            $v->fonction,
            $v->email,
            $v->telephone,
            $v->pays,
            $v->registered_at->format('d/m/Y H:i'),
            $v->estPresent() ? 'Oui' : 'Non',
            $v->first_scanned_at?->format('H:i:s') ?? '',
        ]);

        return response()->streamDownload(function () use ($entetes, $lignes) {
            $sortie = fopen('php://output', 'w');
            // BOM UTF-8 pour Excel
            fputs($sortie, "\xEF\xBB\xBF");
            fputcsv($sortie, $entetes, ';');
            foreach ($lignes as $ligne) {
                fputcsv($sortie, $ligne, ';');
            }
            fclose($sortie);
        }, $nomFichier, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
