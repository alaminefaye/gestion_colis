@extends('layouts.app')

@section('title', 'Mes Colis')

@push('styles')
<style>
.stat-card {
  border-radius: 15px;
  border: none;
  box-shadow: 0 8px 25px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
}
.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(0,0,0,0.12);
}
.filter-card {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 15px;
  border: none;
}
.colis-card {
  border-radius: 12px;
  border: 1px solid #e9ecef;
  transition: all 0.3s ease;
  cursor: pointer;
}
.colis-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  border-color: #007bff;
}
.status-badge {
  padding: 8px 16px;
  border-radius: 25px;
  font-weight: 600;
  font-size: 0.85rem;
}
.gradient-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 15px;
}

/* Pagination moderne */
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

@section('content')

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    
<!-- Header moderne -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card stat-card gradient-header border-0">
      <div class="card-body py-4">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="me-4">
              <div class="d-flex align-items-center justify-content-center" 
                   style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%;">
                <i class="ti ti-package-export text-white" style="font-size: 24px;"></i>
              </div>
            </div>
            <div>
              <h3 class="text-white mb-1">Mes Colis</h3>
              <p class="text-white-75 mb-0">{{ $livreur->nom_complet }} • {{ $colis->total() }} colis 
                @if($periode != 'tout')
                  @switch($periode)
                    @case('aujourd_hui') aujourd'hui @break
                    @case('cette_semaine') cette semaine @break
                    @case('ce_mois') ce mois @break
                  @endswitch
                @else
                  au total
                @endif
              </p>
            </div>
          </div>
          <div class="text-end">
            <a href="{{ route('livreur.dashboard') }}" class="btn btn-light btn-sm">
              <i class="ti ti-arrow-left me-1"></i>Retour Dashboard
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Statistiques modernes -->
<div class="row mb-4">
  <div class="col-lg-3 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #ff6b6b, #ff8e8e); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-package text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['total_ramasse'] }}</h3>
        <p class="text-muted mb-0 fw-500">Colis Ramassés</p>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #51cf66, #69db7c); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-check-circle text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['total_livre'] }}</h3>
        <p class="text-muted mb-0 fw-500">Colis Livrés</p>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #74c0fc, #91d7ff); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-clock text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['en_cours'] }}</h3>
        <p class="text-muted mb-0 fw-500">En Cours</p>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #845ec2, #9775fa); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-truck text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['en_transit'] }}</h3>
        <p class="text-muted mb-0 fw-500">En Transit</p>
      </div>
    </div>
  </div>
</div>

