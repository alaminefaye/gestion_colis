@extends('layouts.app')

@section('title', 'Détails du Colis - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Détails du Colis</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.ecom-product-list') }}">Colis</a></li>
          <li class="breadcrumb-item" aria-current="page">{{ $colis->numero_courrier }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
          <h5>Informations du Colis</h5>
          <div>
            <a href="{{ route('application.ecom-product-edit', $colis->id) }}" class="btn btn-primary btn-sm">
              <i class="ti ti-edit"></i> Modifier
            </a>
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteColis({{ $colis->id }})">
              <i class="ti ti-trash"></i> Supprimer
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Numéro de courrier</label>
              <p class="h6">{{ $colis->numero_courrier }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Destination</label>
              <p class="h6">{{ $colis->destination }}</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Nom de l'expéditeur</label>
              <p class="h6">{{ $colis->nom_expediteur }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Téléphone de l'expéditeur</label>
              <p class="h6">{{ $colis->telephone_expediteur }}</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Nom du bénéficiaire</label>
              <p class="h6">{{ $colis->nom_beneficiaire }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Téléphone du bénéficiaire</label>
              <p class="h6">{{ $colis->telephone_beneficiaire }}</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label text-muted">Montant</label>
              <p class="h6 text-success">{{ number_format($colis->montant, 0, ',', ' ') }} FCFA</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label text-muted">Valeur du colis</label>
              <p class="h6 text-info">{{ number_format($colis->valeur_colis, 0, ',', ' ') }} FCFA</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label class="form-label text-muted">Type de colis</label>
              <p class="h6">
                <span class="badge bg-light-primary border border-primary">
                  {{ ucfirst(str_replace('_', ' ', $colis->type_colis)) }}
                </span>
              </p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Agence de réception</label>
              <p class="h6">{{ ucfirst(str_replace('_', ' ', $colis->agence_reception)) }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Date de création</label>
              <p class="h6">{{ $colis->created_at->format('d/m/Y à H:i') }}</p>
            </div>
          </div>
        </div>

        @if($colis->description)
        <div class="mb-3">
          <label class="form-label text-muted">Description</label>
          <p class="h6">{{ $colis->description }}</p>
        </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <!-- Photo du colis -->
    @if($colis->photo_courrier)
    <div class="card">
      <div class="card-header">
        <h5>Photo du Courrier</h5>
      </div>
      <div class="card-body text-center">
        <img src="{{ asset('uploads/colis/' . $colis->photo_courrier) }}" alt="Photo du colis" class="img-fluid rounded">
      </div>
    </div>
    @endif

    <!-- Informations supplémentaires -->
    <div class="card">
      <div class="card-header">
        <h5>Informations Supplémentaires</h5>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-primary">
              <i class="ti ti-calendar text-primary"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Date de création</h6>
            <p class="text-muted mb-0">{{ $colis->created_at->format('d/m/Y') }}</p>
          </div>
        </div>

        <div class="d-flex align-items-center mb-3">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-success">
              <i class="ti ti-clock text-success"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Heure de création</h6>
            <p class="text-muted mb-0">{{ $colis->created_at->format('H:i') }}</p>
          </div>
        </div>

        @if($colis->updated_at != $colis->created_at)
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-warning">
              <i class="ti ti-edit text-warning"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Dernière modification</h6>
            <p class="text-muted mb-0">{{ $colis->updated_at->format('d/m/Y à H:i') }}</p>
          </div>
        </div>
        @endif
      </div>
    </div>

    <!-- Actions rapides -->
    <div class="card">
      <div class="card-header">
        <h5>Actions Rapides</h5>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <a href="{{ route('application.ecom-product-edit', $colis->id) }}" class="btn btn-primary">
            <i class="ti ti-edit me-2"></i>Modifier ce colis
          </a>
          <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="ti ti-printer me-2"></i>Imprimer les détails
          </button>
          <a href="{{ route('application.ecom-product-list') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-2"></i>Retour à la liste
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Formulaire caché pour la suppression -->
<form id="deleteForm" action="{{ route('application.ecom-product-destroy', $colis->id) }}" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function deleteColis(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce colis ? Cette action est irréversible.')) {
    document.getElementById('deleteForm').submit();
  }
}
</script>
@endpush
