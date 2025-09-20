<?php

if (!function_exists('dashboard_route')) {
    /**
     * Retourne la route du dashboard selon le rôle de l'utilisateur
     */
    function dashboard_route()
    {
        // Redirection selon le rôle de l'utilisateur
        if (auth()->check() && auth()->user()->hasRole('livreur')) {
            return route('livreur.dashboard');
        }
        return route('dashboard.index');
    }
}