<!-- Filtres par période -->
<div class="card filter-card stat-card mb-4 border-0">
  <div class="card-body">
    <div class="d-flex align-items-center mb-3">
      <i class="ti ti-calendar me-2 text-primary"></i>
      <h6 class="mb-0 fw-bold">Filtrer par période</h6>
      @if($periode != 'tout')
        <span class="badge bg-primary ms-2">
          @switch($periode)
            @case('aujourd_hui') 📅 Aujourd'hui @break
            @case('cette_semaine') 📅 Cette semaine @break
            @case('ce_mois') 📅 Ce mois @break
          @endswitch
        </span>
      @endif
    </div>
    
    <!-- Boutons de période -->
    <div class="row mb-3">
      <div class="col-12">
        <div class="btn-group w-100" role="group">
          <a href="{{ route('livreur.mes-colis', array_merge(request()->except('periode'), ['periode' => 'aujourd_hui'])) }}" 
             class="btn {{ $periode == 'aujourd_hui' ? 'btn-primary' : 'btn-outline-primary' }} flex-fill">
            📅 Aujourd'hui
          </a>
          <a href="{{ route('livreur.mes-colis', array_merge(request()->except('periode'), ['periode' => 'cette_semaine'])) }}" 
             class="btn {{ $periode == 'cette_semaine' ? 'btn-primary' : 'btn-outline-primary' }} flex-fill">
            📊 Cette semaine
          </a>
          <a href="{{ route('livreur.mes-colis', array_merge(request()->except('periode'), ['periode' => 'ce_mois'])) }}" 
             class="btn {{ $periode == 'ce_mois' ? 'btn-primary' : 'btn-outline-primary' }} flex-fill">
            🗓️ Ce mois
          </a>
          <a href="{{ route('livreur.mes-colis', array_merge(request()->except('periode'), ['periode' => 'tout'])) }}" 
             class="btn {{ $periode == 'tout' ? 'btn-primary' : 'btn-outline-primary' }} flex-fill">
            🔍 Tout
          </a>
        </div>
      </div>
    </div>

    <!-- Filtres avancés -->
    <form method="GET" id="filterForm">
      <input type="hidden" name="periode" value="{{ $periode }}">
      <div class="row g-3">
        <div class="col-lg-3 col-md-4">
          <label class="form-label fw-500">Statut</label>
          <select class="form-select" name="statut" onchange="document.getElementById('filterForm').submit()">
            <option value="">🔍 Tous les statuts</option>
            <option value="ramasse" {{ request('statut') == 'ramasse' ? 'selected' : '' }}>📦 Ramassé par moi</option>
            <option value="en_transit" {{ request('statut') == 'en_transit' ? 'selected' : '' }}>🚚 En Transit</option>
            <option value="livre" {{ request('statut') == 'livre' ? 'selected' : '' }}>✅ Livré par moi</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-4">
          <label class="form-label fw-500">Date du</label>
          <input type="date" class="form-control" name="date_du" value="{{ request('date_du') }}" onchange="document.getElementById('filterForm').submit()">
        </div>
        <div class="col-lg-3 col-md-4">
          <label class="form-label fw-500">Date au</label>
          <input type="date" class="form-control" name="date_au" value="{{ request('date_au') }}" onchange="document.getElementById('filterForm').submit()">
        </div>
        <div class="col-lg-3 col-md-12 d-flex align-items-end">
          <a href="{{ route('livreur.mes-colis') }}" class="btn btn-outline-secondary w-100">
            <i class="ti ti-refresh me-1"></i>Réinitialiser
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Liste moderne des colis -->
@if($colis->count() > 0)
  <div class="row">
    @foreach($colis as $col)
    <div class="col-lg-6 col-xl-4 mb-4">
      <div class="card colis-card h-100">
        <div class="card-body">
          <!-- En-tête avec numéro et statut -->
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
              <div class="me-3">
                <div class="d-flex align-items-center justify-content-center" 
                     style="width: 45px; height: 45px; background: linear-gradient(135deg, #007bff, #0056b3); border-radius: 50%;">
                  <i class="ti ti-package text-white" style="font-size: 16px;"></i>
                </div>
              </div>
              <div>
                <h6 class="mb-0 fw-bold text-primary">{{ $col->numero_courrier }}</h6>
                <small class="text-muted">{{ $col->destination }}</small>
              </div>
            </div>
            <span class="status-badge bg-{{ $col->statut_color }} text-white">
              {{ $col->statut_livraison_label }}
            </span>
          </div>

          <!-- Informations expéditeur/bénéficiaire -->
          <div class="row mb-3">
            <div class="col-6">
              <div class="border-end pe-2">
                <small class="text-muted fw-500">📤 Expéditeur</small>
                <p class="mb-0 fw-500" style="font-size: 0.9rem;">{{ $col->nom_expediteur }}</p>
                <small class="text-muted">{{ $col->telephone_expediteur }}</small>
              </div>
            </div>
            <div class="col-6">
              <div class="ps-2">
                <small class="text-muted fw-500">📥 Bénéficiaire</small>
                <p class="mb-0 fw-500" style="font-size: 0.9rem;">{{ $col->nom_beneficiaire }}</p>
                <small class="text-muted">{{ $col->telephone_beneficiaire }}</small>
              </div>
            </div>
          </div>

          <!-- Date d'action -->
          <div class="mb-3 p-2 bg-light rounded">
            @if($col->livre_par == $livreur->id && $col->livre_le)
              <small class="fw-500 text-success">✅ Livré le</small>
              <p class="mb-0">{{ $col->livre_le->format('d/m/Y à H:i') }}</p>
            @elseif($col->ramasse_par == $livreur->id && $col->ramasse_le)
              <small class="fw-500 text-warning">📦 Ramassé le</small>
              <p class="mb-0">{{ $col->ramasse_le->format('d/m/Y à H:i') }}</p>
            @else
              <small class="text-muted">Aucune action enregistrée</small>
            @endif
          </div>

          <!-- QR Code et actions -->
          <div class="d-flex align-items-center justify-content-between">
            <div class="text-center">
              <div class="mb-1" style="width: 50px; height: 50px;">
                {!! $col->generateQrCode(50) !!}
              </div>
              <code style="font-size: 0.7rem;">{{ substr($col->qr_code, 0, 8) }}...</code>
            </div>
            <div>
              <a href="{{ route('colis.detail', $col->id) }}" class="btn btn-primary btn-sm">
                <i class="ti ti-eye me-1"></i>Détail
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Pagination moderne -->
  <div class="d-flex justify-content-center mt-4">
    <div class="pagination-wrapper">
      {{ $colis->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
  </div>

@else
  <div class="card stat-card border-0">
    <div class="card-body text-center py-5">
      <div class="mb-4">
        <i class="ti ti-package-off" style="font-size: 4rem; color: #e9ecef;"></i>
      </div>
      <h5 class="text-muted mb-2">Aucun colis trouvé</h5>
      <p class="text-muted mb-4">
        @if(request()->hasAny(['statut', 'date_du', 'date_au']))
          Aucun colis ne correspond aux filtres sélectionnés.
        @elseif($periode == 'aujourd_hui')
          Vous n'avez encore ramassé ou livré aucun colis aujourd'hui.
        @elseif($periode == 'cette_semaine')
          Vous n'avez encore ramassé ou livré aucun colis cette semaine.
        @elseif($periode == 'ce_mois')
          Vous n'avez encore ramassé ou livré aucun colis ce mois.
        @else
          Vous n'avez encore ramassé ou livré aucun colis.
        @endif
      </p>
      <div class="d-flex gap-2 justify-content-center">
        <a href="{{ route('scan.index') }}" class="btn btn-primary">
          <i class="ti ti-qrcode me-1"></i>Scanner QR Code
        </a>
        @if(request()->hasAny(['statut', 'date_du', 'date_au']))
        <a href="{{ route('livreur.mes-colis') }}" class="btn btn-outline-secondary">
          <i class="ti ti-filter-off me-1"></i>Supprimer filtres
        </a>
        @endif
      </div>
    </div>
  </div>
@endif
  </div>
</div>
@endsection
