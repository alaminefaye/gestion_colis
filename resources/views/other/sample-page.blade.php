@extends('layouts.app')

@section('title', 'Page d\'exemple - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Page d'exemple</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="javascript: void(0)">Autres</a></li>
          <li class="breadcrumb-item" aria-current="page">Page d'exemple</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h5>Bienvenue dans le système de gestion des colis</h5>
      </div>
      <div class="card-body">
        <p>Cette page d'exemple vous montre comment utiliser le template Mantis intégré dans votre application Laravel.</p>
        
        <h6>Fonctionnalités disponibles :</h6>
        <ul>
          <li><strong>Dashboard</strong> - Vue d'ensemble des statistiques</li>
          <li><strong>Gestion des colis</strong> - Ajout, suivi et gestion des colis</li>
          <li><strong>Gestion des clients</strong> - Base de données clients</li>
          <li><strong>Chat support</strong> - Communication avec les clients</li>
          <li><strong>Éléments UI</strong> - Composants d'interface utilisateur</li>
          <li><strong>Formulaires</strong> - Formulaires interactifs</li>
          <li><strong>Tableaux</strong> - Affichage et gestion des données</li>
          <li><strong>Graphiques</strong> - Visualisation des données</li>
        </ul>

        <div class="alert alert-info">
          <h6 class="alert-heading">Information</h6>
          <p class="mb-0">Cette intégration complète du template Mantis vous permet de créer rapidement une application web moderne pour la gestion des colis.</p>
        </div>

        <div class="row mt-4">
          <div class="col-md-6">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <h6 class="text-white">Design Moderne</h6>
                <p class="mb-0">Interface utilisateur propre et moderne avec Bootstrap 5</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card bg-success text-white">
              <div class="card-body">
                <h6 class="text-white">Responsive</h6>
                <p class="mb-0">Parfaitement adapté à tous les appareils</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
