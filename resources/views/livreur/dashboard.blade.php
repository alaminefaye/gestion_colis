@extends('layouts.app')

@section('title', 'Tableau de Bord Livreur')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Tableau de Bord Livreur</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item" aria-current="page">Mon Tableau de Bord</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <!-- Profil Livreur -->
  <div class="col-md-12 mb-4">
    <div class="card bg-gradient-primary text-white">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avtar avtar-xl bg-white bg-opacity-20 me-4">
            <span class="f-36 text-white">{{ strtoupper(substr($livreur->prenom, 0, 1)) }}{{ strtoupper(substr($livreur->nom, 0, 1)) }}</span>
          </div>
          <div>
            <h4 class="text-white mb-1">{{ $livreur->nom_complet }}</h4>
            <p class="text-white-50 mb-1">{{ $livreur->telephone }} | {{ $livreur->email }}</p>
            <p class="text-white-50 mb-0">
              <i class="ti ti-calendar me-1"></i>Embauché depuis {{ $livreur->date_embauche->diffForHumans() }}
            </p>
          </div>
          <div class="ms-auto text-end">
            <span class="badge bg-success fs-6">{{ $livreur->statut }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Statistiques -->
  <div class="col-md-3 mb-4">
    <div class="card bg-warning text-white">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avtar avtar-s bg-white bg-opacity-20 me-3">
            <i class="ti ti-package text-warning"></i>
          </div>
          <div>
            <h6 class="text-white mb-0">Colis Ramassés</h6>
            <h4 class="text-white mb-0">{{ $stats['total_ramasse'] }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-3 mb-4">
    <div class="card bg-success text-white">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avtar avtar-s bg-white bg-opacity-20 me-3">
            <i class="ti ti-check text-success"></i>
          </div>
          <div>
            <h6 class="text-white mb-0">Colis Livrés</h6>
            <h4 class="text-white mb-0">{{ $stats['total_livre'] }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-3 mb-4">
    <div class="card bg-info text-white">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avtar avtar-s bg-white bg-opacity-20 me-3">
            <i class="ti ti-clock text-info"></i>
          </div>
          <div>
            <h6 class="text-white mb-0">En Cours</h6>
            <h4 class="text-white mb-0">{{ $stats['en_cours'] }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-3 mb-4">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avtar avtar-s bg-white bg-opacity-20 me-3">
            <i class="ti ti-truck text-primary"></i>
          </div>
          <div>
            <h6 class="text-white mb-0">En Transit</h6>
            <h4 class="text-white mb-0">{{ $stats['en_transit'] }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Actions rapides -->
  <div class="col-md-12 mb-4">
    <div class="card">
      <div class="card-header">
        <h5>Actions Rapides</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 mb-3">
            <a href="{{ route('scan.index') }}" class="btn btn-primary w-100 btn-lg">
              <i class="ti ti-qrcode me-2"></i>Scanner QR Code
            </a>
          </div>
          @can('view_mes_colis')
          <div class="col-md-4 mb-3">
            <a href="{{ route('livreur.mes-colis') }}" class="btn btn-info w-100 btn-lg">
              <i class="ti ti-user me-2"></i>Mes Colis
            </a>
          </div>
          @endcan
          <div class="col-md-4 mb-3">
            <a href="{{ route('livreurs.colis.recuperes') }}" class="btn btn-outline-secondary w-100 btn-lg">
              <i class="ti ti-list me-2"></i>Voir Tous les Colis
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mes Colis En Cours -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5>Mes Colis En Cours ({{ $colisRamasses->count() }})</h5>
      </div>
      <div class="card-body">
        @if($colisRamasses->count() > 0)
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>N° Courrier</th>
                  <th>Bénéficiaire</th>
                  <th>Statut</th>
                  <th>Ramassé le</th>
                </tr>
              </thead>
              <tbody>
                @foreach($colisRamasses as $colis)
                <tr>
                  <td><strong>{{ $colis->numero_courrier }}</strong></td>
                  <td>
                    <div>
                      <small class="d-block">{{ $colis->nom_beneficiaire }}</small>
                      <small class="text-muted">{{ $colis->telephone_beneficiaire }}</small>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-light-{{ $colis->statut_color }}">{{ $colis->statut_livraison_label }}</span>
                  </td>
                  <td>
                    <small>{{ $colis->ramasse_le?->format('d/m/Y') }}</small><br>
                    <small class="text-muted">{{ $colis->ramasse_le?->format('H:i') }}</small>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-4">
            <i class="ti ti-package f-48 text-muted mb-3"></i>
            <h6 class="text-muted">Aucun colis en cours</h6>
            <p class="text-muted">Scannez des colis pour commencer vos livraisons</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Mes Dernières Livraisons -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5>Mes Dernières Livraisons ({{ $colisLivres->count() }})</h5>
      </div>
      <div class="card-body">
        @if($colisLivres->count() > 0)
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>N° Courrier</th>
                  <th>Bénéficiaire</th>
                  <th>Livré le</th>
                </tr>
              </thead>
              <tbody>
                @foreach($colisLivres as $colis)
                <tr>
                  <td><strong>{{ $colis->numero_courrier }}</strong></td>
                  <td>
                    <div>
                      <small class="d-block">{{ $colis->nom_beneficiaire }}</small>
                      <small class="text-muted">{{ $colis->telephone_beneficiaire }}</small>
                    </div>
                  </td>
                  <td>
                    <small>{{ $colis->livre_le?->format('d/m/Y') }}</small><br>
                    <small class="text-muted">{{ $colis->livre_le?->format('H:i') }}</small>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-4">
            <i class="ti ti-check-circle f-48 text-muted mb-3"></i>
            <h6 class="text-muted">Aucune livraison récente</h6>
            <p class="text-muted">Vos livraisons terminées apparaîtront ici</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
