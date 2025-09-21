@extends('layouts.app')

@section('title', 'Gestion des Bagages')

@push('styles')
<style>
/* Pagination moderne pour bagages */
.pagination-wrapper .pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
  margin: 0;
}

.pagination-wrapper .page-item {
  margin: 0;
}

.pagination-wrapper .page-link {
  border: none;
  background: #f8f9fa;
  color: #6c757d;
  padding: 12px 16px;
  border-radius: 10px;
  font-weight: 500;
  font-size: 14px;
  transition: all 0.3s ease;
  text-decoration: none;
  min-width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.pagination-wrapper .page-link:hover {
  background: linear-gradient(135deg, #fd7e14, #ff922b);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(253, 126, 20, 0.3);
}

.pagination-wrapper .page-item.active .page-link {
  background: linear-gradient(135deg, #fd7e14, #ff922b);
  color: white;
  box-shadow: 0 4px 12px rgba(253, 126, 20, 0.3);
}

.pagination-wrapper .page-item.disabled .page-link {
  background: #e9ecef;
  color: #adb5bd;
  cursor: not-allowed;
}

.pagination-wrapper .page-item.disabled .page-link:hover {
  background: #e9ecef;
  color: #adb5bd;
  transform: none;
  box-shadow: none;
}

/* Flèches de navigation */
.pagination-wrapper .page-link[aria-label="Previous"],
.pagination-wrapper .page-link[aria-label="Next"] {
  font-size: 16px;
  padding: 12px 14px;
}

/* Style pour mobile */
@media (max-width: 576px) {
  .pagination-wrapper .page-link {
    padding: 10px 12px;
    font-size: 13px;
    min-width: 38px;
    height: 38px;
  }
}
</style>
@endpush

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Gestion des Bagages</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
          <li class="breadcrumb-item" aria-current="page">Gestion des Bagages</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Liste des Bagages</h5>
        <a href="{{ route('application.bagages.create') }}" class="btn btn-primary">
          <i class="ti ti-plus"></i> Ajouter un Bagage
        </a>
      </div>
      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
        @endif

        <!-- Section de recherche -->
        <div class="row mb-4">
          <div class="col-md-4">
            <div class="form-group">
              <label class="form-label">Recherche globale</label>
              <input type="text" class="form-control" id="searchGlobal" placeholder="Numéro, nom, téléphone, destination...">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">Possède un ticket</label>
              <select class="form-select" id="filterTicket">
                <option value="">Tous</option>
                <option value="1">Oui</option>
                <option value="0">Non</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="form-label">Destination</label>
              <select class="form-select" id="filterDestination">
                <option value="">Toutes les destinations</option>
                @foreach($destinations as $destination)
                  <option value="{{ $destination->libelle }}">{{ $destination->libelle }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-label">&nbsp;</label>
              <div class="d-grid">
                <button type="button" class="btn btn-secondary" id="resetFilters">
                  <i class="ti ti-refresh"></i> Reset
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="alert alert-info d-flex justify-content-between align-items-center">
              <span>
                <strong id="resultCount">{{ $bagages->count() }}</strong> bagage(s) trouvé(s)
              </span>
              <span>
                Valeur totale: <strong id="totalValue">{{ number_format($bagages->sum('valeur'), 0, ',', ' ') }} FCFA</strong>
              </span>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>Numéro</th>
                <th>Ticket</th>
                <th>N° Ticket</th>
                <th>Client</th>
                <th>Destination</th>
                <th>Téléphone</th>
                <th>Poids</th>
                <th>Valeur</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bagages as $bagage)
                <tr>
                  <td><strong>{{ $bagage->numero }}</strong></td>
                  <td>
                    @if($bagage->possede_ticket)
                      <span class="badge bg-success">Oui</span>
                    @else
                      <span class="badge bg-secondary">Non</span>
                    @endif
                  </td>
                  <td>{{ $bagage->numero_ticket ?? '-' }}</td>
                  <td>{{ $bagage->nom_famille }} {{ $bagage->prenom }}</td>
                  <td>{{ $bagage->destination }}</td>
                  <td>{{ $bagage->telephone }}</td>
                  <td>{{ $bagage->poids ? $bagage->poids . ' kg' : '-' }}</td>
                  <td>{{ $bagage->valeur ? number_format($bagage->valeur, 0, ',', ' ') . ' FCFA' : '-' }}</td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="{{ route('application.bagages.show', $bagage) }}" 
                         class="btn btn-info btn-sm" title="Voir">
                        <i class="ti ti-eye"></i>
                      </a>
                      <a href="{{ route('application.bagages.edit', $bagage) }}" 
                         class="btn btn-warning btn-sm" title="Modifier">
                        <i class="ti ti-edit"></i>
                      </a>
                      <form action="{{ route('application.bagages.destroy', $bagage) }}" 
                            method="POST" 
                            style="display: inline-block;"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bagage ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-danger btn-sm" 
                                title="Supprimer">
                          <i class="ti ti-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center">Aucun bagage enregistré</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination moderne -->
        <div class="d-flex justify-content-center mt-4">
          <div class="pagination-wrapper">
            {{ $bagages->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- [ Main Content ] end -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchGlobal');
    const ticketFilter = document.getElementById('filterTicket');
    const destinationFilter = document.getElementById('filterDestination');
    const resetButton = document.getElementById('resetFilters');
    const table = document.querySelector('table tbody');
    const resultCount = document.getElementById('resultCount');
    const totalValue = document.getElementById('totalValue');
    
    let allRows = Array.from(table.querySelectorAll('tr'));

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

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const ticketValue = ticketFilter.value;
        const destinationValue = destinationFilter.value;
        
        let visibleCount = 0;
        let totalVal = 0;
        
        allRows.forEach(row => {
            if (row.cells.length === 0) return; // Skip empty rows
            
            const numero = row.cells[0].textContent.toLowerCase();
            const ticket = row.cells[1].textContent.includes('Oui') ? '1' : '0';
            const numeroTicket = row.cells[2].textContent.toLowerCase();
            const client = row.cells[3].textContent.toLowerCase();
            const destination = row.cells[4].textContent.trim();
            const telephone = row.cells[5].textContent.toLowerCase();
            const valeurText = row.cells[7].textContent;
            
            // Extraction de la valeur numérique
            const valeur = parseFloat(valeurText.replace(/[^0-9]/g, '')) || 0;
            
            let showRow = true;
            
            // Filtre recherche globale
            if (searchTerm) {
                const searchableText = `${numero} ${numeroTicket} ${client} ${destination.toLowerCase()} ${telephone}`;
                if (!searchableText.includes(searchTerm)) {
                    showRow = false;
                }
            }
            
            // Filtre ticket
            if (ticketValue && ticket !== ticketValue) {
                showRow = false;
            }
            
            // Filtre destination
            if (destinationValue && destination !== destinationValue) {
                showRow = false;
            }
            
            if (showRow) {
                row.style.display = '';
                visibleCount++;
                totalVal += valeur;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Mettre à jour les statistiques
        resultCount.textContent = visibleCount;
        totalValue.textContent = new Intl.NumberFormat('fr-FR').format(totalVal) + ' FCFA';
        
        // Afficher message si aucun résultat
        const noResultsRow = table.querySelector('.no-results');
        if (visibleCount === 0) {
            if (!noResultsRow) {
                const newRow = document.createElement('tr');
                newRow.className = 'no-results';
                newRow.innerHTML = '<td colspan="9" class="text-center text-muted">Aucun bagage ne correspond aux critères de recherche</td>';
                table.appendChild(newRow);
            }
        } else {
            if (noResultsRow) {
                noResultsRow.remove();
            }
        }
    }

    function resetFilters() {
        searchInput.value = '';
        ticketFilter.value = '';
        destinationFilter.value = '';
        filterTable();
    }

    // Événements avec debounce pour optimiser les performances
    const debouncedFilter = debounce(filterTable, 300);
    
    searchInput.addEventListener('input', debouncedFilter);
    ticketFilter.addEventListener('change', filterTable);
    destinationFilter.addEventListener('change', filterTable); // Change de 'input' à 'change' pour select
    resetButton.addEventListener('click', resetFilters);
});
</script>
@endpush
@endsection
