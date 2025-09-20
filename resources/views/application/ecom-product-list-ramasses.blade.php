@extends('layouts.app')

@section('title', 'Colis Ramassés - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Colis Ramassés</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.ecom-product-list') }}">Gestion des Colis</a></li>
          <li class="breadcrumb-item" aria-current="page">Colis Ramassés</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
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
      <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h5>Colis Ramassés</h5>
            <small class="text-muted">Colis qui ont été ramassés par les livreurs</small>
          </div>
          <div>
            <a href="{{ route('application.ecom-product-list') }}" class="btn btn-outline-secondary me-2">
              <i class="ti ti-arrow-left me-2"></i>Colis En Cours
            </a>
            <a href="{{ route('application.ecom-product-list-all') }}" class="btn btn-outline-secondary me-2">
              <i class="ti ti-eye me-2"></i>Voir Tous
            </a>
            <a href="{{ route('application.ecom-product-add') }}" class="btn btn-primary">
              <i class="ti ti-plus me-2"></i>Nouveau Colis
            </a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <!-- Section de Recherche -->
        <div class="card border shadow-sm mb-4" style="background: #ffffff;">
          <div class="card-body p-4">
            <div class="row align-items-center mb-3">
              <div class="col">
                <h6 class="text-dark mb-0 fw-bold">🔍 Recherche Avancée</h6>
                <p class="text-muted mb-0 small">Filtrez vos colis ramassés</p>
              </div>
              <div class="col-auto">
                <button class="btn btn-outline-primary btn-sm" type="button" id="toggleFilters">
                  <i class="ti ti-filter me-1"></i>
                  <span id="filterToggleText">Masquer filtres</span>
                </button>
              </div>
            </div>
            
            <!-- Recherche rapide -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0">
                    <i class="ti ti-search text-muted"></i>
                  </span>
                  <input type="text" class="form-control border-start-0 ps-0" id="searchInput" 
                         placeholder="Rechercher par numéro, expéditeur, bénéficiaire, téléphone...">
                </div>
              </div>
            </div>

            <!-- Filtres avancés -->
            <div id="advancedFilters" class="row g-3">
              <div class="col-md-3">
                <label class="form-label text-muted small mb-1">Statut</label>
                <select class="form-select form-select-sm" id="filterStatut">
                  <option value="">Tous les statuts</option>
                  <option value="ramasse">Ramassé</option>
                  <option value="en_transit">En Transit</option>
                  <option value="livre">Livré</option>
                  <option value="probleme">Problème</option>
                </select>
              </div>
              
              <div class="col-md-3">
                <label class="form-label text-muted small mb-1">Livreur</label>
                <select class="form-select form-select-sm" id="filterLivreur">
                  <option value="">Tous les livreurs</option>
                  @foreach($colis->unique('livreurRamassage.nom')->filter(fn($c) => $c->livreurRamassage) as $item)
                    <option value="{{ $item->livreurRamassage->id }}">
                      {{ $item->livreurRamassage->prenom }} {{ $item->livreurRamassage->nom }}
                    </option>
                  @endforeach
                </select>
              </div>
              
              <div class="col-md-3">
                <label class="form-label text-muted small mb-1">Destination</label>
                <select class="form-select form-select-sm" id="filterDestination">
                  <option value="">Toutes destinations</option>
                  @foreach($colis->unique('destination')->pluck('destination')->sort() as $destination)
                    <option value="{{ $destination }}">{{ $destination }}</option>
                  @endforeach
                </select>
              </div>
              
              <div class="col-md-3">
                <label class="form-label text-muted small mb-1">Type</label>
                <select class="form-select form-select-sm" id="filterType">
                  <option value="">Tous les types</option>
                  <option value="document">Document</option>
                  <option value="standard">Standard</option>
                  <option value="fragile">Fragile</option>
                  <option value="express">Express</option>
                  <option value="urgent">Urgent</option>
                </select>
              </div>
              
              <div class="col-md-4">
                <label class="form-label text-muted small mb-1">Date de ramassage (du)</label>
                <input type="date" class="form-control form-control-sm" id="filterDateDu">
              </div>
              
              <div class="col-md-4">
                <label class="form-label text-muted small mb-1">Date de ramassage (au)</label>
                <input type="date" class="form-control form-control-sm" id="filterDateAu">
              </div>
              
              <div class="col-md-4">
                <div class="d-flex justify-content-end align-items-end h-100">
                  <button type="button" class="btn btn-outline-secondary btn-sm" id="resetFilters">
                    <i class="ti ti-refresh me-1"></i>Réinitialiser
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>

        @if($colis->count() > 0)
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="table-light">
              <tr>
                <th>N° COURRIER</th>
                <th>EXPÉDITEUR</th>
                <th>BÉNÉFICIAIRE</th>
                <th>DESTINATION</th>
                <th>MONTANT</th>
                <th>LIVREUR</th>
                <th>RAMASSÉ LE</th>
                <th>QR CODE</th>
                <th class="text-end">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @foreach($colis as $item)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <span class="badge bg-light-warning border border-warning text-warning fw-bold">
                      {{ $item->numero_courrier }}
                    </span>
                  </div>
                </td>
                <td>
                  <h6 class="mb-1">{{ $item->nom_expediteur }}</h6>
                  <small class="text-muted">{{ $item->telephone_expediteur }}</small>
                </td>
                <td>
                  <h6 class="mb-1">{{ $item->nom_beneficiaire }}</h6>
                  <small class="text-muted">{{ $item->telephone_beneficiaire }}</small>
                </td>
                <td>
                  <span class="badge bg-light-info border border-info">
                    {{ $item->destination }}
                  </span>
                </td>
                <td>
                  <h6 class="text-success mb-1">{{ number_format($item->montant, 0, ',', ' ') }} FCFA</h6>
                </td>
                <td>
                  @if($item->livreurRamassage)
                  <div class="d-flex align-items-center">
                    <div class="avtar avtar-s bg-light-warning me-2">
                      <i class="ti ti-user text-warning"></i>
                    </div>
                    <div>
                      <h6 class="mb-0">{{ $item->livreurRamassage->nom }} {{ $item->livreurRamassage->prenom }}</h6>
                      <small class="text-muted">{{ $item->livreurRamassage->telephone }}</small>
                    </div>
                  </div>
                  @else
                  <span class="text-muted">Non défini</span>
                  @endif
                </td>
                <td>
                  @if($item->ramasse_le)
                  <div>
                    <h6 class="mb-0">{{ $item->ramasse_le->format('d/m/Y') }}</h6>
                    <small class="text-muted">{{ $item->ramasse_le->format('H:i') }}</small>
                  </div>
                  @else
                  <span class="text-muted">-</span>
                  @endif
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-items-center">
                    <div class="qr-code-container mb-2" style="width: 80px; height: 80px;">
                      {!! $item->generateQrCode(80) !!}
                    </div>
                    <small class="text-muted">{{ $item->qr_code }}</small>
                  </div>
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <a href="{{ route('application.ecom-product-show', $item->id) }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Voir détails">
                      <i class="ti ti-eye"></i>
                    </a>
                    @if($item->recupere_gare)
                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled data-bs-toggle="tooltip" title="Modification impossible - Colis déjà récupéré">
                      <i class="ti ti-edit"></i>
                    </button>
                    @else
                    <a href="{{ route('application.ecom-product-edit', $item->id) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Modifier">
                      <i class="ti ti-edit"></i>
                    </a>
                    @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
          {{ $colis->links() }}
        </div>
        @else
        <div class="empty-state py-5 text-center">
          <div class="empty-state-icon">
            <i class="ti ti-package-off" style="font-size: 4rem; color: #6c757d;"></i>
          </div>
          <h4 class="mt-3">Aucun colis ramassé</h4>
          <p class="text-muted">Il n'y a actuellement aucun colis qui a été ramassé par les livreurs.</p>
          <div class="mt-4">
            <a href="{{ route('application.ecom-product-list') }}" class="btn btn-primary">
              <i class="ti ti-arrow-left me-2"></i>Voir les Colis En Cours
            </a>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmation pour suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">
          <i class="ti ti-alert-circle me-2"></i>Confirmer la suppression
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer ce colis ? Cette action est irréversible.</p>
        <div class="alert alert-warning">
          <i class="ti ti-alert-triangle me-2"></i>
          <strong>Attention :</strong> Toutes les données liées à ce colis seront définitivement perdues.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-2"></i>Annuler
        </button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
          <i class="ti ti-trash me-2"></i>Supprimer
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Formulaire caché pour suppression -->
<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
let deleteUrl = '';

