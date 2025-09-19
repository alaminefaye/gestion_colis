@extends('layouts.app')

@section('title', 'Gestion des Livreurs')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Gestion des Livreurs</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item" aria-current="page">Livreurs</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
          <h5>Liste des Livreurs</h5>
          @can('create_livreurs')
          <a href="{{ route('livreurs.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-2"></i>Ajouter un Livreur
          </a>
          @endcan
        </div>
      </div>
      <div class="card-body">
        @if($livreurs->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nom Complet</th>
                  <th>Téléphone</th>
                  <th>Email</th>
                  <th>CIN</th>
                  <th>Statut</th>
                  <th>Colis Ramassés</th>
                  <th>Colis Livrés</th>
                  <th>Date d'embauche</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($livreurs as $livreur)
                <tr>
                  <td>{{ $livreur->id }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avtar avtar-s bg-light-primary me-3">
                        <i class="ti ti-user f-18"></i>
                      </div>
                      <div>
                        <h6 class="mb-0">{{ $livreur->nom_complet }}</h6>
                        <small class="text-muted">{{ $livreur->adresse }}</small>
                      </div>
                    </div>
                  </td>
                  <td>{{ $livreur->telephone }}</td>
                  <td>{{ $livreur->email ?? '-' }}</td>
                  <td>{{ $livreur->cin }}</td>
                  <td>
                    @if($livreur->actif)
                      <span class="badge bg-light-success">Actif</span>
                    @else
                      <span class="badge bg-light-danger">Inactif</span>
                    @endif
                  </td>
                  <td>
                    <span class="badge bg-light-warning">{{ $livreur->colis_ramasses_count }}</span>
                  </td>
                  <td>
                    <span class="badge bg-light-success">{{ $livreur->colis_livres_count }}</span>
                  </td>
                  <td>{{ $livreur->date_embauche->format('d/m/Y') }}</td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="{{ route('livreurs.show', $livreur) }}" class="btn btn-outline-info btn-sm" title="Voir">
                        <i class="ti ti-eye"></i>
                      </a>
                      @can('edit_livreurs')
                      <a href="{{ route('livreurs.edit', $livreur) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                        <i class="ti ti-edit"></i>
                      </a>
                      @endcan
                      @can('delete_livreurs')
                      <button type="button" class="btn btn-outline-danger btn-sm" title="Supprimer" onclick="deleteLivreur({{ $livreur->id }})">
                        <i class="ti ti-trash"></i>
                      </button>
                      @endcan
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div class="d-flex justify-content-center mt-3">
            {{ $livreurs->links() }}
          </div>
        @else
          <div class="text-center py-5">
            <i class="ti ti-users f-48 text-muted mb-3"></i>
            <h6 class="text-muted">Aucun livreur trouvé</h6>
            <p class="text-muted">Commencez par ajouter votre premier livreur.</p>
            @can('create_livreurs')
            <a href="{{ route('livreurs.create') }}" class="btn btn-primary">
              <i class="ti ti-plus me-2"></i>Ajouter un Livreur
            </a>
            @endcan
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Formulaire caché pour suppression -->
<form id="deleteForm" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>

@push('scripts')
<script>
function deleteLivreur(id) {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce livreur ?')) {
    const form = document.getElementById('deleteForm');
    form.action = '/livreurs/' + id;
    form.submit();
  }
}
</script>
@endpush
