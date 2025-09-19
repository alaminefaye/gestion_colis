@extends('layouts.app')

@section('title', 'Détails du Livreur')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Détails du Livreur</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('livreurs.index') }}">Livreurs</a></li>
          <li class="breadcrumb-item" aria-current="page">{{ $livreur->nom_complet }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-md-8">
    <!-- Informations personnelles -->
    <div class="card">
      <div class="card-header">
        <h5>Informations Personnelles</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Nom complet</label>
              <h6>{{ $livreur->nom_complet }}</h6>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Téléphone</label>
              <h6>{{ $livreur->telephone }}</h6>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Email</label>
              <h6>{{ $livreur->email ?? 'Non renseigné' }}</h6>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">CIN</label>
              <h6>{{ $livreur->cin }}</h6>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Date d'embauche</label>
              <h6>{{ $livreur->date_embauche->format('d/m/Y') }}</h6>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Statut</label>
              @if($livreur->actif)
                <h6><span class="badge bg-light-success">Actif</span></h6>
              @else
                <h6><span class="badge bg-light-danger">Inactif</span></h6>
              @endif
            </div>
          </div>
        </div>

        @if($livreur->adresse)
        <div class="mb-3">
          <label class="form-label text-muted">Adresse</label>
          <h6>{{ $livreur->adresse }}</h6>
        </div>
        @endif
      </div>
    </div>

    <!-- Colis récents -->
    <div class="card">
      <div class="card-header">
        <h5>Colis Récents</h5>
      </div>
      <div class="card-body">
        @if($livreur->colisRamasses->count() > 0 || $livreur->colisLivres->count() > 0)
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Numéro</th>
                  <th>Action</th>
                  <th>Date</th>
                  <th>Statut</th>
                </tr>
              </thead>
              <tbody>
                @foreach($livreur->colisRamasses->take(5) as $colis)
                <tr>
                  <td>{{ $colis->numero_courrier }}</td>
                  <td><span class="badge bg-light-warning">Ramassé</span></td>
                  <td>{{ $colis->ramasse_le?->format('d/m/Y H:i') }}</td>
                  <td><span class="badge bg-light-{{ $colis->statut_color }}">{{ $colis->statut_livraison_label }}</span></td>
                </tr>
                @endforeach
                @foreach($livreur->colisLivres->take(5) as $colis)
                <tr>
                  <td>{{ $colis->numero_courrier }}</td>
                  <td><span class="badge bg-light-success">Livré</span></td>
                  <td>{{ $colis->livre_le?->format('d/m/Y H:i') }}</td>
                  <td><span class="badge bg-light-success">Livré</span></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-3">
            <i class="ti ti-package f-24 text-muted mb-2"></i>
            <p class="text-muted mb-0">Aucun colis traité</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <!-- Actions -->
    <div class="card">
      <div class="card-header">
        <h5>Actions</h5>
      </div>
      <div class="card-body">
        @can('edit_livreurs')
        <a href="{{ route('livreurs.edit', $livreur) }}" class="btn btn-primary w-100 mb-2">
          <i class="ti ti-edit me-2"></i>Modifier
        </a>
        @endcan
        
        <a href="{{ route('livreurs.index') }}" class="btn btn-outline-secondary w-100 mb-2">
          <i class="ti ti-arrow-left me-2"></i>Retour à la liste
        </a>
        
        @can('delete_livreurs')
        @if($stats['en_cours'] == 0)
        <button type="button" class="btn btn-outline-danger w-100" onclick="deleteLivreur({{ $livreur->id }})">
          <i class="ti ti-trash me-2"></i>Supprimer
        </button>
        @endif
        @endcan
      </div>
    </div>

    <!-- Statistiques -->
    <div class="card">
      <div class="card-header">
        <h5>Statistiques</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted">Colis ramassés</span>
            <span class="badge bg-light-warning">{{ $stats['total_ramasse'] }}</span>
          </div>
        </div>
        
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted">Colis livrés</span>
            <span class="badge bg-light-success">{{ $stats['total_livre'] }}</span>
          </div>
        </div>
        
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted">En cours</span>
            <span class="badge bg-light-info">{{ $stats['en_cours'] }}</span>
          </div>
        </div>
        
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted">Ancienneté</span>
            <span class="badge bg-light-secondary">{{ $livreur->date_embauche->diffForHumans() }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Photo/Avatar -->
    <div class="card">
      <div class="card-header">
        <h5>Profil</h5>
      </div>
      <div class="card-body text-center">
        <div class="avtar avtar-xl bg-light-primary mx-auto mb-3">
          <span class="f-36">{{ strtoupper(substr($livreur->prenom, 0, 1)) }}{{ strtoupper(substr($livreur->nom, 0, 1)) }}</span>
        </div>
        <h6>{{ $livreur->nom_complet }}</h6>
        <p class="text-muted mb-0">{{ $livreur->telephone }}</p>
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Formulaire caché pour suppression -->
<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

@push('scripts')
<script>
function deleteLivreur(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce livreur ?')) {
    const form = document.getElementById('deleteForm');
    form.action = '/livreurs/' + id;
    form.submit();
  }
}
</script>
@endpush
