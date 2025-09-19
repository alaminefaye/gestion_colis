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
    
    <!-- Messages d'information -->
    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
      <i class="ti ti-info-circle me-2"></i>{{ session('info') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    <!-- Recherche de colis -->
    <div class="card mb-4">
      <div class="card-header">
        <h5>Rechercher un Colis</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('scan.rechercher') }}" method="POST" id="scanForm">
          @csrf
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label">QR Code ou N° Courrier <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control @error('code') is-invalid @enderror" 
                         name="code" value="{{ old('code') }}" required 
                         placeholder="Scanner le QR Code ou saisir le numéro de courrier" id="codeInput">
                  <button type="button" class="btn btn-outline-secondary" id="scanButton">
                    <i class="ti ti-camera me-2"></i>Scanner
                  </button>
                  <button type="submit" class="btn btn-primary">
                    <i class="ti ti-search me-2"></i>Rechercher
                  </button>
                </div>
                @error('code')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                  <strong>Note:</strong> Pour utiliser la caméra, le site doit être en HTTPS. 
                  En attendant, vous pouvez saisir le code manuellement.
                  <br>
                  <a href="javascript:void(0)" onclick="showCameraHelp()" class="text-primary">
                    <i class="ti ti-help-circle"></i> Problème avec la caméra ?
                  </a>
                </small>
              </div>
            </div>
          </div>
          
          <!-- Zone de scan caméra -->
          <div id="qr-reader" style="display: none;">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Scanner QR Code</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="stopScan">
                      <i class="ti ti-x"></i> Fermer
                    </button>
                  </div>
                  <div class="card-body text-center">
                    <div id="qr-reader-element" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                    <p class="text-muted mt-2">Pointez votre caméra vers le QR Code</p>
                  </div>
                </div>
              </div>
            </div>
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
                <h6 class="mb-1">Scannez ou Tapez</h6>
                <p class="text-muted mb-0">Utilisez votre appareil pour scanner le QR Code ou tapez le numéro manuellement.</p>
              </div>
            </div>
            
            <div class="d-flex align-items-start mb-3">
              <div class="avtar avtar-s bg-light-success me-3 mt-1">
                <span class="f-16">2</span>
              </div>
              <div>
                <h6 class="mb-1">Recherchez le Colis</h6>
                <p class="text-muted mb-0">Le système trouvera automatiquement le colis correspondant.</p>
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
                <h6 class="mb-1">Suivi automatique</h6>
                <p class="text-muted mb-0">Vos actions sont automatiquement associées à votre compte livreur.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Solution alternative -->
    <div class="card border-warning">
      <div class="card-header bg-light-warning">
        <h5 class="text-warning mb-0">
          <i class="ti ti-bulb me-2"></i>Astuce : Saisie Manuelle
        </h5>
      </div>
      <div class="card-body">
        <p class="mb-3">Si la caméra ne fonctionne pas, vous pouvez saisir directement :</p>
        <ul class="mb-3">
          <li><strong>Le numéro de courrier</strong> (ex: TEST001, Fugiat ducimus labo)</li>
          <li><em>Le QR Code contient maintenant seulement le numéro de courrier</em></li>
        </ul>
        <div class="alert alert-info mb-0">
          <i class="ti ti-info-circle me-2"></i>
          Scan ou saisie manuelle - les deux fonctionnent parfaitement !
        </div>
      </div>
    </div>

    <!-- Exemples de QR Codes -->
    @if(\App\Models\Colis::whereNotNull('qr_code')->exists())
    <div class="card">
      <div class="card-header">
        <h5>Exemples de Codes Disponibles</h5>
      </div>
      <div class="card-body">
        <div class="row">
          @foreach(\App\Models\Colis::whereNotNull('qr_code')->where('recupere_gare', false)->take(6)->get() as $colis)
          <div class="col-md-4 mb-3">
            <div class="card border">
              <div class="card-body text-center py-3">
                <h6 class="mb-1">{{ $colis->numero_courrier }}</h6>
                <code class="small clickable-code" onclick="fillCode('{{ $colis->numero_courrier }}')" style="cursor: pointer;" title="Cliquer pour remplir">{{ $colis->numero_courrier }}</code>
                <div class="mt-2">
                  <span class="badge bg-light-{{ $colis->statut_color }}">{{ $colis->statut_livraison_label }}</span>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        <div class="text-center">
          <small class="text-muted">Cliquez sur un code pour le copier dans le champ de recherche</small>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection

@push('styles')
<style>
#qr-reader {
  margin-top: 20px;
}

#qr-reader-element {
  border: 2px solid #007bff;
  border-radius: 8px;
  overflow: hidden;
}

.input-group .btn {
  border-radius: 0;
}

.input-group .btn:last-child {
  border-top-right-radius: 0.375rem;
  border-bottom-right-radius: 0.375rem;
}

.input-group .btn:not(:last-child) {
  border-right: 0;
}

/* Animation pour le bouton de scan */
#scanButton:disabled {
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% { opacity: 1; }
  50% { opacity: 0.7; }
  100% { opacity: 1; }
}

/* Codes cliquables */
.clickable-code {
  transition: all 0.2s ease;
  padding: 2px 4px;
  border-radius: 3px;
}

.clickable-code:hover {
  background-color: #007bff;
  color: white !important;
  transform: scale(1.05);
}
</style>
@endpush

