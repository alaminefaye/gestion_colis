<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Le middleware sera géré dans les routes
    }

    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirection intelligente selon le rôle de l'utilisateur
            $user = Auth::user();
            
            // Page d'origine ou redirection par défaut selon le rôle
            $defaultRoute = $user->hasRole('livreur') 
                ? route('livreur.dashboard') 
                : route('dashboard.index');
            
            $intended = $request->session()->pull('url.intended', $defaultRoute);
            
            return redirect()->intended($intended)->with('success', 'Connexion réussie ! Bienvenue ' . $user->name);
        }

        throw ValidationException::withMessages([
            'email' => ['Les informations d\'identification fournies ne correspondent pas à nos enregistrements.'],
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