function deleteColis(id) {
  deleteUrl = `/application/colis/${id}`;
  const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
  deleteModal.show();
}

document.getElementById('confirmDelete').addEventListener('click', function() {
  if (deleteUrl) {
    const form = document.getElementById('deleteForm');
    form.action = deleteUrl;
    form.submit();
  }
});

// Auto-hide alerts after 5 seconds
setTimeout(function() {
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(function(alert) {
    if (alert.querySelector('.btn-close')) {
      alert.style.transition = 'opacity 0.5s';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }
  });
}, 5000);

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});

// ================== FONCTIONNALITÉS DE RECHERCHE ==================

// Toggle des filtres avancés
document.getElementById('toggleFilters').addEventListener('click', function() {
  const filters = document.getElementById('advancedFilters');
  const toggleText = document.getElementById('filterToggleText');
  
  if (filters.style.display === 'none') {
    filters.style.display = 'flex';
    toggleText.textContent = 'Masquer filtres';
  } else {
    filters.style.display = 'none';
    toggleText.textContent = 'Afficher filtres';
  }
});

// Variables globales pour la recherche
let searchTimeout;
const tableRows = Array.from(document.querySelectorAll('tbody tr'));
const originalRowsData = tableRows.map(row => ({
  element: row,
  text: row.textContent.toLowerCase().trim(),
  statut: row.querySelector('td:nth-child(2)') ? row.querySelector('td:nth-child(2)').textContent.toLowerCase() : '',
  montant: parseFloat(row.querySelector('td:nth-child(5)') ? row.querySelector('td:nth-child(5)').textContent.replace(/[^\d]/g, '') : 0),
  destination: row.querySelector('td:nth-child(4)') ? row.querySelector('td:nth-child(4)').textContent.trim() : '',
  livreur: row.querySelector('td:nth-child(6)') ? row.querySelector('td:nth-child(6)').textContent.trim() : '',
  ramasse_le: row.querySelector('td:nth-child(7)') ? row.querySelector('td:nth-child(7)').textContent.trim() : ''
}));

