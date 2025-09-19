@extends('layouts.app')

@section('title', 'Créer un Utilisateur')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Créer un Utilisateur</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
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
    <form action="{{ route('admin.users.store') }}" method="POST" id="userForm">
      @csrf
      <div class="card">
        <div class="card-header">
          <h5>Informations de l'Utilisateur</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="name">Nom complet <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" required 
                       placeholder="Ex: Jean Dupont">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="email">Adresse email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" required 
                       placeholder="Ex: jean.dupont@example.com">
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="password">Mot de passe <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" class="form-control @error('password') is-invalid @enderror" 
                         id="password" name="password" required 
                         placeholder="Minimum 8 caractères">
                  <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="ti ti-eye"></i>
                  </button>
                </div>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="password_confirmation">Confirmer le mot de passe <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" class="form-control" 
                         id="password_confirmation" name="password_confirmation" required 
                         placeholder="Confirmer le mot de passe">
                  <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                    <i class="ti ti-eye"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="role">Rôle <span class="text-danger">*</span></label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                  <option value="">Sélectionner un rôle</option>
                  @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                      {{ ucfirst($role->name) }}
                    </option>
                  @endforeach
                </select>
                @error('role')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Informations du rôle</label>
                <div class="card bg-light border-0" id="roleInfo">
                  <div class="card-body py-2">
                    <small class="text-muted">Sélectionnez un rôle pour voir ses permissions</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="card">
        <div class="card-body text-end">
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="ti ti-arrow-left"></i> Annuler
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-check"></i> Créer l'Utilisateur
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
  // Toggle password visibility
  const togglePassword = document.getElementById('togglePassword');
  const password = document.getElementById('password');
  const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
  const passwordConfirm = document.getElementById('password_confirmation');

  togglePassword.addEventListener('click', function() {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.querySelector('i').className = type === 'password' ? 'ti ti-eye' : 'ti ti-eye-off';
  });

  togglePasswordConfirm.addEventListener('click', function() {
    const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordConfirm.setAttribute('type', type);
    this.querySelector('i').className = type === 'password' ? 'ti ti-eye' : 'ti ti-eye-off';
  });

  // Role information
  const roleSelect = document.getElementById('role');
  const roleInfo = document.getElementById('roleInfo');
  
  const roleDescriptions = {
    'super-admin': 'Accès complet à toutes les fonctionnalités du système. Peut gérer tous les utilisateurs, rôles et permissions.',
    'admin': 'Accès administrateur avec gestion des utilisateurs, colis, clients et paramètres. Ne peut pas supprimer les super-admins.',
    'gestionnaire': 'Peut gérer les colis et clients, consulter les statistiques. Accès limité aux paramètres système.',
    'employe': 'Peut créer des colis et des clients, consulter les données. Accès en lecture seule pour la plupart des fonctions.',
    'client': 'Accès client limité au tableau de bord personnel et à ses propres données.'
  };

  roleSelect.addEventListener('change', function() {
    const selectedRole = this.value;
    if (selectedRole && roleDescriptions[selectedRole]) {
      roleInfo.innerHTML = `
        <div class="card-body py-2">
          <h6 class="mb-1">${selectedRole.charAt(0).toUpperCase() + selectedRole.slice(1)}</h6>
          <small class="text-muted">${roleDescriptions[selectedRole]}</small>
        </div>
      `;
    } else {
      roleInfo.innerHTML = `
        <div class="card-body py-2">
          <small class="text-muted">Sélectionnez un rôle pour voir ses permissions</small>
        </div>
      `;
    }
  });

  // Form validation
  const form = document.getElementById('userForm');
  form.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    
    if (password !== passwordConfirm) {
      e.preventDefault();
      alert('Les mots de passe ne correspondent pas.');
      document.getElementById('password_confirmation').focus();
      return false;
    }
    
    if (password.length < 8) {
      e.preventDefault();
      alert('Le mot de passe doit contenir au moins 8 caractères.');
      document.getElementById('password').focus();
      return false;
    }
  });
});
</script>
@endpush
