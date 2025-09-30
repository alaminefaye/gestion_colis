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
     * Rechercher un colis par QR code ou numéro courrier pour réception
     */
    public function rechercher(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        // Chercher le colis par QR code ou numéro courrier
        $colis = Colis::where('qr_code', $request->code)
                     ->orWhere('numero_courrier', $request->code)
                     ->first();

        if (!$colis) {
            // Rechercher des codes similaires pour suggestions  
            $suggestions = Colis::where('numero_courrier', 'LIKE', '%' . $request->code . '%')
                                ->orWhere('qr_code', 'LIKE', '%' . $request->code . '%')
                                ->whereIn('statut_livraison', ['livre', 'en_transit', 'ramasse'])
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
        
        // Vérifier que le colis peut être réceptionné
        if (!in_array($colis->statut_livraison, ['livre', 'en_transit', 'ramasse'])) {
            return back()->withErrors(['error' => 'Ce colis ne peut pas être réceptionné. Statut actuel : ' . $colis->statut_livraison_label . '. Seuls les colis ramassés, en transit ou livrés peuvent être réceptionnés.']);
        }

        // Mettre à jour le statut du colis
        $colis->update([
            'statut_livraison' => 'receptionne',
            'receptionne_par' => Auth::id(),
            'receptionne_le' => now(),
            'notes_reception' => $request->notes_reception
        ]);

        $user = Auth::user();

        return redirect()->route('reception.colis-receptionnes')->with('success', "Colis réceptionné avec succès par {$user->name}!");
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
     * API pour suggestions de codes livrés
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Colis::whereIn('statut_livraison', ['livre', 'en_transit', 'ramasse'])
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
