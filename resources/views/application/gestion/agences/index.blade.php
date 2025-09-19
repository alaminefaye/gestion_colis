@extends('layouts.app')

@section('title', 'Gestion des Agences - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Gestion des Agences</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="javascript: void(0)">Gestion</a></li>
          <li class="breadcrumb-item" aria-current="page">Agences</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-sm-12">
    <!-- Messages de succès/erreur -->
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

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-circle me-2"></i><strong>Erreurs de validation :</strong>
      <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5>Liste des Agences</h5>
        <div>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAgenceModal">
            <i class="ti ti-plus"></i> Nouvelle Agence
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Nom</th>
                <th>Libellé</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Statut</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($agences as $agence)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                      <div class="avtar avtar-xs bg-light-success">
                        <i class="ti ti-building text-success"></i>
                      </div>
                    </div>
                    <span class="fw-medium">{{ $agence->nom }}</span>
                  </div>
                </td>
                <td>{{ $agence->libelle }}</td>
                <td>{{ $agence->adresse ?: '-' }}</td>
                <td>{{ $agence->telephone ?: '-' }}</td>
                <td>
                  @if($agence->actif)
                    <span class="badge bg-success">Actif</span>
                  @else
                    <span class="badge bg-secondary">Inactif</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm" 
                            onclick="editAgence({{ $agence->id }}, '{{ $agence->nom }}', '{{ $agence->libelle }}', '{{ $agence->adresse }}', '{{ $agence->telephone }}', {{ $agence->actif ? 'true' : 'false' }})"
                            data-bs-toggle="tooltip" title="Modifier">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" 
                            onclick="deleteAgence({{ $agence->id }})"
                            data-bs-toggle="tooltip" title="Supprimer">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-4">
                  <div class="d-flex flex-column align-items-center">
                    <i class="ti ti-building f-48 text-muted mb-3"></i>
                    <h6 class="text-muted">Aucune agence trouvée</h6>
                    <p class="text-muted mb-0">Commencez par ajouter votre première agence</p>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($agences->hasPages())
        <div class="d-flex align-items-center justify-content-between mt-3">
          <div>
            <p class="text-muted mb-0">
              Affichage de {{ $agences->firstItem() }} à {{ $agences->lastItem() }} sur {{ $agences->total() }} entrées
            </p>
          </div>
          <nav>
            {{ $agences->links() }}
          </nav>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal Ajouter Agence -->
<div class="modal fade" id="addAgenceModal" tabindex="-1" aria-labelledby="addAgenceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAgenceModalLabel">Ajouter une Agence</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('application.gestion.agences.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="nom">Nom <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nom" name="nom" required placeholder="Ex: agence_paris_centre">
                <div class="form-text">Nom technique (minuscules, sans espaces)</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="libelle">Libellé <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="libelle" name="libelle" required placeholder="Ex: Agence Paris Centre">
                <div class="form-text">Nom affiché dans les formulaires</div>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="adresse">Adresse</label>
            <textarea class="form-control" id="adresse" name="adresse" rows="2" placeholder="Adresse complète de l'agence"></textarea>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="telephone">Téléphone</label>
                <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Ex: +33 1 23 45 67 89">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3 d-flex align-items-end">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="actif" name="actif" checked>
                  <label class="form-check-label" for="actif">
                    Agence active
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Modifier Agence -->
<div class="modal fade" id="editAgenceModal" tabindex="-1" aria-labelledby="editAgenceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAgenceModalLabel">Modifier l'Agence</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editAgenceForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="edit_nom">Nom <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_nom" name="nom" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="edit_libelle">Libellé <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_libelle" name="libelle" required>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="edit_adresse">Adresse</label>
            <textarea class="form-control" id="edit_adresse" name="adresse" rows="2"></textarea>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="edit_telephone">Téléphone</label>
                <input type="tel" class="form-control" id="edit_telephone" name="telephone">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3 d-flex align-items-end">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="edit_actif" name="actif">
                  <label class="form-check-label" for="edit_actif">
                    Agence active
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Formulaire caché pour les suppressions -->
<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
// Fonction pour modifier une agence
function editAgence(id, nom, libelle, adresse, telephone, actif) {
  document.getElementById('edit_nom').value = nom;
  document.getElementById('edit_libelle').value = libelle;
  document.getElementById('edit_adresse').value = adresse || '';
  document.getElementById('edit_telephone').value = telephone || '';
  document.getElementById('edit_actif').checked = actif;
  document.getElementById('editAgenceForm').action = '/application/gestion/agences/' + id;
  
  var editModal = new bootstrap.Modal(document.getElementById('editAgenceModal'));
  editModal.show();
}

// Fonction pour supprimer une agence
function deleteAgence(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette agence ? Cette action est irréversible.')) {
    const form = document.getElementById('deleteForm');
    form.action = '/application/gestion/agences/' + id;
    form.submit();
  }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>
@endpush
