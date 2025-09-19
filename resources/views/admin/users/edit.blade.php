@extends('layouts.app')

@section('title', 'Modifier l\'Utilisateur')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Modifier l'Utilisateur</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
          <li class="breadcrumb-item" aria-current="page">Modifier {{ $user->name }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" id="userForm">
      @csrf
      @method('PUT')
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
                       id="name" name="name" value="{{ old('name', $user->name) }}" required 
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
                       id="email" name="email" value="{{ old('email', $user->email) }}" required 
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
                <label class="form-label" for="password">Nouveau mot de passe</label>
                <div class="input-group">
                  <input type="password" class="form-control @error('password') is-invalid @enderror" 
                         id="password" name="password" 
                         placeholder="Laisser vide pour ne pas changer">
                  <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="ti ti-eye"></i>
                  </button>
                </div>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Laisser vide si vous ne voulez pas changer le mot de passe</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="password_confirmation">Confirmer le nouveau mot de passe</label>
                <div class="input-group">
                  <input type="password" class="form-control" 
                         id="password_confirmation" name="password_confirmation" 
                         placeholder="Confirmer le nouveau mot de passe">
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
                    <option value="{{ $role->name }}" 
                            {{ $user->hasRole($role->name) || old('role') == $role->name ? 'selected' : '' }}>
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
                <label class="form-label">Rôle actuel</label>
                <div class="card bg-light border-0">
                  <div class="card-body py-2">
                    @forelse($user->roles as $userRole)
                      <span class="badge bg-light-info">{{ ucfirst($userRole->name) }}</span>
                    @empty
                      <small class="text-muted">Aucun rôle assigné</small>
                    @endforelse
                  </div>
                </div>
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
        </div>
      </div>

      <!-- Actions -->
      <div class="card">
        <div class="card-body text-end">
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="ti ti-arrow-left"></i> Annuler
          </a>
          <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info me-2">
            <i class="ti ti-eye"></i> Voir détails
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-check"></i> Mettre à jour
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

  // Form validation
  const form = document.getElementById('userForm');
  form.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    
    // Valider seulement si un mot de passe est saisi
    if (password || passwordConfirm) {
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
    }
  });
});
</script>
@endpush
