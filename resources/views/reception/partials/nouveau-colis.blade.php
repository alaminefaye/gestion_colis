<!-- Formulaire pour nouveau colis -->
<div class="row justify-content-center">
  <div class="col-md-8">
    
    <!-- Information sur le code scanné -->
    <div class="alert alert-info mb-4">
      <div class="d-flex align-items-center">
        <i class="ti ti-info-circle me-3 fs-2"></i>
        <div>
          <h6 class="mb-1">Colis non trouvé dans le système</h6>
          <p class="mb-0">Code scanné : <strong class="text-primary">{{ $code_scanne }}</strong></p>
          <small>Vous pouvez créer un nouveau colis avec ce code.</small>
        </div>
      </div>
    </div>

    <!-- Formulaire de création -->
    <div class="card">
      <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
        <div class="d-flex align-items-center text-white">
          <i class="ti ti-plus-circle me-2 fs-4"></i>
          <h5 class="mb-0">Créer un Nouveau Colis</h5>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ route('application.colis.enregistrer-nouveau') }}" method="POST" id="nouveauColisForm">
          @csrf
          
          <!-- Numéro de colis (obligatoire) -->
          <div class="row mb-4">
            <div class="col-12">
              <label for="numero_courrier" class="form-label">
                <i class="ti ti-barcode me-2 text-primary"></i>
                <strong>Numéro de Colis</strong> 
                <span class="text-danger">*</span>
              </label>
              <input 
                type="text" 
                class="form-control @error('numero_courrier') is-invalid @enderror" 
                id="numero_courrier" 
                name="numero_courrier" 
                value="{{ old('numero_courrier', $code_scanne) }}" 
                readonly
                style="background-color: #f8f9fa; font-weight: bold; font-size: 1.1rem;"
              >
              @error('numero_courrier')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="form-text text-muted">
                <i class="ti ti-lock me-1"></i>Ce numéro est automatiquement rempli depuis le scan
              </small>
            </div>
          </div>

          <!-- Nom complet (optionnel) -->
          <div class="row mb-4">
            <div class="col-12">
              <label for="nom_complet" class="form-label">
                <i class="ti ti-user me-2 text-info"></i>
                <strong>Nom Complet</strong>
                <small class="text-muted">(Optionnel)</small>
              </label>
              <input 
                type="text" 
                class="form-control @error('nom_complet') is-invalid @enderror" 
                id="nom_complet" 
                name="nom_complet" 
                value="{{ old('nom_complet') }}"
                placeholder="Nom complet du destinataire ou expéditeur"
                maxlength="255"
              >
              @error('nom_complet')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="form-text text-muted">
                <i class="ti ti-info-circle me-1"></i>Sera utilisé comme expéditeur et bénéficiaire par défaut
              </small>
            </div>
          </div>

          <!-- Numéro de téléphone (optionnel) -->
          <div class="row mb-4">
            <div class="col-12">
              <label for="numero_telephone" class="form-label">
                <i class="ti ti-phone me-2 text-success"></i>
                <strong>Numéro de Téléphone</strong>
                <small class="text-muted">(Optionnel)</small>
              </label>
              <input 
                type="tel" 
                class="form-control @error('numero_telephone') is-invalid @enderror" 
                id="numero_telephone" 
                name="numero_telephone" 
                value="{{ old('numero_telephone') }}"
                placeholder="Ex: +225 01 02 03 04 05"
                maxlength="20"
              >
              @error('numero_telephone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="form-text text-muted">
                <i class="ti ti-info-circle me-1"></i>Numéro de contact pour ce colis
              </small>
            </div>
          </div>

          <!-- Information importante -->
          <div class="alert alert-warning mb-4">
            <div class="d-flex align-items-start">
              <i class="ti ti-alert-triangle me-2 text-warning fs-4"></i>
              <div>
                <h6 class="mb-2">Information importante</h6>
                <ul class="mb-0 ps-3">
                  <li>Les champs <strong>Nom complet</strong> et <strong>Numéro de téléphone</strong> sont <strong>optionnels</strong></li>
                  <li>Seul le <strong>Numéro de colis</strong> est obligatoire</li>
                  <li>Une fois enregistré, le colis sera <strong>automatiquement marqué comme réceptionné</strong></li>
                  <li>Vous pourrez compléter les autres informations plus tard</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Boutons d'action -->
          <div class="row">
            <div class="col-12">
              <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('application.scan-colis') }}" class="btn btn-outline-secondary btn-lg">
                  <i class="ti ti-arrow-left me-2"></i>Retour au Scan
                </a>
                <button type="submit" class="btn btn-success btn-lg" id="btnEnregistrer">
                  <i class="ti ti-device-floppy me-2"></i>Enregistrer le Colis
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Aperçu des informations -->
    <div class="card mt-4">
      <div class="card-header">
        <h6 class="mb-0">
          <i class="ti ti-eye me-2"></i>Aperçu des informations qui seront enregistrées
        </h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <td class="fw-bold text-muted">N° Courrier:</td>
                <td><span class="badge bg-dark">{{ $code_scanne }}</span></td>
              </tr>
              <tr>
                <td class="fw-bold text-muted">Statut:</td>
                <td><span class="badge bg-success">Réceptionné</span></td>
              </tr>
              <tr>
                <td class="fw-bold text-muted">Destination:</td>
                <td>A définir</td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <td class="fw-bold text-muted">Agence:</td>
                <td>Gare centrale</td>
              </tr>
              <tr>
                <td class="fw-bold text-muted">Type:</td>
                <td><span class="badge bg-secondary">Standard</span></td>
              </tr>
              <tr>
                <td class="fw-bold text-muted">Date:</td>
                <td>{{ now()->format('d/m/Y à H:i') }}</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="alert alert-light mb-0">
          <small class="text-muted">
            <i class="ti ti-info-circle me-1"></i>
            <strong>Notes automatiques :</strong> "Colis créé via scan - Informations à compléter"
          </small>
        </div>
      </div>
    </div>

  </div>
</div>
