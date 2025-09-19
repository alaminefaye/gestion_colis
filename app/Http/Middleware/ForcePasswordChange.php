<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Si l'utilisateur est connecté et doit changer son mot de passe
        if ($user && $user->password_change_required) {
            // Permettre l'accès aux routes de changement de mot de passe et déconnexion
            $allowedRoutes = [
                'password.change',
                'password.update', 
                'logout'
            ];
            
            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('password.change')
                              ->with('warning', 'Vous devez changer votre mot de passe avant de continuer.');
            }
        }
        
        return $next($request);
    }
}
