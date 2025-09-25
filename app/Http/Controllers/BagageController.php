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
        $bagages = Bagage::where('created_by', auth()->id())->latest()->paginate(15);
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

        $data = $request->all();
        $data['created_by'] = auth()->id();
        Bagage::create($data);

        return redirect()->route('application.bagages.index')
                        ->with('success', 'Bagage créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bagage $bagage)
    {
        // Vérifier que l'utilisateur est le créateur du bagage
        if ($bagage->created_by !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('bagages.show', compact('bagage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bagage $bagage)
    {
        // Vérifier que l'utilisateur est le créateur du bagage
        if ($bagage->created_by !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        
        $destinations = Destination::actif()->orderBy('libelle')->get();
        return view('bagages.edit', compact('bagage', 'destinations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bagage $bagage)
    {
        // Vérifier que l'utilisateur est le créateur du bagage
        if ($bagage->created_by !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        
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
        // Vérifier que l'utilisateur est le créateur du bagage
        if ($bagage->created_by !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }
        
        $bagage->delete();

        return redirect()->route('application.bagages.index')
                        ->with('success', 'Bagage supprimé avec succès.');
    }

    /**
     * Dashboard des bagages avec statistiques
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $periode = $request->get('periode', 'tout');
        
        // Base query pour filtrer par utilisateur connecté
        $baseQuery = Bagage::where('created_by', $user->id);
        
        // Appliquer le filtre de période
        switch($periode) {
            case 'aujourd_hui':
                $baseQuery = $baseQuery->whereDate('created_at', now()->toDateString());
                break;
            case 'cette_semaine':
                $baseQuery = $baseQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'ce_mois':
                $baseQuery = $baseQuery->whereMonth('created_at', now()->month)
                                      ->whereYear('created_at', now()->year);
                break;
            case 'tout':
            default:
                // Pas de filtre supplémentaire
                break;
        }
        
        // Statistiques des bagages
        $stats = [
            'total_bagages' => (clone $baseQuery)->count(),
            'avec_ticket' => (clone $baseQuery)->where('possede_ticket', true)->count(),
            'sans_ticket' => (clone $baseQuery)->where('possede_ticket', false)->count(),
            'valeur_totale' => (clone $baseQuery)->sum('valeur') ?? 0,
            'montant_total' => (clone $baseQuery)->sum('montant') ?? 0,
            'poids_total' => (clone $baseQuery)->sum('poids') ?? 0,
        ];

        // Bagages récents (filtrés par période) - limité à 5
        $bagagesRecents = (clone $baseQuery)->orderBy('created_at', 'desc')
                                           ->take(5)
                                           ->get();

        // Destinations populaires pour la période
        $destinationsPopulaires = (clone $baseQuery)->select('destination')
                                                    ->selectRaw('COUNT(*) as total')
                                                    ->groupBy('destination')
                                                    ->orderBy('total', 'desc')
                                                    ->take(5)
                                                    ->get();

        // Progression selon la période sélectionnée
        $progresPercentage = 0;
        switch($periode) {
            case 'aujourd_hui':
                $aujourdHui = (clone $baseQuery)->count();
                $hier = Bagage::where('created_by', $user->id)
                            ->whereDate('created_at', now()->subDay()->toDateString())
                            ->count();
                $progresPercentage = $hier > 0 
                    ? round((($aujourdHui - $hier) / $hier) * 100, 1)
                    : ($aujourdHui > 0 ? 100 : 0);
                break;
            case 'cette_semaine':
                $cetteSemaine = (clone $baseQuery)->count();
                $semaineDerniere = Bagage::where('created_by', $user->id)
                                       ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
                                       ->count();
                $progresPercentage = $semaineDerniere > 0 
                    ? round((($cetteSemaine - $semaineDerniere) / $semaineDerniere) * 100, 1)
                    : ($cetteSemaine > 0 ? 100 : 0);
                break;
            case 'ce_mois':
                $ceMois = (clone $baseQuery)->count();
                $moisDernier = Bagage::where('created_by', $user->id)
                                   ->whereMonth('created_at', now()->subMonth()->month)
                                   ->whereYear('created_at', now()->subMonth()->year)
                                   ->count();
                $progresPercentage = $moisDernier > 0 
                    ? round((($ceMois - $moisDernier) / $moisDernier) * 100, 1)
                    : ($ceMois > 0 ? 100 : 0);
                break;
            default:
                $progresPercentage = 0;
                break;
        }

        return view('bagages.dashboard', compact(
            'stats', 
            'bagagesRecents', 
            'destinationsPopulaires',
            'progresPercentage',
            'periode'
        ));
    }
}
