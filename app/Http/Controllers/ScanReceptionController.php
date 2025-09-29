<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colis;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ScanReceptionController extends Controller
{
    /**
     * Afficher la page de scan avec les détails du colis
     */
    public function index()
    {
        return view('application.scan.index');
    }

    /**
     * Scanner un colis par QR code ou numéro
     */
    public function scanColis(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        // Rechercher le colis par numéro de courrier
        $colis = Colis::where('numero_courrier', $request->qr_code)->first();

        if (!$colis) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun colis trouvé avec ce numéro.'
            ]);
        }

        return response()->json([
            'success' => true,
            'colis' => $colis
        ]);
    }

    /**
     * Réceptionner un colis
     */
    public function receptionnerColis(Request $request, $id)
    {
        $request->validate([
            'notes_reception' => 'nullable|string|max:500'
        ]);

        $colis = Colis::findOrFail($id);
        
        // Marquer le colis comme réceptionné
        $colis->update([
            'est_receptionne' => true,
            'receptionne_par' => Auth::user()->name,
            'receptionne_le' => Carbon::now(),
            'notes_reception' => $request->notes_reception,
            'statut_livraison' => 'receptionne'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Colis réceptionné avec succès !',
            'colis' => $colis->fresh()
        ]);
    }

    /**
     * Afficher la liste des colis réceptionnés
     */
    public function colisReceptionnes()
    {
        $colisReceptionnes = Colis::where('est_receptionne', true)
            ->orderBy('receptionne_le', 'desc')
            ->paginate(20);

        return view('application.colis.receptionnes', compact('colisReceptionnes'));
    }
}
