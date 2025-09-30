<!-- Informations du colis existant -->
<div class="row">
  <!-- Informations principales -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0">📋 Informations du Colis</h5>
          <span class="badge bg-{{ $colis->statut_color }} fs-6">
            {{ $colis->statut_livraison_label }}
          </span>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <td class="fw-bold text-muted">N° Courrier:</td>
                <td>
                  <span class="badge bg-dark fs-6">{{ $colis->numero_courrier }}</span>
                </td>
              </tr>
              <tr>
                <td class="fw-bold text-muted">QR Code:</td>
                <td>
                  <code>{{ $colis->qr_code }}</code>
                </td>
              </tr>
              <tr>
                <td class="fw-bold text-muted">Destination:</td>
                <td>
                  <span class="badge bg-info">{{ $colis->destination }}</span>
                </td>
              </tr>
              <tr>
                <td class="fw-bold text-muted">Agence:</td>
                <td>{{ $colis->agence_reception ?? 'Non spécifiée' }}</td>
              </tr>
              <tr>
                <td class="fw-bold text-muted">Type:</td>
                <td>
                  <span class="badge bg-secondary">{{ ucfirst($colis->type_colis ?? 'Standard') }}</span>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <td class="fw-bold text-muted">Montant:</td>
                <td>
                  <span class="text-success fw-bold">{{ number_format($colis->montant, 0, ',', ' ') }} FCFA</span>
                </td>
              </tr>
              <tr>
                <td class="fw-bold text-muted">Valeur:</td>
                <td>{{ number_format($colis->valeur_colis, 0, ',', ' ') }} FCFA</td>
              </tr>
              <tr>
                <td class="fw-bold text-muted">Date d'envoi:</td>
                <td>{{ $colis->created_at->format('d/m/Y à H:i') }}</td>
              </tr>
              @if($colis->livre_le)
              <tr>
                <td class="fw-bold text-muted">Livré le:</td>
                <td>{{ $colis->livre_le->format('d/m/Y à H:i') }}</td>
              </tr>
              @endif
              @if($colis->livreurLivraison)
              <tr>
                <td class="fw-bold text-muted">Livré par:</td>
                <td>{{ $colis->livreurLivraison->nom_complet }}</td>
              </tr>
              @endif
            </table>
          </div>
        </div>

        @if($colis->description)
        <div class="row mt-3">
          <div class="col-12">
            <div class="alert alert-light">
              <strong>Description:</strong><br>
              {{ $colis->description }}
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Actions -->
  <div class="col-md-4">
    <!-- Statut et action -->
    <div class="card mb-3">
      <div class="card-header">
        <h6 class="mb-0">🎯 Action de Réception</h6>
      </div>
      <div class="card-body">
        @if(in_array($colis->statut_livraison, ['en_attente', 'ramasse', 'en_transit', 'livre']))
          <div class="alert alert-success mb-3">
            <i class="ti ti-check-circle me-2"></i>
            <strong>Colis prêt à être réceptionné</strong>
          </div>

          <!-- Formulaire de réception -->
          <form action="{{ route('application.colis.receptionner') }}" method="POST" onsubmit="return confirm('Confirmez-vous la réception de ce colis ?')">
            @csrf
            <input type="hidden" name="colis_id" value="{{ $colis->id }}">
            
            <div class="form-group mb-3">
              <label for="notes_reception" class="form-label">Notes (optionnel)</label>
              <textarea name="notes_reception" id="notes_reception" class="form-control" 
                        rows="3" placeholder="Ajouter des notes sur la réception...">{{ old('notes_reception') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success w-100 btn-lg">
              <i class="ti ti-check me-2"></i>Réceptionner le Colis
            </button>
          </form>

        @elseif($colis->statut_livraison === 'receptionne')
          <div class="alert alert-primary">
            <i class="ti ti-info-circle me-2"></i>
            <strong>Colis déjà réceptionné</strong>
            @if($colis->receptionne_le)
              <br><small>Le {{ $colis->receptionne_le->format('d/m/Y à H:i') }}</small>
            @endif
            @if($colis->receptionneParUser)
              <br><small>Par {{ $colis->receptionneParUser->name }}</small>
            @endif
          </div>

        @else
          <div class="alert alert-warning">
            <i class="ti ti-alert-triangle me-2"></i>
            <strong>Colis non eligible</strong>
            <br>Statut actuel : <span class="badge bg-{{ $colis->statut_color }}">{{ $colis->statut_livraison_label }}</span>
            <br><small>Seuls les colis déjà réceptionnés ne peuvent être re-réceptionnés.</small>
          </div>
        @endif
      </div>
    </div>

    <!-- Informations de livraison -->
    @if($colis->statut_livraison === 'livre' || $colis->statut_livraison === 'receptionne')
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">🚚 Informations de Livraison</h6>
      </div>
      <div class="card-body">
        @if($colis->livreurLivraison)
          <p class="mb-2">
            <strong>Livreur:</strong><br>
            {{ $colis->livreurLivraison->nom_complet }}
          </p>
        @endif
        
        @if($colis->livre_le)
          <p class="mb-2">
            <strong>Date de livraison:</strong><br>
            {{ $colis->livre_le->format('d/m/Y à H:i') }}
          </p>
        @endif
        
        @if($colis->notes_livraison)
          <p class="mb-2">
            <strong>Notes de livraison:</strong><br>
            <small class="text-muted">{{ $colis->notes_livraison }}</small>
          </p>
        @endif

        @if($colis->statut_livraison === 'receptionne' && $colis->notes_reception)
          <hr>
          <p class="mb-0">
            <strong>Notes de réception:</strong><br>
            <small class="text-muted">{{ $colis->notes_reception }}</small>
          </p>
        @endif
      </div>
    </div>
    @endif
  </div>
</div>

<!-- Informations expéditeur/bénéficiaire -->
<div class="row mt-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">📤 Expéditeur</h6>
      </div>
      <div class="card-body">
        <p class="mb-2"><strong>Nom:</strong> {{ $colis->nom_expediteur }}</p>
        <p class="mb-0"><strong>Téléphone:</strong> 
          <a href="tel:{{ $colis->telephone_expediteur }}" class="text-primary">
            {{ $colis->telephone_expediteur }}
          </a>
        </p>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">📥 Bénéficiaire</h6>
      </div>
      <div class="card-body">
        <p class="mb-2"><strong>Nom:</strong> {{ $colis->nom_beneficiaire }}</p>
        <p class="mb-0"><strong>Téléphone:</strong> 
          <a href="tel:{{ $colis->telephone_beneficiaire }}" class="text-primary">
            {{ $colis->telephone_beneficiaire }}
          </a>
        </p>
      </div>
    </div>
  </div>
</div>
