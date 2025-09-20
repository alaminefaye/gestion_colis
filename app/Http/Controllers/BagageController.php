<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bagage;
use App\Models\Destination;
use Illuminate\Support\Facades\Validator;

class BagageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bagages = Bagage::latest()->paginate(15);
        $destinations = Destination::actif()->orderBy('libelle')->get();
        return view('bagages.index', compact('bagages', 'destinations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $destinations = Destination::actif()->orderBy('libelle')->get();
        return view('bagages.create', compact('destinations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero' => 'required|string|unique:bagages,numero|max:255',
            'possede_ticket' => 'required|boolean',
            'numero_ticket' => 'nullable|string|max:255',
            'destination' => 'required|string|max:255',
            'nom_famille' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'valeur' => 'nullable|numeric|min:0',
            'montant' => 'nullable|numeric|min:0',
            'poids' => 'nullable|numeric|min:0',
            'contenu' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        Bagage::create($request->all());

        return redirect()->route('application.bagages.index')
                        ->with('success', 'Bagage créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bagage $bagage)
    {
        return view('bagages.show', compact('bagage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bagage $bagage)
    {
        $destinations = Destination::actif()->orderBy('libelle')->get();
        return view('bagages.edit', compact('bagage', 'destinations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bagage $bagage)
    {
        $validator = Validator::make($request->all(), [
            'numero' => 'required|string|unique:bagages,numero,' . $bagage->id . '|max:255',
            'possede_ticket' => 'required|boolean',
            'numero_ticket' => 'nullable|string|max:255',
            'destination' => 'required|string|max:255',
            'nom_famille' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'valeur' => 'nullable|numeric|min:0',
            'montant' => 'nullable|numeric|min:0',
            'poids' => 'nullable|numeric|min:0',
            'contenu' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $bagage->update($request->all());

        return redirect()->route('application.bagages.index')
                        ->with('success', 'Bagage mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bagage $bagage)
    {
        $bagage->delete();

        return redirect()->route('application.bagages.index')
                        ->with('success', 'Bagage supprimé avec succès.');
    }
}
