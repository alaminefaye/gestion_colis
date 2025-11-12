@extends('layouts.app')

@section('title', 'Ajouter un Colis - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Ajouter un Nouveau Colis</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.ecom-product-list') }}">Colis</a></li>
          <li class="breadcrumb-item" aria-current="page">Ajouter</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    <form action="{{ route('application.ecom-product-store') }}" method="POST" enctype="multipart/form-data" id="colisForm">
      @csrf
      <div class="card">
        <div class="card-header">
          <h5>Informations du Colis</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="numero_courrier">Numéro de courrier <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="numero_courrier" name="numero_courrier" value="{{ old('numero_courrier', $numeroCourrier ?? '') }}" required readonly style="background-color: #f8f9fa; font-weight: bold;">
                <div class="form-text">
                  <i class="ti ti-lock me-1"></i>Ce numéro est généré automatiquement
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="destination">Destination <span class="text-danger">*</span></label>
                <select class="form-select" id="destination" name="destination" required>
                  <option value="">Sélectionner une destination</option>
                  @foreach($destinations as $destination)
                    <option value="{{ $destination->nom }}" {{ old('destination') == $destination->nom ? 'selected' : '' }}>
                      {{ $destination->libelle }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="nom_expediteur">Nom de l'expéditeur <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nom_expediteur" name="nom_expediteur" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="telephone_expediteur">Téléphone de l'expéditeur <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="telephone_expediteur" name="telephone_expediteur" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="nom_beneficiaire">Nom du bénéficiaire <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nom_beneficiaire" name="nom_beneficiaire" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="telephone_beneficiaire">Téléphone du bénéficiaire <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="telephone_beneficiaire" name="telephone_beneficiaire" required>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label" for="montant">Montant <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">FCFA</span>
                  <input type="number" class="form-control" id="montant" name="montant" step="1" min="0" required>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label" for="valeur_colis">Valeur du colis <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">FCFA</span>
                  <input type="number" class="form-control" id="valeur_colis" name="valeur_colis" step="1" min="0" required>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label" for="type_colis">Type de colis <span class="text-danger">*</span></label>
                <select class="form-select" id="type_colis" name="type_colis" required>
                  <option value="">Sélectionner un type</option>
                  <option value="document">Document</option>
                  <option value="colis_standard">Colis standard</option>
                  <option value="colis_fragile">Colis fragile</option>
                  <option value="colis_express">Colis express</option>
                  <option value="colis_urgent">Colis urgent</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="agence_reception">Agence de réception <span class="text-danger">*</span></label>
                <select class="form-select" id="agence_reception" name="agence_reception" required>
                  <option value="">Sélectionner une agence</option>
                  @foreach($agences as $agence)
                    <option value="{{ $agence->nom }}" {{ old('agence_reception') == $agence->nom ? 'selected' : '' }}>
                      {{ $agence->libelle }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="photo_courrier">Photo du courrier</label>
                <input type="file" class="form-control" id="photo_courrier" name="photo_courrier" accept=".jpg,.jpeg,.png,.gif">
                <div class="form-text">Formats acceptés: JPG, PNG, GIF (max 15MB)</div>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description détaillée du colis..."></textarea>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="card">
        <div class="card-body text-end">
          <a href="{{ route('application.ecom-product-list') }}" class="btn btn-outline-secondary me-2">
            <i class="ti ti-arrow-left"></i> Annuler
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-check"></i> Enregistrer
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
<style>
  .dropzone {
    border: 2px dashed #e2e5e8;
    border-radius: 8px;
    background: #f8f9fa;
    min-height: 150px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .dropzone:hover {
    border-color: #4099ff;
    background: #f0f8ff;
  }
  .dropzone.dz-drag-hover {
    border-color: #4099ff;
    background: #f0f8ff;
  }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Auto-complétion pour l'expéditeur
  const telephoneExpediteur = document.getElementById('telephone_expediteur');
  const nomExpediteur = document.getElementById('nom_expediteur');
  
  if (telephoneExpediteur && nomExpediteur) {
    telephoneExpediteur.addEventListener('blur', function() {
      const telephone = this.value.trim();
      if (telephone && telephone.length >= 8) {
        fetchClientByTelephone(telephone, nomExpediteur, 'expéditeur');
      }
    });
  }

  // Auto-complétion pour le bénéficiaire
  const telephoneBeneficiaire = document.getElementById('telephone_beneficiaire');
  const nomBeneficiaire = document.getElementById('nom_beneficiaire');
  
  if (telephoneBeneficiaire && nomBeneficiaire) {
    telephoneBeneficiaire.addEventListener('blur', function() {
      const telephone = this.value.trim();
      if (telephone && telephone.length >= 8) {
        fetchClientByTelephone(telephone, nomBeneficiaire, 'bénéficiaire');
      }
    });
  }

  // Price calculation
  function calculatePrice() {
    const weight = parseFloat(document.getElementById('weight').value) || 0;
    const serviceType = document.getElementById('service_type').value;
    const additionalFees = parseFloat(document.getElementById('additional_fees').value) || 0;
    
    let basePrice = 0;
    let pricePerKg = 0;
    
    switch(serviceType) {
      case 'standard':
        pricePerKg = 15;
        break;
      case 'express':
        pricePerKg = 25;
        break;
      case 'urgent':
        pricePerKg = 35;
        break;
    }
    
    basePrice = weight * pricePerKg;
    document.getElementById('base_price').value = basePrice.toFixed(2);
    document.getElementById('total_price').value = (basePrice + additionalFees).toFixed(2);
  }

  // Event listeners for price calculation
  ['weight', 'service_type', 'additional_fees'].forEach(id => {
    document.getElementById(id).addEventListener('change', calculatePrice);
    document.getElementById(id).addEventListener('input', calculatePrice);
  });

  // Form validation
  document.getElementById('colisForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        field.classList.add('is-invalid');
        isValid = false;
      } else {
        field.classList.remove('is-invalid');
      }
    });
    
    if (isValid) {
      // Here you would typically send the data to the server
      alert('Colis créé avec succès!');
      // window.location.href = "{{ route('application.ecom-product-list') }}";
    } else {
      alert('Veuillez remplir tous les champs obligatoires.');
    }
  });

  // Save draft functionality
  document.getElementById('saveDraft').addEventListener('click', function() {
    // Here you would save the current form data as a draft
    alert('Brouillon sauvegardé!');
  });

  // Auto-complete suggestions (mock data)
  const cities = ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille'];
  
  function setupAutoComplete(inputId) {
    const input = document.getElementById(inputId);
    let suggestions = document.createElement('div');
    suggestions.className = 'autocomplete-suggestions';
    suggestions.style.cssText = 'position: absolute; background: white; border: 1px solid #ccc; border-radius: 4px; max-height: 200px; overflow-y: auto; z-index: 1000; display: none;';
    input.parentNode.style.position = 'relative';
    input.parentNode.appendChild(suggestions);
    
    input.addEventListener('input', function() {
      const value = this.value.toLowerCase();
      suggestions.innerHTML = '';
      
      if (value.length > 1) {
        const matches = cities.filter(city => city.toLowerCase().includes(value));
        if (matches.length > 0) {
          matches.forEach(match => {
            const div = document.createElement('div');
            div.textContent = match;
            div.style.cssText = 'padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #eee;';
            div.addEventListener('click', () => {
              input.value = match;
              suggestions.style.display = 'none';
            });
            div.addEventListener('mouseenter', () => div.style.backgroundColor = '#f8f9fa');
            div.addEventListener('mouseleave', () => div.style.backgroundColor = 'white');
            suggestions.appendChild(div);
          });
          suggestions.style.display = 'block';
        } else {
          suggestions.style.display = 'none';
        }
      } else {
        suggestions.style.display = 'none';
      }
    });
    
    document.addEventListener('click', function(e) {
      if (!input.contains(e.target) && !suggestions.contains(e.target)) {
        suggestions.style.display = 'none';
      }
    });
  }
  
  // Setup autocomplete for city fields
  ['sender_city', 'recipient_city'].forEach(setupAutoComplete);
});

