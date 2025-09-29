@extends('layouts.app')

@section('title', 'Scanner Colis - TSR Système')

@section('content')
<div class="container-fluid">
    <!-- Header section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title mb-1">📷 Scanner un Colis</h3>
                            <p class="card-text mb-0">Scanner un QR Code ou saisir le numéro de courrier</p>
                        </div>
                        <div class="col-auto">
                            <i class="ti ti-qrcode" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section de scan -->
    <div class="row">
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-scan"></i> Scanner / Rechercher
                    </h5>
                </div>
                <div class="card-body">
                    <form id="scanForm" method="POST" action="{{ route('application.scan.index') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="qr_code" class="form-label">Numéro de Courrier ou QR Code</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-hash"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       id="qr_code" 
                                       name="qr_code" 
                                       placeholder="Tapez ou scannez le numéro..." 
                                       autocomplete="off"
                                       value="{{ old('qr_code', request('qr_code')) }}"
                                       required>
                            </div>
                            <small class="text-muted">
                                <strong>Note:</strong> Pour utiliser la caméra, le site doit être en HTTPS. 
                                <span class="text-warning"><strong>Votre site utilise HTTP - caméra peut ne pas fonctionner.</strong></span>
                                <br>En attendant, vous pouvez saisir le code manuellement.
                            </small>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" id="scanButton">
                                <i class="ti ti-camera me-2"></i>Scanner
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-search me-2"></i>Rechercher
                            </button>
                        </div>
                        
                        <!-- Zone de scan caméra -->
                        <div id="qr-reader" class="mt-3" style="display: none;">
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
                    </form>
                </div>
            </div>
        </div>

        <!-- Section des informations du colis -->
        <div class="col-lg-6 col-md-12">
            {{-- DEBUG: Afficher les variables pour debugging --}}
            @if(app()->environment('local'))
                <div class="alert alert-info small">
                    <strong>DEBUG:</strong> 
                    Colis: {{ $colis ? 'OUI' : 'NON' }} | 
                    Error: {{ $error ?? 'AUCUNE' }} |
                    Method: {{ request()->method() }} |
                    QR Code: {{ request('qr_code') ?? 'VIDE' }}
                </div>
            @endif
            
            @if($colis)
            <div class="card" id="colisDetails">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="ti ti-package"></i> Informations du Colis</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="ti ti-hash"></i> Numéro de Courrier</h6>
                            <p class="fw-bold text-primary">{{ $colis->numero_courrier }}</p>
                            
                            <h6><i class="ti ti-user"></i> Expéditeur</h6>
                            <p>{{ $colis->nom_expediteur }}</p>
                            
                            <h6><i class="ti ti-user-check"></i> Bénéficiaire</h6>
                            <p>{{ $colis->nom_beneficiaire }}</p>
                            
                            <h6><i class="ti ti-phone"></i> Téléphone</h6>
                            <p>{{ $colis->telephone_beneficiaire }}</p>
                        </div>
                        
                        <div class="col-md-6">
                            <h6><i class="ti ti-flag"></i> Statut</h6>
                            <p>
                                @switch($colis->statut_livraison)
                                    @case('en_attente')
                                        <span class="badge bg-warning">🔄 En Attente</span>
                                        @break
                                    @case('ramasse')
                                        <span class="badge bg-info">📦 Ramassé</span>
                                        @break
                                    @case('en_transit')
                                        <span class="badge bg-primary">🚛 En Transit</span>
                                        @break
                                    @case('livre')
                                        <span class="badge bg-success">✅ Livré</span>
                                        @break
                                    @case('receptionne')
                                        <span class="badge bg-success">📥 Réceptionné</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">❓ {{ $colis->statut_livraison }}</span>
                                @endswitch
                            </p>
                            
                            <h6><i class="ti ti-map-pin"></i> Destination</h6>
                            <p>{{ $colis->destination }}</p>
                            
                            <h6><i class="ti ti-building"></i> Agence</h6>
                            <p>{{ $colis->agence_reception }}</p>
                            
                            <h6><i class="ti ti-cash"></i> Montant</h6>
                            <p class="fw-bold text-success">{{ number_format($colis->montant) }} FCFA</p>
                        </div>
                    </div>
                    
                    @if($colis->est_receptionne)
                        <div class="alert alert-success mt-3">
                            <i class="ti ti-check-circle me-2"></i>
                            <strong>Colis déjà réceptionné</strong>
                            <p class="mb-1"><strong>Par:</strong> {{ $colis->receptionne_par }}</p>
                            <p class="mb-1"><strong>Le:</strong> {{ $colis->receptionne_le ? $colis->receptionne_le->format('d/m/Y à H:i') : '' }}</p>
                            @if($colis->notes_reception)
                                <p class="mb-0"><strong>Notes:</strong> {{ $colis->notes_reception }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions disponibles -->
            @if(!$colis->est_receptionne && in_array($colis->statut_livraison, ['en_transit', 'ramasse']))
            <div class="card mt-3" id="actionsSection">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success btn-lg w-100" onclick="ouvrirModalReception({{ $colis->id }})">
                                <i class="ti ti-package-import"></i><br>
                                Réceptionner le Colis
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @elseif($error)
            <div class="alert alert-danger">
                <i class="ti ti-alert-circle me-2"></i>
                {{ $error }}
            </div>
            @endif

            @if(!$colis && !$error)
            <!-- Message d'aide initial -->
            <div class="card" id="helpCard">
                <div class="card-body text-center py-5">
                    <i class="ti ti-qrcode" style="font-size: 4rem; color: #ddd;"></i>
                    <h5 class="text-muted mt-3">Scannez un colis pour voir ses détails</h5>
                    <p class="text-muted">Les informations complètes s'afficheront ici</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Section d'actions (visible après scan) -->
    <div class="row mt-4" id="actionsSection" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-clipboard-check"></i> Actions Disponibles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row" id="actionsContent">
                        <!-- Les actions seront injectées ici -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de réception -->
<div class="modal fade" id="receptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📦 Réceptionner le Colis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="receptionForm">
                @csrf
                <div class="modal-body">
                    <p>Confirmez-vous la réception de ce colis ?</p>
                    
                    <div class="mb-3">
                        <label for="notes_reception" class="form-label">Notes de réception (optionnel)</label>
                        <textarea class="form-control" 
                                  id="notes_reception" 
                                  name="notes_reception" 
                                  rows="3" 
                                  placeholder="État du colis, observations..."></textarea>
                    </div>
                    
                    <input type="hidden" id="colisId" name="colis_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-check"></i> Confirmer la Réception
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scanForm = document.getElementById('scanForm');
    const receptionForm = document.getElementById('receptionForm');
    const qrInput = document.getElementById('qr_code');
    
    // Auto-focus sur le champ de saisie
    qrInput.focus();
    
    // Auto-submit quand on scanne (généralement les scanners ajoutent un retour chariot)
    qrInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            scanForm.submit();
        }
    });
    
    // Validation côté client
    scanForm.addEventListener('submit', function(e) {
        const code = qrInput.value;
        
        if (!code) {
            e.preventDefault();
            showAlert('warning', 'Veuillez scanner ou saisir le code QR');
            return false;
        }
        // Laisser le formulaire se soumettre normalement
    });
    
    // Variables globales pour le scanner
    let html5QrcodeScanner = null;
    let isScanning = false;

    // Bouton pour démarrer le scan
    const scanButtonElement = document.getElementById('scanButton');
    if (scanButtonElement) {
        scanButtonElement.addEventListener('click', function() {
            console.log('Bouton Scanner cliqué !');
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
    } else {
        console.error('Bouton Scanner introuvable !');
    }

    // Bouton pour arrêter le scan
    const stopScanButton = document.getElementById('stopScan');
    if (stopScanButton) {
        stopScanButton.addEventListener('click', function() {
            stopScanning();
        });
    }

    function startScanning() {
        // Vérifier si Html5Qrcode est disponible
        if (typeof Html5Qrcode === 'undefined') {
            console.error('Html5Qrcode non disponible');
            showAlert('danger', 'Erreur: Bibliothèque de scan non chargée. Rechargez la page.');
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
                
                // Message d'erreur plus explicite
                let errorMessage = 'Impossible d\'accéder à la caméra. ';
                
                if (err.name === 'NotAllowedError') {
                    errorMessage += 'Permission refusée. Autorisez l\'accès à la caméra.';
                } else if (err.name === 'NotFoundError') {
                    errorMessage += 'Aucune caméra trouvée sur cet appareil.';
                } else if (err.name === 'NotSupportedError') {
                    errorMessage += 'Fonctionnalité non supportée par ce navigateur.';
                } else {
                    errorMessage += 'Utilisez HTTPS ou autorisez l\'accès à la caméra.';
                }
                
                errorMessage += ' Utilisez la saisie manuelle en attendant.';
                
                showAlert('warning', errorMessage);
            });
        } catch (err) {
            console.error('Erreur lors de l\'initialisation:', err);
            showAlert('danger', 'Erreur d\'initialisation du scanner. Rechargez la page.');
            stopScanning();
        }
    }

    // Fonction appelée lors d'un scan réussi
    function onScanSuccess(decodedText, decodedResult) {
        // Mettre le code scanné dans le champ input
        document.getElementById('qr_code').value = decodedText;
        
        // Arrêter le scan
        stopScanning();
        
        // Notification de succès
        showAlert('success', 'QR Code scanné avec succès!');
        
        // Auto-submit du formulaire après 1 seconde
        setTimeout(() => {
            console.log('Soumission automatique du formulaire...');
            // Cliquer sur le bouton submit au lieu de faire submit() direct
            const submitButton = scanForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.click();
            } else {
                // Fallback : soumission directe
                scanForm.submit();
            }
        }, 1000);
    }

    // Fonction appelée lors d'une erreur de scan (normale, se produit en continu)
    function onScanFailure(error) {
        // Ne pas afficher les erreurs de scan (normales)
    }

    // Fonction pour afficher des notifications
    function showAlert(type, message) {
        // Créer une notification Bootstrap
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger'} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="ti ti-${type === 'success' ? 'check' : type === 'warning' ? 'alert-triangle' : 'x'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insérer en haut de la page
        const container = document.querySelector('.container-fluid') || document.body;
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-supprimer après 5 secondes
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
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
    
    // Reception form submission
    receptionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const colisId = document.getElementById('colisId').value;
        const formData = new FormData(receptionForm);
        const submitBtn = receptionForm.querySelector('button[type="submit"]');
        
        // Loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ti ti-loader-2 spin"></i> Traitement...';
        
        fetch(`/application/colis/${colisId}/receptionner`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                // Fermer le modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('receptionModal'));
                modal.hide();
                // Rafraîchir les détails du colis
                afficherColis(data.colis);
            } else {
                showAlert('danger', data.message || 'Erreur lors de la réception.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('danger', 'Une erreur est survenue.');
        })
        .finally(() => {
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti ti-check"></i> Confirmer la Réception';
        });
    });
    
    function afficherColis(colis) {
        // Cacher l'aide et afficher les détails
        document.getElementById('helpCard').style.display = 'none';
        document.getElementById('colisDetails').style.display = 'block';
        document.getElementById('actionsSection').style.display = 'block';
        
        // Badge de statut avec couleur
        let statusBadge = '';
        switch(colis.statut_livraison) {
            case 'en_attente':
                statusBadge = `<span class="badge bg-warning">🔄 En Attente</span>`;
                break;
            case 'ramasse':
                statusBadge = `<span class="badge bg-info">📦 Ramassé</span>`;
                break;
            case 'en_transit':
                statusBadge = `<span class="badge bg-primary">🚛 En Transit</span>`;
                break;
            case 'livre':
                statusBadge = `<span class="badge bg-success">✅ Livré</span>`;
                break;
            case 'receptionne':
                statusBadge = `<span class="badge bg-success">📥 Réceptionné</span>`;
                break;
            default:
                statusBadge = `<span class="badge bg-secondary">❓ ${colis.statut_livraison || 'Inconnu'}</span>`;
                break;
        }
        
        // Contenu des détails du colis
        document.getElementById('colisContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="ti ti-hash"></i> Numéro de Courrier</h6>
                    <p class="fw-bold text-primary">${colis.numero_courrier}</p>
                    
                    <h6><i class="ti ti-user"></i> Expéditeur</h6>
                    <p>${colis.nom_expediteur || 'Non renseigné'}</p>
                    
                    <h6><i class="ti ti-user-check"></i> Bénéficiaire</h6>
                    <p>${colis.nom_beneficiaire || 'Non renseigné'}</p>
                    
                    <h6><i class="ti ti-phone"></i> Téléphone</h6>
                    <p>${colis.telephone_beneficiaire || 'Non renseigné'}</p>
                </div>
                
                <div class="col-md-6">
                    <h6><i class="ti ti-flag"></i> Statut</h6>
                    <p>${statusBadge}</p>
                    
                    <h6><i class="ti ti-map-pin"></i> Destination</h6>
                    <p>${colis.destination || 'Non renseignée'}</p>
                    
                    <h6><i class="ti ti-building"></i> Agence</h6>
                    <p>${colis.agence_reception || 'Non renseignée'}</p>
                    
                    <h6><i class="ti ti-cash"></i> Montant</h6>
                    <p class="fw-bold">${colis.montant || 0} FCFA</p>
                </div>
            </div>
            
            ${colis.est_receptionne ? `
                <div class="alert alert-success mt-3">
                    <h6><i class="ti ti-check"></i> Colis déjà réceptionné</h6>
                    <p class="mb-1"><strong>Par:</strong> ${colis.receptionne_par}</p>
                    <p class="mb-1"><strong>Le:</strong> ${new Date(colis.receptionne_le).toLocaleString('fr-FR')}</p>
                    ${colis.notes_reception ? `<p class="mb-0"><strong>Notes:</strong> ${colis.notes_reception}</p>` : ''}
                </div>
            ` : ''}
        `;
        
        // Actions disponibles
        let actionsHtml = '';
        
        if (!colis.est_receptionne && (colis.statut_livraison === 'en_transit' || colis.statut_livraison === 'ramasse')) {
            actionsHtml += `
                <div class="col-md-4">
                    <button type="button" class="btn btn-success btn-lg w-100" onclick="ouvrirModalReception(${colis.id})">
                        <i class="ti ti-package-import"></i><br>
                        Réceptionner le Colis
                    </button>
                </div>
            `;
        }
        
        actionsHtml += `
            <div class="col-md-4">
                <a href="/application/colis/${colis.id}" class="btn btn-info btn-lg w-100">
                    <i class="ti ti-eye"></i><br>
                    Voir Détails Complets
                </a>
            </div>
            
            <div class="col-md-4">
                <button type="button" class="btn btn-secondary btn-lg w-100" onclick="nouveauScan()">
                    <i class="ti ti-refresh"></i><br>
                    Scanner un Autre
                </button>
            </div>
        `;
        
        document.getElementById('actionsContent').innerHTML = actionsHtml;
    }
    
    function cacherColis() {
        document.getElementById('colisDetails').style.display = 'none';
        document.getElementById('actionsSection').style.display = 'none';
        document.getElementById('helpCard').style.display = 'block';
    }
    
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insérer l'alerte au début de la page
        const container = document.querySelector('.container-fluid');
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto-remove après 5 secondes
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
    
    // Fonctions globales
    window.ouvrirModalReception = function(colisId) {
        document.getElementById('colisId').value = colisId;
        const modal = new bootstrap.Modal(document.getElementById('receptionModal'));
        modal.show();
    };
    
    window.nouveauScan = function() {
        document.getElementById('qr_code').value = '';
        document.getElementById('qr_code').focus();
        cacherColis();
    };
});
</script>

<style>
.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
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

#qr-reader {
    margin-top: 20px;
}

#qr-reader-element {
    border: 2px solid #007bff;
    border-radius: 8px;
    overflow: hidden;
}

.card {
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    border-radius: 10px 10px 0 0;
}

#qr_code {
    font-size: 1.1rem;
    padding: 0.75rem;
}
</style>
@endpush
