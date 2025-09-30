@extends('layouts.app')

@section('title', 'Scan Colis - Réception')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">🏢 Scanner Colis - Réception Gare</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.ecom-product-list') }}">Gestion des Colis</a></li>
          <li class="breadcrumb-item" aria-current="page">Scan Colis</li>
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

    <!-- Informations sur la réception -->
    <div class="card mb-4 border-primary">
      <div class="card-header bg-light-primary">
        <h5 class="text-primary mb-0">
          <i class="ti ti-scan me-2"></i>Réception Gare-à-Gare
        </h5>
      </div>
      <div class="card-body">
        <div class="alert alert-info mb-3">
          <i class="ti ti-info-circle me-2"></i>
          <strong>Information :</strong> Réception gare-à-gare. Tous les colis (sauf ceux déjà réceptionnés) peuvent être réceptionnés à votre gare.
        </div>
        <p class="mb-2">Processus de réception :</p>
        <ol class="mb-0">
          <li>Scannez ou saisissez le code du colis arrivé</li>
          <li>Vérifiez les informations du colis</li>
          <li>Ajoutez des notes de réception si nécessaire</li>
          <li>Cliquez sur "Réceptionner" pour confirmer l'arrivée à votre gare</li>
        </ol>
      </div>
    </div>
    
    <!-- Recherche de colis -->
    <div class="card mb-4">
      <div class="card-header">
        <h5>🔍 Rechercher un Colis à Réceptionner</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('application.scan-colis.rechercher') }}" method="POST" id="scanForm">
          @csrf
          <div class="row">
            <div class="col-md-12">
              <div class="form-group position-relative">
                <label for="codeInput" class="form-label">QR Code ou N° Courrier *</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                       name="code" id="codeInput" 
                       placeholder="Scanner ou taper le code du colis arrivé" 
                       value="{{ old('code') }}" required autocomplete="off">
                
                <!-- Dropdown pour suggestions -->
                <div id="suggestions-dropdown" class="dropdown-menu w-100" style="display: none; position: absolute; top: 100%; z-index: 1000;">
                </div>
                
                @error('code')
                  <div class="invalid-feedback d-block">
                    <div class="alert alert-danger mt-2 mb-0">
                      {!! $message !!}
                    </div>
                  </div>
                @enderror
                <small class="text-muted">
                  <strong>Note:</strong> Pour utiliser la caméra, le site doit être en HTTPS. 
                  <span class="text-warning"><strong>Votre site utilise HTTP - caméra désactivée.</strong></span>
                  <br>
                  En attendant, vous pouvez saisir le code manuellement.
                  <br>
                  <a href="javascript:void(0)" onclick="showCameraHelp()" class="text-primary">
                    <i class="ti ti-help-circle"></i> Comment résoudre le problème ?
                  </a>
                </small>
              </div>
            </div>
          </div>
          
          <!-- Boutons d'action -->
          <div class="mt-3 d-flex gap-2">
            <button type="button" class="btn btn-primary" id="scanButton">
              <i class="ti ti-camera me-2"></i>Scanner
            </button>
            <button type="submit" class="btn btn-success">
              <i class="ti ti-search me-2"></i>Rechercher
            </button>
            <a href="{{ route('application.reception.colis-receptionnes') }}" class="btn btn-outline-info">
              <i class="ti ti-list me-2"></i>Voir les Réceptionnés
            </a>
          </div>
          
          <!-- Zone de scan caméra -->
          <div id="qr-reader" style="display: none;">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">📷 Scanner QR Code</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="stopScan">
                      <i class="ti ti-x"></i> Fermer
                    </button>
                  </div>
                  <div class="card-body text-center">
                    <div id="qr-reader-element" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                    <p class="text-muted mt-2">Pointez votre caméra vers le QR Code du colis</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
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
          <li><strong>Le numéro de courrier</strong> du colis livré</li>
          <li><em>Seuls les colis avec le statut "Livré" apparaîtront dans les résultats</em></li>
        </ul>
        <div class="alert alert-success mb-0">
          <i class="ti ti-check-circle me-2"></i>
          Le système vérifiera automatiquement que le colis peut être réceptionné !
        </div>
      </div>
    </div>

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

