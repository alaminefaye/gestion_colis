<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colis;
use App\Models\Livreur;
use Illuminate\Support\Facades\Auth;

class ScanQRController extends Controller
{
    /**
     * Page de scan QR pour les livreurs
     */
    public function index()
    {
        $livreurs = Livreur::where('actif', true)->get();
        return view('scan.index', compact('livreurs'));
    }

    /**
     * Rechercher un colis par QR code ou numéro courrier
     */
    public function rechercher(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        // Chercher le livreur correspondant à l'utilisateur connecté
        $user = Auth::user();
        $livreur = Livreur::where('email', $user->email)->first();
        
        // Si pas de livreur et que l'utilisateur n'est pas admin, erreur
        if (!$livreur && !$user->hasRole(['super-admin', 'admin'])) {
            return redirect()->back()
                           ->with('error', 'Aucun profil de livreur trouvé pour votre compte.');
        }
        
        // Pour les admins sans profil livreur, on crée un livreur temporaire pour l'affichage
        if (!$livreur) {
            // Créer ou récupérer un livreur "administrateur" générique
            $livreur = Livreur::firstOrCreate(
                ['email' => $user->email],
                [
                    'nom' => 'ADMIN',
                    'prenom' => 'Système',
                    'cin' => '0000000000000', // CIN générique pour admin
                    'telephone' => '000000000',
                    'adresse' => 'Système',
                    'statut' => 'actif',
                    'date_embauche' => now(),
                ]
            );
        }

        // Chercher le colis par QR code ou numéro courrier
        $colis = Colis::where('qr_code', $request->code)
                     ->orWhere('numero_courrier', $request->code)
                     ->first();


        if (!$colis) {
            return back()->withErrors(['code' => 'Aucun colis trouvé avec ce code.']);
        }

        return view('scan.resultat', compact('colis', 'livreur'));
    }

    /**
     * Ramasser un colis
     */
    public function ramasser(Request $request)
    {
        $request->validate([
            'colis_id' => 'required|exists:colis,id',
            'livreur_id' => 'required|exists:livreurs,id',
            'notes_ramassage' => 'nullable|string'
        ]);

        $colis = Colis::find($request->colis_id);
        
        // Vérifier que le colis peut être ramassé
        if ($colis->statut_livraison !== 'en_attente') {
            return back()->withErrors(['error' => 'Ce colis ne peut pas être ramassé car il est déjà ' . $colis->statut_livraison_label]);
        }

        // Mettre à jour le statut du colis
        $colis->update([
            'statut_livraison' => 'ramasse',
            'ramasse_par' => $request->livreur_id,
            'ramasse_le' => now(),
            'notes_ramassage' => $request->notes_ramassage
        ]);

        $livreur = Livreur::find($request->livreur_id);

        return back()->with('success', "Colis ramassé avec succès par {$livreur->nom_complet}!");
    }

    /**
     * Livrer un colis
     */
    public function livrer(Request $request)
    {
        $request->validate([
            'colis_id' => 'required|exists:colis,id',
            'livreur_id' => 'required|exists:livreurs,id',
            'notes_livraison' => 'nullable|string'
        ]);

        $colis = Colis::find($request->colis_id);
        
        // Vérifier que le colis peut être livré
        if (!in_array($colis->statut_livraison, ['ramasse', 'en_transit'])) {
            return back()->withErrors(['error' => 'Ce colis ne peut pas être livré car il n\'a pas été ramassé.']);
        }

        // Mettre à jour le statut du colis
        $colis->update([
            'statut_livraison' => 'livre',
            'livre_par' => $request->livreur_id,
            'livre_le' => now(),
            'notes_livraison' => $request->notes_livraison
        ]);

        $livreur = Livreur::find($request->livreur_id);

        return back()->with('success', "Colis livré avec succès par {$livreur->nom_complet}!");
    }

    /**
     * Marquer un colis comme en transit
     */
    public function enTransit(Request $request)
    {
        $request->validate([
            'colis_id' => 'required|exists:colis,id',
            'livreur_id' => 'required|exists:livreurs,id'
        ]);

        $colis = Colis::find($request->colis_id);
        
        // Vérifier que le colis peut être mis en transit
        if ($colis->statut_livraison !== 'ramasse') {
            return back()->withErrors(['error' => 'Ce colis ne peut être mis en transit car il n\'a pas été ramassé.']);
        }

        // Mettre à jour le statut du colis
        $colis->update([
            'statut_livraison' => 'en_transit'
        ]);

        return back()->with('success', 'Colis marqué comme en transit!');
    }
}
