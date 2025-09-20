@extends('layouts.app')

@section('title', 'Modifier un Bagage')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Modifier le bagage #{{ $bagage->numero }}</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.bagages.index') }}">Gestion des Bagages</a></li>
          <li class="breadcrumb-item" aria-current="page">Modifier Bagage #{{ $bagage->numero }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('application.bagages.update', $bagage) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5>Modifier le Bagage</h5>
          <a href="{{ route('application.bagages.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Retour
          </a>
        </div>
        <div class="card-body">
                            
                            <!-- Question: Le client possède un ticket ? -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Le client possède un ticket ? <span class="text-danger">*</span></label>
                                    <div class="form-check-inline">
                                        <input class="form-check-input" type="radio" name="possede_ticket" id="ticket_oui" value="1" 
                                               {{ (old('possede_ticket', $bagage->possede_ticket) == '1') ? 'checked' : '' }}>
                                        <label class="form-check-label btn btn-outline-primary" for="ticket_oui">
                                            Oui
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input class="form-check-input" type="radio" name="possede_ticket" id="ticket_non" value="0" 
                                               {{ (old('possede_ticket', $bagage->possede_ticket) == '0') ? 'checked' : '' }}>
                                        <label class="form-check-label btn btn-outline-secondary" for="ticket_non">
                                            Non
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Numéro -->
                                <div class="col-md-6 mb-3">
                                    <label for="numero" class="form-label">Numéro <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="numero" name="numero" 
                                           value="{{ old('numero', $bagage->numero) }}" placeholder="Saisir le numéro du bagage" required>
                                </div>

                                <!-- N° Ticket -->
                                <div class="col-md-6 mb-3" id="numero_ticket_field" style="{{ (old('possede_ticket', $bagage->possede_ticket) == '1') ? 'display: block;' : 'display: none;' }}">
                                    <label for="numero_ticket" class="form-label">N° Ticket</label>
                                    <input type="text" class="form-control" id="numero_ticket" name="numero_ticket" 
                                           value="{{ old('numero_ticket', $bagage->numero_ticket) }}" placeholder="Numéro du ticket">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Destination -->
                                <div class="col-md-6 mb-3">
                                    <label for="destination" class="form-label">Destination <span class="text-danger">*</span></label>
                                    <select class="form-select" id="destination" name="destination" required>
                                        <option value="">Sélectionner une destination</option>
                                        @foreach($destinations as $destination)
                                          <option value="{{ $destination->nom }}" {{ old('destination', $bagage->destination) == $destination->nom ? 'selected' : '' }}>
                                            {{ $destination->libelle }}
                                          </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Téléphone -->
                                <div class="col-md-6 mb-3">
                                    <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="telephone" name="telephone" 
                                           value="{{ old('telephone', $bagage->telephone) }}" placeholder="Numéro de téléphone" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Nom de Famille -->
                                <div class="col-md-6 mb-3">
                                    <label for="nom_famille" class="form-label">Nom de Famille <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nom_famille" name="nom_famille" 
                                           value="{{ old('nom_famille', $bagage->nom_famille) }}" placeholder="Nom de famille" required>
                                </div>

                                <!-- Prénom(s) -->
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="form-label">Prénom(s) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" 
                                           value="{{ old('prenom', $bagage->prenom) }}" placeholder="Prénom(s)" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Valeur -->
                                <div class="col-md-4 mb-3">
                                    <label for="valeur" class="form-label">Valeur</label>
                                    <input type="number" step="0.01" class="form-control" id="valeur" name="valeur" 
                                           value="{{ old('valeur', $bagage->valeur) }}" placeholder="Valeur en FCFA">
                                </div>

                                <!-- Montant -->
                                <div class="col-md-4 mb-3">
                                    <label for="montant" class="form-label">Montant</label>
                                    <input type="number" step="0.01" class="form-control" id="montant" name="montant" 
                                           value="{{ old('montant', $bagage->montant) }}" placeholder="Montant en FCFA">
                                </div>

                                <!-- Poids -->
                                <div class="col-md-4 mb-3">
                                    <label for="poids" class="form-label">Poids (kg)</label>
                                    <input type="number" step="0.01" class="form-control" id="poids" name="poids" 
                                           value="{{ old('poids', $bagage->poids) }}" placeholder="Poids en kg">
                                </div>
                            </div>

                            <!-- Contenu -->
                            <div class="mb-3">
                                <label for="contenu" class="form-label">Contenu</label>
                                <textarea class="form-control" id="contenu" name="contenu" rows="4" 
                                          placeholder="Description du contenu du bagage">{{ old('contenu', $bagage->contenu) }}</textarea>
                            </div>

                            <!-- Boutons -->
                            <div class="text-end">
                                <a href="{{ route('application.bagages.index') }}" class="btn btn-secondary me-2">Annuler</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy"></i> Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>

@push('styles')
<style>
.form-check-inline .form-check-label {
    margin-right: 15px;
    cursor: pointer;
}
.form-check-input:checked + .form-check-label {
    background-color: var(--bs-primary);
    color: white;
    border-color: var(--bs-primary);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ticketOui = document.getElementById('ticket_oui');
    const ticketNon = document.getElementById('ticket_non');
    const numeroTicketField = document.getElementById('numero_ticket_field');
    const numeroTicketInput = document.getElementById('numero_ticket');

    function toggleTicketField() {
        if (ticketOui.checked) {
            numeroTicketField.style.display = 'block';
            numeroTicketInput.required = true;
        } else {
            numeroTicketField.style.display = 'none';
            numeroTicketInput.required = false;
            numeroTicketInput.value = '';
        }
    }

    // Vérifier l'état initial (pour old() values et données existantes)
    if (ticketOui.checked) {
        numeroTicketField.style.display = 'block';
        numeroTicketInput.required = true;
    }

    // Écouter les changements
    ticketOui.addEventListener('change', toggleTicketField);
    ticketNon.addEventListener('change', toggleTicketField);
});
</script>
@endpush
@endsection
