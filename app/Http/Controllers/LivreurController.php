<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livreur;
use App\Models\Colis;

class LivreurController extends Controller
{
    /**
     * Display a listing of livreurs.
     */
    public function index()
    {
        $livreurs = Livreur::withCount(['colisRamasses', 'colisLivres'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);
        
        return view('livreurs.index', compact('livreurs'));
    }

    /**
     * Show the form for creating a new livreur.
     */
    public function create()
    {
        return view('livreurs.create');
    }

    /**
     * Store a newly created livreur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:livreurs',
            'email' => 'nullable|email|unique:livreurs',
            'cin' => 'required|string|unique:livreurs',
            'adresse' => 'nullable|string',
            'date_embauche' => 'required|date'
        ]);

        Livreur::create($request->all());

        return redirect()->route('livreurs.index')
                        ->with('success', 'Livreur créé avec succès!');
    }

    /**
     * Display the specified livreur.
     */
    public function show(Livreur $livreur)
    {
        $livreur->load(['colisRamasses', 'colisLivres']);
        
        $stats = [
            'total_ramasse' => $livreur->colisRamasses()->count(),
            'total_livre' => $livreur->colisLivres()->count(),
            'en_cours' => $livreur->colisRamasses()->where('statut_livraison', 'ramasse')->count()
        ];

        return view('livreurs.show', compact('livreur', 'stats'));
    }

    /**
     * Show the form for editing the specified livreur.
     */
    public function edit(Livreur $livreur)
    {
        return view('livreurs.edit', compact('livreur'));
    }

    /**
     * Update the specified livreur.
     */
    public function update(Request $request, Livreur $livreur)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:livreurs,telephone,' . $livreur->id,
            'email' => 'nullable|email|unique:livreurs,email,' . $livreur->id,
            'cin' => 'required|string|unique:livreurs,cin,' . $livreur->id,
            'adresse' => 'nullable|string',
            'date_embauche' => 'required|date',
            'actif' => 'boolean'
        ]);

        $livreur->update($request->all());

        return redirect()->route('livreurs.index')
                        ->with('success', 'Livreur mis à jour avec succès!');
    }

    /**
     * Remove the specified livreur.
     */
    public function destroy(Livreur $livreur)
    {
        // Vérifier s'il a des colis en cours
        if ($livreur->colisRamasses()->whereIn('statut_livraison', ['ramasse', 'en_transit'])->exists()) {
            return redirect()->route('livreurs.index')
                            ->with('error', 'Impossible de supprimer ce livreur car il a des colis en cours de livraison.');
        }

        $livreur->delete();

        return redirect()->route('livreurs.index')
                        ->with('success', 'Livreur supprimé avec succès!');
    }

    /**
     * Page des colis récupérés/livrés
     */
    public function colisRecuperes()
    {
        $colis = Colis::with(['livreurRamassage', 'livreurLivraison'])
                     ->whereIn('statut_livraison', ['ramasse', 'en_transit', 'livre'])
                     ->orderBy('ramasse_le', 'desc')
                     ->paginate(15);

        return view('livreurs.colis-recuperes', compact('colis'));
    }
}
