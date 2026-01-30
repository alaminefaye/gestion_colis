@extends('layouts.app')

@section('title', 'Rapports & Statistiques')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Rapports & Statistiques</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item" aria-current="page">Rapports</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Filters ] start -->
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Filtres</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Date de début</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">Date de fin</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Statut</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Tous</option>
                                    <option value="livre" {{ $status == 'livre' ? 'selected' : '' }}>Livré</option>
                                    <option value="en_attente" {{ $status == 'en_attente' ? 'selected' : '' }}>En attente / Expédié</option>
                                    <option value="ramasse" {{ $status == 'ramasse' ? 'selected' : '' }}>Ramassé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Tous</option>
                                    <option value="colis" {{ $type == 'colis' ? 'selected' : '' }}>Colis</option>
                                    <option value="bagage" {{ $type == 'bagage' ? 'selected' : '' }}>Bagages</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            <a href="{{ route('reports.index') }}" class="btn btn-secondary">Réinitialiser</a>
                            
                            <div class="float-end" style="float: right;">
                                <a href="{{ route('reports.export-csv', request()->all()) }}" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Exporter Excel
                                </a>
                                <button type="button" onclick="window.print()" class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Exporter PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- [ Filters ] end -->

<!-- [ Statistics ] start -->
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Colis</h6>
                <h4 class="mb-3">{{ $totalColis }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Total Bagages</h6>
                <h4 class="mb-3">{{ $totalBagages }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Montant Total</h6>
                <h4 class="mb-3">{{ number_format($totalMontantColis + $totalMontantBagages) }} FCFA</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-2 f-w-400 text-muted">Livrés</h6>
                <h4 class="mb-3 text-success">{{ $colisLivres }}</h4>
            </div>
        </div>
    </div>
</div>
<!-- [ Statistics ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Détails des opérations</h5>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Code</th>
                                <th>Date</th>
                                <th>Expéditeur</th>
                                <th>Destinataire</th>
                                <th>Statut</th>
                                <th>Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($type == 'all' || $type == 'colis')
                                @foreach($colis as $item)
                                <tr>
                                    <td><span class="badge bg-primary">Colis</span></td>
                                    <td>{{ $item->code_suivi ?? $item->id }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $item->expediteur_nom }}</td>
                                    <td>{{ $item->destinataire_nom }}</td>
                                    <td>
                                        @if($item->statut_livraison == 'livre')
                                            <span class="badge bg-success">Livré</span>
                                        @elseif($item->statut_livraison == 'ramasse')
                                            <span class="badge bg-info">Ramassé</span>
                                        @elseif($item->statut_livraison == 'en_attente')
                                            <span class="badge bg-warning">En attente</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($item->statut_livraison) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->montant) }} FCFA</td>
                                </tr>
                                @endforeach
                            @endif

                            @if($type == 'all' || $type == 'bagage')
                                @foreach($bagages as $item)
                                <tr>
                                    <td><span class="badge bg-info">Bagage</span></td>
                                    <td>{{ $item->code_suivi ?? $item->id }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $item->expediteur_nom }}</td>
                                    <td>{{ $item->destinataire_nom }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($item->statut ?? 'N/A') }}</span></td>
                                    <td>{{ number_format($item->montant) }} FCFA</td>
                                </tr>
                                @endforeach
                            @endif
                            
                            @if($colis->isEmpty() && $bagages->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">Aucune donnée trouvée pour cette période.</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="table-active font-weight-bold">
                                <td colspan="6" class="text-right"><strong>TOTAL GÉNÉRAL</strong></td>
                                <td><strong>{{ number_format($totalMontantColis + $totalMontantBagages) }} FCFA</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

<style>
    @media print {
        .page-header, .card-header h5, form button, form a, .float-end, .breadcrumb {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
        /* Show date range in print */
        .print-header {
            display: block !important;
            margin-bottom: 20px;
            text-align: center;
        }
    }
    .print-header {
        display: none;
    }
</style>

<div class="print-header">
    <h2>Rapport d'activité</h2>
    <p>Période : {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
</div>

@endsection
