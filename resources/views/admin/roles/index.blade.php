@extends('layouts.app')

@section('title', 'Rôles & Permissions')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Rôles & Permissions</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="javascript: void(0)">Administration</a></li>
          <li class="breadcrumb-item" aria-current="page">Rôles & Permissions</li>
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
        <h5>Liste des Rôles</h5>
        <div>
          @can('create_roles')
          <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Nouveau Rôle
          </a>
          @endcan
        </div>
      </div>
      
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>RÔLE</th>
                <th>UTILISATEURS</th>
                <th>PERMISSIONS</th>
                <th>DATE CRÉATION</th>
                <th class="text-end">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($roles as $role)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                      <div class="avtar avtar-s bg-light-{{ $role->name === 'super-admin' ? 'danger' : ($role->name === 'admin' ? 'warning' : 'primary') }}">
                        <i class="ti ti-shield f-18"></i>
                      </div>
                    </div>
                    <div>
                      <h6 class="mb-1">{{ ucfirst($role->name) }}</h6>
                      <p class="text-muted mb-0">ID: {{ $role->id }}</p>
                    </div>
                  </div>
                </td>
                <td>
                  <div>
                    <h6 class="mb-1">{{ $role->users()->count() }} utilisateur(s)</h6>
                    @if($role->users()->count() > 0)
                      <div class="d-flex -space-x-1 overflow-hidden">
                        @foreach($role->users()->limit(3)->get() as $user)
                          <div class="avtar avtar-xs bg-light-secondary" data-bs-toggle="tooltip" title="{{ $user->name }}">
                            {{ substr($user->name, 0, 1) }}
                          </div>
                        @endforeach
                        @if($role->users()->count() > 3)
                          <div class="avtar avtar-xs bg-light-info">
                            +{{ $role->users()->count() - 3 }}
                          </div>
                        @endif
                      </div>
                    @endif
                  </div>
                </td>
                <td>
                  <div>
                    <h6 class="mb-1">{{ $role->permissions->count() }} permission(s)</h6>
                    <div class="d-flex flex-wrap gap-1">
                      @foreach($role->permissions->take(3) as $permission)
                        <span class="badge bg-light-info">{{ $permission->name }}</span>
                      @endforeach
                      @if($role->permissions->count() > 3)
                        <span class="badge bg-light-secondary">+{{ $role->permissions->count() - 3 }}</span>
                      @endif
                    </div>
                  </div>
                </td>
                <td>
                  {{ $role->created_at->format('d/m/Y') }}<br>
                  <span class="text-muted">{{ $role->created_at->format('H:i') }}</span>
                </td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Voir détails">
                      <i class="ti ti-eye"></i>
                    </a>
                    @can('edit_roles')
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle="tooltip" title="Modifier">
                      <i class="ti ti-edit"></i>
                    </a>
                    @endcan
                    @can('delete_roles')
                    @if($role->name !== 'super-admin')
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Supprimer" onclick="deleteRole({{ $role->id }})">
                      <i class="ti ti-trash"></i>
                    </button>
                    @endif
                    @endcan
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center py-4">
                  <div class="d-flex flex-column align-items-center">
                    <i class="ti ti-shield f-48 text-muted mb-3"></i>
                    <h6 class="text-muted">Aucun rôle trouvé</h6>
                    <p class="text-muted mb-0">Commencez par créer un rôle</p>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if($roles->hasPages())
        <div class="d-flex align-items-center justify-content-between mt-3">
          <div>
            <p class="text-muted mb-0">
              Affichage de {{ $roles->firstItem() }} à {{ $roles->lastItem() }} sur {{ $roles->total() }} entrées
            </p>
          </div>
          <nav>
            {{ $roles->links() }}
          </nav>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Permissions Overview -->
<div class="row mt-4">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-header">
        <h5>Aperçu des Permissions</h5>
      </div>
      <div class="card-body">
        <div class="row">
          @foreach($permissions as $group => $groupPermissions)
          <div class="col-md-6 col-lg-4 mb-3">
            <div class="card border h-100">
              <div class="card-header bg-light py-2">
                <h6 class="mb-0">{{ ucfirst($group) }}</h6>
              </div>
              <div class="card-body py-2">
                @foreach($groupPermissions as $permission)
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <small>{{ $permission->name }}</small>
                    <span class="badge bg-light-secondary">{{ $permission->roles()->count() }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          @endforeach
        </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});

// Fonction de suppression
function deleteRole(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce rôle ? Cette action est irréversible.')) {
    const form = document.getElementById('deleteForm');
    form.action = '/admin/roles/' + id;
    form.submit();
  }
}
</script>
@endpush
