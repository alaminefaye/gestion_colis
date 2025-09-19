@extends('layouts.guest')

@section('title', 'Changement de Mot de Passe Obligatoire')

@section('content')
<div class="auth-main v1">
  <div class="auth-wrapper">
    <div class="auth-form">
      <div class="card my-5">
        <div class="card-body">
          <div class="text-center">
            <img src="{{ asset('assets/images/logo-dark.svg') }}" alt="img" class="img-fluid mb-3">
            <h4 class="f-w-500 mb-1">Changement de Mot de Passe Requis</h4>
            <p class="text-muted mb-4">
              Pour des raisons de sécurité, vous devez changer votre mot de passe temporaire avant de continuer.
            </p>
          </div>

          @if(session('warning'))
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          @endif

          @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-exclamation-circle me-2"></i>
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          @endif

          <form method="POST" action="{{ route('password.update') }}" class="password-change-form">
            @csrf
            <div class="mb-3">
              <label class="form-label" for="current_password">Mot de passe actuel</label>
              <div class="input-group">
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                       id="current_password" name="current_password" placeholder="Entrez 'passer123'" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                  <i class="ti ti-eye" id="toggleCurrentIcon"></i>
                </button>
              </div>
              <small class="form-text text-muted">Votre mot de passe temporaire est : <strong>passer123</strong></small>
              @error('current_password')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label" for="password">Nouveau mot de passe</label>
              <div class="input-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" placeholder="Nouveau mot de passe (8 caractères min)" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                  <i class="ti ti-eye" id="togglePasswordIcon"></i>
                </button>
              </div>
              @error('password')
              <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label class="form-label" for="password_confirmation">Confirmer le nouveau mot de passe</label>
              <div class="input-group">
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation" placeholder="Confirmez votre nouveau mot de passe" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                  <i class="ti ti-eye" id="toggleConfirmIcon"></i>
                </button>
              </div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="ti ti-lock me-2"></i>Changer le Mot de Passe
              </button>
            </div>
          </form>

          <div class="text-center mt-4">
            <p class="text-muted">
              <small>
                <i class="ti ti-shield-check me-1 text-success"></i>
                Après le changement, vous accéderez automatiquement à votre interface de travail.
              </small>
            </p>
          </div>

          <div class="text-center mt-3">
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
              @csrf
              <button type="submit" class="btn btn-link text-muted p-0">
                <i class="ti ti-logout me-1"></i>Se déconnecter
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function togglePassword(fieldId) {
  const field = document.getElementById(fieldId);
  const icon = document.getElementById('toggle' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1).replace('_', '') + 'Icon');
  
  if (field.type === 'password') {
    field.type = 'text';
    icon.className = 'ti ti-eye-off';
  } else {
    field.type = 'password';
    icon.className = 'ti ti-eye';
  }
}

// Validation en temps réel
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('.password-change-form');
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('password_confirmation');
  
  function validatePasswords() {
    if (password.value && confirmPassword.value) {
      if (password.value === confirmPassword.value) {
        confirmPassword.classList.remove('is-invalid');
        confirmPassword.classList.add('is-valid');
      } else {
        confirmPassword.classList.remove('is-valid');
        confirmPassword.classList.add('is-invalid');
      }
    }
  }
  
  password.addEventListener('input', validatePasswords);
  confirmPassword.addEventListener('input', validatePasswords);
});
</script>

<style>
.auth-main {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.auth-wrapper {
  width: 100%;
  max-width: 420px;
  margin: 0 auto;
}

.card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.input-group .btn {
  border-left: none;
}

.input-group .form-control:focus + .btn {
  border-color: #86b7fe;
}
</style>
@endsection
