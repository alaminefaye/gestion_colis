@extends('layouts.app')

@section('title', 'Colis Récupérés')

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
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  border-radius: 15px;
}
.qr-code-container {
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e9ecef;
  border-radius: 6px;
  background: #fff;
  padding: 3px;
}
.qr-code-container svg {
  width: 100% !important;
  height: 100% !important;
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
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.pagination-wrapper .page-item.active .page-link {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
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
                <i class="ti ti-package-import text-white" style="font-size: 24px;"></i>
              </div>
            </div>
            <div>
              <h3 class="text-white mb-1">Colis Récupérés</h3>
              <p class="text-white-75 mb-0">Suivi des colis récupérés • {{ $colis->total() }} colis au total</p>
            </div>
          </div>
          <div class="text-end">
            <a href="{{ route('dashboard.index') }}" class="btn btn-light btn-sm">
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
  <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #17a2b8, #3dd5f3); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-building text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $colis->where('recupere_gare', true)->count() }}</h3>
        <p class="text-muted mb-0 fw-500">Récupérés Gare</p>
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
        <h3 class="mb-1 fw-bold text-dark">{{ $colis->where('statut_livraison', 'ramasse')->count() }}</h3>
        <p class="text-muted mb-0 fw-500">Ramassés</p>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #fd7e14, #ff922b); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-truck text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $colis->where('statut_livraison', 'en_transit')->count() }}</h3>
        <p class="text-muted mb-0 fw-500">En Transit</p>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #28a745, #51cf66); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-check text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $colis->where('statut_livraison', 'livre')->count() }}</h3>
        <p class="text-muted mb-0 fw-500">Livrés</p>
      </div>
    </div>
  </div>
  <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center">
        <div class="d-flex align-items-center justify-content-center mb-3" 
             style="width: 50px; height: 50px; background: linear-gradient(135deg, #007bff, #74c0fc); border-radius: 50%; margin: 0 auto;">
          <i class="ti ti-sum text-white" style="font-size: 18px;"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $colis->count() }}</h3>
        <p class="text-muted mb-0 fw-500">Total</p>
      </div>
    </div>
  </div>
</div>

