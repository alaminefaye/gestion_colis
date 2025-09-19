<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ElementsController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\ScanQRController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Redirection vers le dashboard (avec protection)
Route::get('/', function () {
    return redirect()->route('dashboard.index');
})->middleware('auth');

// Dashboard Routes
Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
});

// Application Routes
Route::prefix('application')->name('application.')->middleware('auth')->group(function () {
    // Gestion des colis - CRUD complet
    Route::get('/colis', [ApplicationController::class, 'ecomProductList'])->name('ecom-product-list');
    Route::get('/colis/tous', [ApplicationController::class, 'ecomProductListAll'])->name('ecom-product-list-all');
    Route::get('/colis/nouveau', [ApplicationController::class, 'ecomProductAdd'])->name('ecom-product-add');
    Route::post('/colis', [ApplicationController::class, 'ecomProductStore'])->name('ecom-product-store');
    Route::get('/colis/{id}', [ApplicationController::class, 'ecomProductShow'])->name('ecom-product-show');
    Route::get('/colis/{id}/modifier', [ApplicationController::class, 'ecomProductEdit'])->name('ecom-product-edit');
    Route::put('/colis/{id}', [ApplicationController::class, 'ecomProductUpdate'])->name('ecom-product-update');
    Route::delete('/colis/{id}', [ApplicationController::class, 'ecomProductDestroy'])->name('ecom-product-destroy');
    Route::get('/checkout', [ApplicationController::class, 'ecomCheckout'])->name('ecom-checkout');
    
    // Gestion des clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/clients/api/telephone/{telephone}', [ClientController::class, 'getByTelephone'])->name('clients.api.telephone');
    
    // Récupération des colis à la gare
    Route::post('/colis/marquer-recupere', [GestionController::class, 'marquerRecupere'])->name('colis.marquer-recupere');
    
    // Communication
    Route::get('/chat', [ApplicationController::class, 'chat'])->name('chat');
    Route::get('/calendrier', [ApplicationController::class, 'calendar'])->name('calendar');
    
    // Utilisateurs
    Route::get('/utilisateurs', [ApplicationController::class, 'userList'])->name('user-list');
    Route::get('/profil', [ApplicationController::class, 'userProfile'])->name('user-profile');
    
    // Gestion - Destinations et Agences
    Route::prefix('gestion')->name('gestion.')->group(function () {
        Route::get('/', [GestionController::class, 'index'])->name('index');
        
        // Destinations
        Route::get('/destinations', [GestionController::class, 'destinationsIndex'])->name('destinations');
        Route::post('/destinations', [GestionController::class, 'destinationsStore'])->name('destinations.store');
        Route::put('/destinations/{id}', [GestionController::class, 'destinationsUpdate'])->name('destinations.update');
        Route::delete('/destinations/{id}', [GestionController::class, 'destinationsDestroy'])->name('destinations.destroy');
        
        // Agences
        Route::get('/agences', [GestionController::class, 'agencesIndex'])->name('agences');
        Route::post('/agences', [GestionController::class, 'agencesStore'])->name('agences.store');
        Route::put('/agences/{id}', [GestionController::class, 'agencesUpdate'])->name('agences.update');
        Route::delete('/agences/{id}', [GestionController::class, 'agencesDestroy'])->name('agences.destroy');
        
    });
});

// Livreurs Routes
Route::prefix('livreurs')->name('livreurs.')->middleware('auth')->group(function () {
    Route::get('/', [LivreurController::class, 'index'])->name('index')->middleware('can:view_livreurs');
    Route::get('/create', [LivreurController::class, 'create'])->name('create')->middleware('can:create_livreurs');
    Route::post('/', [LivreurController::class, 'store'])->name('store')->middleware('can:create_livreurs');
    Route::get('/{livreur}', [LivreurController::class, 'show'])->name('show')->middleware('can:view_livreurs');
    Route::get('/{livreur}/edit', [LivreurController::class, 'edit'])->name('edit')->middleware('can:edit_livreurs');
    Route::put('/{livreur}', [LivreurController::class, 'update'])->name('update')->middleware('can:edit_livreurs');
    Route::delete('/{livreur}', [LivreurController::class, 'destroy'])->name('destroy')->middleware('can:delete_livreurs');
    
    // Page des colis récupérés
    Route::get('/colis/recuperes', [LivreurController::class, 'colisRecuperes'])->name('colis.recuperes')->middleware('can:view_colis_recuperes');
});

