@extends('layouts.app')

@section('title', 'Modifier le Livreur')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Modifier le Livreur</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('livreurs.index') }}">Livreurs</a></li>
          <li class="breadcrumb-item" aria-current="page">Modifier {{ $livreur->nom_complet }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row justify-content-center">
  <div class="col-md-8">
    <form action="{{ route('livreurs.update', $livreur) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="card">
        <div class="card-header">
          <h5>Informations du Livreur</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="nom">Nom <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                       id="nom" name="nom" value="{{ old('nom', $livreur->nom) }}" required 
                       placeholder="Ex: DIOP">
                @error('nom')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="prenom">Prénom <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                       id="prenom" name="prenom" value="{{ old('prenom', $livreur->prenom) }}" required 
                       placeholder="Ex: Moussa">
                @error('prenom')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="telephone">Téléphone <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                       id="telephone" name="telephone" value="{{ old('telephone', $livreur->telephone) }}" required 
                       placeholder="Ex: 77123456">
                @error('telephone')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', $livreur->email) }}" 
                       placeholder="Ex: moussa.diop@example.com">
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="cin">CIN <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('cin') is-invalid @enderror" 
                       id="cin" name="cin" value="{{ old('cin', $livreur->cin) }}" required 
                       placeholder="Ex: CNI001">
                @error('cin')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="date_embauche">Date d'embauche <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('date_embauche') is-invalid @enderror" 
                       id="date_embauche" name="date_embauche" value="{{ old('date_embauche', $livreur->date_embauche->format('Y-m-d')) }}" required>
                @error('date_embauche')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="adresse">Adresse</label>
            <textarea class="form-control @error('adresse') is-invalid @enderror" 
                      id="adresse" name="adresse" rows="3" 
                      placeholder="Ex: Dakar, Sénégal">{{ old('adresse', $livreur->adresse) }}</textarea>
            @error('adresse')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="actif" name="actif" value="1" 
                     {{ old('actif', $livreur->actif) ? 'checked' : '' }}>
              <label class="form-check-label" for="actif">
                Livreur actif
              </label>
            </div>
            <small class="text-muted">Décochez pour désactiver temporairement ce livreur</small>
          </div>

          <!-- Info stats -->
          <div class="alert alert-info">
            <h6 class="mb-2">Statistiques du livreur :</h6>
            <div class="row">
              <div class="col-md-4">
                <small><strong>Colis ramassés :</strong> {{ $livreur->colisRamasses->count() }}</small>
              </div>
              <div class="col-md-4">
                <small><strong>Colis livrés :</strong> {{ $livreur->colisLivres->count() }}</small>
              </div>
              <div class="col-md-4">
                <small><strong>Ancienneté :</strong> {{ $livreur->date_embauche->diffForHumans() }}</small>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-end">
          <a href="{{ route('livreurs.index') }}" class="btn btn-outline-secondary me-2">
            <i class="ti ti-arrow-left"></i> Annuler
          </a>
          <a href="{{ route('livreurs.show', $livreur) }}" class="btn btn-outline-info me-2">
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
