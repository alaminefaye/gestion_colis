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

// Redirection vers le dashboard
Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

// Dashboard Routes
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
});

// Application Routes
Route::prefix('application')->name('application.')->group(function () {
    // Gestion des colis - CRUD complet
    Route::get('/colis', [ApplicationController::class, 'ecomProductList'])->name('ecom-product-list');
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

// Pages Routes
Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('/login', [PagesController::class, 'login'])->name('login');
    Route::get('/register', [PagesController::class, 'register'])->name('register');
});

// Other Routes
Route::prefix('other')->name('other.')->group(function () {
    Route::get('/sample', [PagesController::class, 'samplePage'])->name('sample-page');
});
