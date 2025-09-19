@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Scanner QR Code</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item" aria-current="page">Scan QR</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row justify-content-center">
  <div class="col-md-8">
    <!-- Sélection du livreur -->
    <div class="card mb-4">
      <div class="card-header">
        <h5>Identification du Livreur</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('scan.rechercher') }}" method="POST" id="scanForm">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Sélectionner le livreur <span class="text-danger">*</span></label>
                <select class="form-select @error('livreur_id') is-invalid @enderror" name="livreur_id" required>
                  <option value="">Choisir un livreur</option>
                  @foreach($livreurs as $livreur)
                  <option value="{{ $livreur->id }}" {{ old('livreur_id') == $livreur->id ? 'selected' : '' }}>
                    {{ $livreur->nom_complet }} - {{ $livreur->telephone }}
                  </option>
                  @endforeach
                </select>
                @error('livreur_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">QR Code ou N° Courrier <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                       name="code" value="{{ old('code') }}" required 
                       placeholder="Scanner ou saisir le code" autofocus>
                @error('code')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          
          <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="ti ti-search me-2"></i>Rechercher le Colis
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Instructions -->
    <div class="card">
      <div class="card-header">
        <h5>Instructions d'utilisation</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="d-flex align-items-start mb-3">
              <div class="avtar avtar-s bg-light-primary me-3 mt-1">
                <span class="f-16">1</span>
              </div>
              <div>
                <h6 class="mb-1">Sélectionnez votre nom</h6>
                <p class="text-muted mb-0">Choisissez votre nom dans la liste des livreurs actifs.</p>
              </div>
            </div>
            
            <div class="d-flex align-items-start mb-3">
              <div class="avtar avtar-s bg-light-success me-3 mt-1">
                <span class="f-16">2</span>
              </div>
              <div>
                <h6 class="mb-1">Scannez le QR Code</h6>
                <p class="text-muted mb-0">Utilisez votre téléphone pour scanner le code QR du colis.</p>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="d-flex align-items-start mb-3">
              <div class="avtar avtar-s bg-light-warning me-3 mt-1">
                <span class="f-16">3</span>
              </div>
              <div>
                <h6 class="mb-1">Actions disponibles</h6>
                <p class="text-muted mb-0">Ramasser le colis ou le marquer comme livré selon le statut.</p>
              </div>
            </div>
            
            <div class="d-flex align-items-start mb-3">
              <div class="avtar avtar-s bg-light-info me-3 mt-1">
                <span class="f-16">4</span>
              </div>
              <div>
                <h6 class="mb-1">Suivi en temps réel</h6>
                <p class="text-muted mb-0">Chaque action est enregistrée avec date et heure automatiquement.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Exemples de QR Codes -->
    @if(\App\Models\Colis::whereNotNull('qr_code')->exists())
    <div class="card">
      <div class="card-header">
        <h5>Exemples de QR Codes disponibles</h5>
      </div>
      <div class="card-body">
        <div class="row">
          @foreach(\App\Models\Colis::whereNotNull('qr_code')->take(6)->get() as $colis)
          <div class="col-md-4 mb-3">
            <div class="card border">
              <div class="card-body text-center py-3">
                <h6 class="mb-1">{{ $colis->numero_courrier }}</h6>
                <code class="small">{{ $colis->qr_code }}</code>
                <div class="mt-2">
                  <span class="badge bg-light-{{ $colis->statut_color }}">{{ $colis->statut_livraison_label }}</span>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <div class="text-center">
          <small class="text-muted">Vous pouvez tester avec ces codes QR existants</small>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Auto-focus sur le champ de saisie
  const codeInput = document.querySelector('input[name="code"]');
  if (codeInput) {
    codeInput.focus();
  }
  
  // Auto-submit quand on scanne (généralement les scanners ajoutent un retour chariot)
  codeInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      document.getElementById('scanForm').submit();
    }
  });
  
  // Validation côté client
  document.getElementById('scanForm').addEventListener('submit', function(e) {
    const livreurId = document.querySelector('select[name="livreur_id"]').value;
    const code = document.querySelector('input[name="code"]').value;
    
    if (!livreurId) {
      e.preventDefault();
      alert('Veuillez sélectionner un livreur');
      return false;
    }
    
    if (!code) {
      e.preventDefault();
      alert('Veuillez scanner ou saisir le code QR');
      return false;
    }
  });
});
</script>
@endpush