// Fonction pour récupérer les données du client
function fetchClientByTelephone(telephone, nomField, type) {
  // Ajouter un indicateur de chargement
  const originalBg = nomField.style.backgroundColor;
  const originalPlaceholder = nomField.placeholder;
  
  nomField.style.backgroundColor = '#f8f9fa';
  nomField.placeholder = 'Recherche en cours...';
  
  fetch(`/application/clients/api/telephone/${encodeURIComponent(telephone)}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Client trouvé - remplir automatiquement le nom
        nomField.value = data.client.nom_complet;
        nomField.style.backgroundColor = '#d4edda'; // Vert clair
        
        // Afficher une notification discrète
        showNotification(`Client ${type} trouvé : ${data.client.nom_complet}`, 'success');
        
        // Remettre le style normal après 2 secondes
        setTimeout(() => {
          nomField.style.backgroundColor = originalBg;
        }, 2000);
      } else {
        // Client non trouvé - ne pas effacer le champ s'il est déjà rempli
        nomField.style.backgroundColor = '#fff3cd'; // Jaune clair
        nomField.placeholder = `Nom du ${type} (nouveau client)`;
        
        if (!nomField.value.trim()) {
          showNotification(`Nouveau ${type} - veuillez saisir le nom`, 'info');
        }
        
        // Remettre le style normal après 2 secondes
        setTimeout(() => {
          nomField.style.backgroundColor = originalBg;
          nomField.placeholder = originalPlaceholder;
        }, 2000);
      }
    })
    .catch(error => {
      console.error('Erreur lors de la recherche:', error);
      nomField.style.backgroundColor = originalBg;
      nomField.placeholder = originalPlaceholder;
      showNotification('Erreur lors de la recherche du client', 'error');
    });
}

// Fonction pour afficher des notifications discrètes
function showNotification(message, type = 'info') {
  // Créer la notification
  const notification = document.createElement('div');
  notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
  notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;';
  notification.innerHTML = `
    <i class="ti ti-${type === 'success' ? 'check' : type === 'error' ? 'x' : 'info-circle'} me-2"></i>${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  
  // Ajouter au DOM
  document.body.appendChild(notification);
  
  // Supprimer automatiquement après 4 secondes
  setTimeout(() => {
    if (notification.parentNode) {
      notification.remove();
    }
  }, 4000);
}
</script>
@endpush
