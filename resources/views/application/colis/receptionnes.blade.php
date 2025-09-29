@extends('layouts.app')

@section('title', 'Colis Réceptionnés - TSR Système')

@section('content')
<div class="container-fluid">
    <!-- Header section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title mb-1">📥 Colis Réceptionnés</h3>
                            <p class="card-text mb-0">Liste des colis venus d'ailleurs et réceptionnés</p>
                        </div>
                        <div class="col-auto">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                <i class="ti ti-package-import" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $colisReceptionnes->total() }}</h3>
                            <p class="mb-0">Total Réceptionnés</p>
                        </div>
                        <i class="ti ti-package-import" style="font-size: 2.5rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $colisReceptionnes->where('receptionne_le', '>=', now()->startOfDay())->count() }}</h3>
                            <p class="mb-0">Aujourd'hui</p>
                        </div>
                        <i class="ti ti-calendar-check" style="font-size: 2.5rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $colisReceptionnes->where('receptionne_le', '>=', now()->startOfWeek())->count() }}</h3>
                            <p class="mb-0">Cette Semaine</p>
                        </div>
                        <i class="ti ti-calendar-week" style="font-size: 2.5rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-0">{{ $colisReceptionnes->sum('montant') }}</h3>
                            <p class="mb-0">Valeur Totale (FCFA)</p>
                        </div>
                        <i class="ti ti-cash" style="font-size: 2.5rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section de recherche -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">🔍 Recherche et Filtres</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Recherche rapide</label>
                                <input type="text" class="form-control" id="searchInput" 
                                       placeholder="Numéro, expéditeur, bénéficiaire...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date de réception</label>
                                <input type="date" class="form-control" id="dateFilter" 
                                       value="{{ request('date_reception') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Réceptionné par</label>
                                <select class="form-control" id="receptionParFilter">
                                    <option value="">Tous les utilisateurs</option>
                                    @foreach($colisReceptionnes->pluck('receptionne_par')->unique()->filter() as $receptionnaire)
                                    <option value="{{ $receptionnaire }}">{{ $receptionnaire }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-secondary d-block w-100" onclick="reinitialiserFiltres()">
                                    <i class="ti ti-refresh"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des colis réceptionnés -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">📦 Liste des Colis Réceptionnés</h5>
                </div>
                <div class="card-body">
                    @if($colisReceptionnes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" id="colisTable">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Courrier</th>
                                    <th>Expéditeur</th>
                                    <th>Bénéficiaire</th>
                                    <th>Destination</th>
                                    <th>Montant</th>
                                    <th>Réceptionné Par</th>
                                    <th>Date/Heure Réception</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($colisReceptionnes as $colis)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $colis->numero_courrier }}</span>
                                    </td>
                                    <td>{{ $colis->expediteur ?: 'Non renseigné' }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $colis->beneficiaire ?: 'Non renseigné' }}</strong>
                                            @if($colis->telephone_beneficiaire)
                                            <br><small class="text-muted">{{ $colis->telephone_beneficiaire }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $colis->destination ?: 'Non définie' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ number_format($colis->montant, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                {{ strtoupper(substr($colis->receptionne_par, 0, 2)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $colis->receptionne_par }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ \Carbon\Carbon::parse($colis->receptionne_le)->format('d/m/Y') }}</strong>
                                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($colis->receptionne_le)->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($colis->notes_reception)
                                            <span class="badge bg-warning" title="{{ $colis->notes_reception }}">
                                                <i class="ti ti-note"></i> Voir
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('application.ecom-product-show', $colis->id) }}" 
                                               class="btn btn-sm btn-outline-info" title="Voir détails">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            @if($colis->notes_reception)
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    title="Voir notes" 
                                                    onclick="voirNotes('{{ $colis->numero_courrier }}', '{{ addslashes($colis->notes_reception) }}')">
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
                    <div class="d-flex justify-content-center mt-4">
                        <div class="pagination-wrapper">
                            {{ $colisReceptionnes->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="ti ti-package-off" style="font-size: 4rem; color: #ddd;"></i>
                        <h5 class="text-muted mt-3">Aucun colis réceptionné</h5>
                        <p class="text-muted">Les colis réceptionnés apparaîtront ici</p>
                    </div>
                    @endif
                </div>
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
                <h6>Colis : <span id="modalColisNumero"></span></h6>
                <hr>
                <p id="modalNotesContent"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const dateFilter = document.getElementById('dateFilter');
    const receptionParFilter = document.getElementById('receptionParFilter');
    const table = document.getElementById('colisTable');
    
    // Fonction de recherche avec debounce
    let searchTimeout;
    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filtrerTable();
        }, 300);
    }
    
    // Event listeners pour les filtres
    searchInput.addEventListener('input', debounceSearch);
    dateFilter.addEventListener('change', filtrerTable);
    receptionParFilter.addEventListener('change', filtrerTable);
    
    function filtrerTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const dateSelected = dateFilter.value;
        const receptionParSelected = receptionParFilter.value.toLowerCase();
        
        const tbody = table.querySelector('tbody');
        const rows = tbody.querySelectorAll('tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            
            // Texte à rechercher (numéro, expéditeur, bénéficiaire, téléphone)
            const numeroCourrier = cells[0].textContent.toLowerCase();
            const expediteur = cells[1].textContent.toLowerCase();
            const beneficiaire = cells[2].textContent.toLowerCase();
            const receptionPar = cells[5].textContent.toLowerCase();
            const dateReception = cells[6].textContent;
            
            // Vérifier les critères de filtrage
            const matchSearch = !searchTerm || 
                numeroCourrier.includes(searchTerm) || 
                expediteur.includes(searchTerm) || 
                beneficiaire.includes(searchTerm);
            
            const matchDate = !dateSelected || dateReception.includes(dateSelected.split('-').reverse().join('/'));
            
            const matchReceptionPar = !receptionParSelected || receptionPar.includes(receptionParSelected);
            
            // Afficher ou cacher la ligne
            if (matchSearch && matchDate && matchReceptionPar) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Afficher le message si aucun résultat
        const noResultsMessage = document.getElementById('noResultsMessage');
        if (noResultsMessage) {
            noResultsMessage.remove();
        }
        
        if (visibleCount === 0 && (searchTerm || dateSelected || receptionParSelected)) {
            const noResultsRow = document.createElement('tr');
            noResultsRow.id = 'noResultsMessage';
            noResultsRow.innerHTML = `
                <td colspan="9" class="text-center py-4">
                    <i class="ti ti-search-off" style="font-size: 2rem; color: #ddd;"></i>
                    <p class="text-muted mt-2">Aucun colis trouvé avec ces critères</p>
                </td>
            `;
            tbody.appendChild(noResultsRow);
        }
    }
    
    // Fonction pour réinitialiser les filtres
    window.reinitialiserFiltres = function() {
        searchInput.value = '';
        dateFilter.value = '';
        receptionParFilter.value = '';
        filtrerTable();
    };
    
    // Fonction pour afficher les notes dans le modal
    window.voirNotes = function(numeroCourrier, notes) {
        document.getElementById('modalColisNumero').textContent = numeroCourrier;
        document.getElementById('modalNotesContent').textContent = notes;
        
        const modal = new bootstrap.Modal(document.getElementById('notesModal'));
        modal.show();
    };
});
</script>

<style>
/* Pagination moderne avec gradient vert */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}

.pagination-wrapper .pagination {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.pagination-wrapper .page-link {
    border: none;
    padding: 12px 16px;
    margin: 0 2px;
    border-radius: 8px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
    min-width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.pagination-wrapper .page-link:hover {
    background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(32, 201, 151, 0.3);
    color: white;
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
    border-color: #007bff;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.pagination-wrapper .page-item.disabled .page-link {
    background: #e9ecef;
    color: #6c757d;
    transform: none;
    box-shadow: none;
}

@media (max-width: 768px) {
    .pagination-wrapper .page-link {
        padding: 10px 12px;
        margin: 0 1px;
        min-width: 38px;
        height: 38px;
        font-size: 0.875rem;
    }
}

/* Styles pour les badges et la table */
.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Animation pour les cartes */
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 25px rgba(0,0,0,0.1);
}

/* Style pour les avatars des réceptionnaires */
.rounded-circle {
    font-weight: bold;
}
</style>
@endsection
