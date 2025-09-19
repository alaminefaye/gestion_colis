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
            // Rechercher des codes similaires pour suggestions  
            $suggestions = Colis::where('numero_courrier', 'LIKE', '%' . $request->code . '%')
                                ->orWhere('qr_code', 'LIKE', '%' . $request->code . '%')
                                ->where('recupere_gare', false)
                                ->take(5)
                                ->pluck('numero_courrier')
                                ->toArray();
            
            $message = "❌ Aucun colis trouvé avec le code : <strong>{$request->code}</strong>";
            
            if (!empty($suggestions)) {
                $message .= "<br><br>🔍 <strong>Codes similaires disponibles :</strong><br>";
                foreach ($suggestions as $suggestion) {
                    $message .= "• <span class='text-primary' style='cursor: pointer;' onclick='fillCodeFromError(\"{$suggestion}\")'>{$suggestion}</span><br>";
                }
            }
            
            return back()->withErrors(['code' => $message]);
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

        return redirect()->route('livreur.mes-colis')->with('success', "Colis ramassé avec succès par {$livreur->nom_complet}!");
    }

    /**
     * Livrer un colis
     */
    public function livrer(Request $request)
    {
        $request->validate([
            'colis_id' => 'required|exists:colis,id',
            'livreur_id' => 'required|exists:livreurs,id',
            'notes_livraison' => 'nullable|string',
            'photo_piece_recto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:15360',
            'photo_piece_verso' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:15360'
        ]);

        $colis = Colis::find($request->colis_id);
        
        // Vérifier que le colis peut être livré
        if (!in_array($colis->statut_livraison, ['ramasse', 'en_transit'])) {
            return back()->withErrors(['error' => 'Ce colis ne peut pas être livré car il n\'a pas été ramassé.']);
        }

        // Gérer l'upload des photos
        $photoRecto = null;
        $photoVerso = null;

        if ($request->hasFile('photo_piece_recto')) {
            $photoRecto = $request->file('photo_piece_recto')->store('livraisons/pieces', 'public');
        }

        if ($request->hasFile('photo_piece_verso')) {
            $photoVerso = $request->file('photo_piece_verso')->store('livraisons/pieces', 'public');
        }

        // Mettre à jour le statut du colis
        $colis->update([
            'statut_livraison' => 'livre',
            'livre_par' => $request->livreur_id,
            'livre_le' => now(),
            'notes_livraison' => $request->notes_livraison,
            'photo_piece_recto' => $photoRecto,
            'photo_piece_verso' => $photoVerso
        ]);

        $livreur = Livreur::find($request->livreur_id);

        return redirect()->route('livreur.mes-colis')->with('success', "Colis livré avec succès par {$livreur->nom_complet}!");
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

        return redirect()->route('livreur.mes-colis')->with('success', 'Colis marqué comme en transit!');
    }

    /**
     * API pour suggestions de codes
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Colis::where('recupere_gare', false)
                           ->where(function($q) use ($query) {
                               $q->where('numero_courrier', 'LIKE', '%' . $query . '%')
                                 ->orWhere('qr_code', 'LIKE', '%' . $query . '%');
                           })
                           ->take(8)
                           ->get(['numero_courrier', 'nom_beneficiaire', 'statut_livraison'])
                           ->map(function($colis) {
                               return [
                                   'code' => $colis->numero_courrier,
                                   'label' => $colis->numero_courrier . ' - ' . $colis->nom_beneficiaire,
                                   'statut' => $colis->statut_livraison_label
                               ];
                           });

        return response()->json($suggestions);
    }
}
