@extends('layouts.app')

@section('title', 'Tableau de Bord - Bagages')

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

.recent-bagage-card {
  border-radius: 12px;
  border: 1px solid #e9ecef;
  transition: all 0.3s ease;
  cursor: pointer;
}

.recent-bagage-card:hover {
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

/* Pagination moderne pour tableau de bord bagages */
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
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="mb-1">🧳 Mes Bagages - Tableau de Bord</h3>
            <p class="mb-0 opacity-75">
              @if($periode == 'aujourd_hui')
                📅 Mes bagages d'aujourd'hui
              @elseif($periode == 'cette_semaine')
                📅 Mes bagages de cette semaine
              @elseif($periode == 'ce_mois')
                📅 Mes bagages de ce mois
              @else
                📅 Tous mes bagages
              @endif
              @if($periode != 'tout')
                • <strong>{{ $stats['total_bagages'] }}</strong> bagages
              @endif
            </p>
          </div>
          <div>
            <select class="form-select text-white" id="periodeFilter" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
              <option value="tout" {{ $periode == 'tout' ? 'selected' : '' }}>📊 Tout</option>
              <option value="aujourd_hui" {{ $periode == 'aujourd_hui' ? 'selected' : '' }}>📅 Aujourd'hui</option>
              <option value="cette_semaine" {{ $periode == 'cette_semaine' ? 'selected' : '' }}>📈 Cette Semaine</option>
              <option value="ce_mois" {{ $periode == 'ce_mois' ? 'selected' : '' }}>📆 Ce Mois</option>
            </select>
          </div>
        </div>
        @if($periode != 'tout')
          <div class="mt-3">
            <span class="badge" style="background: rgba(255,255,255,0.2); color: white;">
              Filtre actif : 
              @if($periode == 'aujourd_hui')
                Aujourd'hui
              @elseif($periode == 'cette_semaine')
                Cette semaine
              @else
                Ce mois
              @endif
            </span>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Statistiques principales -->
<div class="row mb-4">
  <div class="col-md-2">
    <div class="card stat-card">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="d-flex align-items-center justify-content-center" 
               style="width: 60px; height: 60px; background: linear-gradient(135deg, #007bff, #74c0fc); border-radius: 50%; margin: 0 auto;">
            <i class="ti ti-luggage text-white" style="font-size: 24px;"></i>
          </div>
        </div>
        <h3 class="mb-1">{{ $stats['total_bagages'] }}</h3>
        <small class="text-muted">Total Bagages</small>
        @if($progresPercentage != 0)
          <div class="mt-2">
            <span class="badge {{ $progresPercentage > 0 ? 'bg-success' : 'bg-danger' }}">
              {{ $progresPercentage > 0 ? '+' : '' }}{{ $progresPercentage }}%
            </span>
          </div>
        @endif
      </div>
    </div>
  </div>
  
  <div class="col-md-2">
    <div class="card stat-card">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="d-flex align-items-center justify-content-center" 
               style="width: 60px; height: 60px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; margin: 0 auto;">
            <i class="ti ti-ticket text-white" style="font-size: 24px;"></i>
          </div>
        </div>
        <h3 class="mb-1">{{ $stats['avec_ticket'] }}</h3>
        <small class="text-muted">Avec Ticket</small>
        @if($stats['total_bagages'] > 0)
          <div class="mt-1">
            <small class="text-success">{{ round(($stats['avec_ticket'] / $stats['total_bagages']) * 100, 1) }}%</small>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card stat-card">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="d-flex align-items-center justify-content-center" 
               style="width: 60px; height: 60px; background: linear-gradient(135deg, #ffc107, #fd7e14); border-radius: 50%; margin: 0 auto;">
            <i class="ti ti-x text-white" style="font-size: 24px;"></i>
          </div>
        </div>
        <h3 class="mb-1">{{ $stats['sans_ticket'] }}</h3>
        <small class="text-muted">Sans Ticket</small>
        @if($stats['total_bagages'] > 0)
          <div class="mt-1">
            <small class="text-warning">{{ round(($stats['sans_ticket'] / $stats['total_bagages']) * 100, 1) }}%</small>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card stat-card">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="d-flex align-items-center justify-content-center" 
               style="width: 60px; height: 60px; background: linear-gradient(135deg, #17a2b8, #6f42c1); border-radius: 50%; margin: 0 auto;">
            <i class="ti ti-coins text-white" style="font-size: 24px;"></i>
          </div>
        </div>
        <h3 class="mb-1">{{ number_format($stats['valeur_totale'], 0, ',', ' ') }}</h3>
        <small class="text-muted">Valeur Totale (FCFA)</small>
      </div>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card stat-card">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="d-flex align-items-center justify-content-center" 
               style="width: 60px; height: 60px; background: linear-gradient(135deg, #dc3545, #e83e8c); border-radius: 50%; margin: 0 auto;">
            <i class="ti ti-currency-dollar text-white" style="font-size: 24px;"></i>
          </div>
        </div>
        <h3 class="mb-1">{{ number_format($stats['montant_total'], 0, ',', ' ') }}</h3>
        <small class="text-muted">Montant Total (FCFA)</small>
      </div>
    </div>
  </div>

  <div class="col-md-2">
    <div class="card stat-card">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="d-flex align-items-center justify-content-center" 
               style="width: 60px; height: 60px; background: linear-gradient(135deg, #6c757d, #495057); border-radius: 50%; margin: 0 auto;">
            <i class="ti ti-weight text-white" style="font-size: 24px;"></i>
          </div>
        </div>
        <h3 class="mb-1">{{ number_format($stats['poids_total'], 1) }}</h3>
        <small class="text-muted">Poids Total (kg)</small>
      </div>
    </div>
  </div>
</div>

<!-- Contenu principal -->
<div class="row">
  <!-- Bagages récents -->
  <div class="col-lg-8">
    <div class="card stat-card">
      <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="ti ti-history me-2 text-info"></i>Mes Bagages Récents</h5>
        <a href="{{ route('application.bagages.index') }}" class="btn btn-outline-primary btn-sm">
          <i class="ti ti-eye me-1"></i>Voir Tout
        </a>
      </div>
      <div class="card-body">
        @forelse($bagagesRecents as $bagage)
        <div class="recent-bagage-card mb-3">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
              <div class="flex-grow-1">
                <div class="d-flex align-items-center mb-2">
                  <div class="me-3">
                    <div class="d-flex align-items-center justify-content-center" 
                         style="width: 35px; height: 35px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%;">
                      <i class="ti ti-luggage text-white" style="font-size: 12px;"></i>
                    </div>
                  </div>
                  <div>
                    <h6 class="mb-0 fw-bold">{{ $bagage->numero }}</h6>
                    <small class="text-muted">{{ $bagage->destination }}</small>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <small class="text-muted">👤 Propriétaire:</small>
                    <p class="mb-0 fw-500" style="font-size: 0.85rem;">{{ $bagage->nom_famille }} {{ $bagage->prenom }}</p>
                  </div>
                  <div class="col-6">
                    <small class="text-muted">📞 Téléphone:</small>
                    <p class="mb-0 fw-500" style="font-size: 0.85rem;">{{ $bagage->telephone }}</p>
                  </div>
                </div>
              </div>
              <div class="text-end">
                @if($bagage->possede_ticket)
                  <span class="status-badge bg-success text-white">Avec Ticket</span>
                @else
                  <span class="status-badge bg-warning text-white">Sans Ticket</span>
                @endif
                <div class="mt-2">
                  <small class="text-muted">{{ $bagage->created_at->format('d/m/Y') }}</small><br>
                  @if($bagage->valeur)
                    <strong class="text-info">{{ number_format($bagage->valeur, 0, ',', ' ') }} FCFA</strong>
                  @else
                    <small class="text-muted">Valeur non renseignée</small>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="text-center py-4">
          <i class="ti ti-luggage-off f-48 text-muted mb-3"></i>
          <h6 class="text-muted">Aucun bagage enregistré</h6>
          <p class="text-muted mb-3">Commencez par enregistrer votre premier bagage</p>
          <a href="{{ route('application.bagages.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>Ajouter un Bagage
          </a>
        </div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Destinations populaires -->
  <div class="col-lg-4">
    <div class="card stat-card">
      <div class="card-header bg-transparent border-0">
        <h5 class="mb-0"><i class="ti ti-map-pin me-2 text-warning"></i>Mes Destinations Populaires</h5>
      </div>
      <div class="card-body">
        @forelse($destinationsPopulaires as $destination)
        <div class="destination-item d-flex justify-content-between align-items-center">
          <div>
            <strong>{{ $destination->destination }}</strong>
          </div>
          <div class="text-end">
            <span class="badge bg-primary">{{ $destination->total }}</span>
          </div>
        </div>
        @empty
        <div class="text-center py-3">
          <i class="ti ti-map-off f-24 text-muted mb-2"></i>
          <p class="text-muted mb-0">Aucune destination pour cette période</p>
        </div>
        @endforelse
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
