<?php

namespace App\Http\Controllers;

use App\Models\Livreur;
use App\Models\Colis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class PerformanceLivreurController extends Controller
{
    /**
     * Affichage de la page d'analyse des performances
     */
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'ce_mois');
        
        // Récupérer tous les livreurs actifs
        $livreurs = Livreur::where('actif', true)->get();
        
        // Définir les dates selon la période
        $dates = $this->getDatesPeriode($periode);
        
        // Récupérer toutes les activités avec pagination
        $activites = $this->getActivitesPaginees($dates);
        
        // Calculer les statistiques pour la période
        $statistiques = $this->getStatistiquesPeriode($dates);
        
        return view('admin.performances-livreurs.index', compact(
            'livreurs', 
            'periode', 
            'dates',
            'activites',
            'statistiques'
        ));
    }

    /**
     * API pour récupérer les données de performance
     */
    public function getData(Request $request)
    {
        $periode = $request->get('periode', 'ce_mois');
        $livreur_id = $request->get('livreur_id');
        
        $dates = $this->getDatesPeriode($periode);
        $performanceData = $this->getPerformanceData($dates, $livreur_id);
        
        return response()->json($performanceData);
    }

    /**
     * Récupérer les dates selon la période sélectionnée
     */
    private function getDatesPeriode($periode)
    {
        $now = Carbon::now();
        
        switch ($periode) {
            case 'aujourd_hui':
                return [
                    'debut' => $now->startOfDay(),
                    'fin' => $now->copy()->endOfDay(),
                    'label' => 'Aujourd\'hui'
                ];
            
            case 'cette_semaine':
                return [
                    'debut' => $now->startOfWeek(),
                    'fin' => $now->copy()->endOfWeek(),
                    'label' => 'Cette semaine'
                ];
            
            case 'ce_mois':
                return [
                    'debut' => $now->startOfMonth(),
                    'fin' => $now->copy()->endOfMonth(),
                    'label' => 'Ce mois'
                ];
            
            case 'cette_annee':
                return [
                    'debut' => $now->startOfYear(),
                    'fin' => $now->copy()->endOfYear(),
                    'label' => 'Cette année'
                ];
            
            default:
                return [
                    'debut' => null,
                    'fin' => null,
                    'label' => 'Toutes les données'
                ];
        }
    }

    /**
     * Récupérer les données de performance des livreurs
     */
    private function getPerformanceData($dates, $livreur_id = null)
    {
        $query = Livreur::where('actif', true);
        
        if ($livreur_id) {
            $query->where('id', $livreur_id);
        }
        
        $livreurs = $query->get();
        
        $performanceData = [];
        
        foreach ($livreurs as $livreur) {
            $stats = $this->getStatistiquesLivreur($livreur, $dates);
            
            $performanceData[] = [
                'livreur' => $livreur,
                'stats' => $stats,
                'graphique_data' => $this->getGraphiqueData($livreur, $dates)
            ];
        }
        
        // Trier par nombre total d'actions (ramassage + livraison) décroissant
        usort($performanceData, function($a, $b) {
            $total_a = $a['stats']['total_ramasse'] + $a['stats']['total_livre'];
            $total_b = $b['stats']['total_ramasse'] + $b['stats']['total_livre'];
            return $total_b <=> $total_a;
        });
        
        return $performanceData;
    }

    /**
     * Récupérer les statistiques pour un livreur
     */
    private function getStatistiquesLivreur($livreur, $dates)
    {
        $baseQuery = function($type) use ($livreur, $dates) {
            $query = Colis::where($type, $livreur->id);
            
            if ($dates['debut'] && $dates['fin']) {
                $dateField = $type === 'ramasse_par' ? 'ramasse_le' : 'livre_le';
                $query->whereBetween($dateField, [$dates['debut'], $dates['fin']]);
            }
            
            return $query;
        };
        
        // Statistiques de ramassage
        $totalRamasse = $baseQuery('ramasse_par')->count();
        $revenusRamassage = $baseQuery('ramasse_par')->sum('montant');
        
        // Statistiques de livraison
        $totalLivre = $baseQuery('livre_par')->count();
        $revenusLivraison = $baseQuery('livre_par')->sum('montant');
        
        // Colis en cours (ramassés mais pas encore livrés)
        $enCours = Colis::where('ramasse_par', $livreur->id)
            ->whereNotNull('ramasse_le')
            ->whereNull('livre_le')
            ->count();
        
        // Temps moyen de livraison
        $tempsLivraisonMoyen = $this->getTempsLivraisonMoyen($livreur, $dates);
        
        // Taux de réussite (colis livrés par rapport aux colis ramassés)
        $tauxReussite = $totalRamasse > 0 ? round(($totalLivre / $totalRamasse) * 100, 2) : 0;
        
        return [
            'total_ramasse' => $totalRamasse,
            'total_livre' => $totalLivre,
            'en_cours' => $enCours,
            'revenus_ramassage' => $revenusRamassage,
            'revenus_livraison' => $revenusLivraison,
            'revenus_total' => $revenusRamassage + $revenusLivraison,
            'temps_livraison_moyen' => $tempsLivraisonMoyen,
            'taux_reussite' => $tauxReussite
        ];
    }

    /**
     * Calculer le temps moyen de livraison
     */
    private function getTempsLivraisonMoyen($livreur, $dates)
    {
        $query = Colis::where('ramasse_par', $livreur->id)
            ->where('livre_par', $livreur->id)
            ->whereNotNull('ramasse_le')
            ->whereNotNull('livre_le');
        
        if ($dates['debut'] && $dates['fin']) {
            $query->whereBetween('livre_le', [$dates['debut'], $dates['fin']]);
        }
        
        $colis = $query->get(['ramasse_le', 'livre_le']);
        
        if ($colis->isEmpty()) {
            return 0;
        }
        
        $totalHeures = 0;
        foreach ($colis as $c) {
            $ramasse = Carbon::parse($c->ramasse_le);
            $livre = Carbon::parse($c->livre_le);
            $totalHeures += $ramasse->diffInHours($livre);
        }
        
        return round($totalHeures / $colis->count(), 1);
    }

    /**
     * Récupérer les données pour les graphiques
     */
    private function getGraphiqueData($livreur, $dates)
    {
        if (!$dates['debut'] || !$dates['fin']) {
            return [];
        }
        
        $periode = $dates['debut']->diffInDays($dates['fin']);
        
        if ($periode <= 7) {
            // Graphique par jour pour la semaine
            return $this->getDataParJour($livreur, $dates);
        } elseif ($periode <= 31) {
            // Graphique par jour pour le mois
            return $this->getDataParJour($livreur, $dates);
        } else {
            // Graphique par mois pour l'année
            return $this->getDataParMois($livreur, $dates);
        }
    }

    /**
     * Données par jour
     */
    private function getDataParJour($livreur, $dates)
    {
        $data = [];
        $current = $dates['debut']->copy();
        
        while ($current->lte($dates['fin'])) {
            $ramasses = Colis::where('ramasse_par', $livreur->id)
                ->whereDate('ramasse_le', $current)
                ->count();
            
            $livres = Colis::where('livre_par', $livreur->id)
                ->whereDate('livre_le', $current)
                ->count();
            
            $data[] = [
                'date' => $current->format('d/m'),
                'ramasses' => $ramasses,
                'livres' => $livres
            ];
            
            $current->addDay();
        }
        
        return $data;
    }

    /**
     * Données par mois
     */
    private function getDataParMois($livreur, $dates)
    {
        $data = [];
        $current = $dates['debut']->copy()->startOfMonth();
        
        while ($current->lte($dates['fin'])) {
            $ramasses = Colis::where('ramasse_par', $livreur->id)
                ->whereMonth('ramasse_le', $current->month)
                ->whereYear('ramasse_le', $current->year)
                ->count();
            
            $livres = Colis::where('livre_par', $livreur->id)
                ->whereMonth('livre_le', $current->month)
                ->whereYear('livre_le', $current->year)
                ->count();
            
            $data[] = [
                'date' => $current->format('M Y'),
                'ramasses' => $ramasses,
                'livres' => $livres
            ];
            
            $current->addMonth();
        }
        
        return $data;
    }

    /**
     * Récupérer les activités avec pagination
     */
    private function getActivitesPaginees($dates)
    {
        // Récupérer toutes les activités en utilisant une approche différente
        $activites = collect();
        
        // Ramassages
        $ramassages = Colis::with(['livreurRamassage'])
            ->whereNotNull('ramasse_par')
            ->whereNotNull('ramasse_le')
            ->when($dates['debut'] && $dates['fin'], function($q) use ($dates) {
                return $q->whereBetween('ramasse_le', [$dates['debut'], $dates['fin']]);
            })
            ->get()
            ->map(function($colis) {
                $colis->type_action = 'ramassage';
                $colis->date_action = $colis->ramasse_le;
                return $colis;
            });
        
        // Livraisons
        $livraisons = Colis::with(['livreurLivraison'])
            ->whereNotNull('livre_par')
            ->whereNotNull('livre_le')
            ->when($dates['debut'] && $dates['fin'], function($q) use ($dates) {
                return $q->whereBetween('livre_le', [$dates['debut'], $dates['fin']]);
            })
            ->get()
            ->map(function($colis) {
                $colis->type_action = 'livraison';
                $colis->date_action = $colis->livre_le;
                return $colis;
            });
        
        // Fusionner et trier
        $activites = $ramassages->concat($livraisons)
            ->sortByDesc('date_action');
        
        // Pagination manuelle
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $offset = ($currentPage - 1) * $perPage;
        
        $paginatedItems = $activites->slice($offset, $perPage)->values();
        $total = $activites->count();
        
        $paginatedActivites = new LengthAwarePaginator(
            $paginatedItems,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
        
        return $paginatedActivites;
    }

    /**
     * Calculer les statistiques pour la période sélectionnée
     */
    private function getStatistiquesPeriode($dates)
    {
        // Colis ramassés durant la période
        $colisRamasses = Colis::whereNotNull('ramasse_par')
            ->whereNotNull('ramasse_le')
            ->when($dates['debut'] && $dates['fin'], function($q) use ($dates) {
                return $q->whereBetween('ramasse_le', [$dates['debut'], $dates['fin']]);
            })
            ->count();

        // Colis livrés durant la période
        $colisLivres = Colis::whereNotNull('livre_par')
            ->whereNotNull('livre_le')
            ->when($dates['debut'] && $dates['fin'], function($q) use ($dates) {
                return $q->whereBetween('livre_le', [$dates['debut'], $dates['fin']]);
            })
            ->count();

        // Nombre total d'activités
        $totalActivites = $colisRamasses + $colisLivres;

        // Nombre de livreurs actifs durant la période
        $livreursActifs = collect([
            Colis::whereNotNull('ramasse_par')
                ->when($dates['debut'] && $dates['fin'], function($q) use ($dates) {
                    return $q->whereBetween('ramasse_le', [$dates['debut'], $dates['fin']]);
                })
                ->distinct('ramasse_par')
                ->pluck('ramasse_par'),
            Colis::whereNotNull('livre_par')
                ->when($dates['debut'] && $dates['fin'], function($q) use ($dates) {
                    return $q->whereBetween('livre_le', [$dates['debut'], $dates['fin']]);
                })
                ->distinct('livre_par')
                ->pluck('livre_par')
        ])->flatten()->unique()->count();

        return [
            'colis_ramasses' => $colisRamasses,
            'colis_livres' => $colisLivres,
            'total_activites' => $totalActivites,
            'livreurs_actifs' => $livreursActifs
        ];
    }
}
