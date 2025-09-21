<?php

if (!function_exists('dashboard_route')) {
    /**
     * Retourne la route du dashboard selon le rôle de l'utilisateur
     */
    function dashboard_route()
    {
        if (!auth()->check()) {
            return route('login');
        }

        $user = auth()->user();
        
        // Si c'est un super-admin ou admin : dashboard principal
        if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
            return route('dashboard.index');
        }
        
        // Si c'est un livreur : dashboard livreur
        if ($user->hasRole('livreur')) {
            return route('livreur.dashboard');
        }
        
        // Pour tous les autres (gestionnaires, etc.) : tableau de bord gestionnaire
        return route('application.gestionnaire.dashboard');
    }
}
