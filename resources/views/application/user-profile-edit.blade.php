@extends('layouts.app')

@section('title', 'Modifier Profil - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Modifier Profil</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ dashboard_route() }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.user-profile') }}">Profil</a></li>
          <li class="breadcrumb-item" aria-current="page">Modifier</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5>Modifier mes informations</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('application.user-profile-update') }}">
          @csrf
          @method('PUT')
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="name">Nom complet <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="current_password">Mot de passe actuel</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                       id="current_password" name="current_password" 
                       placeholder="Laissez vide si pas de changement">
                @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="password">Nouveau mot de passe</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" 
                       placeholder="Laissez vide si pas de changement">
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="password_confirmation">Confirmer le nouveau mot de passe</label>
            <input type="password" class="form-control" 
                   id="password_confirmation" name="password_confirmation" 
                   placeholder="Confirmer le nouveau mot de passe">
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-device-floppy me-2"></i>Enregistrer les modifications
            </button>
            <a href="{{ route('application.user-profile') }}" class="btn btn-outline-secondary">
              <i class="ti ti-x me-2"></i>Annuler
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5>Informations du compte</h5>
      </div>
      <div class="card-body text-center">
        <div class="user-avtar online user-avtar-xl">
          <img class="rounded-circle img-fluid" src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="user image">
          <span class="user-status"></span>
        </div>
        <h5 class="mt-3 mb-1">{{ $user->name }}</h5>
        <p class="text-muted mb-0">{{ $user->email }}</p>
        <p class="text-muted">
          @if($user->hasRole('super-admin'))
            <span class="badge bg-danger">Super Administrateur</span>
          @elseif($user->hasRole('admin'))
            <span class="badge bg-warning">Administrateur</span>
          @elseif($user->hasRole('gestionnaire'))
            <span class="badge bg-info">Gestionnaire</span>
          @elseif($user->hasRole('employe'))
            <span class="badge bg-secondary">Employé</span>
          @elseif($user->hasRole('livreur'))
            <span class="badge bg-success">Livreur</span>
          @else
            <span class="badge bg-light">Utilisateur</span>
          @endif
        </p>
        
        <div class="mt-4">
          <div class="d-flex align-items-center mb-2">
            <i class="ti ti-calendar text-muted me-2"></i>
            <small class="text-muted">Membre depuis</small>
          </div>
          <p class="mb-0">{{ $user->created_at->format('d/m/Y') }}</p>
        </div>
      </div>
    </div>
    
    <div class="card">
      <div class="card-header">
        <h5>Actions rapides</h5>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <a href="{{ route('application.user-profile') }}" class="btn btn-outline-primary">
            <i class="ti ti-user me-2"></i>Voir mon profil
          </a>
          @can('view_colis')
          <a href="{{ route('application.ecom-product-list') }}" class="btn btn-outline-secondary">
            <i class="ti ti-package me-2"></i>Mes colis
          </a>
          @endcan
          @can('scan_qr_colis')
          <a href="{{ route('scan.index') }}" class="btn btn-outline-success">
            <i class="ti ti-qrcode me-2"></i>Scanner QR
          </a>
          @endcan
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
// Auto-hide alerts after 5 seconds
setTimeout(function() {
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(function(alert) {
    if (alert.querySelector('.btn-close')) {
      alert.style.transition = 'opacity 0.5s';
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 500);
    }
  });
}, 5000);
</script>
@endpush
