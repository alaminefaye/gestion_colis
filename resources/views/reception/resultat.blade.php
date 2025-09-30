@extends('layouts.app')

@section('title', 'Résultat du Scan - Réception')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">
            @if(isset($colis) && $colis)
              📦 Détails du Colis à Réceptionner
            @else
              ➕ Nouveau Colis à Enregistrer
            @endif
          </h5>
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

    @if(isset($colis) && $colis)
      <!-- COLIS EXISTANT - Affichage des informations -->
      @include('reception.partials.colis-existant', ['colis' => $colis])
    @else
      <!-- NOUVEAU COLIS - Formulaire d'enregistrement -->
      @include('reception.partials.nouveau-colis', ['code_scanne' => $code_scanne])
    @endif

  </div>
</div></div>
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
