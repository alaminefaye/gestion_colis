@extends('layouts.app')

@section('title', 'Résultat du Scan')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Résultat du Scan</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('scan.index') }}">Scan QR</a></li>
          <li class="breadcrumb-item" aria-current="page">Résultat</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row justify-content-center">
  <div class="col-md-10">
    <!-- Informations du livreur -->
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="avtar avtar-l bg-light-primary me-3">
            <i class="ti ti-user f-24"></i>
          </div>
          <div>
            <h5 class="mb-1">{{ $livreur->nom_complet }}</h5>
            <p class="text-muted mb-0">{{ $livreur->telephone }} | {{ $livreur->email ?? 'Email non renseigné' }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Détails du colis -->
    <div class="card mb-4">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h5>Détails du Colis</h5>
          <span class="badge bg-light-{{ $colis->statut_color }} fs-6">{{ $colis->statut_livraison_label }}</span>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Numéro de courrier</label>
              <h6>{{ $colis->numero_courrier }}</h6>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">QR Code</label>
              <h6><code>{{ $colis->qr_code }}</code></h6>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Destination</label>
              <h6>{{ $colis->destination }}</h6>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Type de colis</label>
              <h6>{{ ucfirst($colis->type_colis) }}</h6>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Expéditeur</label>
              <h6>{{ $colis->nom_expediteur }}</h6>
              <small class="text-muted">{{ $colis->telephone_expediteur }}</small>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Bénéficiaire</label>
              <h6>{{ $colis->nom_beneficiaire }}</h6>
              <small class="text-muted">{{ $colis->telephone_beneficiaire }}</small>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Montant</label>
              <h6>{{ number_format($colis->montant, 0, ',', ' ') }} FCFA</h6>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Agence de réception</label>
              <h6>{{ $colis->agence_reception }}</h6>
            </div>
          </div>
        </div>
        
        @if($colis->description)
        <div class="mb-3">
          <label class="form-label text-muted">Description</label>
          <p>{{ $colis->description }}</p>
        </div>
        @endif
      </div>
    </div>

    <!-- Historique de livraison -->
    @if($colis->statut_livraison !== 'en_attente')
    <div class="card mb-4">
      <div class="card-header">
        <h5>Historique de Livraison</h5>
      </div>
      <div class="card-body">
        <div class="timeline">
          @if($colis->ramasse_le)
          <div class="timeline-item">
            <div class="timeline-marker bg-warning"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Colis ramassé</h6>
              <p class="mb-1">Par {{ $colis->livreurRamassage->nom_complet ?? 'Inconnu' }}</p>
              @if($colis->ramasse_le)
                <small class="text-muted">{{ $colis->ramasse_le->format('d/m/Y à H:i') }}</small>
              @endif
              @if($colis->notes_ramassage)
                <div class="mt-2">
                  <small><strong>Note :</strong> {{ $colis->notes_ramassage }}</small>
                </div>
              @endif
            </div>
          </div>
          @endif
          
          @if($colis->livre_le)
          <div class="timeline-item">
            <div class="timeline-marker bg-success"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Colis livré</h6>
              <p class="mb-1">Par {{ $colis->livreurLivraison->nom_complet ?? 'Inconnu' }}</p>
              @if($colis->livre_le)
                <small class="text-muted">{{ $colis->livre_le->format('d/m/Y à H:i') }}</small>
              @endif
              @if($colis->notes_livraison)
                <div class="mt-2">
                  <small><strong>Note :</strong> {{ $colis->notes_livraison }}</small>
                </div>
              @endif
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
    @endif

    <!-- Actions disponibles -->
    <div class="card">
      <div class="card-header">
        <h5>Actions Disponibles</h5>
      </div>
      <div class="card-body">
        @if($colis->statut_livraison === 'en_attente')
          <!-- Ramasser le colis -->
          <form action="{{ route('scan.ramasser') }}" method="POST" class="mb-3">
            @csrf
            <input type="hidden" name="colis_id" value="{{ $colis->id }}">
            <input type="hidden" name="livreur_id" value="{{ $livreur->id }}">
            
            <div class="mb-3">
              <label class="form-label">Notes de ramassage (optionnel)</label>
              <textarea class="form-control" name="notes_ramassage" rows="2" 
                        placeholder="Ex: Colis en bon état, emballage intact..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-warning btn-lg">
              <i class="ti ti-package me-2"></i>Ramasser ce Colis
            </button>
          </form>
          
        @elseif(in_array($colis->statut_livraison, ['ramasse', 'en_transit']))
          <!-- Mettre en transit -->
          @if($colis->statut_livraison === 'ramasse')
          <form action="{{ route('scan.en-transit') }}" method="POST" class="mb-3">
            @csrf
            <input type="hidden" name="colis_id" value="{{ $colis->id }}">
            <input type="hidden" name="livreur_id" value="{{ $livreur->id }}">
            
            <button type="submit" class="btn btn-info me-2">
              <i class="ti ti-truck me-2"></i>Marquer En Transit
            </button>
          </form>
          @endif
          
          <!-- Livrer le colis -->
          <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalLivraison">
            <i class="ti ti-check me-2"></i>Livrer ce Colis
          </button>
          
        @else
          <!-- Colis déjà livré -->
          <div class="alert alert-success">
            <i class="ti ti-check-circle me-2"></i>
            <strong>Colis déjà livré</strong><br>
            Ce colis a été livré
            @if($colis->livre_le)
              le {{ $colis->livre_le->format('d/m/Y à H:i') }}
            @endif
            par {{ $colis->livreurLivraison->nom_complet ?? 'Inconnu' }}.
          </div>
        @endif
        
        <!-- Nouvelle recherche -->
        <div class="mt-4 pt-3 border-top">
          <a href="{{ route('scan.index') }}" class="btn btn-outline-primary">
            <i class="ti ti-qrcode me-2"></i>Scanner un Autre Colis
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Livraison -->
<div class="modal fade" id="modalLivraison" tabindex="-1" aria-labelledby="modalLivraisonLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLivraisonLabel">
          <i class="ti ti-check me-2"></i>Confirmer la Livraison
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="{{ route('scan.livrer') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="colis_id" value="{{ $colis->id }}">
        <input type="hidden" name="livreur_id" value="{{ $livreur->id }}">
        
        <div class="modal-body">
          <!-- Info du colis -->
          <div class="alert alert-info mb-4">
            <div class="d-flex align-items-center">
              <i class="ti ti-package f-24 me-3"></i>
              <div>
                <h6 class="mb-1">Colis N° {{ $colis->numero_courrier }}</h6>
                <p class="mb-0">Bénéficiaire : <strong>{{ $colis->nom_beneficiaire }}</strong></p>
                <small class="text-muted">Tél : {{ $colis->telephone_beneficiaire }}</small>
              </div>
            </div>
          </div>

          <!-- Notes de livraison -->
          <div class="mb-4">
            <label class="form-label">Notes de livraison (optionnel)</label>
            <textarea class="form-control" name="notes_livraison" rows="3" 
                      placeholder="Ex: Livré au bénéficiaire en main propre, signature obtenue, présence d'un témoin..."></textarea>
          </div>

          <!-- Upload photos pièces d'identité -->
          <div class="row">
            <div class="col-md-6">
              <div class="card border">
                <div class="card-header">
                  <h6 class="mb-0">
                    <i class="ti ti-id me-2"></i>Photo Pièce - Recto (optionnel)
                  </h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <input type="file" class="form-control" name="photo_piece_recto" accept="image/*" id="photoRecto">
                    <small class="text-muted">Formats acceptés : JPG, PNG, WEBP (max 15MB)</small>
                  </div>
                  <div id="previewRecto" class="text-center" style="display: none;">
                    <img id="imgRecto" class="img-fluid rounded" style="max-height: 150px;" alt="Aperçu recto">
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="card border">
                <div class="card-header">
                  <h6 class="mb-0">
                    <i class="ti ti-id me-2"></i>Photo Pièce - Verso (optionnel)
                  </h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <input type="file" class="form-control" name="photo_piece_verso" accept="image/*" id="photoVerso">
                    <small class="text-muted">Formats acceptés : JPG, PNG, WEBP (max 15MB)</small>
                  </div>
                  <div id="previewVerso" class="text-center" style="display: none;">
                    <img id="imgVerso" class="img-fluid rounded" style="max-height: 150px;" alt="Aperçu verso">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="alert alert-warning mt-3">
            <i class="ti ti-info-circle me-2"></i>
            <strong>Note :</strong> Les photos des pièces d'identité sont optionnelles mais recommandées pour justifier la livraison.
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ti ti-x me-2"></i>Annuler
          </button>
          <button type="submit" class="btn btn-success">
            <i class="ti ti-check me-2"></i>Confirmer la Livraison
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Prévisualisation des images
document.getElementById('photoRecto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imgRecto').src = e.target.result;
            document.getElementById('previewRecto').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('photoVerso').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imgVerso').src = e.target.result;
            document.getElementById('previewVerso').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush

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

.timeline-item:not(:last-child)::before {
  content: '';
  position: absolute;
  left: -22px;
  top: 25px;
  height: calc(100% + 10px);
  width: 2px;
  background-color: #e9ecef;
}

.timeline-marker {
  position: absolute;
  left: -28px;
  top: 5px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
}

.timeline-content {
  padding-left: 15px;
}
</style>
@endpush
