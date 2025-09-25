@extends('layouts.app')

@section('title', 'Données des Livreurs')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header avec gradient coloré -->
        <div class="card border-0 mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
            <div class="card-body text-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-2">📊 Données des Livreurs</h2>
                        <p class="mb-0">Suivi des ramassages et livraisons</p>
                        @if(isset($dates['label']))
                            <small class="badge badge-light mt-2">{{ $dates['label'] }}</small>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="h4 mb-0">{{ count($livreurs) }}</div>
                        <small>Livreurs actifs</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section de recherche -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="recherche_livreur" class="form-label">🔍 Rechercher un livreur</label>
                        <input type="text" class="form-control" id="recherche_livreur" placeholder="Nom, prénom ou téléphone...">
                    </div>
                    <div class="col-md-3">
                        <label for="periode" class="form-label">📅 Période</label>
                        <select class="form-select" id="periode" name="periode">
                            <option value="aujourd_hui" {{ $periode === 'aujourd_hui' ? 'selected' : '' }}>Aujourd'hui</option>
                            <option value="cette_semaine" {{ $periode === 'cette_semaine' ? 'selected' : '' }}>Cette semaine</option>
                            <option value="ce_mois" {{ $periode === 'ce_mois' ? 'selected' : '' }}>Ce mois</option>
                            <option value="cette_annee" {{ $periode === 'cette_annee' ? 'selected' : '' }}>Cette année</option>
                            <option value="tout" {{ $periode === 'tout' ? 'selected' : '' }}>Tout</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="type_action" class="form-label">📦 Type d'action</label>
                        <select class="form-select" id="type_action">
                            <option value="tout">Ramassages + Livraisons</option>
                            <option value="ramassage">Ramassages uniquement</option>
                            <option value="livraison">Livraisons uniquement</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-secondary w-100" id="reset-filters" style="border-radius: 10px;">
                            🔄 Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des statistiques -->
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #667eea20, #667eea10);">
                    <div class="card-body text-center p-4">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-box-open text-white fa-lg"></i>
                            </div>
                        </div>
                        <h3 class="mb-2" style="color: #667eea;">{{ number_format($statistiques['colis_ramasses']) }}</h3>
                        <p class="text-muted mb-0 small">Colis Ramassés</p>
                        <small class="text-muted">{{ $dates['label'] ?? 'Période sélectionnée' }}</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #28a74520, #28a74510);">
                    <div class="card-body text-center p-4">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #20c997); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-truck text-white fa-lg"></i>
                            </div>
                        </div>
                        <h3 class="mb-2" style="color: #28a745;">{{ number_format($statistiques['colis_livres']) }}</h3>
                        <p class="text-muted mb-0 small">Colis Livrés</p>
                        <small class="text-muted">{{ $dates['label'] ?? 'Période sélectionnée' }}</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #ffc10720, #ffc10710);">
                    <div class="card-body text-center p-4">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <div class="stat-icon" style="background: linear-gradient(135deg, #ffc107, #fd7e14); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chart-line text-white fa-lg"></i>
                            </div>
                        </div>
                        <h3 class="mb-2" style="color: #ffc107;">{{ number_format($statistiques['total_activites']) }}</h3>
                        <p class="text-muted mb-0 small">Total Activités</p>
                        <small class="text-muted">Ramassages + Livraisons</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section liste des activités -->
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="activites-table">
                        <thead class="table-light">
                            <tr>
                                <th>📦 Colis</th>
                                <th>👤 Livreur</th>
                                <th>📞 Contact</th>
                                <th>🎯 Action</th>
                                <th>📅 Date/Heure</th>
                                <th>🏢 Destination</th>
                                <th>📋 Statut</th>
                            </tr>
                        </thead>
                        <tbody id="activites-body">
                            @forelse($activites as $colis)
                                @php
                                    $type = $colis->type_action;
                                    $livreur = $type === 'ramassage' ? $colis->livreurRamassage : $colis->livreurLivraison;
                                    $date = $type === 'ramassage' ? $colis->ramasse_le : $colis->livre_le;
                                @endphp
                                
                                <tr class="activite-row" 
                                    data-livreur="{{ strtolower($livreur->nom_complet ?? '') }}"
                                    data-telephone="{{ $livreur->telephone ?? '' }}"
                                    data-type="{{ $type }}">
                                    <td>
                                        <strong class="text-primary">{{ $colis->numero_courrier }}</strong><br>
                                        <small class="text-muted">{{ $colis->nom_expediteur }} → {{ $colis->nom_beneficiaire }}</small>
                                    </td>
                                    <td>
                                        @if($livreur)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    {{ strtoupper(substr($livreur->prenom ?? '', 0, 1) . substr($livreur->nom ?? '', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $livreur->nom_complet }}</div>
                                                    <small class="text-muted">{{ $livreur->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($livreur)
                                            <strong>📱 {{ $livreur->telephone }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($type === 'ramassage')
                                            <span class="badge bg-warning text-dark">📦 Ramassage</span>
                                        @else
                                            <span class="badge bg-success">✅ Livraison</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($date)->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $colis->destination }}</strong><br>
                                        <small class="text-muted">{{ $colis->agence_reception }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'en_attente' => 'secondary',
                                                'ramasse' => 'warning',
                                                'en_transit' => 'info',
                                                'livre' => 'success'
                                            ];
                                            $color = $statusColors[$colis->statut_livraison] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ $colis->statut_livraison_label }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-search fa-3x mb-3"></i><br>
                                            <h5>Aucune activité trouvée</h5>
                                            <p>Aucun ramassage ou livraison pour la période sélectionnée.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section pagination -->
        @if($activites->hasPages())
            <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="text-muted mb-0">
                            Affichage de {{ $activites->firstItem() ?? 0 }} à {{ $activites->lastItem() ?? 0 }} 
                            sur {{ $activites->total() }} activités
                        </p>
                        <small class="text-muted">10 éléments par page</small>
                    </div>
                    <nav>
                        <div class="pagination-wrapper">
                            {{ $activites->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </nav>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 12px;
}

.card {
    border: none !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-select, .form-control {
    border-radius: 10px;
    border: 1px solid #e0e6ed;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-select:focus, .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.table > :not(caption) > * > * {
    border-bottom-width: 1px;
}

.table-hover > tbody > tr:hover > * {
    background-color: rgba(102, 126, 234, 0.05);
}

.activite-row {
    transition: all 0.2s ease;
}

.activite-row.hidden {
    display: none;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    background-color: #f8f9fa !important;
}

/* Statistiques cards */
.stat-icon {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover .stat-icon {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Pagination moderne pour données livreurs */
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
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rechercheInput = document.getElementById('recherche_livreur');
    const periodeSelect = document.getElementById('periode');
    const typeActionSelect = document.getElementById('type_action');
    const resetButton = document.getElementById('reset-filters');
    const activiteRows = document.querySelectorAll('.activite-row');
    
    // Fonction de filtrage en temps réel
    function filtrerActivites() {
        const recherche = rechercheInput.value.toLowerCase().trim();
        const typeAction = typeActionSelect.value;
        
        let visibleCount = 0;
        
        activiteRows.forEach(row => {
            const livreur = row.dataset.livreur;
            const telephone = row.dataset.telephone;
            const type = row.dataset.type;
            
            // Filtre par recherche (nom ou téléphone)
            const matchRecherche = recherche === '' || 
                livreur.includes(recherche) || 
                telephone.includes(recherche);
            
            // Filtre par type d'action
            const matchType = typeAction === 'tout' || type === typeAction;
            
            if (matchRecherche && matchType) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });
        
        // Afficher/masquer le message "Aucun résultat"
        const noResultsRow = document.querySelector('#activites-body tr:last-child');
        if (visibleCount === 0 && activiteRows.length > 0) {
            // Créer un message "Aucun résultat" si nécessaire
            if (!document.querySelector('.no-results-message')) {
                const tbody = document.getElementById('activites-body');
                const noResultsHtml = `
                    <tr class="no-results-message">
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-search fa-2x mb-3"></i><br>
                                <h6>Aucun résultat trouvé</h6>
                                <p class="small">Aucune activité ne correspond à vos critères de recherche.</p>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', noResultsHtml);
            }
            document.querySelector('.no-results-message').style.display = '';
        } else {
            const noResultsMsg = document.querySelector('.no-results-message');
            if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
        }
        
        // Mettre à jour le compteur dans l'en-tête
        updateCounter(visibleCount);
    }
    
    // Mettre à jour le compteur d'activités visibles
    function updateCounter(count) {
        const counterElement = document.querySelector('.text-right .h4');
        if (counterElement) {
            counterElement.textContent = count;
        }
        
        // Mettre à jour aussi le texte de pagination s'il existe
        const paginationInfo = document.querySelector('.pagination-wrapper').closest('.card-body').querySelector('p.text-muted');
        if (paginationInfo && count < activiteRows.length) {
            paginationInfo.innerHTML = `Affichage de ${count} activités filtrées`;
        }
    }
    
    // Événements de filtrage en temps réel
    rechercheInput.addEventListener('input', debounce(filtrerActivites, 300));
    typeActionSelect.addEventListener('change', filtrerActivites);
    
    // Changement de période (rechargement de page pour mettre à jour les statistiques)
    periodeSelect.addEventListener('change', function() {
        // Ajouter un indicateur de chargement
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'loading-overlay';
        loadingOverlay.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        `;
        loadingOverlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        `;
        document.body.appendChild(loadingOverlay);
        
        const url = new URL(window.location);
        url.searchParams.set('periode', this.value);
        window.location.href = url.toString();
    });
    
    // Reset des filtres
    resetButton.addEventListener('click', function() {
        rechercheInput.value = '';
        typeActionSelect.value = 'tout';
        filtrerActivites();
        
        // Reset de la période aussi
        const url = new URL(window.location);
        url.searchParams.delete('periode');
        window.location.href = url.toString();
    });
    
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
    
    // Initialiser le compteur au chargement
    updateCounter(activiteRows.length);
    
    // Surligner les résultats de recherche
    function highlightSearchTerm(text, term) {
        if (!term) return text;
        const regex = new RegExp(`(${term})`, 'gi');
        return text.replace(regex, '<mark class="bg-warning">$1</mark>');
    }
    
    // Ajouter la fonctionnalité de surlignage
    rechercheInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        activiteRows.forEach(row => {
            const livreurCell = row.querySelector('td:nth-child(2)');
            const telephoneCell = row.querySelector('td:nth-child(3)');
            
            if (searchTerm) {
                // Remettre le texte original puis surligner
                const originalLivreur = livreurCell.dataset.original || livreurCell.innerHTML;
                const originalTelephone = telephoneCell.dataset.original || telephoneCell.innerHTML;
                
                if (!livreurCell.dataset.original) {
                    livreurCell.dataset.original = originalLivreur;
                    telephoneCell.dataset.original = originalTelephone;
                }
                
                livreurCell.innerHTML = highlightSearchTerm(originalLivreur, searchTerm);
                telephoneCell.innerHTML = highlightSearchTerm(originalTelephone, searchTerm);
            } else {
                // Remettre le texte original
                if (livreurCell.dataset.original) {
                    livreurCell.innerHTML = livreurCell.dataset.original;
                    telephoneCell.innerHTML = telephoneCell.dataset.original;
                }
            }
        });
    });
});
</script>
@endpush
