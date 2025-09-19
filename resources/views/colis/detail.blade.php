@extends('layouts.app')

@section('title', 'Détail du Colis - ' . $colis->numero_courrier)

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Détail du Colis</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item">Colis</li>
          <li class="breadcrumb-item" aria-current="page">{{ $colis->numero_courrier }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    
    <!-- En-tête du colis -->
    <div class="card mb-4 bg-gradient-primary text-white">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8">
            <div class="d-flex align-items-center">
              <div class="avtar avtar-xl bg-white bg-opacity-20 me-4">
                <i class="ti ti-package f-36 text-white"></i>
              </div>
              <div>
                <h3 class="text-white mb-1">Colis N° {{ $colis->numero_courrier }}</h3>
                <p class="text-white-50 mb-1">Destination : {{ $colis->destination }}</p>
                <span class="badge bg-light-{{ $colis->statut_color }} fs-6">{{ $colis->statut_livraison_label }}</span>
              </div>
            </div>
          </div>
          <div class="col-md-4 text-end">
            <div class="qr-code-container bg-white p-3 rounded d-inline-block">
              {!! $colis->generateQrCode(120) !!}
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Informations générales -->
      <div class="col-md-6">
        <div class="card mb-4">
          <div class="card-header">
            <h5><i class="ti ti-info-circle me-2"></i>Informations Générales</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <p><strong>Type de colis :</strong></p>
                <p class="text-muted">{{ ucfirst($colis->type_colis) }}</p>
              </div>
              <div class="col-6">
                <p><strong>Agence de réception :</strong></p>
                <p class="text-muted">{{ $colis->agence_reception }}</p>
              </div>
              <div class="col-6">
                <p><strong>Montant :</strong></p>
                <p class="text-muted">{{ number_format($colis->montant, 0, ',', ' ') }} FCFA</p>
              </div>
              <div class="col-6">
                <p><strong>Valeur du colis :</strong></p>
                <p class="text-muted">{{ number_format($colis->valeur_colis, 0, ',', ' ') }} FCFA</p>
              </div>
              <div class="col-12">
                <p><strong>Description :</strong></p>
                <p class="text-muted">{{ $colis->description ?: 'Aucune description' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Expéditeur et Bénéficiaire -->
      <div class="col-md-6">
        <div class="card mb-4">
          <div class="card-header">
            <h5><i class="ti ti-users me-2"></i>Expéditeur & Bénéficiaire</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12 mb-3">
                <div class="border-start border-primary ps-3">
                  <h6 class="text-primary">Expéditeur</h6>
                  <p class="mb-1"><strong>{{ $colis->nom_expediteur }}</strong></p>
                  <p class="text-muted mb-0">📞 {{ $colis->telephone_expediteur }}</p>
                </div>
              </div>
              <div class="col-12">
                <div class="border-start border-success ps-3">
                  <h6 class="text-success">Bénéficiaire</h6>
                  <p class="mb-1"><strong>{{ $colis->nom_beneficiaire }}</strong></p>
                  <p class="text-muted mb-0">📞 {{ $colis->telephone_beneficiaire }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Historique de livraison -->
    <div class="card mb-4">
      <div class="card-header">
        <h5><i class="ti ti-clock-history me-2"></i>Historique de Livraison</h5>
      </div>
      <div class="card-body">
        <div class="timeline">
          
          <!-- Création -->
          <div class="timeline-item">
            <div class="timeline-marker bg-info"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Colis créé</h6>
              <p class="text-muted mb-1">{{ $colis->created_at->format('d/m/Y à H:i') }}</p>
            </div>
          </div>

          <!-- Récupération à la gare -->
          @if($colis->recupere_gare)
          <div class="timeline-item">
            <div class="timeline-marker bg-warning"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Récupéré à la gare</h6>
              <p class="text-muted mb-1">{{ $colis->recupere_le ? $colis->recupere_le->format('d/m/Y à H:i') : 'Date non précisée' }}</p>
              @if($colis->recupere_par_nom)
                <p class="mb-1"><strong>Récupéré par :</strong> {{ $colis->recupere_par_nom }}</p>
                <p class="mb-1"><strong>Téléphone :</strong> {{ $colis->recupere_par_telephone }}</p>
                <p class="mb-1"><strong>CIN :</strong> {{ $colis->recupere_par_cin }}</p>
              @endif
              @if($colis->notes_recuperation)
                <div class="alert alert-light mt-2">
                  <small><strong>Notes :</strong> {{ $colis->notes_recuperation }}</small>
                </div>
              @endif
            </div>
          </div>
          @endif

          <!-- Ramassage -->
          @if($colis->ramasse_le)
          <div class="timeline-item">
            <div class="timeline-marker bg-primary"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Colis ramassé</h6>
              <p class="text-muted mb-1">{{ $colis->ramasse_le->format('d/m/Y à H:i') }}</p>
              @if($colis->livreurRamassage)
                <p class="mb-1"><strong>Ramassé par :</strong> {{ $colis->livreurRamassage->nom_complet }}</p>
              @endif
              @if($colis->notes_ramassage)
                <div class="alert alert-light mt-2">
                  <small><strong>Notes :</strong> {{ $colis->notes_ramassage }}</small>
                </div>
              @endif
            </div>
          </div>
          @endif

          <!-- En transit -->
          @if($colis->statut_livraison == 'en_transit')
          <div class="timeline-item">
            <div class="timeline-marker bg-warning"></div>
            <div class="timeline-content">
              <h6 class="mb-1">En transit</h6>
              <p class="text-muted mb-1">Le colis est actuellement en cours de livraison</p>
            </div>
          </div>
          @endif

          <!-- Livraison -->
          @if($colis->livre_le)
          <div class="timeline-item">
            <div class="timeline-marker bg-success"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Colis livré</h6>
              <p class="text-muted mb-1">{{ $colis->livre_le->format('d/m/Y à H:i') }}</p>
              @if($colis->livreurLivraison)
                <p class="mb-1"><strong>Livré par :</strong> {{ $colis->livreurLivraison->nom_complet }}</p>
              @endif
              @if($colis->notes_livraison)
                <div class="alert alert-light mt-2">
                  <small><strong>Notes :</strong> {{ $colis->notes_livraison }}</small>
                </div>
              @endif
            </div>
          </div>
          @endif

        </div>
      </div>
    </div>

    <!-- Photos de livraison -->
    @if($colis->photo_piece_recto || $colis->photo_piece_verso)
    <div class="card mb-4">
      <div class="card-header">
        <h5><i class="ti ti-camera me-2"></i>Photos des Pièces d'Identité</h5>
      </div>
      <div class="card-body">
        <div class="row">
          @if($colis->photo_piece_recto)
          <div class="col-md-6">
            <div class="card">
              <div class="card-header text-center">
                <h6 class="mb-0">Pièce - Recto</h6>
              </div>
              <div class="card-body text-center">
                <img src="{{ $colis->photo_recto_url }}" class="img-fluid rounded" alt="Pièce Recto" style="max-height: 300px; cursor: pointer;" onclick="showImageModal('{{ $colis->photo_recto_url }}', 'Pièce d\'identité - Recto')">
                <br>
                {{-- <a href="{{ $colis->photo_recto_url }}" download="piece_recto_{{ $colis->numero_courrier }}.jpg" class="btn btn-sm btn-outline-primary mt-2">
                  <i class="ti ti-download"></i> Télécharger
                </a> --}}
              </div>
            </div>
          </div>
          @endif
          
          @if($colis->photo_piece_verso)
          <div class="col-md-6">
            <div class="card">
              <div class="card-header text-center">
                <h6 class="mb-0">Pièce - Verso</h6>
              </div>
              <div class="card-body text-center">
                <img src="{{ $colis->photo_verso_url }}" class="img-fluid rounded" alt="Pièce Verso" style="max-height: 300px; cursor: pointer;" onclick="showImageModal('{{ $colis->photo_verso_url }}', 'Pièce d\'identité - Verso')">
                <br>
                {{-- <a href="{{ $colis->photo_verso_url }}" download="piece_verso_{{ $colis->numero_courrier }}.jpg" class="btn btn-sm btn-outline-primary mt-2">
                  <i class="ti ti-download"></i> Télécharger
                </a> --}}
              </div>
            </div>
          </div>
          @endif
        </div>
        
        <div class="alert alert-info mt-3">
          <i class="ti ti-info-circle me-2"></i>
          <strong>Info :</strong> Ces photos ont été prises lors de la livraison du colis pour justifier l'identité du bénéficiaire.
        </div>
      </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="card">
      <div class="card-body">
        <div class="d-flex gap-2">
          <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-2"></i>Retour
          </a>
          
          @can('view_mes_colis')
          <a href="{{ route('livreur.mes-colis') }}" class="btn btn-info">
            <i class="ti ti-user me-2"></i>Mes Colis
          </a>
          @endcan
          
          {{-- @can('view_colis_recuperes')
          <a href="{{ route('livreurs.colis.recuperes') }}" class="btn btn-primary">
            <i class="ti ti-list me-2"></i>Tous les Colis
          </a>
          @endcan
          
          <!-- <button type="button" class="btn btn-success" onclick="window.print()">
            <i class="ti ti-printer me-2"></i>Imprimer
          </button> --}} -->
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Modal pour agrandir les images -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" class="img-fluid" alt="Image agrandie">
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
  position: relative;
  padding-left: 30px;
}

.timeline-item {
  position: relative;
  margin-bottom: 20px;
}

.timeline-item:before {
  content: '';
  position: absolute;
  left: -22px;
  top: 8px;
  bottom: -20px;
  border-left: 2px solid #dee2e6;
}

.timeline-item:last-child:before {
  display: none;
}

.timeline-marker {
  position: absolute;
  left: -30px;
  top: 0;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  border: 2px solid white;
  z-index: 1;
}

.timeline-content {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
  border-left: 4px solid #dee2e6;
}

@media print {
  .card-body .d-flex {
    display: none !important;
  }
}
</style>
@endpush

@push('scripts')
<script>
function showImageModal(imageSrc, title) {
  document.getElementById('modalImage').src = imageSrc;
  document.getElementById('imageModalLabel').textContent = title;
  const modal = new bootstrap.Modal(document.getElementById('imageModal'));
  modal.show();
}
</script>
@endpush
