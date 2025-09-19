@extends('layouts.app')

@section('title', 'Liste des Clients - Gestion des Colis')

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
          <li class="breadcrumb-item"><a href="javascript: void(0)">Clients</a></li>
          <li class="breadcrumb-item" aria-current="page">Liste des Clients</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <!-- Statistiques -->
  <div class="col-md-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-primary">
              <i class="ti ti-users text-primary f-18"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Total Clients</h6>
            <h4 class="mb-0">1,248</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-success">
              <i class="ti ti-user-plus text-success f-18"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Nouveaux ce mois</h6>
            <h4 class="mb-0">156</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-warning">
              <i class="ti ti-star text-warning f-18"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Clients VIP</h6>
            <h4 class="mb-0">89</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-info">
              <i class="ti ti-activity text-info f-18"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Actifs aujourd'hui</h6>
            <h4 class="mb-0">342</h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Liste des clients -->
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5>Liste des Clients</h5>
        <div>
          <button class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="ti ti-upload"></i> Importer
          </button>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">
            <i class="ti ti-plus"></i> Nouveau Client
          </button>
        </div>
      </div>
      <div class="card-body">
        <!-- Filtres -->
        <div class="row mb-3">
          <div class="col-md-3">
            <select class="form-select" id="statusFilter">
              <option value="">Tous les statuts</option>
              <option value="active">Actif</option>
              <option value="inactive">Inactif</option>
              <option value="vip">VIP</option>
              <option value="blacklist">Liste noire</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="cityFilter">
              <option value="">Toutes les villes</option>
              <option value="paris">Paris</option>
              <option value="lyon">Lyon</option>
              <option value="marseille">Marseille</option>
              <option value="toulouse">Toulouse</option>
            </select>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" id="searchFilter" placeholder="Rechercher par nom, email, téléphone...">
          </div>
          <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" type="button">
              <i class="ti ti-filter"></i> Filtrer
            </button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                  </div>
                </th>
                <th>Client</th>
                <th>Contact</th>
                <th>Localisation</th>
                <th>Colis Envoyés</th>
                <th>Dépenses Totales</th>
                <th>Dernière Activité</th>
                <th>Statut</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody id="clientsTable">
              <tr>
                <td>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox">
                  </div>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                      <div class="avtar avtar-s">
                        <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="client" class="img-radius">
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h6 class="mb-0">Jean Dupont</h6>
                      <p class="text-muted mb-0">Client depuis 2 ans</p>
                    </div>
                  </div>
                </td>
                <td>
                  <p class="mb-1">jean.dupont@email.com</p>
                  <p class="text-muted mb-0">+33 1 23 45 67 89</p>
                </td>
                <td>
                  <p class="mb-0">Paris, France</p>
                  <p class="text-muted mb-0">75001</p>
                </td>
                <td>
                  <h6 class="mb-0">127</h6>
                  <p class="text-muted mb-0">colis</p>
                </td>
                <td>
                  <h6 class="text-success mb-0">€3,456.78</h6>
                  <p class="text-muted mb-0">Total</p>
                </td>
                <td>
                  <p class="mb-0">Il y a 2 heures</p>
                  <p class="text-muted mb-0">Nouveau colis</p>
                </td>
                <td>
                  <span class="badge bg-light-warning border border-warning">
                    <i class="ti ti-star me-1"></i>VIP
                  </span>
                </td>
                <td class="text-end">
                  <ul class="list-inline me-auto mb-0">
                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Voir profil">
                      <a href="#" class="avtar avtar-xs btn-link-secondary">
                        <i class="ti ti-eye f-18"></i>
                      </a>
                    </li>
                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Modifier">
                      <a href="#" class="avtar avtar-xs btn-link-success">
                        <i class="ti ti-edit f-18"></i>
                      </a>
                    </li>
                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Supprimer">
                      <a href="#" class="avtar avtar-xs btn-link-danger">
                        <i class="ti ti-trash f-18"></i>
                      </a>
                    </li>
                  </ul>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox">
                  </div>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                      <div class="avtar avtar-s">
                        <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="client" class="img-radius">
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h6 class="mb-0">Sophie Martin</h6>
                      <p class="text-muted mb-0">Client depuis 1 an</p>
                    </div>
                  </div>
                </td>
                <td>
                  <p class="mb-1">sophie.martin@email.com</p>
                  <p class="text-muted mb-0">+33 2 34 56 78 90</p>
                </td>
                <td>
                  <p class="mb-0">Lyon, France</p>
                  <p class="text-muted mb-0">69000</p>
                </td>
                <td>
                  <h6 class="mb-0">45</h6>
                  <p class="text-muted mb-0">colis</p>
                </td>
                <td>
                  <h6 class="text-success mb-0">€1,234.56</h6>
                  <p class="text-muted mb-0">Total</p>
                </td>
                <td>
                  <p class="mb-0">Hier</p>
                  <p class="text-muted mb-0">Suivi colis</p>
                </td>
                <td>
                  <span class="badge bg-light-success border border-success">
                    <i class="ti ti-check me-1"></i>Actif
                  </span>
                </td>
                <td class="text-end">
                  <ul class="list-inline me-auto mb-0">
                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Voir profil">
                      <a href="#" class="avtar avtar-xs btn-link-secondary">
                        <i class="ti ti-eye f-18"></i>
                      </a>
                    </li>
                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Modifier">
                      <a href="#" class="avtar avtar-xs btn-link-success">
                        <i class="ti ti-edit f-18"></i>
                      </a>
                    </li>
                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Supprimer">
                      <a href="#" class="avtar avtar-xs btn-link-danger">
                        <i class="ti ti-trash f-18"></i>
                      </a>
                    </li>
                  </ul>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox">
                  </div>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                      <div class="avtar avtar-s">
                        <img src="{{ asset('assets/images/user/avatar-3.jpg') }}" alt="client" class="img-radius">
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h6 class="mb-0">Pierre Bernard</h6>
                      <p class="text-muted mb-0">Client depuis 6 mois</p>
                    </div>
                  </div>
                </td>
                <td>
                  <p class="mb-1">pierre.bernard@email.com</p>
                  <p class="text-muted mb-0">+33 3 45 67 89 01</p>
                </td>
                <td>
                  <p class="mb-0">Marseille, France</p>
                  <p class="text-muted mb-0">13000</p>
                </td>
                <td>
                  <h6 class="mb-0">23</h6>
                  <p class="text-muted mb-0">colis</p>
                </td>
                <td>
                  <h6 class="text-success mb-0">€789.12</h6>
                  <p class="text-muted mb-0">Total</p>
                </td>
                <td>
                  <p class="mb-0">Il y a 3 jours</p>
                  <p class="text-muted mb-0">Réclamation</p>
                </td>
                <td>
                  <span class="badge bg-light-secondary border border-secondary">
                    <i class="ti ti-clock me-1"></i>Inactif
                  </span>
                </td>
                <td class="text-end">
                  <ul class="list-inline me-auto mb-0">
                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Voir profil">
                      <a href="#" class="avtar avtar-xs btn-link-secondary">
                        <i class="ti ti-eye f-18"></i>
                      </a>
                    </li>
                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Modifier">
                      <a href="#" class="avtar avtar-xs btn-link-success">
                        <i class="ti ti-edit f-18"></i>
                      </a>
                    </li>
                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Supprimer">
                      <a href="#" class="avtar avtar-xs btn-link-danger">
                        <i class="ti ti-trash f-18"></i>
                      </a>
                    </li>
                  </ul>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex align-items-center justify-content-between mt-3">
          <div>
            <p class="text-muted mb-0">Affichage de 1 à 3 sur 1,248 clients</p>
          </div>
          <nav>
            <ul class="pagination mb-0">
              <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Précédent</a>
              </li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#">Suivant</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Ajouter Client -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addClientModalLabel">Ajouter un Nouveau Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addClientForm">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="client_name">Nom Complet <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="client_name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="client_email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="client_email" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="client_phone">Téléphone <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="client_phone" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="client_city">Ville</label>
                <input type="text" class="form-control" id="client_city">
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="client_address">Adresse</label>
            <textarea class="form-control" id="client_address" rows="2"></textarea>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="client_status">Statut</label>
                <select class="form-select" id="client_status">
                  <option value="active">Actif</option>
                  <option value="inactive">Inactif</option>
                  <option value="vip">VIP</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="client_type">Type de Client</label>
                <select class="form-select" id="client_type">
                  <option value="individual">Particulier</option>
                  <option value="business">Entreprise</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="saveClient">Ajouter Client</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importModalLabel">Importer des Clients</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Fichier CSV/Excel</label>
          <input type="file" class="form-control" accept=".csv,.xlsx,.xls">
          <div class="form-text">Formats supportés: CSV, Excel (.xlsx, .xls)</div>
        </div>
        <div class="alert alert-info">
          <i class="ti ti-info-circle me-2"></i>
          Le fichier doit contenir les colonnes: Nom, Email, Téléphone, Ville, Adresse
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary">Importer</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Select all functionality
  document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('#clientsTable input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      checkbox.checked = this.checked;
    });
  });

  // Search functionality
  document.getElementById('searchFilter').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#clientsTable tr');
    
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
  });

  // Save client
  document.getElementById('saveClient').addEventListener('click', function() {
    const form = document.getElementById('addClientForm');
    const formData = new FormData(form);
    
    // Basic validation
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        field.classList.add('is-invalid');
        isValid = false;
      } else {
        field.classList.remove('is-invalid');
      }
    });
    
    if (isValid) {
      // Here you would typically send the data to the server
      alert('Client ajouté avec succès!');
      bootstrap.Modal.getInstance(document.getElementById('addClientModal')).hide();
      form.reset();
    }
  });
});
</script>
@endpush
