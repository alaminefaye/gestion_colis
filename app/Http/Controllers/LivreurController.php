<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livreur;
use App\Models\Colis;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            'email' => 'required|email|unique:livreurs|unique:users', // Email obligatoire et unique
            'cin' => 'required|string|unique:livreurs',
            'adresse' => 'nullable|string',
            'date_embauche' => 'required|date'
        ]);

        DB::transaction(function () use ($request) {
            // Créer le livreur
            $livreur = Livreur::create($request->all());
            
            // Créer l'utilisateur associé automatiquement
            $user = User::create([
                'name' => $livreur->prenom . ' ' . $livreur->nom,
                'email' => $livreur->email,
                'password' => Hash::make('passer123'), // Mot de passe par défaut
                'password_change_required' => true, // Forcer le changement de mot de passe
            ]);
            
            // Attribuer le rôle livreur
            $user->assignRole('livreur');
        });

        return redirect()->route('livreurs.index')
                        ->with('success', 'Livreur créé avec succès! Un compte utilisateur a été créé avec le mot de passe temporaire "passer123".');
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
    public function colisRecuperes(Request $request)
    {
        $query = Colis::with(['livreurRamassage', 'livreurLivraison'])
                     ->where(function($q) {
                         // Colis récupérés à la gare
                         $q->where('recupere_gare', true)
                           // OU colis traités par les livreurs
                           ->orWhereIn('statut_livraison', ['ramasse', 'en_transit', 'livre']);
                     });

        // Filtrage par statut
        if ($request->statut) {
            if ($request->statut === 'recupere_gare') {
                $query->where('recupere_gare', true);
            } else {
                $query->where('statut_livraison', $request->statut);
            }
        }

        // Filtrage par livreur
        if ($request->livreur) {
            $query->where(function($q) use ($request) {
                $q->where('ramasse_par', $request->livreur)
                  ->orWhere('livre_par', $request->livreur);
            });
        }

        // Filtrage par date
        if ($request->date_du) {
            $query->whereDate('created_at', '>=', $request->date_du);
        }
        if ($request->date_au) {
            $query->whereDate('created_at', '<=', $request->date_au);
        }

        $colis = $query->orderByRaw('COALESCE(recupere_le, ramasse_le, created_at) DESC')
                      ->paginate(15);

        return view('livreurs.colis-recuperes', compact('colis'));
    }

    /**
     * Tableau de bord pour les livreurs connectés
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Trouver le livreur correspondant à cet utilisateur (par email UNIQUEMENT)
        $livreur = Livreur::where('email', $user->email)->first();
        
        if (!$livreur) {
            return redirect()->route('dashboard.index')
                           ->with('error', 'Aucun profil de livreur associé à votre email. Contactez l\'administrateur.');
        }

        // Statistiques du livreur (AUJOURD'HUI SEULEMENT)
        $today = now()->toDateString();
        $stats = [
            'total_ramasse' => $livreur->colisRamasses()->whereDate('ramasse_le', $today)->count(),
            'total_livre' => $livreur->colisLivres()->whereDate('livre_le', $today)->count(),
            'en_cours' => $livreur->colisRamasses()->where('statut_livraison', 'ramasse')->whereDate('ramasse_le', $today)->count(),
            'en_transit' => $livreur->colisRamasses()->where('statut_livraison', 'en_transit')->whereDate('ramasse_le', $today)->count()
        ];

        // Colis récents du livreur (AUJOURD'HUI SEULEMENT)
        $colisRamasses = $livreur->colisRamasses()
                               ->where('statut_livraison', '!=', 'livre')
                               ->whereDate('ramasse_le', $today)
                               ->orderBy('ramasse_le', 'desc')
                               ->take(10)
                               ->get();

        $colisLivres = $livreur->colisLivres()
                              ->orderBy('livre_le', 'desc')
                              ->take(10)
                              ->get();

        return view('livreur.dashboard', compact('livreur', 'stats', 'colisRamasses', 'colisLivres'));
    }

    /**
     * Page "Mes Colis" pour un livreur spécifique
     */
    public function mesColis(Request $request)
    {
        $user = Auth::user();
        
        // Trouver le livreur correspondant à cet utilisateur (par email UNIQUEMENT)
        $livreur = Livreur::where('email', $user->email)->first();
        
        if (!$livreur) {
            return redirect()->route('dashboard.index')
                           ->with('error', 'Aucun profil de livreur associé à votre email. Contactez l\'administrateur.');
        }

        // Query de base pour les colis de ce livreur
        $query = Colis::with(['livreurRamassage', 'livreurLivraison'])
                     ->where(function($q) use ($livreur) {
                         $q->where('ramasse_par', $livreur->id)
                           ->orWhere('livre_par', $livreur->id);
                     });

        // Filtrage par statut
        if ($request->statut) {
            $query->where('statut_livraison', $request->statut);
        }

        // Filtrage par date
        if ($request->date_du) {
            $query->whereDate('created_at', '>=', $request->date_du);
        }
        if ($request->date_au) {
            $query->whereDate('created_at', '<=', $request->date_au);
        }

        $colis = $query->orderByRaw('COALESCE(livre_le, ramasse_le, created_at) DESC')
                      ->paginate(15);

        // Statistiques du livreur
        $stats = [
            'total_ramasse' => $livreur->colisRamasses()->count(),
            'total_livre' => $livreur->colisLivres()->count(),
            'en_cours' => $livreur->colisRamasses()->where('statut_livraison', 'ramasse')->count(),
            'en_transit' => $livreur->colisRamasses()->where('statut_livraison', 'en_transit')->count()
        ];

        return view('livreur.mes-colis', compact('livreur', 'colis', 'stats'));
    }

    /**
     * Afficher les détails complets d'un colis
     */
    public function detailColis(Colis $colis)
    {
        // Charger les relations
        $colis->load(['livreurRamassage', 'livreurLivraison']);
        
        return view('colis.detail', compact('colis'));
    }
}
