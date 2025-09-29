@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Gestion des Utilisateurs</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="javascript: void(0)">Administration</a></li>
          <li class="breadcrumb-item" aria-current="page">Utilisateurs</li>
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

    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5>Liste des Utilisateurs</h5>
        <div>
          @can('create_users')
          <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Nouveau Utilisateur
          </a>
          @endcan
        </div>
      </div>
      
      <div class="card-body">
        <!-- Section de Recherche -->
        <div class="card border shadow-sm mb-4" style="background: #ffffff;">
          <div class="card-body p-3">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="ti ti-search text-muted"></i>
                  </span>
                  <input type="text" class="form-control border-start-0 ps-0" id="searchUsers" 
                         placeholder="Rechercher par nom ou email...">
                </div>
              </div>
              <div class="col-md-3">
                <select class="form-select" id="roleFilter">
                  <option value="">Tous les rôles</option>
                  @foreach(\Spatie\Permission\Models\Role::all() as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100" type="button" id="clearFilters">
                  <i class="ti ti-refresh me-1"></i>Réinitialiser
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-striped" id="usersTable">
            <thead>
              <tr>
                <th>UTILISATEUR</th>
                <th>EMAIL</th>
                <th>RÔLE(S)</th>
                <th>DATE CRÉATION</th>
                <th>STATUT</th>
                <th class="text-end">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                      <div class="avtar avtar-s bg-light-primary">
                        <i class="ti ti-user f-18"></i>
                      </div>
                    </div>
                    <div>
                      <h6 class="mb-1">{{ $user->name }}</h6>
                      <p class="text-muted mb-0">ID: {{ $user->id }}</p>
                    </div>
                  </div>
                </td>
                <td>
                  <div>
                    <h6 class="mb-1">{{ $user->email }}</h6>
                    @if($user->email_verified_at)
                      <span class="badge bg-light-success">Vérifié</span>
                    @else
                      <span class="badge bg-light-warning">Non vérifié</span>
                    @endif
                  </div>
                </td>
                <td>
                  @forelse($user->roles as $role)
                    <span class="badge bg-light-info me-1">{{ ucfirst($role->name) }}</span>
                  @empty
                    <span class="text-muted">Aucun rôle</span>
                  @endforelse
                </td>
                <td>
                  {{ $user->created_at->format('d/m/Y') }}<br>
                  <span class="text-muted">{{ $user->created_at->format('H:i') }}</span>
                </td>
                <td>
                  @if($user->created_at->diffInDays() <= 7)
                    <span class="badge bg-light-success">Nouveau</span>
                  @else
                    <span class="badge bg-light-primary">Actif</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Voir détails">
                      <i class="ti ti-eye"></i>
                    </a>
                    @can('edit_users')
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Modifier">
                      <i class="ti ti-edit"></i>
                    </a>
                    @endcan
                    @can('delete_users')
                    @if($user->id !== auth()->id())
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Supprimer" onclick="deleteUser({{ $user->id }})">
                      <i class="ti ti-trash"></i>
                    </button>
                    @endif
                    @endcan
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center py-4">
                  <div class="d-flex flex-column align-items-center">
                    <i class="ti ti-users f-48 text-muted mb-3"></i>
                    <h6 class="text-muted">Aucun utilisateur trouvé</h6>
                    <p class="text-muted mb-0">Commencez par créer un utilisateur</p>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="d-flex align-items-center justify-content-between mt-3">
          <div>
            <p class="text-muted mb-0">
              Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} entrées
            </p>
          </div>
          
          <!-- Pagination moderne -->
          <div class="pagination-wrapper">
            <nav aria-label="Navigation">
              <ul class="pagination mb-0">
                {{-- Lien "Précédent" --}}
                @if ($users->onFirstPage())
                  <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                  </li>
                @else
                  <li class="page-item">
                    <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">&laquo;</a>
                  </li>
                @endif

                {{-- Liens de pagination --}}
                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                  @if ($page == $users->currentPage())
                    <li class="page-item active">
                      <span class="page-link">{{ $page }}</span>
                    </li>
                  @else
                    <li class="page-item">
                      <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                  @endif
                @endforeach

                {{-- Lien "Suivant" --}}
                @if ($users->hasMorePages())
                  <li class="page-item">
                    <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">&raquo;</a>
                  </li>
                @else
                  <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                  </li>
                @endif
              </ul>
            </nav>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Formulaire caché pour les suppressions -->
<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

@push('styles')
<style>
/* Style moderne pour la pagination - Thème Gestion Utilisateurs */
.pagination-wrapper .pagination {
  gap: 8px;
}

.pagination-wrapper .page-link {
  border: none;
  background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
  color: white;
  font-weight: 500;
  border-radius: 10px;
  padding: 10px 16px;
  min-width: 44px;
  min-height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
}

.pagination-wrapper .page-link:hover {
  background: linear-gradient(135deg, #3730a3 0%, #6b21a8 100%);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
  color: white;
}

.pagination-wrapper .page-item.active .page-link {
  background: linear-gradient(135deg, #1e1b4b 0%, #581c87 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
}

.pagination-wrapper .page-item.disabled .page-link {
  background: #e5e7eb;
  color: #9ca3af;
  cursor: not-allowed;
  box-shadow: none;
}

.pagination-wrapper .page-item.disabled .page-link:hover {
  background: #e5e7eb;
  transform: none;
  box-shadow: none;
}

/* Responsive */
@media (max-width: 768px) {
  .pagination-wrapper .page-link {
    padding: 8px 12px;
    min-width: 38px;
    min-height: 38px;
    font-size: 14px;
  }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Recherche utilisateurs
  const searchInput = document.getElementById('searchUsers');
  const roleFilter = document.getElementById('roleFilter');
  const clearBtn = document.getElementById('clearFilters');

  function filterUsers() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedRole = roleFilter.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');

    rows.forEach(row => {
      const nameCell = row.querySelector('td:first-child h6');
      const emailCell = row.querySelector('td:nth-child(2) h6');
      const roleCell = row.querySelector('td:nth-child(3)');
      
      if (!nameCell || !emailCell || !roleCell) return;

      const name = nameCell.textContent.toLowerCase();
      const email = emailCell.textContent.toLowerCase();
      const roles = roleCell.textContent.toLowerCase();

      const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
      const matchesRole = !selectedRole || roles.includes(selectedRole);

      row.style.display = matchesSearch && matchesRole ? '' : 'none';
    });
  }

  searchInput.addEventListener('input', filterUsers);
  roleFilter.addEventListener('change', filterUsers);

  clearBtn.addEventListener('click', function() {
    searchInput.value = '';
    roleFilter.value = '';
    filterUsers();
  });
});

// Fonction de suppression
function deleteUser(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
    const form = document.getElementById('deleteForm');
    form.action = '/admin/users/' + id;
    form.submit();
  }
}
</script>
@endpush
