@extends('layouts.app')

@section('title', 'Créer un Rôle')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Créer un Rôle</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Rôles & Permissions</a></li>
          <li class="breadcrumb-item" aria-current="page">Créer</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    <form action="{{ route('admin.roles.store') }}" method="POST" id="roleForm">
      @csrf
      <div class="card">
        <div class="card-header">
          <h5>Informations du Rôle</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="name">Nom du rôle <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" required 
                       placeholder="Ex: moderateur">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Le nom du rôle doit être unique et en minuscules</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h5>Permissions</h5>
          <small class="text-muted">Sélectionnez les permissions à associer à ce rôle</small>
        </div>
        <div class="card-body">
          @if($permissions->count() > 0)
            <div class="row">
              @foreach($permissions as $group => $groupPermissions)
              <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border">
                  <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                    <div class="form-check">
                      <input class="form-check-input group-checkbox" type="checkbox" id="group_{{ $group }}" data-group="{{ $group }}">
                      <label class="form-check-label" for="group_{{ $group }}">
                        <small>Tout sélectionner</small>
                      </label>
                    </div>
                  </div>
                  <div class="card-body py-2">
                    @foreach($groupPermissions as $permission)
                    <div class="form-check mb-2">
                      <input class="form-check-input permission-checkbox" type="checkbox" 
                             name="permissions[]" value="{{ $permission->name }}" 
                             id="permission_{{ $permission->id }}" data-group="{{ $group }}"
                             {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                      <label class="form-check-label" for="permission_{{ $permission->id }}">
                        {{ $permission->name }}
                      </label>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          @else
            <div class="text-center py-4">
              <i class="ti ti-shield-off f-48 text-muted mb-3"></i>
              <h6 class="text-muted">Aucune permission disponible</h6>
            </div>
          @endif
        </div>
      </div>

      <!-- Actions -->
      <div class="card">
        <div class="card-body text-end">
          <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary me-2">
            <i class="ti ti-arrow-left"></i> Annuler
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-check"></i> Créer le Rôle
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Gestion des cases à cocher de groupe
  document.querySelectorAll('.group-checkbox').forEach(groupCheckbox => {
    groupCheckbox.addEventListener('change', function() {
      const group = this.dataset.group;
      const groupPermissions = document.querySelectorAll(`input[data-group="${group}"].permission-checkbox`);
      
      groupPermissions.forEach(permission => {
        permission.checked = this.checked;
      });
    });
  });

  // Mettre à jour la case de groupe quand les permissions individuelles changent
  document.querySelectorAll('.permission-checkbox').forEach(permissionCheckbox => {
    permissionCheckbox.addEventListener('change', function() {
      const group = this.dataset.group;
      const groupCheckbox = document.querySelector(`#group_${group}`);
      const groupPermissions = document.querySelectorAll(`input[data-group="${group}"].permission-checkbox`);
      const checkedPermissions = document.querySelectorAll(`input[data-group="${group}"].permission-checkbox:checked`);
      
      if (checkedPermissions.length === groupPermissions.length) {
        groupCheckbox.checked = true;
        groupCheckbox.indeterminate = false;
      } else if (checkedPermissions.length > 0) {
        groupCheckbox.checked = false;
        groupCheckbox.indeterminate = true;
      } else {
        groupCheckbox.checked = false;
        groupCheckbox.indeterminate = false;
      }
    });
  });

  // Initialiser l'état des cases de groupe
  document.querySelectorAll('.group-checkbox').forEach(groupCheckbox => {
    const group = groupCheckbox.dataset.group;
    const groupPermissions = document.querySelectorAll(`input[data-group="${group}"].permission-checkbox`);
    const checkedPermissions = document.querySelectorAll(`input[data-group="${group}"].permission-checkbox:checked`);
    
    if (checkedPermissions.length === groupPermissions.length && groupPermissions.length > 0) {
      groupCheckbox.checked = true;
    } else if (checkedPermissions.length > 0) {
      groupCheckbox.indeterminate = true;
    }
  });
});
</script>
@endpush
