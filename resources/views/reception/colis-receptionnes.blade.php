@extends('layouts.app')

@section('title', 'Colis Réceptionnés')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">📦 Colis Réceptionnés</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.ecom-product-list') }}">Gestion des Colis</a></li>
          <li class="breadcrumb-item" aria-current="page">Colis Réceptionnés</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    
    <!-- Messages de succès -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- Header avec statistiques -->
    <div class="card mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
      <div class="card-body text-white">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h4 class="text-white mb-2">
              <i class="ti ti-package me-2"></i>Colis Réceptionnés
            </h4>
            <p class="mb-0 opacity-75">
              📊 <span id="total-count">{{ $colis->total() }}</span> colis réceptionnés au total
            </p>
          </div>
          <div class="col-md-4 text-end">
            <div class="d-flex gap-2 justify-content-end flex-wrap">
              <a href="{{ route('application.scan-colis') }}" class="btn btn-light">
                <i class="ti ti-scan me-2"></i>Scanner Colis
              </a>
              <a href="{{ route('application.ecom-product-list') }}" class="btn btn-outline-light">
                <i class="ti ti-list me-2"></i>Tous les Colis
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Section de recherche -->
    <div class="card mb-4">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col">
            <h5 class="mb-0">🔍 Recherche et Filtres</h5>
          </div>
          <div class="col-auto">
            <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#searchFilters">
              <i class="ti ti-filter me-2"></i>Filtres
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <!-- Recherche rapide -->
        <div class="row mb-3">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Recherche rapide</label>
              <input type="text" id="searchInput" class="form-control" 
                     placeholder="🔍 N° courrier, expéditeur, bénéficiaire, téléphone...">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">Trier par</label>
              <select id="sortSelect" class="form-select">
                <option value="recent">Plus récents</option>
                <option value="ancien">Plus anciens</option>
                <option value="numero">N° Courrier A-Z</option>
                <option value="montant">Montant ↑</option>
                <option value="montant_desc">Montant ↓</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">Réceptionné par</label>
              <select id="userFilter" class="form-select">
                <option value="">Tous les utilisateurs</option>
                @foreach($colis->unique('receptionneParUser.name')->whereNotNull('receptionneParUser') as $item)
                  <option value="{{ $item->receptionneParUser->name }}">{{ $item->receptionneParUser->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <!-- Filtres avancés (collapsés) -->
        <div class="collapse" id="searchFilters">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label class="form-label">Destination</label>
                <select id="destinationFilter" class="form-select">
                  <option value="">Toutes destinations</option>
                  @foreach($colis->unique('destination')->pluck('destination') as $destination)
                    <option value="{{ $destination }}">{{ $destination }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="form-label">Type de colis</label>
                <select id="typeFilter" class="form-select">
                  <option value="">Tous les types</option>
                  <option value="document">Document</option>
                  <option value="standard">Standard</option>
                  <option value="fragile">Fragile</option>
                  <option value="express">Express</option>
                  <option value="urgent">Urgent</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="form-label">Date réception (du)</label>
                <input type="date" id="dateFrom" class="form-control">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="form-label">Date réception (au)</label>
                <input type="date" id="dateTo" class="form-control">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label class="form-label">Montant minimum</label>
                <input type="number" id="montantMin" class="form-control" placeholder="0">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label class="form-label">Montant maximum</label>
                <input type="number" id="montantMax" class="form-control" placeholder="1000000">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">&nbsp;</label>
                <div>
                  <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                    <i class="ti ti-refresh me-2"></i>Réinitialiser
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistiques en temps réel -->
        <div class="alert alert-info mb-0">
          <div class="row text-center">
            <div class="col-md-4">
              📊 <span id="results-count">{{ $colis->count() }}</span> résultats affichés
            </div>
            <div class="col-md-4">
              💰 <span id="total-value">{{ number_format($colis->sum('montant'), 0, ',', ' ') }}</span> FCFA au total
            </div>
            <div class="col-md-4">
              📅 Période: <span id="period-info">Toutes les dates</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Liste des colis -->
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">📋 Liste des Colis Réceptionnés</h5>
      </div>
      <div class="card-body">
        @if($colis->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover" id="colisTable">
              <thead>
                <tr>
                  <th>N° Courrier</th>
                  <th>Expéditeur</th>
                  <th>Bénéficiaire</th>
                  <th>Destination</th>
                  <th>Type</th>
                  <th>Montant</th>
                  <th>Réceptionné le</th>
                  <th>Réceptionné par</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($colis as $item)
                <tr class="colis-row" 
                    data-numero="{{ $item->numero_courrier }}"
                    data-expediteur="{{ $item->nom_expediteur }}"
                    data-beneficiaire="{{ $item->nom_beneficiaire }}"
                    data-telephone-exp="{{ $item->telephone_expediteur }}"
                    data-telephone-ben="{{ $item->telephone_beneficiaire }}"
                    data-destination="{{ $item->destination }}"
                    data-type="{{ $item->type_colis }}"
                    data-montant="{{ $item->montant }}"
                    data-date="{{ $item->receptionne_le?->format('Y-m-d') }}"
                    data-user="{{ $item->receptionneParUser?->name }}">
                  <td>
                    <div class="d-flex align-items-center">
                      <span class="badge bg-dark me-2">{{ $item->numero_courrier }}</span>
                      @if($item->type_colis === 'urgent')
                        <i class="ti ti-flame text-danger" title="Urgent"></i>
                      @elseif($item->type_colis === 'express')
                        <i class="ti ti-bolt text-warning" title="Express"></i>
                      @elseif($item->type_colis === 'fragile')
                        <i class="ti ti-fragile text-info" title="Fragile"></i>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div>
                      <strong>{{ $item->nom_expediteur }}</strong><br>
                      <small class="text-muted">{{ $item->telephone_expediteur }}</small>
                    </div>
                  </td>
                  <td>
                    <div>
                      <strong>{{ $item->nom_beneficiaire }}</strong><br>
                      <small class="text-muted">{{ $item->telephone_beneficiaire }}</small>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-info">{{ $item->destination }}</span>
                  </td>
                  <td>
                    <span class="badge bg-secondary">{{ ucfirst($item->type_colis ?? 'Standard') }}</span>
                  </td>
                  <td>
                    <span class="text-success fw-bold">{{ number_format($item->montant, 0, ',', ' ') }} FCFA</span>
                  </td>
                  <td>
                    <div>
                      <strong>{{ $item->receptionne_le->format('d/m/Y') }}</strong><br>
                      <small class="text-muted">{{ $item->receptionne_le->format('H:i') }}</small>
                    </div>
                  </td>
                  <td>
                    @if($item->receptionneParUser)
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-primary text-white rounded-circle me-2">
                          {{ substr($item->receptionneParUser->name, 0, 2) }}
                        </div>
                        <div>
                          <small>{{ $item->receptionneParUser->name }}</small>
                        </div>
                      </div>
                    @else
                      <span class="text-muted">N/A</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex gap-1">
                      <a href="{{ route('application.ecom-product-show', $item->id) }}" 
                         class="btn btn-outline-primary btn-sm" title="Voir détails">
                        <i class="ti ti-eye"></i>
                      </a>
                      @if($item->notes_reception)
                        <button class="btn btn-outline-info btn-sm" title="Voir notes" 
                                onclick="showNotes('{{ addslashes($item->notes_reception) }}')">
                          <i class="ti ti-note"></i>
                        </button>
                      @endif
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Pagination moderne -->
          @if($colis->hasPages())
          <div class="d-flex align-items-center justify-content-between mt-4">
            <div>
              <p class="text-muted mb-0">
                Affichage de {{ $colis->firstItem() }} à {{ $colis->lastItem() }} sur {{ $colis->total() }} entrées
              </p>
            </div>
            
            <!-- Pagination moderne -->
            <div class="pagination-wrapper">
              <nav aria-label="Navigation">
                <ul class="pagination mb-0">
                  {{-- Lien "Précédent" --}}
                  @if ($colis->onFirstPage())
                    <li class="page-item disabled">
                      <span class="page-link">&laquo;</span>
                    </li>
                  @else
                    <li class="page-item">
                      <a class="page-link" href="{{ $colis->previousPageUrl() }}" rel="prev">&laquo;</a>
                    </li>
                  @endif

                  {{-- Liens de pagination --}}
                  @foreach ($colis->getUrlRange(1, $colis->lastPage()) as $page => $url)
                    @if ($page == $colis->currentPage())
                      <li class="page-item active">
                        <span class="page-link">{{ $page }}</span>
                      </li>
                    @else
                      <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                      </li>
                    @endif
                  @endforeach

                  {{-- Lien "Suivant" --}}
                  @if ($colis->hasMorePages())
                    <li class="page-item">
                      <a class="page-link" href="{{ $colis->nextPageUrl() }}" rel="next">&raquo;</a>
                    </li>
                  @else
                    <li class="page-item disabled">
                      <span class="page-link">&raquo;</span>
                    </li>
                  @endif
                </ul>
              </nav>
            </div>
          </div>
          @endif

        @else
          <div class="text-center py-5">
            <div class="mb-3">
              <i class="ti ti-package-off" style="font-size: 4rem; color: #dee2e6;"></i>
            </div>
            <h5 class="text-muted mb-3">Aucun colis réceptionné</h5>
            <p class="text-muted mb-4">
              Aucun colis n'a encore été réceptionné avec les critères de recherche actuels.
            </p>
            <div class="d-flex gap-2 justify-content-center">
              <a href="{{ route('application.scan-colis') }}" class="btn btn-primary">
                <i class="ti ti-scan me-2"></i>Scanner un Colis
              </a>
              <button class="btn btn-outline-secondary" onclick="resetFilters()">
                <i class="ti ti-refresh me-2"></i>Réinitialiser les Filtres
              </button>
            </div>
          </div>
        @endif
      </div>
    </div>

  </div>
</div>

<!-- Modal pour afficher les notes -->
<div class="modal fade" id="notesModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">📝 Notes de Réception</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="notesContent" class="alert alert-light"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<style>
.avatar {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: bold;
}

.table th {
  font-weight: 600;
  background-color: #f8f9fa;
  border-top: none;
}

.badge {
  font-size: 0.75rem;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
}

.alert-info {
  border-radius: 0.5rem;
}

.card-header h5 {
  font-weight: 600;
}

/* Style moderne pour la pagination - Thème Admin */
.pagination-wrapper .pagination {
  gap: 8px;
}

.pagination-wrapper .page-link {
  border: none;
  background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
  color: white;
  font-weight: 500;
  border-radius: 10px;
  padding: 10px 16px;
  min-width: 44px;
  min-height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
}

.pagination-wrapper .page-link:hover {
  background: linear-gradient(135deg, #3730a3 0%, #6b21a8 100%);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
  color: white;
}

.pagination-wrapper .page-item.active .page-link {
  background: linear-gradient(135deg, #1e1b4b 0%, #581c87 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
}

.pagination-wrapper .page-item.disabled .page-link {
  background: #e5e7eb;
  color: #9ca3af;
  cursor: not-allowed;
  box-shadow: none;
}

.pagination-wrapper .page-item.disabled .page-link:hover {
  background: #e5e7eb;
  transform: none;
  box-shadow: none;
}

@media (max-width: 768px) {
  .table-responsive {
    font-size: 0.875rem;
  }
  
  .d-flex.gap-2 {
    gap: 0.5rem !important;
  }
  
  .btn {
    font-size: 0.875rem;
  }

  .pagination-wrapper .page-link {
    padding: 8px 12px;
    min-width: 38px;
    min-height: 38px;
    font-size: 14px;
  }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  const sortSelect = document.getElementById('sortSelect');
  const userFilter = document.getElementById('userFilter');
  const destinationFilter = document.getElementById('destinationFilter');
  const typeFilter = document.getElementById('typeFilter');
  const dateFrom = document.getElementById('dateFrom');
  const dateTo = document.getElementById('dateTo');
  const montantMin = document.getElementById('montantMin');
  const montantMax = document.getElementById('montantMax');
  const resultsCount = document.getElementById('results-count');
  const totalValue = document.getElementById('total-value');
  const periodInfo = document.getElementById('period-info');

  let debounceTimer = null;

  // Attacher les événements
  searchInput.addEventListener('input', debounceFilter);
  sortSelect.addEventListener('change', filterAndSort);
  userFilter.addEventListener('change', filterAndSort);
  destinationFilter.addEventListener('change', filterAndSort);
  typeFilter.addEventListener('change', filterAndSort);
  dateFrom.addEventListener('change', filterAndSort);
  dateTo.addEventListener('change', filterAndSort);
  montantMin.addEventListener('input', debounceFilter);
  montantMax.addEventListener('input', debounceFilter);

  function debounceFilter() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(filterAndSort, 300);
  }

  function filterAndSort() {
    const rows = document.querySelectorAll('.colis-row');
    const searchTerm = searchInput.value.toLowerCase();
    const userFilterValue = userFilter.value;
    const destinationFilterValue = destinationFilter.value;
    const typeFilterValue = typeFilter.value;
    const dateFromValue = dateFrom.value;
    const dateToValue = dateTo.value;
    const montantMinValue = parseInt(montantMin.value) || 0;
    const montantMaxValue = parseInt(montantMax.value) || Infinity;

    let visibleRows = [];
    let totalAmount = 0;

    rows.forEach(row => {
      const numero = row.dataset.numero.toLowerCase();
      const expediteur = row.dataset.expediteur.toLowerCase();
      const beneficiaire = row.dataset.beneficiaire.toLowerCase();
      const telephoneExp = row.dataset.telephoneExp;
      const telephoneBen = row.dataset.telephoneBen;
      const destination = row.dataset.destination;
      const type = row.dataset.type || 'standard';
      const montant = parseInt(row.dataset.montant);
      const date = row.dataset.date;
      const user = row.dataset.user || '';

      // Filtrage par recherche
      const matchesSearch = searchTerm === '' || 
        numero.includes(searchTerm) ||
        expediteur.includes(searchTerm) ||
        beneficiaire.includes(searchTerm) ||
        telephoneExp.includes(searchTerm) ||
        telephoneBen.includes(searchTerm);

      // Filtrage par utilisateur
      const matchesUser = userFilterValue === '' || user === userFilterValue;

      // Filtrage par destination
      const matchesDestination = destinationFilterValue === '' || destination === destinationFilterValue;

      // Filtrage par type
      const matchesType = typeFilterValue === '' || type === typeFilterValue;

      // Filtrage par dates
      const matchesDate = (!dateFromValue || date >= dateFromValue) && 
                         (!dateToValue || date <= dateToValue);

      // Filtrage par montant
      const matchesMontant = montant >= montantMinValue && montant <= montantMaxValue;

      if (matchesSearch && matchesUser && matchesDestination && matchesType && matchesDate && matchesMontant) {
        row.style.display = '';
        visibleRows.push(row);
        totalAmount += montant;
      } else {
        row.style.display = 'none';
      }
    });

    // Tri
    const sortValue = sortSelect.value;
    visibleRows.sort((a, b) => {
      switch(sortValue) {
        case 'recent':
          return new Date(b.dataset.date) - new Date(a.dataset.date);
        case 'ancien':
          return new Date(a.dataset.date) - new Date(b.dataset.date);
        case 'numero':
          return a.dataset.numero.localeCompare(b.dataset.numero);
        case 'montant':
          return parseInt(a.dataset.montant) - parseInt(b.dataset.montant);
        case 'montant_desc':
          return parseInt(b.dataset.montant) - parseInt(a.dataset.montant);
        default:
          return 0;
      }
    });

    // Réorganiser les lignes dans le DOM
    const tbody = document.querySelector('#colisTable tbody');
    visibleRows.forEach(row => tbody.appendChild(row));

    // Mettre à jour les statistiques
    resultsCount.textContent = visibleRows.length;
    totalValue.textContent = new Intl.NumberFormat('fr-FR').format(totalAmount);

    // Mettre à jour l'information de période
    updatePeriodInfo();
  }

  function updatePeriodInfo() {
    const dateFromValue = dateFrom.value;
    const dateToValue = dateTo.value;

    if (dateFromValue && dateToValue) {
      periodInfo.textContent = `Du ${new Date(dateFromValue).toLocaleDateString('fr-FR')} au ${new Date(dateToValue).toLocaleDateString('fr-FR')}`;
    } else if (dateFromValue) {
      periodInfo.textContent = `Depuis le ${new Date(dateFromValue).toLocaleDateString('fr-FR')}`;
    } else if (dateToValue) {
      periodInfo.textContent = `Jusqu'au ${new Date(dateToValue).toLocaleDateString('fr-FR')}`;
    } else {
      periodInfo.textContent = 'Toutes les dates';
    }
  }

  // Fonction pour réinitialiser les filtres
  window.resetFilters = function() {
    searchInput.value = '';
    sortSelect.value = 'recent';
    userFilter.value = '';
    destinationFilter.value = '';
    typeFilter.value = '';
    dateFrom.value = '';
    dateTo.value = '';
    montantMin.value = '';
    montantMax.value = '';
    
    filterAndSort();
  }

  // Fonction pour afficher les notes
  window.showNotes = function(notes) {
    document.getElementById('notesContent').textContent = notes;
    new bootstrap.Modal(document.getElementById('notesModal')).show();
  }
});
</script>
@endpush
