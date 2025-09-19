<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Liste des clients
     */
    public function index()
    {
        $clients = Client::orderBy('nom_complet')->paginate(15);
        return view('application.clients.index', compact('clients'));
    }

    /**
     * Ajouter un client
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom_complet' => 'required|string|max:255',
                'telephone' => 'required|string|unique:clients,telephone|max:20',
                'email' => 'nullable|email|max:255'
            ]);

            Client::create($validated);

            return redirect()->route('application.clients.index')
                             ->with('success', 'Client ajouté avec succès!');
        } catch (\Exception $e) {
            return redirect()->route('application.clients.index')
                             ->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour un client
     */
    public function update(Request $request, $id)
    {
        try {
            $client = Client::findOrFail($id);
            
            $validated = $request->validate([
                'nom_complet' => 'required|string|max:255',
                'telephone' => 'required|string|unique:clients,telephone,' . $id . '|max:20',
                'email' => 'nullable|email|max:255'
            ]);

            $client->update($validated);

            return redirect()->route('application.clients.index')
                             ->with('success', 'Client mis à jour avec succès!');
        } catch (\Exception $e) {
            return redirect()->route('application.clients.index')
                             ->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un client
     */
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return redirect()->route('application.clients.index')
                             ->with('success', 'Client supprimé avec succès!');
        } catch (\Exception $e) {
            return redirect()->route('application.clients.index')
                             ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * API : Récupérer un client par téléphone
     */
    public function getByTelephone($telephone)
    {
        $client = Client::where('telephone', $telephone)->first();
        
        if ($client) {
            return response()->json([
                'success' => true,
                'client' => [
                    'nom_complet' => $client->nom_complet,
                    'telephone' => $client->telephone,
                    'email' => $client->email
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Client non trouvé'
        ]);
    }
}