@push('scripts')
<!-- HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
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
    const code = document.querySelector('input[name="code"]').value;
    
    if (!code) {
      e.preventDefault();
      alert('Veuillez scanner ou saisir le code QR');
      return false;
    }
  });

  // Variables pour le scanner QR
  let html5QrcodeScanner;
  let isScanning = false;

  // Bouton pour démarrer le scan
  document.getElementById('scanButton').addEventListener('click', function() {
    if (isScanning) return;
    
    const qrReaderDiv = document.getElementById('qr-reader');
    const scanButton = document.getElementById('scanButton');
    
    // Afficher la zone de scan
    qrReaderDiv.style.display = 'block';
    scanButton.innerHTML = '<i class="ti ti-camera me-2"></i>Scan en cours...';
    scanButton.disabled = true;
    
    // Configuration du scanner
    const config = {
      fps: 10,
      qrbox: { width: 250, height: 250 },
      aspectRatio: 1.0,
      rememberLastUsedCamera: true
    };
    
    // Initialiser le scanner
    html5QrcodeScanner = new Html5Qrcode("qr-reader-element");
    
    // Démarrer le scan
    html5QrcodeScanner.start(
      { facingMode: "environment" }, // Caméra arrière
      config,
      onScanSuccess,
      onScanFailure
    ).catch(err => {
      console.error('Erreur lors du démarrage du scanner:', err);
      stopScanning();
      
      // Message d'erreur plus explicite
      showNotification(
        'Impossible d\'accéder à la caméra. Le site doit être en HTTPS ou vous devez autoriser l\'accès. Utilisez la saisie manuelle en attendant.',
        'error'
      );
      
      // Afficher automatiquement l'aide après 2 secondes
      setTimeout(() => {
        showCameraHelp();
      }, 2000);
    });
    
    isScanning = true;
  });

  // Fonction appelée lors d'un scan réussi
  function onScanSuccess(decodedText, decodedResult) {
    // Mettre le code scanné dans le champ input
    document.getElementById('codeInput').value = decodedText;
    
    // Arrêter le scan
    stopScanning();
    
    // Notification de succès
    showNotification('QR Code scanné avec succès!', 'success');
    
    // Auto-submit du formulaire après 1 seconde
    setTimeout(() => {
      document.getElementById('scanForm').submit();
    }, 1000);
  }

  // Fonction appelée lors d'une erreur de scan (normale, se produit en continu)
  function onScanFailure(error) {
    // Ne pas afficher les erreurs de scan (normales)
    // console.warn('Erreur de scan:', error);
  }

  // Bouton pour arrêter le scan
  document.getElementById('stopScan').addEventListener('click', function() {
    stopScanning();
  });

  // Fonction pour arrêter le scan
  function stopScanning() {
    if (html5QrcodeScanner && isScanning) {
      html5QrcodeScanner.stop().then(() => {
        html5QrcodeScanner.clear();
      }).catch(err => {
        console.error('Erreur lors de l\'arrêt du scanner:', err);
      });
    }
    
    // Masquer la zone de scan
    document.getElementById('qr-reader').style.display = 'none';
    
    // Réactiver le bouton
    const scanButton = document.getElementById('scanButton');
    scanButton.innerHTML = '<i class="ti ti-camera me-2"></i>Scanner';
    scanButton.disabled = false;
    
    isScanning = false;
  }

  // Fonction pour afficher des notifications
  function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
      <i class="ti ti-${type === 'success' ? 'check' : type === 'error' ? 'x' : 'info-circle'} me-2"></i>${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
      if (notification.parentNode) {
        notification.remove();
      }
    }, 3000);
  }

  // Fonction d'aide pour les problèmes de caméra
  window.showCameraHelp = function() {
    const helpModal = document.createElement('div');
    helpModal.className = 'modal fade';
    helpModal.innerHTML = `
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Problème d'accès à la caméra</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <h6>Pourquoi la caméra ne fonctionne pas ?</h6>
            <p>Les navigateurs modernes exigent HTTPS pour accéder à la caméra. Votre site utilise HTTP.</p>
            
            <h6>Solutions :</h6>
            <ol>
              <li><strong>Saisie manuelle :</strong> Tapez directement le numéro de courrier dans le champ</li>
              <li><strong>Autoriser temporairement :</strong>
                <ul>
                  <li>Cliquez sur l'icône 🔒 dans la barre d'adresse</li>
                  <li>Sélectionnez "Paramètres du site"</li>
                  <li>Changez "Appareil photo" à "Autoriser"</li>
                </ul>
              </li>
              <li><strong>Pour l'administrateur :</strong> Configurer HTTPS sur le serveur</li>
            </ol>
            
            <div class="alert alert-info">
              <i class="ti ti-info-circle me-2"></i>
              En attendant, vous pouvez utiliser la saisie manuelle qui fonctionne parfaitement !
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Compris</button>
          </div>
        </div>
      </div>
    `;
    
    document.body.appendChild(helpModal);
    const modal = new bootstrap.Modal(helpModal);
    modal.show();
    
    helpModal.addEventListener('hidden.bs.modal', function() {
      helpModal.remove();
    });
  }

  // Fonction pour remplir le code en cliquant sur les exemples
  window.fillCode = function(code) {
    document.getElementById('codeInput').value = code;
    document.getElementById('codeInput').focus();
    showNotification('Code copié ! Cliquez sur "Rechercher" pour continuer.', 'success');
  }
});
</script>
@endpush