// Fonction de filtrage avec debounce
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = function() {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Fonction principale de filtrage
function filterTable() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase();
  const statutFilter = document.getElementById('filterStatut').value.toLowerCase();
  const livreurFilter = document.getElementById('filterLivreur').value;
  const destinationFilter = document.getElementById('filterDestination').value;
  const typeFilter = document.getElementById('filterType').value;
  const dateDu = document.getElementById('filterDateDu').value;
  const dateAu = document.getElementById('filterDateAu').value;
  
  let visibleRows = [];
  let totalValue = 0;
  
  originalRowsData.forEach(rowData => {
    let showRow = true;
    
    // Filtre de recherche globale
    if (searchTerm && !rowData.text.includes(searchTerm)) {
      showRow = false;
    }
    
    // Filtre par statut
    if (statutFilter && !rowData.statut.includes(statutFilter)) {
      showRow = false;
    }
    
    // Filtre par destination
    if (destinationFilter && !rowData.destination.includes(destinationFilter)) {
      showRow = false;
    }
    
    // Filtre par livreur
    if (livreurFilter && !rowData.livreur.includes(livreurFilter)) {
      showRow = false;
    }
    
    // Filtre par date (si les champs de date sont remplis)
    if (dateDu || dateAu) {
      const rowDate = rowData.ramasse_le.split(' ')[0]; // Extraire la date
      if (dateDu && rowDate < dateDu) showRow = false;
      if (dateAu && rowDate > dateAu) showRow = false;
    }
    
    if (showRow) {
      visibleRows.push(rowData);
      totalValue += rowData.montant;
    }
    
    // Afficher/masquer la ligne
    rowData.element.style.display = showRow ? '' : 'none';
  });
  
  // Les statistiques sont supprimées
  // Pas de mise à jour nécessaire
}

// Fonctions de statistiques supprimées

// Event listeners pour la recherche
const debouncedFilter = debounce(filterTable, 300);

document.getElementById('searchInput').addEventListener('input', debouncedFilter);
document.getElementById('filterStatut').addEventListener('change', filterTable);
document.getElementById('filterLivreur').addEventListener('change', filterTable);
document.getElementById('filterDestination').addEventListener('change', filterTable);
document.getElementById('filterType').addEventListener('change', filterTable);
document.getElementById('filterDateDu').addEventListener('change', filterTable);
document.getElementById('filterDateAu').addEventListener('change', filterTable);

// Reset des filtres
document.getElementById('resetFilters').addEventListener('click', function() {
  document.getElementById('searchInput').value = '';
  document.getElementById('filterStatut').value = '';
  document.getElementById('filterLivreur').value = '';
  document.getElementById('filterDestination').value = '';
  document.getElementById('filterType').value = '';
  document.getElementById('filterDateDu').value = '';
  document.getElementById('filterDateAu').value = '';
  
  // Réafficher toutes les lignes
  originalRowsData.forEach(rowData => {
    rowData.element.style.display = '';
  });
  
  // Animation de reset
  const searchCard = document.querySelector('.card[style*="background: #ffffff"]');
  searchCard.style.transform = 'scale(0.98)';
  setTimeout(() => {
    searchCard.style.transform = 'scale(1)';
  }, 150);
});

// Animation d'entrée au chargement
document.addEventListener('DOMContentLoaded', function() {
  const searchCard = document.querySelector('.card[style*="background: #ffffff"]');
  if (searchCard) {
    searchCard.style.opacity = '0';
    searchCard.style.transform = 'translateY(-20px)';
    searchCard.style.transition = 'all 0.6s ease';
    
    setTimeout(() => {
      searchCard.style.opacity = '1';
      searchCard.style.transform = 'translateY(0)';
    }, 100);
  }
});
</script>
@endpush
