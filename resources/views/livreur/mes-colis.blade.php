@extends('layouts.app')

@section('title', 'Mes Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Mes Colis</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('livreur.dashboard') }}">Mon Tableau de Bord</a></li>
          <li class="breadcrumb-item" aria-current="page">Mes Colis</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    
    <!-- Info Livreur -->
    <div class="card mb-4 bg-gradient-primary text-white">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avtar avtar-xl bg-white bg-opacity-20 me-4">
            <span class="f-36 text-white">{{ strtoupper(substr($livreur->prenom, 0, 1)) }}{{ strtoupper(substr($livreur->nom, 0, 1)) }}</span>
          </div>
          <div>
            <h4 class="text-white mb-1">{{ $livreur->nom_complet }}</h4>
            <p class="text-white-50 mb-0">Mes colis personnels uniquement</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card bg-light-warning">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avtar avtar-s bg-warning me-3">
                <i class="ti ti-package f-18"></i>
              </div>
              <div>
                <h6 class="mb-0">J'ai Ramassé</h6>
                <p class="mb-0">{{ $stats['total_ramasse'] }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-light-success">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avtar avtar-s bg-success me-3">
                <i class="ti ti-check f-18"></i>
              </div>
              <div>
                <h6 class="mb-0">J'ai Livré</h6>
                <p class="mb-0">{{ $stats['total_livre'] }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-light-info">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avtar avtar-s bg-info me-3">
                <i class="ti ti-clock f-18"></i>
              </div>
              <div>
                <h6 class="mb-0">En Cours</h6>
                <p class="mb-0">{{ $stats['en_cours'] }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-light-primary">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avtar avtar-s bg-primary me-3">
                <i class="ti ti-truck f-18"></i>
              </div>
              <div>
                <h6 class="mb-0">En Transit</h6>
                <p class="mb-0">{{ $stats['en_transit'] }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
      <div class="card-body">
        <form method="GET" id="filterForm">
          <div class="row align-items-end">
            <div class="col-md-4">
              <label class="form-label">Statut</label>
              <select class="form-select" name="statut" onchange="document.getElementById('filterForm').submit()">
                <option value="">Tous les statuts</option>
                <option value="ramasse" {{ request('statut') == 'ramasse' ? 'selected' : '' }}>Ramassé par moi</option>
                <option value="en_transit" {{ request('statut') == 'en_transit' ? 'selected' : '' }}>En Transit</option>
                <option value="livre" {{ request('statut') == 'livre' ? 'selected' : '' }}>Livré par moi</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Date du</label>
              <input type="date" class="form-control" name="date_du" value="{{ request('date_du') }}" onchange="document.getElementById('filterForm').submit()">
            </div>
            <div class="col-md-3">
              <label class="form-label">Date au</label>
              <input type="date" class="form-control" name="date_au" value="{{ request('date_au') }}" onchange="document.getElementById('filterForm').submit()">
            </div>
            <div class="col-md-2">
              <a href="{{ route('livreur.mes-colis') }}" class="btn btn-outline-secondary w-100">
                <i class="ti ti-refresh"></i> Reset
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Liste des colis -->
    <div class="card">
      <div class="card-header">
        <h5>Mes Colis ({{ $colis->total() }})</h5>
      </div>
      <div class="card-body">
        @if($colis->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>N° Courrier</th>
                  <th>Expéditeur</th>
                  <th>Bénéficiaire</th>
                  <th>Destination</th>
                  <th>Statut</th>
                  <th>Mon Action</th>
                  <th>Date Action</th>
                  <th>QR Code</th>
                </tr>
              </thead>
              <tbody>
                @foreach($colis as $col)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avtar avtar-s bg-light-primary me-2">
                        <i class="ti ti-package f-16"></i>
                      </div>
                      <strong>{{ $col->numero_courrier }}</strong>
                    </div>
                  </td>
                  <td>
                    <div>
                      <h6 class="mb-0">{{ $col->nom_expediteur }}</h6>
                      <small class="text-muted">{{ $col->telephone_expediteur }}</small>
                    </div>
                  </td>
                  <td>
                    <div>
                      <h6 class="mb-0">{{ $col->nom_beneficiaire }}</h6>
                      <small class="text-muted">{{ $col->telephone_beneficiaire }}</small>
                    </div>
                  </td>
                  <td>{{ $col->destination }}</td>
                  <td>
                    <span class="badge bg-light-{{ $col->statut_color }}">
                      {{ $col->statut_livraison_label }}
                    </span>
                  </td>
                  <td>
                    @if($col->ramasse_par == $livreur->id && $col->livre_par == $livreur->id)
                      <span class="badge bg-purple">
                        <i class="ti ti-star me-1"></i>Ramassé & Livré
                      </span>
                    @elseif($col->ramasse_par == $livreur->id)
                      <span class="badge bg-warning">
                        <i class="ti ti-package me-1"></i>Ramassé
                      </span>
                    @elseif($col->livre_par == $livreur->id)
                      <span class="badge bg-success">
                        <i class="ti ti-check me-1"></i>Livré
                      </span>
                    @endif
                  </td>
                  <td>
                    @if($col->livre_par == $livreur->id && $col->livre_le)
                      <div>
                        <small><strong>Livré le :</strong></small><br>
                        <small>{{ $col->livre_le->format('d/m/Y') }}</small><br>
                        <small class="text-muted">{{ $col->livre_le->format('H:i') }}</small>
                      </div>
                    @elseif($col->ramasse_par == $livreur->id && $col->ramasse_le)
                      <div>
                        <small><strong>Ramassé le :</strong></small><br>
                        <small>{{ $col->ramasse_le->format('d/m/Y') }}</small><br>
                        <small class="text-muted">{{ $col->ramasse_le->format('H:i') }}</small>
                      </div>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="d-flex flex-column align-items-center">
                      <div class="qr-code-container mb-2" style="width: 60px; height: 60px;">
                        {!! $col->generateQrCode(60) !!}
                      </div>
                      <code class="small">{{ $col->qr_code }}</code>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div class="d-flex justify-content-center">
            {{ $colis->withQueryString()->links() }}
          </div>
          
        @else
          <div class="text-center py-5">
            <i class="ti ti-package f-48 text-muted mb-3"></i>
            <h5 class="text-muted">Aucun colis trouvé</h5>
            <p class="text-muted">Vous n'avez encore ramassé ou livré aucun colis.</p>
            <a href="{{ route('scan.index') }}" class="btn btn-primary">
              <i class="ti ti-qrcode me-2"></i>Commencer à scanner
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
