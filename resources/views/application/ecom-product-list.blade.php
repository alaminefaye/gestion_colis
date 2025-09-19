@extends('layouts.app')

@section('title', 'Liste des Colis - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Liste des Colis</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="javascript: void(0)">Gestion</a></li>
          <li class="breadcrumb-item" aria-current="page">Liste des Colis</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-sm-12">
    <!-- Messages de succès/erreur -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5>Tous les Colis</h5>
        <div>
          <a href="{{ route('application.ecom-product-add') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Nouveau Colis
          </a>
        </div>
      </div>
      <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
          <div class="col-md-3">
            <select class="form-select" id="statusFilter">
              <option value="">Tous les statuts</option>
              <option value="preparation">Préparation</option>
              <option value="transit">En Transit</option>
              <option value="livre">Livré</option>
              <option value="probleme">Problème</option>
            </select>
          </div>
          <div class="col-md-3">
            <input type="date" class="form-control" id="dateFilter" placeholder="Date">
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" id="searchFilter" placeholder="Rechercher par numéro, destinataire...">
          </div>
          <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" type="button">
              <i class="ti ti-filter"></i> Filtrer
            </button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-striped" id="colisTable">
            <thead>
              <tr>
                <th>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                  </div>
                </th>
                <th>N° COURRIER</th>
                <th>EXPÉDITEUR</th>
                <th>BÉNÉFICIAIRE</th>
                <th>DESTINATION</th>
                <th>TYPE</th>
                <th>MONTANT</th>
                <th>VALEUR COLIS</th>
                <th>DATE CRÉATION</th>
                <th class="text-end">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($colis as $item)
              <tr>
                <td>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{ $item->id }}">
                  </div>
                </td>
                <td>
                  <div class="d-inline-block align-middle">
                    <h6 class="m-b-0">{{ $item->numero_courrier }}</h6>
                    <p class="m-b-0 text-primary">{{ ucfirst(str_replace('_', ' ', $item->type_colis)) }}</p>
                  </div>
                </td>
                <td>
                  <div>
                    <h6 class="mb-1">{{ $item->nom_expediteur }}</h6>
                    <p class="text-muted mb-0">{{ $item->telephone_expediteur }}</p>
                  </div>
                </td>
                <td>
                  <div>
                    <h6 class="mb-1">{{ $item->nom_beneficiaire }}</h6>
                    <p class="text-muted mb-0">{{ $item->telephone_beneficiaire }}</p>
                  </div>
                </td>
                <td>
                  <p class="mb-0">{{ $item->destination }}</p>
                  <p class="text-muted mb-0">{{ $item->agence_reception }}</p>
                </td>
                <td>
                  <span class="badge bg-light-primary border border-primary">
                    {{ ucfirst(str_replace('_', ' ', $item->type_colis)) }}
                  </span>
                </td>
                <td>
                  <h6 class="text-success mb-1">{{ number_format($item->montant, 0, ',', ' ') }} FCFA</h6>
                </td>
                <td>
                  <h6 class="text-info mb-1">{{ number_format($item->valeur_colis, 0, ',', ' ') }} FCFA</h6>
                </td>
                <td>{{ $item->created_at->format('d/m/Y') }}<br><span class="text-muted">{{ $item->created_at->format('H:i') }}</span></td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <a href="{{ route('application.ecom-product-show', $item->id) }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Voir détails">
                      <i class="ti ti-eye"></i>
                    </a>
                    <a href="{{ route('application.ecom-product-edit', $item->id) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Modifier">
                      <i class="ti ti-edit"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Supprimer" onclick="deleteColis({{ $item->id }})">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="10" class="text-center py-4">
                  <div class="d-flex flex-column align-items-center">
                    <i class="ti ti-package f-48 text-muted mb-3"></i>
                    <h6 class="text-muted">Aucun colis trouvé</h6>
                    <p class="text-muted mb-0">Commencez par ajouter votre premier coli</p>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($colis->hasPages())
        <div class="d-flex align-items-center justify-content-between mt-3">
          <div>
            <p class="text-muted mb-0">
              Affichage de {{ $colis->firstItem() }} à {{ $colis->lastItem() }} sur {{ $colis->total() }} entrées
            </p>
          </div>
          <nav>
            {{ $colis->links() }}
          </nav>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
.img-prod {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
}
</style>
@endpush

<!-- Formulaire caché pour les suppressions -->
<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Select all checkbox functionality
  if(document.getElementById('selectAll')) {
    document.getElementById('selectAll').addEventListener('change', function() {
      const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
      checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
      });
    });
  }

  // Search functionality
  if(document.getElementById('searchFilter')) {
    document.getElementById('searchFilter').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const rows = document.querySelectorAll('#colisTable tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });
  }
});

// Fonction de suppression
function deleteColis(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce colis ? Cette action est irréversible.')) {
    const form = document.getElementById('deleteForm');
    form.action = '/application/colis/' + id;
    form.submit();
  }
}
</script>
@endpush
