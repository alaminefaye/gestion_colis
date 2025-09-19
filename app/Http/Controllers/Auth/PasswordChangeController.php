<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
    /**
     * Afficher le formulaire de changement de mot de passe
     */
    public function showChangeForm()
    {
        return view('auth.password-change');
    }

    /**
     * Traiter le changement de mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($request->password),
            'password_change_required' => false, // Plus besoin de changer le mot de passe
        ]);

        return redirect()->route('dashboard.index')
                        ->with('success', 'Mot de passe changé avec succès !');
    }
}
