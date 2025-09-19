@extends('layouts.app')

@section('title', 'Profil Utilisateur - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Profil Utilisateur</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="javascript: void(0)">Utilisateur</a></li>
          <li class="breadcrumb-item" aria-current="page">Profil</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <!-- Profile Info -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-body text-center">
        <div class="position-relative d-inline-block">
          <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="img-radius wid-80 mb-3">
          <div class="position-absolute bottom-0 end-0">
            <button class="btn btn-primary btn-sm rounded-pill">
              <i class="ti ti-camera"></i>
            </button>
          </div>
        </div>
        <h5 class="mb-1">Administrateur Système</h5>
        <p class="text-muted mb-3">admin@gestion-colis.com</p>
        <div class="row text-center">
          <div class="col-4">
            <h5 class="mb-0">87</h5>
            <small class="text-muted">Colis Traités</small>
          </div>
          <div class="col-4">
            <h5 class="mb-0">1.2k</h5>
            <small class="text-muted">Clients</small>
          </div>
          <div class="col-4">
            <h5 class="mb-0">95%</h5>
            <small class="text-muted">Performance</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Activity -->
    <div class="card">
      <div class="card-header">
        <h5>Activité Récente</h5>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-success">
              <i class="ti ti-package text-success"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-1">Colis traité</h6>
            <p class="text-muted mb-0">Il y a 2 heures</p>
          </div>
        </div>
        <div class="d-flex align-items-center mb-3">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-primary">
              <i class="ti ti-user-plus text-primary"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-1">Nouveau client</h6>
            <p class="text-muted mb-0">Il y a 4 heures</p>
          </div>
        </div>
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-warning">
              <i class="ti ti-settings text-warning"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-1">Mise à jour système</h6>
            <p class="text-muted mb-0">Hier</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Profile Details -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab-1" data-bs-toggle="tab" data-bs-target="#profile-1" type="button" role="tab" aria-controls="profile-1" aria-selected="true">
              <i class="ti ti-user me-2"></i>Informations Personnelles
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab-2" data-bs-toggle="tab" data-bs-target="#profile-2" type="button" role="tab" aria-controls="profile-2" aria-selected="false">
              <i class="ti ti-lock me-2"></i>Sécurité
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab-3" data-bs-toggle="tab" data-bs-target="#profile-3" type="button" role="tab" aria-controls="profile-3" aria-selected="false">
              <i class="ti ti-settings me-2"></i>Paramètres
            </button>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content" id="myTabContent">
          <!-- Personal Information -->
          <div class="tab-pane fade show active" id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
            <form>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" class="form-control" value="Admin" placeholder="Prénom">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control" value="Système" placeholder="Nom">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="admin@gestion-colis.com" placeholder="Email">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" value="+33 1 23 45 67 89" placeholder="Téléphone">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Poste</label>
                    <input type="text" class="form-control" value="Gestionnaire Système" placeholder="Poste">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label">Département</label>
                    <select class="form-select">
                      <option>Administration</option>
                      <option>Logistique</option>
                      <option>Service Client</option>
                      <option>IT</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Adresse</label>
                <textarea class="form-control" rows="3" placeholder="Adresse complète">123 Rue de la Logistique, 75001 Paris, France</textarea>
              </div>
              <div class="text-end">
                <button type="button" class="btn btn-outline-secondary me-2">Annuler</button>
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
              </div>
            </form>
          </div>

          <!-- Security -->
          <div class="tab-pane fade" id="profile-2" role="tabpanel" aria-labelledby="profile-tab-2">
            <form>
              <div class="mb-3">
                <label class="form-label">Mot de passe actuel</label>
                <input type="password" class="form-control" placeholder="Mot de passe actuel">
              </div>
              <div class="mb-3">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control" placeholder="Nouveau mot de passe">
              </div>
              <div class="mb-3">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" placeholder="Confirmer le mot de passe">
              </div>
              
              <div class="card border">
                <div class="card-header">
                  <h6 class="mb-0">Authentification à deux facteurs</h6>
                </div>
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                    <div>
                      <h6 class="mb-1">Activer 2FA</h6>
                      <p class="text-muted mb-0">Sécurité renforcée pour votre compte</p>
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                    </div>
                  </div>
                </div>
              </div>

              <div class="text-end mt-3">
                <button type="button" class="btn btn-outline-secondary me-2">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
              </div>
            </form>
          </div>

          <!-- Settings -->
          <div class="tab-pane fade" id="profile-3" role="tabpanel" aria-labelledby="profile-tab-3">
            <div class="row">
              <div class="col-md-6">
                <div class="card border">
                  <div class="card-body">
                    <h6 class="mb-3">Notifications</h6>
                    <div class="form-check form-switch mb-2">
                      <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                      <label class="form-check-label" for="emailNotif">Notifications par email</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                      <input class="form-check-input" type="checkbox" id="smsNotif">
                      <label class="form-check-label" for="smsNotif">Notifications SMS</label>
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="pushNotif" checked>
                      <label class="form-check-label" for="pushNotif">Notifications push</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card border">
                  <div class="card-body">
                    <h6 class="mb-3">Préférences</h6>
                    <div class="mb-3">
                      <label class="form-label">Langue</label>
                      <select class="form-select">
                        <option>Français</option>
                        <option>English</option>
                        <option>Español</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Fuseau horaire</label>
                      <select class="form-select">
                        <option>Europe/Paris</option>
                        <option>Europe/London</option>
                        <option>America/New_York</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-end">
              <button type="button" class="btn btn-outline-secondary me-2">Réinitialiser</button>
              <button type="button" class="btn btn-primary">Sauvegarder</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
.profile-tabs .nav-link {
  border: none;
  border-bottom: 2px solid transparent;
  color: #6c757d;
}
.profile-tabs .nav-link.active {
  background: none;
  border-bottom-color: #4099ff;
  color: #4099ff;
}
</style>
@endpush
