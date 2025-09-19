<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Agence;
use App\Models\Colis;
use Illuminate\Support\Facades\Auth;

class GestionController extends Controller
{
    /**
     * Afficher la page de gestion
     */
    public function index()
    {
        $destinations = Destination::orderBy('libelle')->get();
        $agences = Agence::orderBy('libelle')->get();
        
        return view('application.gestion.index', compact('destinations', 'agences'));
    }

    /**
     * Gestion des destinations
     */
    public function destinationsIndex()
    {
        $destinations = Destination::orderBy('libelle')->paginate(10);
        return view('application.gestion.destinations.index', compact('destinations'));
    }

    public function destinationsStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|unique:destinations,nom|max:255',
                'libelle' => 'required|string|max:255'
            ]);

            $validated['actif'] = $request->has('actif');
            Destination::create($validated);

            return redirect()->route('application.gestion.destinations')
                             ->with('success', 'Destination ajoutée avec succès!');
        } catch (\Exception $e) {
            return redirect()->route('application.gestion.destinations')
                             ->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage());
        }
    }

    public function destinationsUpdate(Request $request, $id)
    {
        $destination = Destination::findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|unique:destinations,nom,' . $id . '|max:255',
            'libelle' => 'required|string|max:255'
        ]);

        $validated['actif'] = $request->has('actif');
        $destination->update($validated);

        return redirect()->route('application.gestion.destinations')
                         ->with('success', 'Destination mise à jour avec succès!');
    }

    public function destinationsDestroy($id)
    {
        $destination = Destination::findOrFail($id);
        $destination->delete();

        return redirect()->route('application.gestion.destinations')
                         ->with('success', 'Destination supprimée avec succès!');
    }

    /**
     * Gestion des agences
     */
    public function agencesIndex()
    {
        $agences = Agence::orderBy('libelle')->paginate(10);
        return view('application.gestion.agences.index', compact('agences'));
    }

    public function agencesStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|unique:agences,nom|max:255',
                'libelle' => 'required|string|max:255',
                'adresse' => 'nullable|string|max:500',
                'telephone' => 'nullable|string|max:20'
            ]);

            $validated['actif'] = $request->has('actif');
            Agence::create($validated);

            return redirect()->route('application.gestion.agences')
                             ->with('success', 'Agence ajoutée avec succès!');
        } catch (\Exception $e) {
            return redirect()->route('application.gestion.agences')
                             ->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage());
        }
    }

    public function agencesUpdate(Request $request, $id)
    {
        $agence = Agence::findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|unique:agences,nom,' . $id . '|max:255',
            'libelle' => 'required|string|max:255',
            'adresse' => 'nullable|string|max:500',
            'telephone' => 'nullable|string|max:20'
        ]);

        $validated['actif'] = $request->has('actif');
        $agence->update($validated);

        return redirect()->route('application.gestion.agences')
                         ->with('success', 'Agence mise à jour avec succès!');
    }

    public function agencesDestroy($id)
    {
        $agence = Agence::findOrFail($id);
        $agence->delete();

        return redirect()->route('application.gestion.agences')
                         ->with('success', 'Agence supprimée avec succès!');
    }

    /**
     * Marquer un colis comme récupéré à la gare
     */
    public function marquerRecupere(Request $request)
    {
        $request->validate([
            'colis_id' => 'required|exists:colis,id',
            'recupere_par_nom' => 'required|string|max:255',
            'recupere_par_telephone' => 'required|string|max:20',
            'recupere_par_cin' => 'nullable|string|max:50',
            'notes_recuperation' => 'nullable|string|max:1000',
        ]);

        $colis = Colis::findOrFail($request->colis_id);

        // Vérifier que le colis n'est pas déjà récupéré
        if ($colis->recupere_gare) {
            return redirect()->back()
                           ->with('error', 'Ce colis a déjà été marqué comme récupéré.');
        }

        // Marquer comme récupéré
        $colis->update([
            'recupere_gare' => true,
            'recupere_le' => now(),
            'recupere_par_nom' => $request->recupere_par_nom,
            'recupere_par_telephone' => $request->recupere_par_telephone,
            'recupere_par_cin' => $request->recupere_par_cin,
            'notes_recuperation' => $request->notes_recuperation,
            'recupere_par_user_id' => Auth::id(),
        ]);

        return redirect()->back()
                       ->with('success', 'Colis marqué comme récupéré avec succès!');
    }
}
