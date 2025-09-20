@extends('layouts.app')

@section('title', 'Colis Ramassés - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Colis Ramassés</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.colis.list') }}">Gestion des Colis</a></li>
          <li class="breadcrumb-item" aria-current="page">Colis Ramassés</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
      <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h5>Colis Ramassés</h5>
            <small class="text-muted">Colis qui ont été ramassés par les livreurs</small>
          </div>
          <div>
            <a href="{{ route('application.colis.list') }}" class="btn btn-outline-secondary me-2">
              <i class="ti ti-arrow-left me-2"></i>Colis En Cours
            </a>
            <a href="{{ route('application.colis.list-all') }}" class="btn btn-outline-secondary me-2">
              <i class="ti ti-eye me-2"></i>Voir Tous
            </a>
            <a href="{{ route('application.colis.add') }}" class="btn btn-primary">
              <i class="ti ti-plus me-2"></i>Nouveau Colis
            </a>
          </div>
        </div>
      </div>
      <div class="card-body">
        @if($colis->count() > 0)
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="table-light">
              <tr>
                <th>N° COURRIER</th>
                <th>EXPÉDITEUR</th>
                <th>BÉNÉFICIAIRE</th>
                <th>DESTINATION</th>
                <th>MONTANT</th>
                <th>LIVREUR</th>
                <th>RAMASSÉ LE</th>
                <th>QR CODE</th>
                <th class="text-end">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @foreach($colis as $item)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <span class="badge bg-light-warning border border-warning text-warning fw-bold">
                      {{ $item->numero_courrier }}
                    </span>
                  </div>
                </td>
                <td>
                  <h6 class="mb-1">{{ $item->nom_expediteur }}</h6>
                  <small class="text-muted">{{ $item->telephone_expediteur }}</small>
                </td>
                <td>
                  <h6 class="mb-1">{{ $item->nom_beneficiaire }}</h6>
                  <small class="text-muted">{{ $item->telephone_beneficiaire }}</small>
                </td>
                <td>
                  <span class="badge bg-light-info border border-info">
                    {{ $item->destination }}
                  </span>
                </td>
                <td>
                  <h6 class="text-success mb-1">{{ number_format($item->montant, 0, ',', ' ') }} FCFA</h6>
                </td>
                <td>
                  @if($item->livreurRamassage)
                  <div class="d-flex align-items-center">
                    <div class="avtar avtar-s bg-light-warning me-2">
                      <i class="ti ti-user text-warning"></i>
                    </div>
                    <div>
                      <h6 class="mb-0">{{ $item->livreurRamassage->nom }} {{ $item->livreurRamassage->prenom }}</h6>
                      <small class="text-muted">{{ $item->livreurRamassage->telephone }}</small>
                    </div>
                  </div>
                  @else
                  <span class="text-muted">Non défini</span>
                  @endif
                </td>
                <td>
                  @if($item->ramasse_le)
                  <div>
                    <h6 class="mb-0">{{ $item->ramasse_le->format('d/m/Y') }}</h6>
                    <small class="text-muted">{{ $item->ramasse_le->format('H:i') }}</small>
                  </div>
                  @else
                  <span class="text-muted">-</span>
                  @endif
                </td>
                <td class="text-center">
                  <div class="d-flex flex-column align-items-center">
                    <div class="qr-code-container mb-2" style="width: 80px; height: 80px;">
                      {!! $item->generateQrCode(80) !!}
                    </div>
                    <small class="text-muted">{{ $item->qr_code }}</small>
                  </div>
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <a href="{{ route('application.colis.show', $item->id) }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Voir détails">
                      <i class="ti ti-eye"></i>
                    </a>
                    @if($item->recupere_gare)
                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled data-bs-toggle="tooltip" title="Modification impossible - Colis déjà récupéré">
                      <i class="ti ti-edit"></i>
                    </button>
                    @else
                    <a href="{{ route('application.colis.edit', $item->id) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Modifier">
                      <i class="ti ti-edit"></i>
                    </a>
                    @endif
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
          {{ $colis->links() }}
        </div>
        @else
        <div class="empty-state py-5 text-center">
          <div class="empty-state-icon">
            <i class="ti ti-package-off" style="font-size: 4rem; color: #6c757d;"></i>
          </div>
          <h4 class="mt-3">Aucun colis ramassé</h4>
          <p class="text-muted">Il n'y a actuellement aucun colis qui a été ramassé par les livreurs.</p>
          <div class="mt-4">
            <a href="{{ route('application.colis.list') }}" class="btn btn-primary">
              <i class="ti ti-arrow-left me-2"></i>Voir les Colis En Cours
            </a>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmation pour suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">
          <i class="ti ti-alert-circle me-2"></i>Confirmer la suppression
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Êtes-vous sûr de vouloir supprimer ce colis ? Cette action est irréversible.</p>
        <div class="alert alert-warning">
          <i class="ti ti-alert-triangle me-2"></i>
          <strong>Attention :</strong> Toutes les données liées à ce colis seront définitivement perdues.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x me-2"></i>Annuler
        </button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
          <i class="ti ti-trash me-2"></i>Supprimer
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Formulaire caché pour suppression -->
<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
let deleteUrl = '';

function deleteColis(id) {
  deleteUrl = `/application/colis/${id}`;
  const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
  deleteModal.show();
}

document.getElementById('confirmDelete').addEventListener('click', function() {
  if (deleteUrl) {
    const form = document.getElementById('deleteForm');
    form.action = deleteUrl;
    form.submit();
  }
});

// Auto-hide alerts after 5 seconds
setTimeout(function() {
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(function(alert) {
    if (alert.querySelector('.btn-close')) {
      alert.style.transition = 'opacity 0.5s';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }
  });
}, 5000);

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>
@endpush