// Tableau de bord livreur
Route::get('/livreur/dashboard', [LivreurController::class, 'dashboard'])->name('livreur.dashboard')->middleware('auth');
Route::get('/livreur/mes-colis', [LivreurController::class, 'mesColis'])->name('livreur.mes-colis')->middleware(['auth', 'can:view_mes_colis']);

// Scan QR Routes
Route::prefix('scan')->name('scan.')->middleware('auth')->group(function () {
    Route::get('/', [ScanQRController::class, 'index'])->name('index')->middleware('can:scan_qr_colis');
    Route::get('/rechercher', function() {
        return redirect()->route('scan.index')->with('info', 'Utilisez le formulaire pour rechercher un colis.');
    });
    Route::post('/rechercher', [ScanQRController::class, 'rechercher'])->name('rechercher')->middleware('can:scan_qr_colis');
    Route::post('/ramasser', [ScanQRController::class, 'ramasser'])->name('ramasser')->middleware('can:ramasser_colis');
    Route::post('/livrer', [ScanQRController::class, 'livrer'])->name('livrer')->middleware('can:livrer_colis');
    Route::post('/en-transit', [ScanQRController::class, 'enTransit'])->name('en-transit')->middleware('can:ramasser_colis');
});

// Admin Routes - Gestion des utilisateurs et rôles/permissions
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Gestion des utilisateurs
    Route::middleware('can:view_users')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create')->middleware('can:create_users');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store')->middleware('can:create_users');
        Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit')->middleware('can:edit_users');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update')->middleware('can:edit_users');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy')->middleware('can:delete_users');
    });
    
    // Gestion des rôles et permissions
    Route::middleware('can:view_roles')->group(function () {
        Route::get('/roles', [RolePermissionController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RolePermissionController::class, 'create'])->name('roles.create')->middleware('can:create_roles');
        Route::post('/roles', [RolePermissionController::class, 'store'])->name('roles.store')->middleware('can:create_roles');
        Route::get('/roles/{role}', [RolePermissionController::class, 'show'])->name('roles.show');
        Route::get('/roles/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit')->middleware('can:edit_roles');
        Route::put('/roles/{role}', [RolePermissionController::class, 'update'])->name('roles.update')->middleware('can:edit_roles');
        Route::delete('/roles/{role}', [RolePermissionController::class, 'destroy'])->name('roles.destroy')->middleware('can:delete_roles');
        
        // Gestion des permissions pour un utilisateur
        Route::post('/users/{user}/assign-role', [UserManagementController::class, 'assignRole'])->name('users.assign-role')->middleware('can:assign_permissions');
        Route::delete('/users/{user}/remove-role/{role}', [UserManagementController::class, 'removeRole'])->name('users.remove-role')->middleware('can:assign_permissions');
    });
});

// Elements Routes
Route::prefix('elements')->name('elements.')->group(function () {
    Route::get('/typography', [ElementsController::class, 'bcTypography'])->name('bc-typography');
    Route::get('/colors', [ElementsController::class, 'bcColor'])->name('bc-color');
    Route::get('/icons', [ElementsController::class, 'iconTabler'])->name('icon-tabler');
    Route::get('/buttons', [ElementsController::class, 'bcButton'])->name('bc-button');
    Route::get('/cards', [ElementsController::class, 'bcCard'])->name('bc-card');
    Route::get('/badges', [ElementsController::class, 'bcBadges'])->name('bc-badges');
    Route::get('/alerts', [ElementsController::class, 'bcAlert'])->name('bc-alert');
});

// Forms Routes
Route::prefix('forms')->name('forms.')->group(function () {
    Route::get('/elements', [FormsController::class, 'formElements'])->name('form-elements');
    Route::get('/validation', [FormsController::class, 'formValidation'])->name('form-validation');
    Route::get('/wizard', [FormsController::class, 'formWizard'])->name('form-wizard');
});

// Tables Routes
Route::prefix('tables')->name('table.')->group(function () {
    Route::get('/bootstrap', [TableController::class, 'tblBootstrap'])->name('tbl-bootstrap');
    Route::get('/datatable', [TableController::class, 'tblDtSimple'])->name('tbl-dt-simple');
});

// Charts Routes
Route::prefix('charts')->name('chart.')->group(function () {
    Route::get('/apex', [ChartController::class, 'chartApex'])->name('chart-apex');
    Route::get('/maps', [ChartController::class, 'mapVector'])->name('map-vector');
});

// Pages Routes (autres pages statiques)
Route::prefix('pages')->name('pages.')->group(function () {
    // Autres pages peuvent être ajoutées ici
});

// Other Routes
Route::prefix('other')->name('other.')->group(function () {
    Route::get('/sample', [PagesController::class, 'samplePage'])->name('sample-page');
});
