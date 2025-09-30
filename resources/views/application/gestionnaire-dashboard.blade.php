@extends('layouts.app')

@section('title', 'Mon Tableau de Bord - Gestionnaire')

@push('styles')
<style>
.gradient-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 15px;
}

.stat-card {
  border-radius: 15px;
  border: none;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  overflow: hidden;
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(0,0,0,0.12);
}

.recent-colis-card {
  border-radius: 12px;
  border: 1px solid #e9ecef;
  transition: all 0.3s ease;
  cursor: pointer;
}

.recent-colis-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  border-color: #007bff;
}

.status-badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.8rem;
}
.destination-item {
  background: linear-gradient(135deg, #f8f9fa, #e9ecef);
  border-radius: 10px;
  padding: 10px 15px;
  margin-bottom: 8px;
  transition: all 0.3s ease;
}

.destination-item:hover {
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: white;
}

.progress-circle {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.2rem;
  color: white;
}

.chart-container {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 15px;
  padding: 20px;
}

/* Pagination moderne pour tableau de bord gestionnaire */
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
  padding: 8px 12px;
  border-radius: 8px;
  font-weight: 500;
  font-size: 13px;
  transition: all 0.3s ease;
  text-decoration: none;
  min-width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.pagination-wrapper .page-link:hover {
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
}

.pagination-wrapper .page-item.active .page-link {
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: white;
  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
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
  font-size: 14px;
  padding: 8px 10px;
}
</style>
@endpush

@section('content')

<!-- Header moderne avec filtre de période -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card gradient-header border-0">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="me-4">
              <div class="d-flex align-items-center justify-content-center" 
                   style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%;">
                <i class="ti ti-chart-line text-white" style="font-size: 24px;"></i>
              </div>
            </div>
            <div>
              <h3 class="text-white mb-1">Mon Tableau de Bord</h3>
              <p class="text-white-75 mb-0">Suivi de vos colis et performances • {{ $user->name }}</p>
            </div>
          </div>
          <div class="text-end">
            <div class="d-flex gap-2 align-items-center">
              <!-- Filtre de période -->
              <div class="me-3">
                <select class="form-select form-select-sm text-dark" id="periodeFilter" style="min-width: 150px;">
                  <option value="tout" {{ $periode == 'tout' ? 'selected' : '' }}>📊 Toutes les données</option>
                  <option value="aujourd_hui" {{ $periode == 'aujourd_hui' ? 'selected' : '' }}>📅 Aujourd'hui</option>
                  <option value="cette_semaine" {{ $periode == 'cette_semaine' ? 'selected' : '' }}>📈 Cette semaine</option>
                  <option value="ce_mois" {{ $periode == 'ce_mois' ? 'selected' : '' }}>📋 Ce mois</option>
                </select>
              </div>
              <a href="{{ route('application.ecom-product-add') }}" class="btn btn-light btn-sm">
                <i class="ti ti-plus me-1"></i>Nouveau Colis
              </a>
              <a href="{{ route('application.ecom-product-list') }}" class="btn btn-outline-light btn-sm">
                <i class="ti ti-list me-1"></i>Mes Colis
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Badge de période active -->
@if($periode != 'tout')
<div class="row mb-3">
  <div class="col-12">
    <div class="alert alert-info border-0 rounded-4 d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <i class="ti ti-filter me-2"></i>
        <span>
          <strong>Filtre actif :</strong>
          @if($periode == 'aujourd_hui')
            📅 Données d'aujourd'hui ({{ now()->format('d/m/Y') }})
          @elseif($periode == 'cette_semaine')
            📈 Données de cette semaine ({{ now()->startOfWeek()->format('d/m') }} au {{ now()->endOfWeek()->format('d/m/Y') }})
          @elseif($periode == 'ce_mois')
            📋 Données de ce mois ({{ now()->format('F Y') }})
          @endif
        </span>
      </div>
      <div>
        <span class="badge bg-primary">{{ $stats['total_crees'] }} colis trouvé(s)</span>
      </div>
    </div>
  </div>
</div>
@endif

<!-- Statistiques principales -->
<div class="row mb-4">
  <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #007bff, #74c0fc); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-package text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['total_crees'] }}</h3>
        <p class="text-muted mb-0 fw-500">Total Créés</p>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #dc3545, #ff6b7d); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-clock text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['en_attente'] }}</h3>
        <p class="text-muted mb-0 fw-500">En Attente</p>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #ffc107, #ffda6a); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-package text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['ramasses'] }}</h3>
        <p class="text-muted mb-0 fw-500">Ramassés</p>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #17a2b8, #20c997); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-building text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['receptionnes'] }}</h3>
        <p class="text-muted mb-0 fw-500">Réceptionnés</p>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-check text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['livres'] }}</h3>
        <p class="text-muted mb-0 fw-500">Livrés</p>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #17a2b8, #20c997); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-building text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['recuperes_gare'] }}</h3>
        <p class="text-muted mb-0 fw-500">Récupérés Gare</p>
      </div>
    </div>
  </div>
</div>