/* Styles pour les suggestions */
#suggestions-dropdown {
  max-height: 300px;
  overflow-y: auto;
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  background: white;
}

#suggestions-dropdown .dropdown-item {
  border-bottom: 1px solid #f8f9fa;
  padding: 12px 16px;
  transition: all 0.2s ease;
}

#suggestions-dropdown .dropdown-item:hover {
  background-color: #f8f9fa;
  transform: translateX(2px);
}

#suggestions-dropdown .dropdown-item:last-child {
  border-bottom: none;
}
</style>
@endpush

@push('scripts')
<!-- HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Auto-focus sur le champ de saisie
  const codeInput = document.getElementById('codeInput');
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
    const code = codeInput.value;
    
    if (!code) {
      e.preventDefault();
      alert('Veuillez scanner ou saisir le code QR');
      return false;
    }
  });

  // Variables globales pour le scanner
  let html5QrcodeScanner = null;
  let isScanning = false;

  // Bouton pour démarrer le scan
  document.getElementById('scanButton').addEventListener('click', function() {
    if (isScanning) return;
    
    const qrReaderDiv = document.getElementById('qr-reader');
    const scanButton = document.getElementById('scanButton');
    
    if (!qrReaderDiv || !scanButton) {
      console.error('Éléments DOM non trouvés');
      return;
    }
    
    // Afficher la zone de scan
    qrReaderDiv.style.display = 'block';
    scanButton.innerHTML = '<i class="ti ti-camera me-2"></i>Scan en cours...';
    scanButton.disabled = true;
    
    startScanning();
  });

  // Bouton pour arrêter le scan
  document.getElementById('stopScan').addEventListener('click', function() {
    stopScanning();
  });

  function startScanning() {
    // Vérifier si Html5Qrcode est disponible
    if (typeof Html5Qrcode === 'undefined') {
      console.error('Html5Qrcode non disponible');
      showNotification('Erreur: Bibliothèque de scan non chargée. Rechargez la page.', 'error');
      stopScanning();
      return;
    }
    
    // Configuration du scanner
    const config = {
      fps: 10,
      qrbox: { width: 250, height: 250 },
      aspectRatio: 1.0,
      rememberLastUsedCamera: true
    };
    
    try {
      // Initialiser le scanner
      html5QrcodeScanner = new Html5Qrcode("qr-reader-element");
      
      // Démarrer le scan
      html5QrcodeScanner.start(
        { facingMode: "environment" }, // Caméra arrière
        config,
        onScanSuccess,
        onScanFailure
      ).then(() => {
        isScanning = true;
      }).catch(err => {
        console.error('Erreur lors du démarrage du scanner:', err);
        stopScanning();
        
        let errorMessage = 'Impossible d\'accéder à la caméra.';
        
        if (err.name === 'NotAllowedError') {
          errorMessage += ' Permission refusée. Autorisez l\'accès à la caméra.';
        } else if (err.name === 'NotFoundError') {
          errorMessage += ' Aucune caméra trouvée sur cet appareil.';
        } else if (err.name === 'NotSupportedError') {
          errorMessage += ' Fonctionnalité non supportée par ce navigateur.';
        } else {
          errorMessage += ' Utilisez HTTPS ou autorisez l\'accès à la caméra.';
        }
        
        errorMessage += ' Utilisez la saisie manuelle en attendant.';
        
        showNotification(errorMessage, 'error');
        
        setTimeout(() => {
          showCameraHelp();
        }, 2000);
      });
    } catch (err) {
      console.error('Erreur lors de l\'initialisation:', err);
      showNotification('Erreur d\'initialisation du scanner. Rechargez la page.', 'error');
      stopScanning();
    }
  }

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
  }

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
    const qrReaderDiv = document.getElementById('qr-reader');
    if (qrReaderDiv) {
      qrReaderDiv.style.display = 'none';
    }
    
    // Réactiver le bouton
    const scanButton = document.getElementById('scanButton');
    if (scanButton) {
      scanButton.innerHTML = '<i class="ti ti-camera me-2"></i>Scanner';
      scanButton.disabled = false;
    }
    
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
            <div class="alert alert-warning">
              <i class="ti ti-alert-triangle me-2"></i>
              <strong>Problème identifié :</strong> Votre site utilise HTTP au lieu de HTTPS
            </div>
            
            <h6>Solutions :</h6>
            <ol>
              <li><strong>HTTPS (Recommandé)</strong> - Configurez un certificat SSL</li>
              <li><strong>Localhost</strong> - Utilisez localhost pour les tests</li>
              <li><strong>Saisie manuelle</strong> - Tapez le numéro de courrier directement</li>
            </ol>
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

  // Fonction pour remplir depuis les suggestions d'erreur
  window.fillCodeFromError = function(code) {
    document.getElementById('codeInput').value = code;
    document.getElementById('codeInput').focus();
    const errorDiv = document.querySelector('.invalid-feedback');
    if (errorDiv) {
      errorDiv.style.display = 'none';
    }
    showNotification('Code sélectionné ! Cliquez sur "Rechercher".', 'info');
  }

  // Autocomplétion pour le champ de recherche
  const suggestionsDropdown = document.getElementById('suggestions-dropdown');
  let debounceTimer = null;

  codeInput.addEventListener('input', function() {
    const query = this.value.trim();
    
    clearTimeout(debounceTimer);
    
    if (query.length < 2) {
      hideSuggestions();
      return;
    }

    // Debounce de 300ms
    debounceTimer = setTimeout(() => {
      fetchSuggestions(query);
    }, 300);
  });

  codeInput.addEventListener('blur', function() {
    // Délai pour permettre le clic sur une suggestion
    setTimeout(hideSuggestions, 200);
  });

  function fetchSuggestions(query) {
    fetch(`{{ route('application.scan-reception.suggestions') }}?q=${encodeURIComponent(query)}`)
      .then(response => response.json())
      .then(data => {
        showSuggestions(data);
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des suggestions:', error);
        hideSuggestions();
      });
  }

  function showSuggestions(suggestions) {
    if (suggestions.length === 0) {
      hideSuggestions();
      return;
    }

    let html = '';
    suggestions.forEach(suggestion => {
      const statusColor = getStatusColor(suggestion.statut);
      html += `
        <div class="dropdown-item d-flex justify-content-between align-items-center" 
             onclick="selectSuggestion('${suggestion.code}')" style="cursor: pointer;">
          <div>
            <strong>${suggestion.code}</strong><br>
            <small class="text-muted">${suggestion.label}</small>
          </div>
          <span class="badge bg-${statusColor}">${suggestion.statut}</span>
        </div>
      `;
    });

    suggestionsDropdown.innerHTML = html;
    suggestionsDropdown.style.display = 'block';
  }

  function hideSuggestions() {
    suggestionsDropdown.style.display = 'none';
  }

  window.selectSuggestion = function(code) {
    if (codeInput) {
      codeInput.value = code;
      hideSuggestions();
      codeInput.focus();
      showNotification('Code sélectionné ! Cliquez sur "Rechercher".', 'success');
    }
  }

  function getStatusColor(statut) {
    const colors = {
      'En attente': 'secondary',
      'Ramassé': 'warning',
      'En transit': 'info',
      'Livré': 'success',
      'Réceptionné': 'primary'
    };
    return colors[statut] || 'secondary';
  }
});
</script>
@endpush
