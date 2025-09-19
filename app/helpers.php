<?php

if (!function_exists('dashboard_route')) {
    /**
     * Retourne la route du dashboard selon le rôle de l'utilisateur
     */
    function dashboard_route()
    {
        // Si l'utilisateur peut scanner les QR codes et voir ses colis, c'est probablement un livreur
        if (auth()->check() && auth()->user()->can('scan_qr_colis') && auth()->user()->can('view_mes_colis')) {
            return route('livreur.dashboard');
        }
        return route('dashboard.index');
    }
}
