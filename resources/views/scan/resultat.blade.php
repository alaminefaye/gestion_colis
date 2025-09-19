@extends('layouts.app')

@section('title', 'Résultat du Scan')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Résultat du Scan</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('scan.index') }}">Scan QR</a></li>
          <li class="breadcrumb-item" aria-current="page">Résultat</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row justify-content-center">
  <div class="col-md-10">
    <!-- Informations du livreur -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avtar avtar-l bg-light-primary me-3">
            <i class="ti ti-user f-24"></i>
          </div>
          <div>
            <h5 class="mb-1">{{ $livreur->nom_complet }}</h5>
            <p class="text-muted mb-0">{{ $livreur->telephone }} | {{ $livreur->email ?? 'Email non renseigné' }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Détails du colis -->
    <div class="card mb-4">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h5>Détails du Colis</h5>
          <span class="badge bg-light-{{ $colis->statut_color }} fs-6">{{ $colis->statut_livraison_label }}</span>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Numéro de courrier</label>
              <h6>{{ $colis->numero_courrier }}</h6>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">QR Code</label>
              <h6><code>{{ $colis->qr_code }}</code></h6>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Destination</label>
              <h6>{{ $colis->destination }}</h6>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Type de colis</label>
              <h6>{{ ucfirst($colis->type_colis) }}</h6>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Expéditeur</label>
              <h6>{{ $colis->nom_expediteur }}</h6>
              <small class="text-muted">{{ $colis->telephone_expediteur }}</small>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Bénéficiaire</label>
              <h6>{{ $colis->nom_beneficiaire }}</h6>
              <small class="text-muted">{{ $colis->telephone_beneficiaire }}</small>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Montant</label>
              <h6>{{ number_format($colis->montant, 0, ',', ' ') }} FCFA</h6>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Agence de réception</label>
              <h6>{{ $colis->agence_reception }}</h6>
            </div>
          </div>
        </div>
        
        @if($colis->description)
        <div class="mb-3">
          <label class="form-label text-muted">Description</label>
          <p>{{ $colis->description }}</p>
        </div>
        @endif
      </div>
    </div>

    <!-- Historique de livraison -->
    @if($colis->statut_livraison !== 'en_attente')
    <div class="card mb-4">
      <div class="card-header">
        <h5>Historique de Livraison</h5>
      </div>
      <div class="card-body">
        <div class="timeline">
          @if($colis->ramasse_le)
          <div class="timeline-item">
            <div class="timeline-marker bg-warning"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Colis ramassé</h6>
              <p class="mb-1">Par {{ $colis->livreurRamassage->nom_complet ?? 'Inconnu' }}</p>
              <small class="text-muted">{{ $colis->ramasse_le->format('d/m/Y à H:i') }}</small>
              @if($colis->notes_ramassage)
                <div class="mt-2">
                  <small><strong>Note :</strong> {{ $colis->notes_ramassage }}</small>
                </div>
              @endif
            </div>
          </div>
          @endif
          
          @if($colis->livre_le)
          <div class="timeline-item">
            <div class="timeline-marker bg-success"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Colis livré</h6>
              <p class="mb-1">Par {{ $colis->livreurLivraison->nom_complet ?? 'Inconnu' }}</p>
              <small class="text-muted">{{ $colis->livre_le->format('d/m/Y à H:i') }}</small>
              @if($colis->notes_livraison)
                <div class="mt-2">
                  <small><strong>Note :</strong> {{ $colis->notes_livraison }}</small>
                </div>
              @endif
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
    @endif

    <!-- Actions disponibles -->
    <div class="card">
      <div class="card-header">
        <h5>Actions Disponibles</h5>
      </div>
      <div class="card-body">
        @if($colis->statut_livraison === 'en_attente')
          <!-- Ramasser le colis -->
          <form action="{{ route('scan.ramasser') }}" method="POST" class="mb-3">
            @csrf
            <input type="hidden" name="colis_id" value="{{ $colis->id }}">
            <input type="hidden" name="livreur_id" value="{{ $livreur->id }}">
            
            <div class="mb-3">
              <label class="form-label">Notes de ramassage (optionnel)</label>
              <textarea class="form-control" name="notes_ramassage" rows="2" 
                        placeholder="Ex: Colis en bon état, emballage intact..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-warning btn-lg">
              <i class="ti ti-package me-2"></i>Ramasser ce Colis
            </button>
          </form>
          
        @elseif(in_array($colis->statut_livraison, ['ramasse', 'en_transit']))
          <!-- Mettre en transit -->
          @if($colis->statut_livraison === 'ramasse')
          <form action="{{ route('scan.en-transit') }}" method="POST" class="mb-3">
            @csrf
            <input type="hidden" name="colis_id" value="{{ $colis->id }}">
            <input type="hidden" name="livreur_id" value="{{ $livreur->id }}">
            
            <button type="submit" class="btn btn-info me-2">
              <i class="ti ti-truck me-2"></i>Marquer En Transit
            </button>
          </form>
          @endif
          
          <!-- Livrer le colis -->
          <form action="{{ route('scan.livrer') }}" method="POST">
            @csrf
            <input type="hidden" name="colis_id" value="{{ $colis->id }}">
            <input type="hidden" name="livreur_id" value="{{ $livreur->id }}">
            
            <div class="mb-3">
              <label class="form-label">Notes de livraison (optionnel)</label>
              <textarea class="form-control" name="notes_livraison" rows="2" 
                        placeholder="Ex: Livré au bénéficiaire en main propre, signature obtenue..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-success btn-lg">
              <i class="ti ti-check me-2"></i>Livrer ce Colis
            </button>
          </form>
          
        @else
          <!-- Colis déjà livré -->
          <div class="alert alert-success">
            <i class="ti ti-check-circle me-2"></i>
            <strong>Colis déjà livré</strong><br>
            Ce colis a été livré le {{ $colis->livre_le->format('d/m/Y à H:i') }} 
            par {{ $colis->livreurLivraison->nom_complet ?? 'Inconnu' }}.
          </div>
        @endif
        
        <!-- Nouvelle recherche -->
        <div class="mt-4 pt-3 border-top">
          <a href="{{ route('scan.index') }}" class="btn btn-outline-primary">
            <i class="ti ti-qrcode me-2"></i>Scanner un Autre Colis
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
  position: relative;
  padding-left: 30px;
}

.timeline-item {
  position: relative;
  margin-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
  content: '';
  position: absolute;
  left: -22px;
  top: 25px;
  height: calc(100% + 10px);
  width: 2px;
  background-color: #e9ecef;
}

.timeline-marker {
  position: absolute;
  left: -28px;
  top: 5px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
}

.timeline-content {
  padding-left: 15px;
}
</style>
@endpush
