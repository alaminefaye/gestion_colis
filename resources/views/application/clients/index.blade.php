@extends('layouts.app')

@section('title', 'Gestion des Clients - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Gestion des Clients</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item" aria-current="page">Clients</li>
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
        <h5>Liste des Clients</h5>
        <div>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">
            <i class="ti ti-plus"></i> Nouveau Client
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="clientsTable">
            <thead>
              <tr>
                <th>Nom Complet</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Colis Envoyés</th>
                <th>Colis Reçus</th>
                <th>Date d'ajout</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($clients as $client)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                      <div class="avtar avtar-xs bg-light-primary">
                        <i class="ti ti-user text-primary"></i>
                      </div>
                    </div>
                    <span class="fw-medium">{{ $client->nom_complet }}</span>
                  </div>
                </td>
                <td>
                  <span class="badge bg-light-info border border-info">{{ $client->telephone }}</span>
                </td>
                <td>{{ $client->email ?: '-' }}</td>
                <td>
                  <span class="badge bg-light-success">{{ $client->colisEnvoyes()->count() }}</span>
                </td>
                <td>
                  <span class="badge bg-light-warning">{{ $client->colisRecus()->count() }}</span>
                </td>
                <td>{{ $client->created_at->format('d/m/Y H:i') }}</td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary btn-sm" 
                            onclick="editClient({{ $client->id }}, '{{ addslashes($client->nom_complet) }}', '{{ $client->telephone }}', '{{ $client->email }}')"
                            data-bs-toggle="tooltip" title="Modifier">
                      <i class="ti ti-edit"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" 
                            onclick="deleteClient({{ $client->id }})"
                            data-bs-toggle="tooltip" title="Supprimer">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center py-4">
                  <div class="d-flex flex-column align-items-center">
                    <i class="ti ti-users f-48 text-muted mb-3"></i>
                    <h6 class="text-muted">Aucun client trouvé</h6>
                    <p class="text-muted mb-0">Les clients seront créés automatiquement lors de l'ajout de colis</p>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($clients->hasPages())
        <div class="d-flex align-items-center justify-content-between mt-3">
          <div>
            <p class="text-muted mb-0">
              Affichage de {{ $clients->firstItem() }} à {{ $clients->lastItem() }} sur {{ $clients->total() }} entrées
            </p>
          </div>
          <nav>
            {{ $clients->links() }}
          </nav>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal Ajouter Client -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addClientModalLabel">Ajouter un Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('application.clients.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" for="nom_complet">Nom Complet <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nom_complet" name="nom_complet" required placeholder="Ex: Jean Dupont">
          </div>
          <div class="mb-3">
            <label class="form-label" for="telephone">Téléphone <span class="text-danger">*</span></label>
            <input type="tel" class="form-control" id="telephone" name="telephone" required placeholder="Ex: +33123456789">
            <div class="form-text">Le téléphone doit être unique</div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Ex: jean.dupont@email.com">
            <div class="form-text">Optionnel</div>
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

<!-- Modal Modifier Client -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editClientModalLabel">Modifier le Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editClientForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" for="edit_nom_complet">Nom Complet <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_nom_complet" name="nom_complet" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="edit_telephone">Téléphone <span class="text-danger">*</span></label>
            <input type="tel" class="form-control" id="edit_telephone" name="telephone" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="edit_email">Email</label>
            <input type="email" class="form-control" id="edit_email" name="email">
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
// Fonction pour modifier un client
function editClient(id, nom_complet, telephone, email) {
  document.getElementById('edit_nom_complet').value = nom_complet;
  document.getElementById('edit_telephone').value = telephone;
  document.getElementById('edit_email').value = email || '';
  document.getElementById('editClientForm').action = '/application/clients/' + id;
  
  var editModal = new bootstrap.Modal(document.getElementById('editClientModal'));
  editModal.show();
}

// Fonction pour supprimer un client
function deleteClient(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce client ? Cette action est irréversible.')) {
    const form = document.getElementById('deleteForm');
    form.action = '/application/clients/' + id;
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
