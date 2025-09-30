<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colis;
use Illuminate\Support\Facades\Auth;

class ScanReceptionController extends Controller
{
    /**
     * Page de scan pour la réception
     */
    public function index()
    {
        return view('reception.scan');
    }

    /**
     * Rechercher un colis par code
     */
    public function rechercher(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $colis = Colis::where('numero_courrier', $request->code)
                      ->orWhere('qr_code', $request->code)
                      ->with(['livreurRamassage', 'livreurLivraison'])
                      ->first();

        if (!$colis) {
            // Colis n'existe pas - afficher le formulaire de création
            return view('reception.resultat', [
                'colis' => null,
                'code_scanne' => $request->code
            ]);
        }

        // Colis existe - afficher les informations
        return view('reception.resultat', compact('colis'));
    }

    /**
     * Réceptionner un colis
     */
    public function receptionner(Request $request)
    {
        $request->validate([
            'colis_id' => 'required|exists:colis,id',
            'notes_reception' => 'nullable|string'
        ]);

        $colis = Colis::find($request->colis_id);
        
        // Vérifier que le colis peut être réceptionné (processus gare-à-gare)
        if (!in_array($colis->statut_livraison, ['en_attente', 'ramasse', 'en_transit', 'livre'])) {
            return redirect()->route('application.scan-colis')->withErrors(['error' => 'Ce colis ne peut pas être réceptionné. Statut actuel : ' . $colis->statut_livraison_label . '. Seuls les colis déjà réceptionnés ne peuvent être re-réceptionnés.']);
        }

        // Mettre à jour le statut du colis
        $colis->update([
            'statut_livraison' => 'receptionne',
            'receptionne_par' => Auth::id(),
            'receptionne_le' => now(),
            'notes_reception' => $request->notes_reception
        ]);

        $user = Auth::user();

        return redirect()->route('application.reception.colis-receptionnes')->with('success', "Colis réceptionné avec succès par {$user->name}!");
    }

    /**
     * Enregistrer un nouveau colis scanné
     */
    public function enregistrerNouveau(Request $request)
    {
        $request->validate([
            'numero_courrier' => 'required|string|unique:colis,numero_courrier',
            'nom_complet' => 'nullable|string|max:255',
            'numero_telephone' => 'nullable|string|max:20'
        ]);

        // Créer le nouveau colis avec statut "receptionne"
        $colis = Colis::create([
            'numero_courrier' => $request->numero_courrier,
            'qr_code' => $request->numero_courrier, // Utiliser le numéro comme QR code
            'nom_expediteur' => $request->nom_complet ?? 'Non renseigné',
            'telephone_expediteur' => $request->numero_telephone ?? '',
            'nom_beneficiaire' => $request->nom_complet ?? 'Non renseigné',
            'telephone_beneficiaire' => $request->numero_telephone ?? '',
            'destination' => 'A définir',
            'agence_reception' => 'Gare centrale',
            'type_colis' => 'standard',
            'montant' => 0,
            'valeur_colis' => 0,
            'statut_livraison' => 'receptionne',
            'receptionne_par' => Auth::id(),
            'receptionne_le' => now(),
            'notes_reception' => 'Colis créé via scan - Informations à compléter',
            'created_by' => Auth::id()
        ]);

        return redirect()->route('application.reception.colis-receptionnes')
                        ->with('success', "Nouveau colis {$colis->numero_courrier} enregistré avec succès !");
    }

    /**
     * Liste des colis réceptionnés
     */
    public function colisReceptionnes()
    {
        $colis = Colis::where('statut_livraison', 'receptionne')
                     ->with(['receptionneParUser'])
                     ->orderBy('receptionne_le', 'desc')
                     ->paginate(20);

        return view('reception.colis-receptionnes', compact('colis'));
    }

    /**
     * Liste des colis livrés
     */
    public function colisLivres()
    {
        $colis = Colis::where('statut_livraison', 'livre')
                     ->with(['livreurLivraison'])
                     ->orderBy('livre_le', 'desc')
                     ->paginate(20);

        return view('reception.colis-livres', compact('colis'));
    }

    /**
     * API pour suggestions de codes livrés
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Colis::whereIn('statut_livraison', ['en_attente', 'ramasse', 'en_transit', 'livre'])
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
