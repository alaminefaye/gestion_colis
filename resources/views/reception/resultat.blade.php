@extends('layouts.app')

@section('title', 'Résultat du Scan - Réception')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">📦 Détails du Colis à Réceptionner</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.ecom-product-list') }}">Gestion des Colis</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.scan-colis') }}">Scan Colis</a></li>
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
    
    <!-- Messages d'erreur -->
    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        @foreach($errors->all() as $error)
          <div><i class="ti ti-x me-2"></i>{!! $error !!}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- Actions rapides -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('application.scan-colis') }}" class="btn btn-outline-primary">
            <i class="ti ti-arrow-left me-2"></i>Nouveau Scan
          </a>
          <a href="{{ route('application.reception.colis-receptionnes') }}" class="btn btn-outline-info">
            <i class="ti ti-list me-2"></i>Colis Réceptionnés
          </a>
          <a href="{{ route('application.ecom-product-list') }}" class="btn btn-outline-secondary">
            <i class="ti ti-package me-2"></i>Tous les Colis
          </a>
        </div>
      </div>
    </div>

    <!-- Informations du colis -->
    <div class="row">
      <!-- Informations principales -->
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="mb-0">📋 Informations du Colis</h5>
              <span class="badge bg-{{ $colis->statut_color }} fs-6">
                {{ $colis->statut_livraison_label }}
              </span>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <td class="fw-bold text-muted">N° Courrier:</td>
                    <td>
                      <span class="badge bg-dark fs-6">{{ $colis->numero_courrier }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-bold text-muted">QR Code:</td>
                    <td>
                      <code>{{ $colis->qr_code }}</code>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-bold text-muted">Destination:</td>
                    <td>
                      <span class="badge bg-info">{{ $colis->destination }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-bold text-muted">Agence:</td>
                    <td>{{ $colis->agence_reception ?? 'Non spécifiée' }}</td>
                  </tr>
                  <tr>
                    <td class="fw-bold text-muted">Type:</td>
                    <td>
                      <span class="badge bg-secondary">{{ ucfirst($colis->type_colis ?? 'Standard') }}</span>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <td class="fw-bold text-muted">Montant:</td>
                    <td>
                      <span class="text-success fw-bold">{{ number_format($colis->montant, 0, ',', ' ') }} FCFA</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="fw-bold text-muted">Valeur:</td>
                    <td>{{ number_format($colis->valeur_colis, 0, ',', ' ') }} FCFA</td>
                  </tr>
                  <tr>
                    <td class="fw-bold text-muted">Date d'envoi:</td>
                    <td>{{ $colis->created_at->format('d/m/Y à H:i') }}</td>
                  </tr>
                  @if($colis->livre_le)
                  <tr>
                    <td class="fw-bold text-muted">Livré le:</td>
                    <td>{{ $colis->livre_le->format('d/m/Y à H:i') }}</td>
                  </tr>
                  @endif
                  @if($colis->livreurLivraison)
                  <tr>
                    <td class="fw-bold text-muted">Livré par:</td>
                    <td>{{ $colis->livreurLivraison->nom_complet }}</td>
                  </tr>
                  @endif
                </table>
              </div>
            </div>

            @if($colis->description)
            <div class="row mt-3">
              <div class="col-12">
                <div class="alert alert-light">
                  <strong>Description:</strong><br>
                  {{ $colis->description }}
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="col-md-4">
        <!-- Statut et action -->
        <div class="card mb-3">
          <div class="card-header">
            <h6 class="mb-0">🎯 Action de Réception</h6>
          </div>
          <div class="card-body">
            @if(in_array($colis->statut_livraison, ['en_attente', 'ramasse', 'en_transit', 'livre']))
              <div class="alert alert-success mb-3">
                <i class="ti ti-check-circle me-2"></i>
                <strong>Colis prêt à être réceptionné</strong>
              </div>

              <!-- Formulaire de réception -->
              <form action="{{ route('application.colis.receptionner') }}" method="POST" onsubmit="return confirm('Confirmez-vous la réception de ce colis ?')">
                @csrf
                <input type="hidden" name="colis_id" value="{{ $colis->id }}">
                
                <div class="form-group mb-3">
                  <label for="notes_reception" class="form-label">Notes (optionnel)</label>
                  <textarea name="notes_reception" id="notes_reception" class="form-control" 
                            rows="3" placeholder="Ajouter des notes sur la réception...">{{ old('notes_reception') }}</textarea>
                </div>

                <button type="submit" class="btn btn-success w-100 btn-lg">
                  <i class="ti ti-check me-2"></i>Réceptionner le Colis
                </button>
              </form>

            @elseif($colis->statut_livraison === 'receptionne')
              <div class="alert alert-primary">
                <i class="ti ti-info-circle me-2"></i>
                <strong>Colis déjà réceptionné</strong>
                @if($colis->receptionne_le)
                  <br><small>Le {{ $colis->receptionne_le->format('d/m/Y à H:i') }}</small>
                @endif
                @if($colis->receptionneParUser)
                  <br><small>Par {{ $colis->receptionneParUser->name }}</small>
                @endif
              </div>

            @else
              <div class="alert alert-warning">
                <i class="ti ti-alert-triangle me-2"></i>
                <strong>Colis non eligible</strong>
                <br>Statut actuel : <span class="badge bg-{{ $colis->statut_color }}">{{ $colis->statut_livraison_label }}</span>
                <br><small>Seuls les colis déjà réceptionnés ne peuvent être re-réceptionnés.</small>
              </div>
            @endif
          </div>
        </div>

        <!-- Informations de livraison -->
        @if($colis->statut_livraison === 'livre' || $colis->statut_livraison === 'receptionne')
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">🚚 Informations de Livraison</h6>
          </div>
          <div class="card-body">
            @if($colis->livreurLivraison)
              <p class="mb-2">
                <strong>Livreur:</strong><br>
                {{ $colis->livreurLivraison->nom_complet }}
              </p>
            @endif
            
            @if($colis->livre_le)
              <p class="mb-2">
                <strong>Date de livraison:</strong><br>
                {{ $colis->livre_le->format('d/m/Y à H:i') }}
              </p>
            @endif
            
            @if($colis->notes_livraison)
              <p class="mb-0">
                <strong>Notes de livraison:</strong><br>
                <small class="text-muted">{{ $colis->notes_livraison }}</small>
              </p>
            @endif

            @if($colis->statut_livraison === 'receptionne' && $colis->notes_reception)
              <hr>
              <p class="mb-0">
                <strong>Notes de réception:</strong><br>
                <small class="text-muted">{{ $colis->notes_reception }}</small>
              </p>
            @endif
          </div>
        </div>
        @endif
      </div>
    </div>

    <!-- Informations expéditeur/bénéficiaire -->
    <div class="row mt-4">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">📤 Expéditeur</h6>
          </div>
          <div class="card-body">
            <p class="mb-2"><strong>Nom:</strong> {{ $colis->nom_expediteur }}</p>
            <p class="mb-0"><strong>Téléphone:</strong> 
              <a href="tel:{{ $colis->telephone_expediteur }}" class="text-primary">
                {{ $colis->telephone_expediteur }}
              </a>
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">📥 Bénéficiaire</h6>
          </div>
          <div class="card-body">
            <p class="mb-2"><strong>Nom:</strong> {{ $colis->nom_beneficiaire }}</p>
            <p class="mb-0"><strong>Téléphone:</strong> 
              <a href="tel:{{ $colis->telephone_beneficiaire }}" class="text-primary">
                {{ $colis->telephone_beneficiaire }}
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('styles')
<style>
.table-borderless td {
  padding: 0.5rem 0.75rem;
  border: none;
}

.badge.fs-6 {
  font-size: 0.875rem !important;
}

.btn-lg {
  padding: 0.75rem 1.5rem;
  font-size: 1.1rem;
}

.card-header h6 {
  font-size: 1rem;
  font-weight: 600;
}

.alert {
  border-radius: 0.5rem;
}

.text-success.fw-bold {
  font-size: 1.1rem;
}

code {
  background-color: #f8f9fa;
  padding: 0.2rem 0.4rem;
  border-radius: 0.25rem;
  font-size: 0.9rem;
}

@media (max-width: 768px) {
  .btn-lg {
    padding: 0.6rem 1.2rem;
    font-size: 1rem;
  }
  
  .d-flex.flex-wrap .btn {
    margin-bottom: 0.5rem;
  }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Fonction pour copier le numéro de courrier
  window.copyToClipboard = function(text) {
    navigator.clipboard.writeText(text).then(function() {
      // Créer une notification
      const notification = document.createElement('div');
      notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
      notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
      notification.innerHTML = `
        <i class="ti ti-check me-2"></i>Numéro copié !
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      
      document.body.appendChild(notification);
      
      setTimeout(() => {
        if (notification.parentNode) {
          notification.remove();
        }
      }, 2000);
    }, function(err) {
      console.error('Erreur lors de la copie:', err);
    });
  }
});
</script>
@endpush
