@extends('layouts.app')

@section('title', 'Tous les Colis - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Tous les Colis</h5>
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
      <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h5>Tous les Colis</h5>
            <small class="text-muted">Y compris les colis récupérés et livrés</small>
          </div>
          <div>
            <a href="{{ route('application.colis.list') }}" class="btn btn-outline-secondary me-2">
              <i class="ti ti-arrow-left me-2"></i>En Cours Seulement
            </a>
            <a href="{{ route('application.colis.add') }}" class="btn btn-primary">
              <i class="ti ti-plus me-2"></i>Nouveau Colis
            </a>
          </div>
        </div>
      </div>
      <div class="card-body">
      <!-- Section de Recherche Avancée -->
      <div class="card border shadow-sm mb-4" style="background: #ffffff;">
        <div class="card-body p-4">
          <div class="row align-items-center mb-3">
            <div class="col">
              <h6 class="text-dark mb-0">
                <i class="ti ti-search me-2"></i>Recherche Avancée
              </h6>
              <small class="text-muted">Filtrez vos colis selon vos critères</small>
            </div>
            <div class="col-auto">
              <button class="btn btn-sm btn-outline-secondary" type="button" id="toggleAdvancedSearch">
                <i class="ti ti-adjustments me-1"></i>
                <span id="toggleText">Masquer filtres</span>
              </button>
            </div>
          </div>
          
          <!-- Recherche rapide -->
          <div class="row mb-3">
            <div class="col-md-8">
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                  <i class="ti ti-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 ps-0" id="quickSearch" 
                       placeholder="Recherche rapide : N° courrier, expéditeur, bénéficiaire, téléphone...">
              </div>
            </div>
            <div class="col-md-4">
              <button class="btn btn-outline-secondary w-100" type="button" id="clearFilters">
                <i class="ti ti-refresh me-1"></i>Réinitialiser
              </button>
            </div>
          </div>

            <!-- Filtres avancés -->
            <div id="advancedFilters">
              <div class="row g-3">
                <div class="col-md-3">
                  <label class="form-label text-dark small">Statut</label>
                  <select class="form-select form-select-sm" id="statusFilter">
                    <option value="">Tous les statuts</option>
                    <option value="preparation">Préparation</option>
                    <option value="transit">En Transit</option>
                    <option value="livre">Livré</option>
                    <option value="probleme">Problème</option>
                  </select>
                </div>
                
                <div class="col-md-3">
                  <label class="form-label text-dark small">Type de colis</label>
                  <select class="form-select form-select-sm" id="typeFilter">
                    <option value="">Tous les types</option>
                    <option value="document">Document</option>
                    <option value="colis_standard">Colis standard</option>
                    <option value="colis_fragile">Colis fragile</option>
                    <option value="colis_express">Colis express</option>
                    <option value="colis_urgent">Colis urgent</option>
                  </select>
                </div>
                
                <div class="col-md-3">
                  <label class="form-label text-dark small">Destination</label>
                  <select class="form-select form-select-sm" id="destinationFilter">
                    <option value="">Toutes destinations</option>
                    @foreach($destinations ?? [] as $destination)
                      <option value="{{ $destination->nom }}">{{ $destination->libelle }}</option>
                    @endforeach
                  </select>
                </div>
                
                <div class="col-md-3">
                  <label class="form-label text-dark small">Agence de réception</label>
                  <select class="form-select form-select-sm" id="agenceFilter">
                    <option value="">Toutes agences</option>
                    @foreach($agences ?? [] as $agence)
                      <option value="{{ $agence->nom }}">{{ $agence->libelle }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              
              <div class="row g-3 mt-2">
                <div class="col-md-3">
                  <label class="form-label text-dark small">Date de création</label>
                  <input type="date" class="form-control form-control-sm" id="dateFromFilter">
                </div>
                
                <div class="col-md-3">
                  <label class="form-label text-dark small">À</label>
                  <input type="date" class="form-control form-control-sm" id="dateToFilter">
                </div>
                
                <div class="col-md-3">
                  <label class="form-label text-dark small">Montant minimum</label>
                  <div class="input-group input-group-sm">
                    <input type="number" class="form-control" id="montantMinFilter" placeholder="0">
                    <span class="input-group-text">FCFA</span>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <label class="form-label text-dark small">Montant maximum</label>
                  <div class="input-group input-group-sm">
                    <input type="number" class="form-control" id="montantMaxFilter" placeholder="999999">
                    <span class="input-group-text">FCFA</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="row mb-4" id="searchStats" style="display: none;">
          <div class="col-md-12">
            <div class="alert alert-info border-0 d-flex align-items-center">
              <i class="ti ti-info-circle me-2"></i>
              <span id="searchStatsText">Aucun filtre appliqué</span>
            </div>
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
                <th>QR CODE</th>
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
                    @if($item->recupere_gare)
                    <span class="badge bg-success mt-1">
                      <i class="ti ti-check-circle me-1"></i>Récupéré
                    </span>
                    @endif
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
                <td class="text-center">
                  <div class="d-flex flex-column align-items-center">
                    <div class="qr-code-container mb-2" style="width: 80px; height: 80px;">
                      {!! $item->generateQrCode(80) !!}
                    </div>
                    <small class="text-muted">{{ $item->qr_code }}</small>
                  </div>
                </td>
                <td>{{ $item->created_at->format('d/m/Y') }}<br><span class="text-muted">{{ $item->created_at->format('H:i') }}</span></td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <a href="{{ route('application.colis.show', $item->id) }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Voir détails">
                      <i class="ti ti-eye"></i>
                    </a>
                    @if($item->recupere_gare)
                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled data-bs-toggle="tooltip" title="Modification impossible - Colis déjà récupéré">
                      <i class="ti ti-edit"></i>
                    </button>
                    @else
                    <a href="{{ route('application.colis.edit', $item->id) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Modifier">
                      <i class="ti ti-edit"></i>
                    </a>
                    @endif
                    @can('marquer_recupere_colis')
                    @if(!$item->recupere_gare)
                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Marquer comme récupéré" onclick="marquerRecupere({{ $item->id }}, '{{ $item->nom_beneficiaire }}', '{{ $item->telephone_beneficiaire }}')">
                      <i class="ti ti-check"></i>
                    </button>
                    @else
                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="Récupéré le {{ $item->recupere_le?->format('d/m/Y H:i') }}">
                      <i class="ti ti-check-circle"></i>
                    </button>
                    @endif
                    @endcan
                    @if($item->recupere_gare)
                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled data-bs-toggle="tooltip" title="Suppression impossible - Colis déjà récupéré">
                      <i class="ti ti-trash"></i>
                    </button>
                    @else
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Supprimer" onclick="deleteColis({{ $item->id }})">
                      <i class="ti ti-trash"></i>
                    </button>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="11" class="text-center py-4">
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
<!-- Modal de récupération -->
<div class="modal fade" id="recuperationModal" tabindex="-1" aria-labelledby="recuperationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('application.colis.marquer-recupere') }}" method="POST">
        @csrf
        <input type="hidden" id="modalColisId" name="colis_id">
        
        <div class="modal-header">
          <h5 class="modal-title" id="recuperationModalLabel">
            <i class="ti ti-check-circle me-2"></i>Marquer comme Récupéré
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body">
          <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            <strong>Récupération à la gare</strong><br>
            Cette action marquera le colis comme récupéré directement à la gare par le bénéficiaire ou son représentant.
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="recupereParNom">Nom de la personne <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="recupereParNom" name="recupere_par_nom" required 
                       placeholder="Nom de qui récupère">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="recupereParTelephone">Téléphone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="recupereParTelephone" name="recupere_par_telephone" required 
                       placeholder="Téléphone">
              </div>
            </div>
          </div>
          
          <div class="mb-3">
            <label class="form-label" for="recupereParCin">CIN (optionnel)</label>
            <input type="text" class="form-control" id="recupereParCin" name="recupere_par_cin" 
                   placeholder="Numéro de carte d'identité">
          </div>
          
          <div class="mb-3">
            <label class="form-label" for="notesRecuperation">Notes (optionnel)</label>
            <textarea class="form-control" id="notesRecuperation" name="notes_recuperation" rows="3" 
                      placeholder="Notes sur la récupération..."></textarea>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ti ti-x me-2"></i>Annuler
          </button>
          <button type="submit" class="btn btn-success">
            <i class="ti ti-check me-2"></i>Confirmer la Récupération
          </button>
        </div>
      </form>
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

/* Styles pour la section de recherche avancée */
.search-section {
  background: #ffffff;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.search-section .form-control,
.search-section .form-select {
  border: 1px solid #ced4da;
  background: #ffffff;
  transition: all 0.3s ease;
}

.search-section .form-control:focus,
.search-section .form-select:focus {
  border-color: #86b7fe;
  background: #ffffff;
  box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.search-section .input-group-text {
  background: #f8f9fa;
  border: 1px solid #ced4da;
  border-right: none;
}

/* Animation pour le toggle des filtres */
#advancedFilters {
  transition: all 0.3s ease;
  overflow: hidden;
}

/* Styles pour les QR codes */
.qr-code-container {
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  background: #fff;
  padding: 5px;
}

.qr-code-container svg {
  width: 100% !important;
  height: 100% !important;
}

/* Amélioration des badges de statistiques */
.alert-info {
  background: linear-gradient(45deg, #e3f2fd, #f0f8ff);
  border: 1px solid #1976d2;
  color: #1565c0;
}

/* Amélioration de la table responsive */
.table-responsive {
  border-radius: 8px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.table thead th {
  background: #f8f9fa;
  border-bottom: 2px solid #dee2e6;
  font-weight: 600;
  color: #495057;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Animation pour les lignes de la table */
.table tbody tr {
  transition: all 0.2s ease;
}

.table tbody tr:hover {
  background-color: #f8f9fa;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Styles pour les notifications de recherche */
.search-notification {
  animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

/* Amélioration des boutons d'action */
.btn-group .btn {
  transition: all 0.2s ease;
}

.btn-group .btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Responsive pour mobile */
@media (max-width: 768px) {
  .search-section .card-body {
    padding: 1rem !important;
  }
  
  .search-section .row.g-3 > * {
    margin-bottom: 0.75rem;
  }
  
  .table-responsive {
    font-size: 0.875rem;
  }
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

  // Fonctionnalité de recherche avancée
  initAdvancedSearch();
});

// Fonction de suppression
function deleteColis(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce colis ? Cette action est irréversible.')) {
    const form = document.getElementById('deleteForm');
    form.action = '/application/colis/' + id;
    form.submit();
  }
}

// Initialisation de la recherche avancée
function initAdvancedSearch() {
  const filters = {
    quickSearch: document.getElementById('quickSearch'),
    statusFilter: document.getElementById('statusFilter'),
    typeFilter: document.getElementById('typeFilter'),
    destinationFilter: document.getElementById('destinationFilter'),
    agenceFilter: document.getElementById('agenceFilter'),
    dateFromFilter: document.getElementById('dateFromFilter'),
    dateToFilter: document.getElementById('dateToFilter'),
    montantMinFilter: document.getElementById('montantMinFilter'),
    montantMaxFilter: document.getElementById('montantMaxFilter')
  };

  // Toggle des filtres avancés
  const toggleBtn = document.getElementById('toggleAdvancedSearch');
  const advancedFilters = document.getElementById('advancedFilters');
  const toggleText = document.getElementById('toggleText');
  let filtersVisible = true;

  if (toggleBtn) {
    toggleBtn.addEventListener('click', function() {
      filtersVisible = !filtersVisible;
      advancedFilters.style.display = filtersVisible ? 'block' : 'none';
      toggleText.textContent = filtersVisible ? 'Masquer filtres' : 'Afficher filtres';
      toggleBtn.querySelector('i').className = filtersVisible ? 'ti ti-eye-off me-1' : 'ti ti-eye me-1';
    });
  }

  // Bouton de réinitialisation
  const clearBtn = document.getElementById('clearFilters');
  if (clearBtn) {
    clearBtn.addEventListener('click', function() {
      // Réinitialiser tous les filtres
      Object.values(filters).forEach(filter => {
        if (filter) filter.value = '';
      });
      
      // Réafficher toutes les lignes
      const rows = document.querySelectorAll('#colisTable tbody tr');
      rows.forEach(row => {
        row.style.display = '';
      });
      
      // Masquer les statistiques
      const searchStats = document.getElementById('searchStats');
      if (searchStats) searchStats.style.display = 'none';
      
      // Notification
      showSearchNotification('Tous les filtres ont été réinitialisés', 'success');
    });
  }

  // Event listeners pour tous les filtres
  Object.entries(filters).forEach(([key, element]) => {
    if (element) {
      const eventType = element.type === 'text' || element.type === 'number' ? 'input' : 'change';
      element.addEventListener(eventType, debounce(() => applyFilters(filters), 300));
    }
  });
}

// Fonction principale de filtrage
function applyFilters(filters) {
  const rows = document.querySelectorAll('#colisTable tbody tr');
  let visibleCount = 0;
  let totalValue = 0;
  const activeFilters = [];

  rows.forEach(row => {
    const cells = row.querySelectorAll('td');
    if (cells.length === 0) return; // Ignorer les lignes vides

    let isVisible = true;

    // Recherche rapide
    if (filters.quickSearch && filters.quickSearch.value.trim()) {
      const searchTerm = filters.quickSearch.value.toLowerCase().trim();
      const rowText = row.textContent.toLowerCase();
      if (!rowText.includes(searchTerm)) {
        isVisible = false;
      } else {
        activeFilters.push(`Recherche: "${filters.quickSearch.value}"`);
      }
    }

    // Filtre par type de colis
    if (filters.typeFilter && filters.typeFilter.value && isVisible) {
      const typeCell = cells[5]; // Colonne TYPE
      const typeText = typeCell ? typeCell.textContent.toLowerCase() : '';
      const filterValue = filters.typeFilter.value.replace('_', ' ').toLowerCase();
      if (!typeText.includes(filterValue)) {
        isVisible = false;
      } else {
        activeFilters.push(`Type: ${filters.typeFilter.options[filters.typeFilter.selectedIndex].text}`);
      }
    }

    // Filtre par destination
    if (filters.destinationFilter && filters.destinationFilter.value && isVisible) {
      const destinationCell = cells[4]; // Colonne DESTINATION
      const destinationText = destinationCell ? destinationCell.textContent.toLowerCase() : '';
      if (!destinationText.includes(filters.destinationFilter.value.toLowerCase())) {
        isVisible = false;
      } else {
        activeFilters.push(`Destination: ${filters.destinationFilter.options[filters.destinationFilter.selectedIndex].text}`);
      }
    }

    // Filtre par agence
    if (filters.agenceFilter && filters.agenceFilter.value && isVisible) {
      const destinationCell = cells[4]; // Colonne DESTINATION (contient aussi l'agence)
      const destinationText = destinationCell ? destinationCell.textContent.toLowerCase() : '';
      if (!destinationText.includes(filters.agenceFilter.value.toLowerCase())) {
        isVisible = false;
      } else {
        activeFilters.push(`Agence: ${filters.agenceFilter.options[filters.agenceFilter.selectedIndex].text}`);
      }
    }

    // Filtre par montant
    if (isVisible) {
      const montantCell = cells[6]; // Colonne MONTANT
      if (montantCell) {
        const montantText = montantCell.textContent.replace(/[^\d]/g, '');
        const montant = parseInt(montantText) || 0;
        
        if (filters.montantMinFilter && filters.montantMinFilter.value) {
          const min = parseInt(filters.montantMinFilter.value);
          if (montant < min) {
            isVisible = false;
          } else {
            activeFilters.push(`Montant min: ${min.toLocaleString()} FCFA`);
          }
        }
        
        if (filters.montantMaxFilter && filters.montantMaxFilter.value && isVisible) {
          const max = parseInt(filters.montantMaxFilter.value);
          if (montant > max) {
            isVisible = false;
          } else {
            activeFilters.push(`Montant max: ${max.toLocaleString()} FCFA`);
          }
        }
        
        if (isVisible) {
          totalValue += montant;
        }
      }
    }

    // Filtre par date
    if (isVisible) {
      const dateCell = cells[8]; // Colonne DATE CRÉATION
      if (dateCell && (filters.dateFromFilter?.value || filters.dateToFilter?.value)) {
        const dateParts = dateCell.textContent.split('/');
        if (dateParts.length >= 3) {
          const itemDate = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
          
          if (filters.dateFromFilter && filters.dateFromFilter.value) {
            const fromDate = new Date(filters.dateFromFilter.value);
            if (itemDate < fromDate) {
              isVisible = false;
            } else {
              activeFilters.push(`Du: ${fromDate.toLocaleDateString()}`);
            }
          }
          
          if (filters.dateToFilter && filters.dateToFilter.value && isVisible) {
            const toDate = new Date(filters.dateToFilter.value);
            toDate.setHours(23, 59, 59); // Inclure toute la journée
            if (itemDate > toDate) {
              isVisible = false;
            } else {
              activeFilters.push(`Au: ${toDate.toLocaleDateString()}`);
            }
          }
        }
      }
    }

    // Appliquer la visibilité
    row.style.display = isVisible ? '' : 'none';
    if (isVisible) visibleCount++;
  });

  // Mettre à jour les statistiques
  updateSearchStats(visibleCount, totalValue, [...new Set(activeFilters)]);
}

// Mise à jour des statistiques de recherche
function updateSearchStats(visibleCount, totalValue, activeFilters) {
  const searchStats = document.getElementById('searchStats');
  const searchStatsText = document.getElementById('searchStatsText');
  
  if (searchStats && searchStatsText) {
    if (activeFilters.length > 0) {
      const filtersText = activeFilters.slice(0, 3).join(', ');
      const moreText = activeFilters.length > 3 ? ` et ${activeFilters.length - 3} autre(s)` : '';
      searchStatsText.innerHTML = `
        <strong>${visibleCount}</strong> colis trouvé(s) • 
        Valeur totale: <strong>${totalValue.toLocaleString()} FCFA</strong><br>
        <small>Filtres actifs: ${filtersText}${moreText}</small>
      `;
      searchStats.style.display = 'block';
    } else {
      searchStats.style.display = 'none';
    }
  }
}

// Fonction de debounce pour optimiser les performances
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Notification pour les actions de recherche
function showSearchNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
  notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;';
  notification.innerHTML = `
    <i class="ti ti-${type === 'success' ? 'check' : type === 'error' ? 'x' : 'info-circle'} me-2"></i>${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  
  document.body.appendChild(notification);
  
  setTimeout(() => {
    if (notification.parentNode) {
      notification.remove();
    }
  }, 3000);
}


function deleteColis(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce colis ?')) {
    // Implémentation de suppression
    console.log('Suppression du colis ID:', id);
  }
}

// Fonction pour marquer un colis comme récupéré
function marquerRecupere(colisId, nomBeneficiaire, telephoneBeneficiaire) {
  // Ouvrir la modal
  document.getElementById('modalColisId').value = colisId;
  document.getElementById('recupereParNom').value = nomBeneficiaire;
  document.getElementById('recupereParTelephone').value = telephoneBeneficiaire;
  
  const modal = new bootstrap.Modal(document.getElementById('recuperationModal'));
  modal.show();
}
</script>
@endpush
