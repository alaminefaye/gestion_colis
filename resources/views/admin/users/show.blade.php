@extends('layouts.app')

@section('title', 'Détails de l\'Utilisateur')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Détails de l'Utilisateur</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
          <li class="breadcrumb-item" aria-current="page">{{ $user->name }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-md-8">
    <!-- Informations de l'utilisateur -->
    <div class="card">
      <div class="card-header">
        <h5>Informations Personnelles</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Nom complet</label>
              <h6>{{ $user->name }}</h6>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Adresse email</label>
              <h6>{{ $user->email }}</h6>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Date de création</label>
              <h6>{{ $user->created_at->format('d/m/Y à H:i') }}</h6>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Dernière mise à jour</label>
              <h6>{{ $user->updated_at->format('d/m/Y à H:i') }}</h6>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Email vérifié</label>
              @if($user->email_verified_at)
                <h6><span class="badge bg-light-success">Vérifié le {{ $user->email_verified_at->format('d/m/Y') }}</span></h6>
              @else
                <h6><span class="badge bg-light-warning">Non vérifié</span></h6>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Statut du compte</label>
              @if($user->created_at->diffInDays() <= 7)
                <h6><span class="badge bg-light-success">Nouveau compte</span></h6>
              @else
                <h6><span class="badge bg-light-primary">Actif</span></h6>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Rôles et permissions -->
    <div class="card">
      <div class="card-header">
        <h5>Rôles et Permissions</h5>
      </div>
      <div class="card-body">
        <div class="mb-4">
          <label class="form-label text-muted">Rôles assignés</label>
          <div>
            @forelse($user->roles as $role)
              <span class="badge bg-light-primary me-2 mb-2">{{ ucfirst($role->name) }}</span>
            @empty
              <span class="text-muted">Aucun rôle assigné</span>
            @endforelse
          </div>
        </div>

        @if($user->roles->count() > 0)
          <div class="mb-3">
            <label class="form-label text-muted">Permissions via les rôles</label>
            @php
              $allPermissions = $user->roles->flatMap->permissions->unique('name');
              $groupedPermissions = $allPermissions->groupBy(function($permission) {
                return explode('_', $permission->name)[1] ?? 'general';
              });
            @endphp
            
            @if($allPermissions->count() > 0)
              <div class="row">
                @foreach($groupedPermissions as $group => $permissions)
                <div class="col-md-6 mb-3">
                  <div class="card border">
                    <div class="card-header bg-light py-2">
                      <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                    </div>
                    <div class="card-body py-2">
                      @foreach($permissions as $permission)
                        <span class="badge bg-light-info me-1 mb-1">{{ $permission->name }}</span>
                      @endforeach
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            @endif
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
        @can('edit_users')
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary w-100 mb-2">
          <i class="ti ti-edit me-2"></i>Modifier cet utilisateur
        </a>
        @endcan
        
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100 mb-2">
          <i class="ti ti-arrow-left me-2"></i>Retour à la liste
        </a>
        
        @can('delete_users')
        @if($user->id !== auth()->id())
        <button type="button" class="btn btn-outline-danger w-100" onclick="deleteUser({{ $user->id }})">
          <i class="ti ti-trash me-2"></i>Supprimer
        </button>
        @endif
        @endcan
      </div>
    </div>

    <!-- Statistiques -->
    <div class="card">
      <div class="card-header">
        <h5>Statistiques</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted">Rôles assignés</span>
            <span class="badge bg-light-primary">{{ $user->roles->count() }}</span>
          </div>
        </div>
        
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted">Permissions totales</span>
            <span class="badge bg-light-info">{{ $user->roles->flatMap->permissions->unique('name')->count() }}</span>
          </div>
        </div>
        
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="text-muted">Ancienneté</span>
            <span class="badge bg-light-secondary">{{ $user->created_at->diffForHumans() }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Profil -->
    <div class="card">
      <div class="card-header">
        <h5>Avatar</h5>
      </div>
      <div class="card-body text-center">
        <div class="avtar avtar-xl bg-light-primary mx-auto mb-3">
          <span class="f-36">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
        </div>
        <h6>{{ $user->name }}</h6>
        <p class="text-muted mb-0">{{ $user->email }}</p>
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
function deleteUser(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')) {
    const form = document.getElementById('deleteForm');
    form.action = '/admin/users/' + id;
    form.submit();
  }
}
</script>
@endpush
