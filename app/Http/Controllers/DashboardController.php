<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard principal
     */
    public function index()
    {
        // Données du dashboard (à remplacer par de vraies données depuis la base)
        $stats = [
            'total_colis' => 1236,
            'livraisons_reussies' => 1156,
            'en_transit' => 68,
            'revenus_total' => 45678
        ];

        return view('dashboard.index', compact('stats'));
    }

    /**
     * Afficher les analytics
     */
    public function analytics()
    {
        // Données analytics (à remplacer par de vraies données)
        $analytics = [
            'colis_aujourd_hui' => 87,
            'livraisons_reussies' => 82,
            'revenus_jour' => 2845,
            'problemes' => 5
        ];

        return view('dashboard.analytics', compact('analytics'));
    }
}
