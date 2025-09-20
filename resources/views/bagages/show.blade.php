@extends('layouts.app')

@section('title', 'Détails du Bagage #' . $bagage->numero)

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Détails du bagage #{{ $bagage->numero }}</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('application.bagages.index') }}">Gestion des Bagages</a></li>
          <li class="breadcrumb-item" aria-current="page">Bagage #{{ $bagage->numero }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
          <h5>Informations du Bagage</h5>
          <div>
            <a href="{{ route('application.bagages.edit', $bagage) }}" class="btn btn-primary btn-sm">
              <i class="ti ti-edit"></i> Modifier
            </a>
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteBagage({{ $bagage->id }})">
              <i class="ti ti-trash"></i> Supprimer
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Numéro du Bagage</label>
              <p class="h6">{{ $bagage->numero }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Possède un ticket ?</label>
              <p class="h6">
                @if($bagage->possede_ticket)
                  <span class="badge bg-success">Oui</span>
                @else
                  <span class="badge bg-secondary">Non</span>
                @endif
              </p>
            </div>
          </div>
        </div>

        @if($bagage->numero_ticket)
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">N° Ticket</label>
              <p class="h6">{{ $bagage->numero_ticket }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Destination</label>
              <p class="h6">{{ $bagage->destination }}</p>
            </div>
          </div>
        </div>
        @else
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Destination</label>
              <p class="h6">{{ $bagage->destination }}</p>
            </div>
          </div>
        </div>
        @endif

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Nom de famille</label>
              <p class="h6">{{ $bagage->nom_famille }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Prénom(s)</label>
              <p class="h6">{{ $bagage->prenom }}</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Téléphone</label>
              <p class="h6">{{ $bagage->telephone }}</p>
            </div>
          </div>
          @if($bagage->valeur)
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Valeur</label>
              <p class="h6">{{ number_format($bagage->valeur, 0, ',', ' ') }} FCFA</p>
            </div>
          </div>
          @endif
        </div>

        @if($bagage->montant || $bagage->poids)
        <div class="row">
          @if($bagage->montant)
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Montant</label>
              <p class="h6">{{ number_format($bagage->montant, 0, ',', ' ') }} FCFA</p>
            </div>
          </div>
          @endif
          @if($bagage->poids)
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label text-muted">Poids</label>
              <p class="h6">{{ $bagage->poids }} kg</p>
            </div>
          </div>
          @endif
        </div>
        @endif

        @if($bagage->contenu)
        <div class="row">
          <div class="col-md-12">
            <div class="mb-3">
              <label class="form-label text-muted">Contenu</label>
              <p class="h6">{{ $bagage->contenu }}</p>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5>Actions</h5>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <a href="{{ route('application.bagages.edit', $bagage) }}" class="btn btn-warning">
            <i class="ti ti-edit"></i> Modifier le bagage
          </a>
          <a href="{{ route('application.bagages.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Retour à la liste
          </a>
          <button type="button" class="btn btn-danger" onclick="deleteBagage({{ $bagage->id }})">
            <i class="ti ti-trash"></i> Supprimer le bagage
          </button>
        </div>
        
        <hr class="my-3">
        
        <div class="mb-2">
          <small class="text-muted">Créé le :</small><br>
          <small>{{ $bagage->created_at->format('d/m/Y à H:i') }}</small>
        </div>
        <div>
          <small class="text-muted">Modifié le :</small><br>
          <small>{{ $bagage->updated_at->format('d/m/Y à H:i') }}</small>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- [ Main Content ] end -->

<script>
function deleteBagage(bagageId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce bagage ? Cette action est irréversible.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/application/bagages/${bagageId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
