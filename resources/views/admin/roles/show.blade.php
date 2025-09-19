@extends('layouts.app')

@section('title', 'Détails du Rôle')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Détails du Rôle</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Rôles & Permissions</a></li>
          <li class="breadcrumb-item" aria-current="page">{{ ucfirst($role->name) }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-md-8">
    <!-- Informations du rôle -->
    <div class="card">
      <div class="card-header">
        <h5>Informations du Rôle</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Nom du rôle</label>
              <h6>{{ ucfirst($role->name) }}</h6>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Date de création</label>
              <h6>{{ $role->created_at->format('d/m/Y à H:i') }}</h6>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Nombre d'utilisateurs</label>
              <h6>{{ $role->users->count() }} utilisateur(s)</h6>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Nombre de permissions</label>
              <h6>{{ $role->permissions->count() }} permission(s)</h6>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Permissions du rôle -->
    <div class="card">
      <div class="card-header">
        <h5>Permissions associées</h5>
      </div>
      <div class="card-body">
        @if($role->permissions->count() > 0)
          @php
            $groupedPermissions = $role->permissions->groupBy(function($permission) {
              return explode('_', $permission->name)[1] ?? 'general';
            });
          @endphp
          
          <div class="row">
            @foreach($groupedPermissions as $group => $permissions)
            <div class="col-md-6 mb-3">
              <div class="card border">
                <div class="card-header bg-light py-2">
                  <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                </div>
                <div class="card-body py-2">
                  @foreach($permissions as $permission)
                    <span class="badge bg-light-primary me-1 mb-1">{{ $permission->name }}</span>
                  @endforeach
                </div>
              </div>
            </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-4">
            <i class="ti ti-shield-off f-48 text-muted mb-3"></i>
            <h6 class="text-muted">Aucune permission associée</h6>
            <p class="text-muted">Ce rôle n'a aucune permission assignée.</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <!-- Actions -->
    <div class="card">
      <div class="card-header">
        <h5>Actions</h5>
      </div>
      <div class="card-body">
        @can('edit_roles')
        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary w-100 mb-2">
          <i class="ti ti-edit me-2"></i>Modifier ce rôle
        </a>
        @endcan
        
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary w-100 mb-2">
          <i class="ti ti-arrow-left me-2"></i>Retour à la liste
        </a>
        
        @can('delete_roles')
        @if($role->name !== 'super-admin' && $role->users->count() === 0)
        <button type="button" class="btn btn-outline-danger w-100" onclick="deleteRole({{ $role->id }})">
          <i class="ti ti-trash me-2"></i>Supprimer
        </button>
        @endif
        @endcan
      </div>
    </div>

    <!-- Utilisateurs avec ce rôle -->
    <div class="card">
      <div class="card-header">
        <h5>Utilisateurs ({{ $role->users->count() }})</h5>
      </div>
      <div class="card-body">
        @forelse($role->users as $user)
          <div class="d-flex align-items-center mb-2">
            <div class="flex-shrink-0 me-3">
              <div class="avtar avtar-s bg-light-primary">
                <i class="ti ti-user f-18"></i>
              </div>
            </div>
            <div class="flex-grow-1">
              <h6 class="mb-0">{{ $user->name }}</h6>
              <small class="text-muted">{{ $user->email }}</small>
            </div>
          </div>
        @empty
          <div class="text-center py-3">
            <i class="ti ti-users f-24 text-muted mb-2"></i>
            <p class="text-muted mb-0">Aucun utilisateur</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Formulaire caché pour suppression -->
<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

@push('scripts')
<script>
function deleteRole(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce rôle ? Cette action est irréversible.')) {
    const form = document.getElementById('deleteForm');
    form.action = '/admin/roles/' + id;
    form.submit();
  }
}
</script>
@endpush
