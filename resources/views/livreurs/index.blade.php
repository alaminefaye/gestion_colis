@extends('layouts.app')

@section('title', 'Gestion des Livreurs')

@push('styles')
<style>
/* Pagination moderne pour livreurs */
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
  background: linear-gradient(135deg, #20c997, #51cf66);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(32, 201, 151, 0.3);
}

.pagination-wrapper .page-item.active .page-link {
  background: linear-gradient(135deg, #20c997, #51cf66);
  color: white;
  box-shadow: 0 4px 12px rgba(32, 201, 151, 0.3);
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

/* Styles pour la recherche */
.search-section {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 15px;
  border: none;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.search-input {
  border-radius: 10px;
  border: 1px solid #e9ecef;
  padding: 12px 16px;
  transition: all 0.3s ease;
}

.search-input:focus {
  border-color: #20c997;
  box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.25);
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
          <h5 class="m-b-10">Gestion des Livreurs</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item" aria-current="page">Livreurs</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
          <h5>Liste des Livreurs</h5>
          @can('create_livreurs')
          <a href="{{ route('livreurs.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-2"></i>Ajouter un Livreur
          </a>
          @endcan
        </div>
      </div>
      <div class="card-body">
        
        <!-- Section de recherche moderne -->
        <div class="card search-section mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <i class="ti ti-search me-2 text-primary"></i>
              <h6 class="mb-0 fw-bold">Rechercher des livreurs</h6>
            </div>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label fw-500">Recherche globale</label>
                <input type="text" class="form-control search-input" id="searchGlobal" placeholder="🔍 Nom, téléphone, email, CIN...">
              </div>
              <div class="col-md-2">
                <label class="form-label fw-500">Statut</label>
                <select class="form-select search-input" id="filterStatut">
                  <option value="">📋 Tous</option>
                  <option value="actif">✅ Actifs</option>
                  <option value="inactif">❌ Inactifs</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-500">Trier par</label>
                <select class="form-select search-input" id="sortBy">
                  <option value="">📋 Par défaut</option>
                  <option value="nom">👤 Nom A-Z</option>
                  <option value="recent">📅 Plus récents</option>
                  <option value="colis_ramasses">📦 Plus de ramassages</option>
                  <option value="colis_livres">✅ Plus de livraisons</option>
                </select>
              </div>
              <div class="col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-outline-secondary w-100" id="resetFilters">
                  <i class="ti ti-refresh me-1"></i>Réinitialiser
                </button>
              </div>
            </div>
            
          </div>
        </div>

        @if($livreurs->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover" id="livreursTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nom Complet</th>
                  <th>Téléphone</th>
                  <th>Email</th>
                  <th>CIN</th>
                  <th>Statut</th>
                  <th>Colis Ramassés</th>
                  <th>Colis Livrés</th>
                  <th>Date d'embauche</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($livreurs as $livreur)
                <tr>
                  <td>{{ $livreur->id }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avtar avtar-s bg-light-primary me-3">
                        <i class="ti ti-user f-18"></i>
                      </div>
                      <div>
                        <h6 class="mb-0">{{ $livreur->nom_complet }}</h6>
                        <small class="text-muted">{{ $livreur->adresse }}</small>
                      </div>
                    </div>
                  </td>
                  <td>{{ $livreur->telephone }}</td>
                  <td>{{ $livreur->email ?? '-' }}</td>
                  <td>{{ $livreur->cin }}</td>
                  <td>
                    @if($livreur->actif)
                      <span class="badge bg-light-success">Actif</span>
                    @else
                      <span class="badge bg-light-danger">Inactif</span>
                    @endif
                  </td>
                  <td>
                    <span class="badge bg-light-warning">{{ $livreur->colis_ramasses_count }}</span>
                  </td>
                  <td>
                    <span class="badge bg-light-success">{{ $livreur->colis_livres_count }}</span>
                  </td>
                  <td>{{ $livreur->date_embauche->format('d/m/Y') }}</td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="{{ route('livreurs.show', $livreur) }}" class="btn btn-outline-info btn-sm" title="Voir">
                        <i class="ti ti-eye"></i>
                      </a>
                      @can('edit_livreurs')
                      <a href="{{ route('livreurs.edit', $livreur) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                        <i class="ti ti-edit"></i>
                      </a>
                      @endcan
                      @can('delete_livreurs')
                      <button type="button" class="btn btn-outline-danger btn-sm" title="Supprimer" onclick="deleteLivreur({{ $livreur->id }})">
                        <i class="ti ti-trash"></i>
                      </button>
                      @endcan
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <!-- Pagination moderne -->
          <div class="d-flex justify-content-center mt-4">
            <div class="pagination-wrapper">
              {{ $livreurs->links('pagination::bootstrap-4') }}
            </div>
          </div>
        @else
          <div class="text-center py-5">
            <i class="ti ti-users f-48 text-muted mb-3"></i>
            <h6 class="text-muted">Aucun livreur trouvé</h6>
            <p class="text-muted">Commencez par ajouter votre premier livreur.</p>
            @can('create_livreurs')
            <a href="{{ route('livreurs.create') }}" class="btn btn-primary">
              <i class="ti ti-plus me-2"></i>Ajouter un Livreur
            </a>
            @endcan
          </div>
        @endif
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

// Fonctionnalité de recherche et tri
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchGlobal');
  const statutFilter = document.getElementById('filterStatut');
  const sortSelect = document.getElementById('sortBy');
  const resetButton = document.getElementById('resetFilters');
  const table = document.getElementById('livreursTable');
  const resultCount = document.getElementById('resultCount');
  const activeCount = document.getElementById('activeCount');
  const inactiveCount = document.getElementById('inactiveCount');
  
  if (searchInput && table) {
    // Fonction debounce pour optimiser les performances
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

    // Fonction de filtrage
    function filterTable() {
      const searchTerm = searchInput.value.toLowerCase();
      const statutValue = statutFilter.value;
      const sortValue = sortSelect.value;
      const tbody = table.querySelector('tbody');
      const allRows = Array.from(tbody.querySelectorAll('tr')).filter(row => row.cells.length > 1);
      
      let visibleCount = 0;
      let activeCountNum = 0;
      let inactiveCountNum = 0;
      let filteredRows = [];

      // Filtrage des lignes
      allRows.forEach(row => {
        const nomComplet = row.cells[1].textContent.toLowerCase();
        const telephone = row.cells[2].textContent.toLowerCase();
        const email = row.cells[3].textContent.toLowerCase();
        const cin = row.cells[4].textContent.toLowerCase();
        const statutText = row.cells[5].textContent.trim();
        const isActif = statutText.includes('Actif');
        const colisRamasses = parseInt(row.cells[6].textContent.trim()) || 0;
        const colisLivres = parseInt(row.cells[7].textContent.trim()) || 0;
        const dateEmbauche = row.cells[8].textContent;
        
        let showRow = true;
        
        // Filtre recherche globale
        if (searchTerm) {
          const searchableText = `${nomComplet} ${telephone} ${email} ${cin}`;
          if (!searchableText.includes(searchTerm)) {
            showRow = false;
          }
        }
        
        // Filtre statut
        if (statutValue) {
          if (statutValue === 'actif' && !isActif) {
            showRow = false;
          } else if (statutValue === 'inactif' && isActif) {
            showRow = false;
          }
        }
        
        if (showRow) {
          visibleCount++;
          if (isActif) activeCountNum++;
          else inactiveCountNum++;
          
          filteredRows.push({
            row: row,
            nomComplet: nomComplet,
            colisRamasses: colisRamasses,
            colisLivres: colisLivres,
            dateEmbauche: dateEmbauche
          });
        }
        
        row.style.display = showRow ? '' : 'none';
      });

      // Tri des lignes visibles
      if (sortValue && filteredRows.length > 0) {
        filteredRows.sort((a, b) => {
          switch(sortValue) {
            case 'nom':
              return a.nomComplet.localeCompare(b.nomComplet);
            case 'recent':
              return new Date(b.dateEmbauche) - new Date(a.dateEmbauche);
            case 'colis_ramasses':
              return b.colisRamasses - a.colisRamasses;
            case 'colis_livres':
              return b.colisLivres - a.colisLivres;
            default:
              return 0;
          }
        });

        // Réorganiser les lignes dans le DOM
        filteredRows.forEach((item, index) => {
          tbody.appendChild(item.row);
        });
      }

      // Mettre à jour les statistiques
      resultCount.textContent = visibleCount;
      activeCount.textContent = activeCountNum;
      inactiveCount.textContent = inactiveCountNum;

      // Gestion du message "aucun résultat"
      const noResultsRow = table.querySelector('.no-results');
      if (visibleCount === 0) {
        if (!noResultsRow) {
          const newRow = document.createElement('tr');
          newRow.className = 'no-results';
          newRow.innerHTML = '<td colspan="10" class="text-center text-muted py-4">Aucun livreur ne correspond aux critères de recherche</td>';
          tbody.appendChild(newRow);
        }
      } else {
        if (noResultsRow) {
          noResultsRow.remove();
        }
      }
    }

    // Fonction de reset
    function resetFilters() {
      searchInput.value = '';
      statutFilter.value = '';
      sortSelect.value = '';
      filterTable();
    }

    // Événements
    const debouncedFilter = debounce(filterTable, 300);
    searchInput.addEventListener('input', debouncedFilter);
    statutFilter.addEventListener('change', filterTable);
    sortSelect.addEventListener('change', filterTable);
    resetButton.addEventListener('click', resetFilters);
  }
});
</script>
@endpush
