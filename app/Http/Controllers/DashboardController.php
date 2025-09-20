<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colis;
use App\Models\Bagage;
use App\Models\Livreur;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard principal
     */
    public function index()
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur est un livreur
        if ($user->hasRole('livreur')) {
            return $this->livreurDashboard();
        }
        
        // Dashboard Admin - toutes les données
        return $this->adminDashboard();
    }

    /**
     * Dashboard pour les admins - toutes les données
     */
    private function adminDashboard()
    {
        // Date d'aujourd'hui
        $today = Carbon::today();
        $currentWeek = Carbon::now()->startOfWeek();
        $currentMonth = Carbon::now()->startOfMonth();

        // Statistiques générales (données de la journée)
        $totalColis = Colis::whereDate('created_at', $today)->count();
        $totalBagages = Bagage::whereDate('created_at', $today)->count();
        $livraisonsReussies = Colis::whereDate('created_at', $today)
            ->where('statut_livraison', 'livre')->count();
        $enTransit = Colis::whereDate('created_at', $today)
            ->where('statut_livraison', 'en_transit')->count();
        $revenus_jour = Colis::whereDate('created_at', $today)->sum('montant');
        $revenus_bagages_jour = Bagage::whereDate('created_at', $today)->sum('montant');

        // Statistiques pour les cartes principales (globales)
        $stats = [
            'total_colis' => Colis::count(),
            'total_colis_jour' => $totalColis,
            'total_colis_croissance' => $this->calculateGrowthPercentage('colis', $currentMonth),
            
            // Statistiques bagages
            'total_bagages' => Bagage::count(),
            'total_bagages_jour' => $totalBagages,
            'total_bagages_croissance' => $this->calculateGrowthPercentage('bagages', $currentMonth),
            
            'livraisons_reussies' => Colis::where('statut_livraison', 'livre')->count(),
            'livraisons_reussies_jour' => $livraisonsReussies,
            'taux_reussite' => Colis::count() > 0 ? round((Colis::where('statut_livraison', 'livre')->count() / Colis::count()) * 100, 1) : 0,
            
            'en_transit' => Colis::where('statut_livraison', 'en_transit')->count(),
            'en_transit_jour' => $enTransit,
            
            'revenus_total' => Colis::sum('montant') + Bagage::sum('montant'),
            'revenus_colis' => Colis::sum('montant'),
            'revenus_bagages' => Bagage::sum('montant'),
            'revenus_jour' => $revenus_jour + $revenus_bagages_jour,
            'revenus_colis_jour' => $revenus_jour,
            'revenus_bagages_jour' => $revenus_bagages_jour,
            'revenus_croissance' => $this->calculateRevenueGrowth($currentMonth),
            'revenus_semaine' => Colis::where('created_at', '>=', $currentWeek)->sum('montant') + 
                               Bagage::where('created_at', '>=', $currentWeek)->sum('montant'),
        ];

        // Colis récents (derniers 6 de la journée)
        $colisRecents = Colis::whereDate('created_at', $today)
            ->with(['livreurRamassage', 'livreurLivraison'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Si pas assez de colis aujourd'hui, compléter avec les plus récents
        if ($colisRecents->count() < 6) {
            $additional = Colis::with(['livreurRamassage', 'livreurLivraison'])
                ->whereNotIn('id', $colisRecents->pluck('id'))
                ->orderBy('created_at', 'desc')
                ->limit(6 - $colisRecents->count())
                ->get();
            $colisRecents = $colisRecents->merge($additional);
        }

        // Bagages récents (derniers 6 de la journée)
        $bagagesRecents = Bagage::whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Si pas assez de bagages aujourd'hui, compléter avec les plus récents
        if ($bagagesRecents->count() < 6) {
            $additional = Bagage::whereNotIn('id', $bagagesRecents->pluck('id'))
                ->orderBy('created_at', 'desc')
                ->limit(6 - $bagagesRecents->count())
                ->get();
            $bagagesRecents = $bagagesRecents->merge($additional);
        }

        // Données pour les graphiques (journée par défaut pour Statistiques des Livraisons)
        $statistiquesLivraisons = $this->getStatistiquesLivraisons('jour');
        
        // Données pour Aperçu des Revenus (garder tel quel - semaine)
        $apercuRevenus = $this->getApercuRevenus();

        // Transactions récentes (de la journée)
        $transactionsRecentes = Colis::whereDate('created_at', $today)
            ->whereIn('statut_livraison', ['livre', 'en_transit', 'ramasse'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Rapport d'activité
        $rapportActivite = [
            'taux_livraison' => $stats['taux_reussite'],
            'satisfaction_client' => 96.8, // Peut être calculé plus tard avec un système de reviews
            'colis_problematiques' => Colis::count() > 0 ? round((Colis::where('statut_livraison', 'probleme')->count() / Colis::count()) * 100, 1) : 0,
        ];

        return view('dashboard.index', compact(
            'stats', 
            'colisRecents', 
            'bagagesRecents',
            'statistiquesLivraisons', 
            'apercuRevenus', 
            'transactionsRecentes', 
            'rapportActivite'
        ));
    }

    /**
     * Dashboard pour les livreurs - données filtrées par livreur
     */
    private function livreurDashboard()
    {
        $user = Auth::user();
        
        // Trouver le livreur correspondant à cet utilisateur
        $livreur = Livreur::where('email', $user->email)->first();
        
        if (!$livreur) {
            // Si pas de profil livreur, rediriger vers dashboard admin avec message
            return redirect()->route('dashboard.index')
                           ->with('error', 'Aucun profil de livreur associé à votre email. Contactez l\'administrateur.');
        }

        // Date d'aujourd'hui
        $today = Carbon::today();
        $currentWeek = Carbon::now()->startOfWeek();
        $currentMonth = Carbon::now()->startOfMonth();

        // Statistiques du livreur (données de la journée)
        $totalColis = Colis::where(function($q) use ($livreur) {
                $q->where('ramasse_par', $livreur->id)
                  ->orWhere('livre_par', $livreur->id);
            })
            ->whereDate('created_at', $today)->count();
            
        $livraisonsReussies = Colis::where('livre_par', $livreur->id)
            ->whereDate('livre_le', $today)->count();
            
        $enTransit = Colis::where('ramasse_par', $livreur->id)
            ->where('statut_livraison', 'en_transit')
            ->whereDate('ramasse_le', $today)->count();
            
        $revenus_jour = Colis::where(function($q) use ($livreur) {
                $q->where('ramasse_par', $livreur->id)
                  ->orWhere('livre_par', $livreur->id);
            })
            ->whereDate('created_at', $today)->sum('montant');

        // Statistiques pour les cartes principales (données du livreur)
        $stats = [
            'total_colis' => Colis::where(function($q) use ($livreur) {
                $q->where('ramasse_par', $livreur->id)
                  ->orWhere('livre_par', $livreur->id);
            })->count(),
            'total_colis_jour' => $totalColis,
            'total_colis_croissance' => $this->calculateLivreurGrowthPercentage('colis', $currentMonth, $livreur->id),
            
            'livraisons_reussies' => Colis::where('livre_par', $livreur->id)->count(),
            'livraisons_reussies_jour' => $livraisonsReussies,
            'taux_reussite' => $this->calculateLivreurSuccessRate($livreur->id),
            
            'en_transit' => Colis::where('ramasse_par', $livreur->id)
                ->where('statut_livraison', 'en_transit')->count(),
            'en_transit_jour' => $enTransit,
            
            'revenus_total' => Colis::where(function($q) use ($livreur) {
                $q->where('ramasse_par', $livreur->id)
                  ->orWhere('livre_par', $livreur->id);
            })->sum('montant'),
            'revenus_jour' => $revenus_jour,
            'revenus_croissance' => $this->calculateLivreurRevenueGrowth($currentMonth, $livreur->id),
            'revenus_semaine' => Colis::where(function($q) use ($livreur) {
                $q->where('ramasse_par', $livreur->id)
                  ->orWhere('livre_par', $livreur->id);
            })->where('created_at', '>=', $currentWeek)->sum('montant'),
        ];

        // Colis récents du livreur (derniers 6 de la journée)
        $colisRecents = Colis::where(function($q) use ($livreur) {
                $q->where('ramasse_par', $livreur->id)
                  ->orWhere('livre_par', $livreur->id);
            })
            ->whereDate('created_at', $today)
            ->with(['livreurRamassage', 'livreurLivraison'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Si pas assez de colis aujourd'hui, compléter avec les plus récents du livreur
        if ($colisRecents->count() < 6) {
            $additional = Colis::where(function($q) use ($livreur) {
                    $q->where('ramasse_par', $livreur->id)
                      ->orWhere('livre_par', $livreur->id);
                })
                ->with(['livreurRamassage', 'livreurLivraison'])
                ->whereNotIn('id', $colisRecents->pluck('id'))
                ->orderBy('created_at', 'desc')
                ->limit(6 - $colisRecents->count())
                ->get();
            $colisRecents = $colisRecents->merge($additional);
        }

        // Données pour les graphiques (journée par défaut pour Statistiques des Livraisons)
        $statistiquesLivraisons = $this->getLivreurStatistiquesLivraisons('jour', $livreur->id);
        
        // Données pour Aperçu des Revenus (données du livreur)
        $apercuRevenus = $this->getLivreurApercuRevenus($livreur->id);

        // Transactions récentes du livreur (de la journée)
        $transactionsRecentes = Colis::where(function($q) use ($livreur) {
                $q->where('ramasse_par', $livreur->id)
                  ->orWhere('livre_par', $livreur->id);
            })
            ->whereDate('created_at', $today)
            ->whereIn('statut_livraison', ['livre', 'en_transit', 'ramasse'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Rapport d'activité du livreur
        $rapportActivite = [
            'taux_livraison' => $stats['taux_reussite'],
            'satisfaction_client' => 96.8, // Peut être calculé plus tard avec un système de reviews
            'colis_problematiques' => $this->calculateLivreurProblematicRate($livreur->id),
        ];

        return view('dashboard.index', compact(
            'stats', 
            'colisRecents', 
            'statistiquesLivraisons', 
            'apercuRevenus', 
            'transactionsRecentes', 
            'rapportActivite'
        ));
    }

    /**
     * Afficher les analytics
     */
    public function analytics()
    {
        $today = Carbon::today();
        
        // Données analytics (données de la journée)
        $analytics = [
            'colis_aujourd_hui' => Colis::whereDate('created_at', $today)->count(),
            'livraisons_reussies' => Colis::whereDate('created_at', $today)
                ->where('statut_livraison', 'livre')->count(),
            'revenus_jour' => Colis::whereDate('created_at', $today)->sum('montant'),
            'problemes' => Colis::whereDate('created_at', $today)
                ->where('statut_livraison', 'probleme')->count()
        ];

        return view('dashboard.analytics', compact('analytics'));
    }

    /**
     * Calculer le pourcentage de croissance
     */
    private function calculateGrowthPercentage($type, $currentPeriod)
    {
        $previousPeriod = $currentPeriod->copy()->subMonth();
        
        if ($type === 'colis') {
            $current = Colis::where('created_at', '>=', $currentPeriod)->count();
            $previous = Colis::whereBetween('created_at', [$previousPeriod, $currentPeriod])->count();
        } elseif ($type === 'bagages') {
            $current = Bagage::where('created_at', '>=', $currentPeriod)->count();
            $previous = Bagage::whereBetween('created_at', [$previousPeriod, $currentPeriod])->count();
        }
        
        if ($previous > 0) {
            return round((($current - $previous) / $previous) * 100, 1);
        }
        
        return $current > 0 ? 100 : 0;
    }

    /**
     * Calculer la croissance des revenus (colis + bagages)
     */
    private function calculateRevenueGrowth($currentPeriod)
    {
        $previousPeriod = $currentPeriod->copy()->subMonth();
        
        $current = Colis::where('created_at', '>=', $currentPeriod)->sum('montant') + 
                   Bagage::where('created_at', '>=', $currentPeriod)->sum('montant');
        $previous = Colis::whereBetween('created_at', [$previousPeriod, $currentPeriod])->sum('montant') +
                    Bagage::whereBetween('created_at', [$previousPeriod, $currentPeriod])->sum('montant');
        
        if ($previous > 0) {
            return round((($current - $previous) / $previous) * 100, 1);
        }
        
        return $current > 0 ? 100 : 0;
    }

    /**
     * Obtenir les statistiques de livraisons par période
     */
    private function getStatistiquesLivraisons($periode = 'jour')
    {
        if ($periode === 'jour') {
            // Données par heure pour la journée
            $data = [];
            for ($hour = 0; $hour < 24; $hour++) {
                $startHour = Carbon::today()->addHours($hour);
                $endHour = $startHour->copy()->addHour();
                
                $data[] = [
                    'heure' => $hour . 'h',
                    'livraisons' => (int) Colis::whereBetween('created_at', [$startHour, $endHour])
                        ->where('statut_livraison', 'livre')->count(),
                    'ramassages' => (int) Colis::whereBetween('created_at', [$startHour, $endHour])
                        ->where('statut_livraison', 'ramasse')->count(),
                ];
            }
            return $data;
        } elseif ($periode === 'semaine') {
            // Données par jour pour la semaine
            $data = [];
            $startWeek = Carbon::now()->startOfWeek();
            
            for ($day = 0; $day < 7; $day++) {
                $currentDay = $startWeek->copy()->addDays($day);
                
                $data[] = [
                    'jour' => $currentDay->format('D'),
                    'livraisons' => (int) Colis::whereDate('created_at', $currentDay)
                        ->where('statut_livraison', 'livre')->count(),
                    'ramassages' => (int) Colis::whereDate('created_at', $currentDay)
                        ->where('statut_livraison', 'ramasse')->count(),
                ];
            }
            return $data;
        }
        
        return [];
    }

    /**
     * Obtenir l'aperçu des revenus (colis + bagages)
     */
    private function getApercuRevenus()
    {
        $startWeek = Carbon::now()->startOfWeek();
        $data = [];
        
        for ($day = 0; $day < 7; $day++) {
            $currentDay = $startWeek->copy()->addDays($day);
            
            $revenus = Colis::whereDate('created_at', $currentDay)->sum('montant') +
                       Bagage::whereDate('created_at', $currentDay)->sum('montant');
            
            $data[] = [
                'jour' => $currentDay->format('D'),
                'revenus' => (int) $revenus, // S'assurer que c'est un entier
            ];
        }
        
        return $data;
    }

    /**
     * API pour récupérer les statistiques par période (AJAX)
     */
    public function getStatistiques($periode)
    {
        $data = $this->getStatistiquesLivraisons($periode);
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Calculer le pourcentage de croissance pour un livreur spécifique
     */
    private function calculateLivreurGrowthPercentage($type, $currentPeriod, $livreurId)
    {
        $previousPeriod = $currentPeriod->copy()->subMonth();
        
        if ($type === 'colis') {
            $current = Colis::where(function($q) use ($livreurId) {
                $q->where('ramasse_par', $livreurId)
                  ->orWhere('livre_par', $livreurId);
            })->where('created_at', '>=', $currentPeriod)->count();
            
            $previous = Colis::where(function($q) use ($livreurId) {
                $q->where('ramasse_par', $livreurId)
                  ->orWhere('livre_par', $livreurId);
            })->whereBetween('created_at', [$previousPeriod, $currentPeriod])->count();
        }
        
        if ($previous > 0) {
            return round((($current - $previous) / $previous) * 100, 1);
        }
        
        return $current > 0 ? 100 : 0;
    }

    /**
     * Calculer la croissance des revenus pour un livreur spécifique
     */
    private function calculateLivreurRevenueGrowth($currentPeriod, $livreurId)
    {
        $previousPeriod = $currentPeriod->copy()->subMonth();
        
        $current = Colis::where(function($q) use ($livreurId) {
            $q->where('ramasse_par', $livreurId)
              ->orWhere('livre_par', $livreurId);
        })->where('created_at', '>=', $currentPeriod)->sum('montant');
        
        $previous = Colis::where(function($q) use ($livreurId) {
            $q->where('ramasse_par', $livreurId)
              ->orWhere('livre_par', $livreurId);
        })->whereBetween('created_at', [$previousPeriod, $currentPeriod])->sum('montant');
        
        if ($previous > 0) {
            return round((($current - $previous) / $previous) * 100, 1);
        }
        
        return $current > 0 ? 100 : 0;
    }

    /**
     * Calculer le taux de réussite pour un livreur spécifique
     */
    private function calculateLivreurSuccessRate($livreurId)
    {
        $totalColis = Colis::where(function($q) use ($livreurId) {
            $q->where('ramasse_par', $livreurId)
              ->orWhere('livre_par', $livreurId);
        })->count();
        
        if ($totalColis == 0) return 0;
        
        $colisLivres = Colis::where('livre_par', $livreurId)->count();
        
        return round(($colisLivres / $totalColis) * 100, 1);
    }

    /**
     * Calculer le taux de colis problématiques pour un livreur spécifique
     */
    private function calculateLivreurProblematicRate($livreurId)
    {
        $totalColis = Colis::where(function($q) use ($livreurId) {
            $q->where('ramasse_par', $livreurId)
              ->orWhere('livre_par', $livreurId);
        })->count();
        
        if ($totalColis == 0) return 0;
        
        $colisProblematiques = Colis::where(function($q) use ($livreurId) {
            $q->where('ramasse_par', $livreurId)
              ->orWhere('livre_par', $livreurId);
        })->where('statut_livraison', 'probleme')->count();
        
        return round(($colisProblematiques / $totalColis) * 100, 1);
    }

    /**
     * Obtenir les statistiques de livraisons par période pour un livreur spécifique
     */
    private function getLivreurStatistiquesLivraisons($periode = 'jour', $livreurId)
    {
        if ($periode === 'jour') {
            // Données par heure pour la journée
            $data = [];
            for ($hour = 0; $hour < 24; $hour++) {
                $startHour = Carbon::today()->addHours($hour);
                $endHour = $startHour->copy()->addHour();
                
                $data[] = [
                    'heure' => $hour . 'h',
                    'livraisons' => (int) Colis::where('livre_par', $livreurId)
                        ->whereBetween('livre_le', [$startHour, $endHour])->count(),
                    'ramassages' => (int) Colis::where('ramasse_par', $livreurId)
                        ->whereBetween('ramasse_le', [$startHour, $endHour])->count(),
                ];
            }
            return $data;
        } elseif ($periode === 'semaine') {
            // Données par jour pour la semaine
            $data = [];
            $startWeek = Carbon::now()->startOfWeek();
            
            for ($day = 0; $day < 7; $day++) {
                $currentDay = $startWeek->copy()->addDays($day);
                
                $data[] = [
                    'jour' => $currentDay->format('D'),
                    'livraisons' => (int) Colis::where('livre_par', $livreurId)
                        ->whereDate('livre_le', $currentDay)->count(),
                    'ramassages' => (int) Colis::where('ramasse_par', $livreurId)
                        ->whereDate('ramasse_le', $currentDay)->count(),
                ];
            }
            return $data;
        }
        
        return [];
    }

    /**
     * Obtenir l'aperçu des revenus pour un livreur spécifique
     */
    private function getLivreurApercuRevenus($livreurId)
    {
        $startWeek = Carbon::now()->startOfWeek();
        $data = [];
        
        for ($day = 0; $day < 7; $day++) {
            $currentDay = $startWeek->copy()->addDays($day);
            
            $revenus = Colis::where(function($q) use ($livreurId) {
                $q->where('ramasse_par', $livreurId)
                  ->orWhere('livre_par', $livreurId);
            })->whereDate('created_at', $currentDay)->sum('montant');
            
            $data[] = [
                'jour' => $currentDay->format('D'),
                'revenus' => (int) $revenus,
            ];
        }
        
        return $data;
    }
}
