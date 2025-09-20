<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colis;
use Carbon\Carbon;

class TrackingController extends Controller
{
    /**
     * Affichage public du suivi d'un colis
     * Accessible sans authentification
     */
    public function track($id)
    {
        // Récupérer le colis avec les relations
        $colis = Colis::with(['livreurRamassage', 'livreurLivraison'])
                     ->findOrFail($id);

        // Préparer l'historique des statuts
        $timeline = $this->getColisTimeline($colis);
        
        // Obtenir le statut actuel
        $currentStatus = $this->getCurrentStatus($colis);

        return view('tracking.show', compact('colis', 'timeline', 'currentStatus'));
    }

    /**
     * Génère la timeline des événements du colis
     */
    private function getColisTimeline($colis)
    {
        $timeline = [];

        // 1. Création du colis
        $timeline[] = [
            'date' => $colis->created_at,
            'status' => 'created',
            'title' => 'Colis créé',
            'description' => 'Votre colis a été enregistré dans notre système',
            'icon' => 'ti-plus-circle',
            'color' => 'primary',
            'completed' => true
        ];

        // 2. Préparation (toujours après création)
        $timeline[] = [
            'date' => $colis->created_at,
            'status' => 'preparation',
            'title' => 'En préparation',
            'description' => 'Votre colis est en cours de préparation pour l\'expédition',
            'icon' => 'ti-package',
            'color' => 'warning',
            'completed' => true
        ];

        // 3. Récupération en gare (si applicable)
        if ($colis->recupere_gare && $colis->recupere_le) {
            $timeline[] = [
                'date' => $colis->recupere_le,
                'status' => 'recovered',
                'title' => 'Récupéré en gare',
                'description' => 'Le colis a été récupéré à la gare de départ',
                'icon' => 'ti-building-warehouse',
                'color' => 'info',
                'completed' => true
            ];
        }

        // 4. Ramassage (si applicable)
        if ($colis->ramasse_par && $colis->ramasse_le) {
            $timeline[] = [
                'date' => $colis->ramasse_le,
                'status' => 'picked_up',
                'title' => 'Ramassé',
                'description' => $colis->livreurRamassage 
                    ? "Ramassé par {$colis->livreurRamassage->nom_complet}"
                    : 'Colis ramassé par notre équipe',
                'icon' => 'ti-truck',
                'color' => 'success',
                'completed' => true
            ];
        }

        // 5. En transit (si ramassé mais pas encore livré)
        if ($colis->ramasse_par && !$colis->livre_le) {
            $timeline[] = [
                'date' => $colis->ramasse_le,
                'status' => 'in_transit',
                'title' => 'En cours de livraison',
                'description' => 'Votre colis est en route vers sa destination',
                'icon' => 'ti-truck-delivery',
                'color' => 'primary',
                'completed' => true
            ];
        }

        // 6. Livraison (si applicable)
        if ($colis->livre_par && $colis->livre_le) {
            $timeline[] = [
                'date' => $colis->livre_le,
                'status' => 'delivered',
                'title' => 'Livré',
                'description' => $colis->livreurLivraison 
                    ? "Livré par {$colis->livreurLivraison->nom_complet}"
                    : 'Colis livré avec succès',
                'icon' => 'ti-check-circle',
                'color' => 'success',
                'completed' => true
            ];
        } else {
            // 7. Livraison prévue (étape future)
            $timeline[] = [
                'date' => null,
                'status' => 'to_be_delivered',
                'title' => 'À livrer',
                'description' => 'En attente de livraison au destinataire',
                'icon' => 'ti-clock',
                'color' => 'secondary',
                'completed' => false
            ];
        }

        // Trier par date
        usort($timeline, function($a, $b) {
            if ($a['date'] === null) return 1;
            if ($b['date'] === null) return -1;
            return $a['date']->timestamp - $b['date']->timestamp;
        });

        return $timeline;
    }

    /**
     * Déterminer le statut actuel du colis
     */
    private function getCurrentStatus($colis)
    {
        if ($colis->livre_par && $colis->livre_le) {
            return [
                'status' => 'delivered',
                'label' => 'Livré',
                'color' => 'success',
                'progress' => 100
            ];
        }

        if ($colis->ramasse_par && $colis->ramasse_le) {
            return [
                'status' => 'in_transit',
                'label' => 'En cours de livraison',
                'color' => 'primary',
                'progress' => 75
            ];
        }

        if ($colis->recupere_gare && $colis->recupere_le) {
            return [
                'status' => 'ready_for_pickup',
                'label' => 'Prêt pour ramassage',
                'color' => 'info',
                'progress' => 50
            ];
        }

        return [
            'status' => 'preparation',
            'label' => 'En préparation',
            'color' => 'warning',
            'progress' => 25
        ];
    }
}
