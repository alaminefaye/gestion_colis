<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colis;
use App\Models\Destination;
use App\Models\Agence;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ApplicationController extends Controller
{
    /**
     * Liste des colis
     */
    public function ecomProductList()
    {
        // Exclure les colis récupérés à la gare, ramassés ou livrés
        $colis = Colis::where('recupere_gare', false)
                      ->where('statut_livraison', 'en_attente')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        return view('application.ecom-product-list', compact('colis'));
    }

    /**
     * Liste complète des colis (y compris récupérés et livrés)
     */
    public function ecomProductListAll()
    {
        $colis = Colis::orderBy('created_at', 'desc')->paginate(10);
        return view('application.ecom-product-list-all', compact('colis'));
    }

    /**
     * Liste des colis ramassés avec informations du livreur
     */
    public function ecomProductListRamasses()
    {
        $colis = Colis::where('statut_livraison', 'ramasse')
                      ->with(['livreurRamassage']) // Charger la relation livreur
                      ->orderBy('ramasse_le', 'desc')
                      ->paginate(10);
        return view('application.ecom-product-list-ramasses', compact('colis'));
    }

    /**
     * Ajouter un colis
     */
    public function ecomProductAdd()
    {
        $destinations = Destination::actif()->orderBy('libelle')->get();
        $agences = Agence::actif()->orderBy('libelle')->get();
        
        return view('application.ecom-product-add', compact('destinations', 'agences'));
    }

    /**
     * Enregistrer un nouveau colis
     */
    public function ecomProductStore(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'numero_courrier' => 'required|string|unique:colis,numero_courrier|max:50',
            'destination' => 'required|string',
            'nom_expediteur' => 'required|string|max:255',
            'telephone_expediteur' => 'required|string|max:20',
            'nom_beneficiaire' => 'required|string|max:255',
            'telephone_beneficiaire' => 'required|string|max:20',
            'montant' => 'required|numeric|min:0',
            'valeur_colis' => 'required|numeric|min:0',
            'type_colis' => 'required|string',
            'agence_reception' => 'required|string',
            'description' => 'nullable|string',
            'photo_courrier' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:15360'
        ], [
            'numero_courrier.required' => 'Le numéro de courrier est obligatoire.',
            'numero_courrier.unique' => 'Ce numéro de courrier existe déjà. Veuillez en choisir un autre.',
            'numero_courrier.max' => 'Le numéro de courrier ne peut pas dépasser 50 caractères.',
            'destination.required' => 'La destination est obligatoire.',
            'nom_expediteur.required' => 'Le nom de l\'expéditeur est obligatoire.',
            'telephone_expediteur.required' => 'Le téléphone de l\'expéditeur est obligatoire.',
            'nom_beneficiaire.required' => 'Le nom du bénéficiaire est obligatoire.',
            'telephone_beneficiaire.required' => 'Le téléphone du bénéficiaire est obligatoire.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.min' => 'Le montant doit être positif.',
            'valeur_colis.required' => 'La valeur du colis est obligatoire.',
            'valeur_colis.min' => 'La valeur du colis doit être positive.',
            'type_colis.required' => 'Le type de colis est obligatoire.',
            'agence_reception.required' => 'L\'agence de réception est obligatoire.',
            'photo_courrier.image' => 'Le fichier doit être une image.',
            'photo_courrier.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'photo_courrier.max' => 'L\'image ne peut pas dépasser 15MB.'
        ]);

        // Créer automatiquement les clients expéditeur et bénéficiaire
        Client::createOrUpdate(
            $validated['nom_expediteur'], 
            $validated['telephone_expediteur']
        );
        
        Client::createOrUpdate(
            $validated['nom_beneficiaire'], 
            $validated['telephone_beneficiaire']
        );

        // Gestion de l'upload de photo
        if ($request->hasFile('photo_courrier')) {
            $photo = $request->file('photo_courrier');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('uploads/colis'), $photoName);
            $validated['photo_courrier'] = $photoName;
        }

        // Créer le colis
        $colis = Colis::create($validated);

        return redirect()->route('application.ecom-product-list')
                         ->with('success', 'Colis ajouté avec succès! Les clients ont été créés automatiquement.');
    }

    /**
     * Afficher un colis
     */
    public function ecomProductShow($id)
    {
        $colis = Colis::findOrFail($id);
        return view('application.ecom-product-show', compact('colis'));
    }

    /**
     * Modifier un colis
     */
    public function ecomProductEdit($id)
    {
        $colis = Colis::findOrFail($id);

        // Vérifier si le colis a déjà été récupéré
        if ($colis->recupere_gare) {
            return redirect()->route('application.ecom-product-show', $id)
                           ->with('error', 'Impossible de modifier ce colis car il a déjà été récupéré le ' . $colis->recupere_le?->format('d/m/Y à H:i') . '.');
        }

        $destinations = Destination::actif()->orderBy('libelle')->get();
        $agences = Agence::actif()->orderBy('libelle')->get();
        
        return view('application.ecom-product-edit', compact('colis', 'destinations', 'agences'));
    }

    /**
     * Mettre à jour un colis
     */
    public function ecomProductUpdate(Request $request, $id)
    {
        $colis = Colis::findOrFail($id);

        // Vérifier si le colis a déjà été récupéré
        if ($colis->recupere_gare) {
            return redirect()->back()
                           ->with('error', 'Impossible de modifier ce colis car il a déjà été récupéré le ' . $colis->recupere_le?->format('d/m/Y à H:i') . '.');
        }

        // Validation des données
        $validated = $request->validate([
            'numero_courrier' => 'required|string|unique:colis,numero_courrier,' . $id . '|max:50',
            'destination' => 'required|string',
            'nom_expediteur' => 'required|string|max:255',
            'telephone_expediteur' => 'required|string|max:20',
            'nom_beneficiaire' => 'required|string|max:255',
            'telephone_beneficiaire' => 'required|string|max:20',
            'montant' => 'required|numeric|min:0',
            'valeur_colis' => 'required|numeric|min:0',
            'type_colis' => 'required|string',
            'agence_reception' => 'required|string',
            'description' => 'nullable|string',
            'photo_courrier' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:15360'
        ], [
            'numero_courrier.required' => 'Le numéro de courrier est obligatoire.',
            'numero_courrier.unique' => 'Ce numéro de courrier existe déjà. Veuillez en choisir un autre.',
            'numero_courrier.max' => 'Le numéro de courrier ne peut pas dépasser 50 caractères.',
            'destination.required' => 'La destination est obligatoire.',
            'nom_expediteur.required' => 'Le nom de l\'expéditeur est obligatoire.',
            'telephone_expediteur.required' => 'Le téléphone de l\'expéditeur est obligatoire.',
            'nom_beneficiaire.required' => 'Le nom du bénéficiaire est obligatoire.',
            'telephone_beneficiaire.required' => 'Le téléphone du bénéficiaire est obligatoire.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.min' => 'Le montant doit être positif.',
            'valeur_colis.required' => 'La valeur du colis est obligatoire.',
            'valeur_colis.min' => 'La valeur du colis doit être positive.',
            'type_colis.required' => 'Le type de colis est obligatoire.',
            'agence_reception.required' => 'L\'agence de réception est obligatoire.',
            'photo_courrier.image' => 'Le fichier doit être une image.',
            'photo_courrier.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'photo_courrier.max' => 'L\'image ne peut pas dépasser 15MB.'
        ]);

        // Gestion de l'upload de photo
        if ($request->hasFile('photo_courrier')) {
            // Supprimer l'ancienne photo si elle existe
            if ($colis->photo_courrier && file_exists(public_path('uploads/colis/' . $colis->photo_courrier))) {
                unlink(public_path('uploads/colis/' . $colis->photo_courrier));
            }

            $photo = $request->file('photo_courrier');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('uploads/colis'), $photoName);
            $validated['photo_courrier'] = $photoName;
        }

        $colis->update($validated);

        return redirect()->route('application.ecom-product-list')
                         ->with('success', 'Colis mis à jour avec succès!');
    }

    /**
     * Supprimer un colis
     */
    public function ecomProductDestroy($id)
    {
        $colis = Colis::findOrFail($id);

        // Vérifier si le colis a déjà été récupéré
        if ($colis->recupere_gare) {
            return redirect()->back()
                           ->with('error', 'Impossible de supprimer ce colis car il a déjà été récupéré le ' . $colis->recupere_le?->format('d/m/Y à H:i') . '.');
        }

        // Supprimer la photo si elle existe
        if ($colis->photo_courrier && file_exists(public_path('uploads/colis/' . $colis->photo_courrier))) {
            unlink(public_path('uploads/colis/' . $colis->photo_courrier));
        }

        $colis->delete();

        return redirect()->route('application.ecom-product-list')
                         ->with('success', 'Colis supprimé avec succès!');
    }

    /**
     * Liste des clients
     */
    public function custCustomerList()
    {
        // Données des clients (à remplacer par de vraies données)
        $clients = [
            [
                'id' => 1,
                'nom' => 'Jean Dupont',
                'email' => 'jean.dupont@email.com',
                'telephone' => '+33 1 23 45 67 89',
                'ville' => 'Paris',
                'colis_envoyes' => 127,
                'depenses_totales' => 3456.78,
                'statut' => 'vip',
                'derniere_activite' => 'Il y a 2 heures'
            ],
            // ... autres clients
        ];

        return view('application.cust-customer-list', compact('clients'));
    }

    /**
     * Chat support client
     */
    public function chat()
    {
        // Données des conversations (à remplacer par de vraies données)
        $conversations = [
            [
                'id' => 1,
                'client_nom' => 'Jean Dupont',
                'client_avatar' => 'assets/images/user/avatar-1.jpg',
                'dernier_message' => 'Bonjour, où en est mon colis COL-2024-001 ?',
                'heure' => '10:30',
                'non_lus' => 2,
                'en_ligne' => true
            ],
            // ... autres conversations
        ];

        return view('application.chat', compact('conversations'));
    }

    /**
     * Calendrier
     */
    public function calendar()
    {
        // Données des événements calendrier
        $events = [
            [
                'title' => 'Livraison COL-2024-001',
                'start' => '2024-01-20',
                'backgroundColor' => '#4099ff'
            ],
            // ... autres événements
        ];

        return view('application.calendar', compact('events'));
    }

    /**
     * Profil utilisateur
     */
    public function userProfile()
    {
        // Récupérer l'utilisateur authentifié
        $user = auth()->user();
        return view('application.user-profile', compact('user'));
    }

    /**
     * Modifier profil utilisateur
     */
    public function userProfileEdit()
    {
        // Récupérer l'utilisateur authentifié
        $user = auth()->user();
        return view('application.user-profile-edit', compact('user'));
    }

    /**
     * Mettre à jour profil utilisateur
     */
    public function userProfileUpdate(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Vérifier le mot de passe actuel si un nouveau mot de passe est fourni
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
        }

        // Mettre à jour les informations
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('application.user-profile')->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Liste des utilisateurs
     */
    public function userList()
    {
        // Données des utilisateurs
        $users = [
            [
                'id' => 1,
                'nom' => 'Administrateur',
                'email' => 'admin@gestion-colis.com',
                'role' => 'Admin',
                'statut' => 'actif',
                'derniere_connexion' => 'En ligne'
            ],
            // ... autres utilisateurs
        ];

        return view('application.user-list', compact('users'));
    }

    /**
     * Checkout/Traitement
     */
    public function ecomCheckout()
    {
        return view('application.ecom-checkout');
    }
}