<!-- Filtres modernes -->
<div class="card filter-card stat-card mb-4 border-0">
  <div class="card-body">
    <div class="d-flex align-items-center mb-3">
      <i class="ti ti-filter me-2 text-primary"></i>
      <h6 class="mb-0 fw-bold">Filtrer les colis récupérés</h6>
    </div>
    <form method="GET" id="filterForm">
      <div class="row g-3">
        <div class="col-lg-3 col-md-6">
          <label class="form-label fw-500">Statut</label>
          <select class="form-select" name="statut" onchange="document.getElementById('filterForm').submit()">
            <option value="">🔍 Tous les statuts</option>
            <option value="recupere_gare" {{ request('statut') == 'recupere_gare' ? 'selected' : '' }}>🏢 Récupéré à la gare</option>
            <option value="ramasse" {{ request('statut') == 'ramasse' ? 'selected' : '' }}>📦 Ramassé par livreur</option>
            <option value="en_transit" {{ request('statut') == 'en_transit' ? 'selected' : '' }}>🚚 En Transit</option>
            <option value="livre" {{ request('statut') == 'livre' ? 'selected' : '' }}>✅ Livré</option>
          </select>
        </div>
        <div class="col-lg-3 col-md-6">
          <label class="form-label fw-500">Livreur</label>
          <select class="form-select" name="livreur" onchange="document.getElementById('filterForm').submit()">
            <option value="">👥 Tous les livreurs</option>
            @foreach(\App\Models\Livreur::all() as $livreurItem)
            <option value="{{ $livreurItem->id }}" {{ request('livreur') == $livreurItem->id ? 'selected' : '' }}>
              {{ $livreurItem->nom_complet }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-2 col-md-6">
          <label class="form-label fw-500">Date du</label>
          <input type="date" class="form-control" name="date_du" value="{{ request('date_du') }}" onchange="document.getElementById('filterForm').submit()">
        </div>
        <div class="col-lg-2 col-md-6">
          <label class="form-label fw-500">Date au</label>
          <input type="date" class="form-control" name="date_au" value="{{ request('date_au') }}" onchange="document.getElementById('filterForm').submit()">
        </div>
        <div class="col-lg-2 col-md-12 d-flex align-items-end">
          <a href="{{ route('livreurs.colis.recuperes') }}" class="btn btn-outline-secondary w-100">
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
                     style="width: 45px; height: 45px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%;">
                  <i class="ti ti-package-import text-white" style="font-size: 16px;"></i>
                </div>
              </div>
              <div>
                <h6 class="mb-0 fw-bold text-success">{{ $col->numero_courrier }}</h6>
                <small class="text-muted">{{ $col->destination }}</small>
              </div>
            </div>
            @if($col->recupere_gare)
              <span class="status-badge bg-info text-white">
                <i class="ti ti-building me-1"></i>Récupéré Gare
              </span>
            @else
              <span class="status-badge bg-{{ $col->statut_color }} text-white">
                {{ $col->statut_livraison_label }}
              </span>
            @endif
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

          <!-- Information de récupération -->
          <div class="mb-3 p-2 bg-light rounded">
            @if($col->recupere_gare)
              <div class="mb-2">
                <small class="fw-500 text-info">🏢 Récupéré à la gare par</small>
                <p class="mb-1 fw-500">{{ $col->recupere_par_nom }}</p>
                <small class="text-muted">📞 {{ $col->recupere_par_telephone }}</small><br>
                <small class="text-info">🆔 CIN: {{ $col->recupere_par_cin }}</small>
              </div>
              @if($col->recupere_le)
                <small class="fw-500 text-success">📅 Le {{ $col->recupere_le->format('d/m/Y à H:i') }}</small>
              @endif
            @else
              @if($col->livreurRamassage)
                <small class="fw-500 text-warning">🚛 Ramassé par</small>
                <p class="mb-1 fw-500">{{ $col->livreurRamassage->nom_complet }}</p>
                <small class="text-muted">{{ $col->livreurRamassage->telephone }}</small>
                @if($col->ramasse_le)
                  <br><small class="text-success">📅 Le {{ $col->ramasse_le->format('d/m/Y à H:i') }}</small>
                @endif
              @elseif($col->livreurLivraison)
                <small class="fw-500 text-success">✅ Livré par</small>
                <p class="mb-1 fw-500">{{ $col->livreurLivraison->nom_complet }}</p>
                <small class="text-muted">{{ $col->livreurLivraison->telephone }}</small>
                @if($col->livre_le)
                  <br><small class="text-success">📅 Le {{ $col->livre_le->format('d/m/Y à H:i') }}</small>
                @endif
              @else
                <small class="text-muted">Aucune information de récupération</small>
              @endif
            @endif
          </div>

          <!-- QR Code et actions -->
          <div class="d-flex align-items-center justify-content-between">
            <div class="text-center">
              <div class="qr-code-container mb-1" style="width: 50px; height: 50px;">
                {!! $col->generateQrCode(50) !!}
              </div>
              <code style="font-size: 0.7rem;">{{ substr($col->qr_code, 0, 8) }}...</code>
              <br>
              <button type="button" class="btn btn-outline-primary btn-sm mt-1" onclick="downloadQR('{{ $col->qr_code }}', '{{ $col->numero_courrier }}')">
                <i class="ti ti-download"></i>
              </button>
            </div>
            <div>
              <a href="{{ route('colis.detail', $col->id) }}" class="btn btn-success btn-sm">
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
      {{ $colis->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
  </div>

@else
  <div class="card stat-card border-0">
    <div class="card-body text-center py-5">
      <div class="mb-4">
        <i class="ti ti-package-import" style="font-size: 4rem; color: #e9ecef;"></i>
      </div>
      <h5 class="text-muted mb-2">Aucun colis récupéré trouvé</h5>
      <p class="text-muted mb-4">
        @if(request()->hasAny(['statut', 'livreur', 'date_du', 'date_au']))
          Aucun colis ne correspond aux filtres sélectionnés.
        @else
          Aucun colis n'a encore été récupéré dans le système.
        @endif
      </p>
      <div class="d-flex gap-2 justify-content-center">
        <a href="{{ route('dashboard.index') }}" class="btn btn-primary">
          <i class="ti ti-dashboard me-1"></i>Retour Dashboard
        </a>
        @if(request()->hasAny(['statut', 'livreur', 'date_du', 'date_au']))
        <a href="{{ route('livreurs.colis.recuperes') }}" class="btn btn-outline-secondary">
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

@push('scripts')
<script>
// Fonction pour télécharger le QR code
function downloadQR(qrCode, numeroCourrier) {
  // Créer un canvas pour convertir le SVG en image
  const canvas = document.createElement('canvas');
  const ctx = canvas.getContext('2d');
  
  canvas.width = 300;
  canvas.height = 320;
  
  // Fond blanc
  ctx.fillStyle = 'white';
  ctx.fillRect(0, 0, 300, 320);
  
  // Dessiner le QR code (simulation)
  ctx.fillStyle = 'black';
  ctx.font = '14px Arial';
  ctx.textAlign = 'center';
  ctx.fillText('QR Code: ' + qrCode, 150, 30);
  ctx.fillText('Colis: ' + numeroCourrier, 150, 50);
  
  // Rectangle pour simuler le QR code
  ctx.fillRect(75, 70, 150, 150);
  
  // Texte en bas
  ctx.fillText(qrCode, 150, 250);
  ctx.fillText('Scannez pour actions livreur', 150, 270);
  
  canvas.toBlob(function(blob) {
    const link = document.createElement('a');
    link.download = `QR_${numeroCourrier}.png`;
    link.href = URL.createObjectURL(blob);
    link.click();
  });
}
</script>
@endpush