<!-- Revenus et Progression -->
<div class="row mb-4">
  <div class="col-lg-8">
    <div class="card stat-card">
      <div class="card-header bg-transparent border-0">
        <h5 class="mb-0"><i class="ti ti-trending-up me-2 text-success"></i>Revenus Générés</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 text-center">
            <div class="mb-3">
              <h3 class="text-primary mb-1">{{ number_format($revenus['periode_actuelle'], 0, ',', ' ') }} FCFA</h3>
              <small class="text-muted">💰 
                @if($periode == 'aujourd_hui')
                  Aujourd'hui
                @elseif($periode == 'cette_semaine')
                  Cette Semaine
                @elseif($periode == 'ce_mois')
                  Ce Mois
                @else
                  Total Généré
                @endif
              </small>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="mb-3">
              <h4 class="text-success mb-1">{{ number_format($revenusGlobaux['ce_mois'], 0, ',', ' ') }} FCFA</h4>
              <small class="text-muted">📅 Ce Mois (global)</small>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="mb-3">
              <h4 class="text-info mb-1">{{ number_format($revenusGlobaux['cette_semaine'], 0, ',', ' ') }} FCFA</h4>
              <small class="text-muted">📊 Cette Semaine (global)</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card stat-card">
      <div class="card-header bg-transparent border-0">
        <h5 class="mb-0"><i class="ti ti-chart-pie me-2 text-primary"></i>Progression</h5>
      </div>
      <div class="card-body text-center">
        <div class="progress-circle mx-auto mb-3" style="background: linear-gradient(135deg, 
          {{ $progresPercentage >= 0 ? '#28a745, #20c997' : '#dc3545, #ff6b7d' }});">
          {{ $progresPercentage >= 0 ? '+' : '' }}{{ $progresPercentage }}%
        </div>
        <small class="text-muted">vs Mois Dernier</small>
      </div>
    </div>
  </div>
</div>

<!-- Destinations Populaires et Colis Récents -->
<div class="row">
  <div class="col-lg-4">
    <div class="card stat-card">
      <div class="card-header bg-transparent border-0">
        <h5 class="mb-0"><i class="ti ti-map-pin me-2 text-warning"></i>Mes Destinations Top</h5>
      </div>
      <div class="card-body">
        @forelse($destinationsTop as $destination)
        <div class="destination-item d-flex justify-content-between align-items-center">
          <div>
            <strong>{{ $destination->destination }}</strong>
          </div>
          <span class="badge bg-primary">{{ $destination->total }}</span>
        </div>
        @empty
        <div class="text-center py-3">
          <i class="ti ti-map-off f-24 text-muted mb-2"></i>
          <p class="text-muted mb-0">Aucune destination encore</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card stat-card">
      <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="ti ti-history me-2 text-info"></i>Mes Colis Récents</h5>
        <a href="{{ route('application.ecom-product-list') }}" class="btn btn-outline-primary btn-sm">
          <i class="ti ti-eye me-1"></i>Voir Tout
        </a>
      </div>
      <div class="card-body">
        @forelse($colisRecents as $colis)
        <div class="recent-colis-card mb-3">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
              <div class="flex-grow-1">
                <div class="d-flex align-items-center mb-2">
                  <div class="me-3">
                    <div class="d-flex align-items-center justify-content-center" 
                         style="width: 35px; height: 35px; background: linear-gradient(135deg, #007bff, #74c0fc); border-radius: 50%;">
                      <i class="ti ti-package text-white" style="font-size: 12px;"></i>
                    </div>
                  </div>
                  <div>
                    <h6 class="mb-0 fw-bold">{{ $colis->numero_courrier }}</h6>
                    <small class="text-muted">{{ $colis->destination }}</small>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <small class="text-muted">📤 Expéditeur:</small>
                    <p class="mb-0 fw-500" style="font-size: 0.85rem;">{{ $colis->nom_expediteur }}</p>
                  </div>
                  <div class="col-6">
                    <small class="text-muted">📥 Bénéficiaire:</small>
                    <p class="mb-0 fw-500" style="font-size: 0.85rem;">{{ $colis->nom_beneficiaire }}</p>
                  </div>
                </div>
              </div>
              <div class="text-end">
                @if($colis->statut_livraison === 'en_attente')
                  <span class="status-badge bg-danger text-white">En Attente</span>
                @elseif($colis->statut_livraison === 'ramasse')
                  <span class="status-badge bg-warning text-white">Ramassé</span>
                @elseif($colis->statut_livraison === 'receptionne')
                  <span class="status-badge bg-info text-white">Réceptionné</span>
                @elseif($colis->statut_livraison === 'en_transit')
                  <span class="status-badge bg-secondary text-white">En Transit</span>
                @elseif($colis->statut_livraison === 'livre')
                  <span class="status-badge bg-success text-white">Livré</span>
                @elseif($colis->recupere_gare)
                  <span class="status-badge bg-primary text-white">Récupéré Gare</span>
                @endif
                <div class="mt-2">
                  <small class="text-muted">{{ $colis->created_at->format('d/m/Y') }}</small><br>
                  <strong class="text-success">{{ number_format($colis->montant, 0, ',', ' ') }} FCFA</strong>
                </div>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="text-center py-4">
          <i class="ti ti-package-off f-48 text-muted mb-3"></i>
          <h6 class="text-muted">Aucun colis créé</h6>
          <p class="text-muted mb-3">Commencez par créer votre premier colis</p>
          <a href="{{ route('application.ecom-product-add') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>Créer un Colis
          </a>
        </div>
        @endforelse
        
        <!-- Pagination des colis récents -->
        @if($colisRecents->hasPages())
        <div class="d-flex justify-content-center mt-3">
          <div class="pagination-wrapper">
            {{ $colisRecents->appends(request()->query())->links('pagination::bootstrap-4') }}
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const periodeFilter = document.getElementById('periodeFilter');
  
  if (periodeFilter) {
    periodeFilter.addEventListener('change', function() {
      const selectedPeriode = this.value;
      const currentUrl = new URL(window.location.href);
      
      if (selectedPeriode === 'tout') {
        currentUrl.searchParams.delete('periode');
      } else {
        currentUrl.searchParams.set('periode', selectedPeriode);
      }
      
      // Afficher un indicateur de chargement
      const body = document.querySelector('body');
      body.style.cursor = 'wait';
      
      // Rediriger vers la nouvelle URL
      window.location.href = currentUrl.toString();
    });
  }
});
</script>
@endpush
