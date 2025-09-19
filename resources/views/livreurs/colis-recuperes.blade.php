@extends('layouts.app')

@section('title', 'Colis Récupérés')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Colis Récupérés</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item" aria-current="page">Colis Récupérés</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-md-2">
        <div class="card bg-light-info">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avtar avtar-s bg-info me-3">
                <i class="ti ti-building f-18"></i>
              </div>
              <div>
                <h6 class="mb-0">Récupérés Gare</h6>
                <p class="mb-0">{{ $colis->where('recupere_gare', true)->count() }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="card bg-light-warning">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avtar avtar-s bg-warning me-3">
                <i class="ti ti-package f-18"></i>
              </div>
              <div>
                <h6 class="mb-0">Ramassés</h6>
                <p class="mb-0">{{ $colis->where('statut_livraison', 'ramasse')->count() }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="card bg-light-orange">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avtar avtar-s bg-orange me-3">
                <i class="ti ti-truck f-18"></i>
              </div>
              <div>
                <h6 class="mb-0">En Transit</h6>
                <p class="mb-0">{{ $colis->where('statut_livraison', 'en_transit')->count() }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="card bg-light-success">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avtar avtar-s bg-success me-3">
                <i class="ti ti-check f-18"></i>
              </div>
              <div>
                <h6 class="mb-0">Livrés</h6>
                <p class="mb-0">{{ $colis->where('statut_livraison', 'livre')->count() }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="card bg-light-primary">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="avtar avtar-s bg-primary me-3">
                <i class="ti ti-sum f-18"></i>
              </div>
              <div>
                <h6 class="mb-0">Total</h6>
                <p class="mb-0">{{ $colis->count() }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
      <div class="card-body">
        <form method="GET" id="filterForm">
          <div class="row align-items-end">
            <div class="col-md-4">
              <label class="form-label">Statut</label>
              <select class="form-select" name="statut" onchange="document.getElementById('filterForm').submit()">
                <option value="">Tous les statuts</option>
                <option value="recupere_gare" {{ request('statut') == 'recupere_gare' ? 'selected' : '' }}>Récupéré à la gare</option>
                <option value="ramasse" {{ request('statut') == 'ramasse' ? 'selected' : '' }}>Ramassé par livreur</option>
                <option value="en_transit" {{ request('statut') == 'en_transit' ? 'selected' : '' }}>En Transit</option>
                <option value="livre" {{ request('statut') == 'livre' ? 'selected' : '' }}>Livré</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Livreur</label>
              <select class="form-select" name="livreur" onchange="document.getElementById('filterForm').submit()">
                <option value="">Tous les livreurs</option>
                @foreach(\App\Models\Livreur::all() as $livreur)
                <option value="{{ $livreur->id }}" {{ request('livreur') == $livreur->id ? 'selected' : '' }}>
                  {{ $livreur->nom_complet }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label">Date du</label>
              <input type="date" class="form-control" name="date_du" value="{{ request('date_du') }}" onchange="document.getElementById('filterForm').submit()">
            </div>
            <div class="col-md-2">
              <label class="form-label">Date au</label>
              <input type="date" class="form-control" name="date_au" value="{{ request('date_au') }}" onchange="document.getElementById('filterForm').submit()">
            </div>
            <div class="col-md-2">
              <a href="{{ route('livreurs.colis.recuperes') }}" class="btn btn-outline-secondary w-100">
                <i class="ti ti-refresh"></i> Reset
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Liste des colis -->
    <div class="card">
      <div class="card-header">
        <h5>Liste des Colis Récupérés ({{ $colis->total() }})</h5>
      </div>
      <div class="card-body">
        @if($colis->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>N° Courrier</th>
                  <th>Expéditeur</th>
                  <th>Bénéficiaire</th>
                  <th>Destination</th>
                  <th>Statut</th>
                  <th>Type Récupération</th>
                  <th>Récupéré/Ramassé par</th>
                  <th>Date</th>
                  <th>QR Code</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($colis as $col)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avtar avtar-s bg-light-primary me-2">
                        <i class="ti ti-package f-16"></i>
                      </div>
                      <strong>{{ $col->numero_courrier }}</strong>
                    </div>
                  </td>
                  <td>
                    <div>
                      <h6 class="mb-0">{{ $col->nom_expediteur }}</h6>
                      <small class="text-muted">{{ $col->telephone_expediteur }}</small>
                    </div>
                  </td>
                  <td>
                    <div>
                      <h6 class="mb-0">{{ $col->nom_beneficiaire }}</h6>
                      <small class="text-muted">{{ $col->telephone_beneficiaire }}</small>
                    </div>
                  </td>
                  <td>{{ $col->destination }}</td>
                  <td>
                    @if($col->recupere_gare)
                      <span class="badge bg-light-info">Récupéré à la gare</span>
                    @else
                      <span class="badge bg-light-{{ $col->statut_color }}">
                        {{ $col->statut_livraison_label }}
                      </span>
                    @endif
                  </td>
                  <td>
                    @if($col->recupere_gare)
                      <span class="badge bg-info">
                        <i class="ti ti-building me-1"></i>Gare
                      </span>
                    @else
                      <span class="badge bg-warning">
                        <i class="ti ti-truck me-1"></i>Livreur
                      </span>
                    @endif
                  </td>
                  <td>
                    @if($col->recupere_gare)
                      <div>
                        <h6 class="mb-0">{{ $col->recupere_par_nom }}</h6>
                        <small class="text-muted">{{ $col->recupere_par_telephone }}</small>
                        <br><small class="text-info">CIN: {{ $col->recupere_par_cin }}</small>
                      </div>
                    @elseif($col->livreurRamassage)
                      <div>
                        <h6 class="mb-0">{{ $col->livreurRamassage->nom_complet }}</h6>
                        <small class="text-muted">{{ $col->livreurRamassage->telephone }}</small>
                      </div>
                    @elseif($col->livreurLivraison)
                      <div>
                        <h6 class="mb-0">{{ $col->livreurLivraison->nom_complet }}</h6>
                        <small class="text-muted">{{ $col->livreurLivraison->telephone }}</small>
                      </div>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @if($col->recupere_gare && $col->recupere_le)
                      <div>
                        <small>{{ $col->recupere_le->format('d/m/Y') }}</small><br>
                        <small class="text-muted">{{ $col->recupere_le->format('H:i') }}</small>
                      </div>
                    @elseif($col->livre_le)
                      <div>
                        <small>{{ $col->livre_le->format('d/m/Y') }}</small><br>
                        <small class="text-muted">{{ $col->livre_le->format('H:i') }}</small>
                      </div>
                    @elseif($col->ramasse_le)
                      <div>
                        <small>{{ $col->ramasse_le->format('d/m/Y') }}</small><br>
                        <small class="text-muted">{{ $col->ramasse_le->format('H:i') }}</small>
                      </div>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="d-flex flex-column align-items-center">
                      <div class="qr-code-container mb-2" style="width: 60px; height: 60px;">
                        {!! $col->generateQrCode(60) !!}
                      </div>
                      <code class="small">{{ $col->qr_code }}</code>
                      <button type="button" class="btn btn-outline-primary btn-sm mt-1" onclick="downloadQR('{{ $col->qr_code }}', '{{ $col->numero_courrier }}')">
                        <i class="ti ti-download"></i>
                      </button>
                    </div>
                  </td>
                  <td>
                    <a href="{{ route('colis.detail', $col->id) }}" class="btn btn-info btn-sm">
                      <i class="ti ti-eye me-1"></i>Détail
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div class="d-flex justify-content-center mt-3">
            {{ $colis->appends(request()->query())->links() }}
          </div>
        @else
          <div class="text-center py-5">
            <i class="ti ti-package-off f-48 text-muted mb-3"></i>
            <h6 class="text-muted">Aucun colis récupéré</h6>
            <p class="text-muted">Les colis récupérés par les livreurs apparaîtront ici.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
/* Styles pour les QR codes */
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
</style>
@endpush

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
