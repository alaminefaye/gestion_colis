@extends('layouts.app')

@section('title', 'Dashboard - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Dashboard Gestion des Colis</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
          <li class="breadcrumb-item" aria-current="page">Accueil</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <!-- Première ligne : 4 cartes principales -->
  <div class="col-md-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-2 f-w-400 text-muted">Total Colis</h6>
        <h4 class="mb-3">{{ number_format($stats['total_colis']) }} <span class="badge bg-light-primary border border-primary"><i class="ti ti-trending-up"></i> {{ $stats['total_colis_croissance'] }}%</span></h4>
        <p class="mb-0 text-muted text-sm">Aujourd'hui: <span class="text-primary">{{ $stats['total_colis_jour'] }}</span> nouveaux</p>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-2 f-w-400 text-muted">Total Bagages</h6>
        <h4 class="mb-3">{{ number_format($stats['total_bagages']) }} <span class="badge bg-light-info border border-info"><i class="ti ti-trending-up"></i> {{ $stats['total_bagages_croissance'] }}%</span></h4>
        <p class="mb-0 text-muted text-sm">Aujourd'hui: <span class="text-info">{{ $stats['total_bagages_jour'] }}</span> nouveaux</p>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-2 f-w-400 text-muted">Livraisons Réussies</h6>
        <h4 class="mb-3">{{ number_format($stats['livraisons_reussies']) }} <span class="badge bg-light-success border border-success"><i class="ti ti-trending-up"></i> {{ $stats['taux_reussite'] }}%</span></h4>
        <p class="mb-0 text-muted text-sm">Aujourd'hui: <span class="text-success">{{ $stats['livraisons_reussies_jour'] }}</span> livrées</p>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <h6 class="mb-2 f-w-400 text-muted">En Transit</h6>
        <h4 class="mb-3">{{ $stats['en_transit'] }} <span class="badge bg-light-warning border border-warning"><i class="ti ti-truck"></i> En cours</span></h4>
        <p class="mb-0 text-muted text-sm">Aujourd'hui: <span class="text-warning">{{ $stats['en_transit_jour'] }}</span> en transit</p>
      </div>
    </div>
  </div>
</div>

<!-- Deuxième ligne : Revenus -->
<div class="row mb-4">
  <div class="col-md-12 col-xl-12">
    <div class="card">
      <div class="card-body text-center">
        <h6 class="mb-2 f-w-400 text-muted">Revenus Total</h6>
        <h3 class="mb-3 text-success">{{ number_format($stats['revenus_total']) }} FCFA <span class="badge bg-light-success border border-success"><i class="ti ti-trending-up"></i> {{ $stats['revenus_croissance'] }}%</span></h3>
        <div class="row">
          <div class="col-4">
            <p class="mb-0 text-muted">Total Global</p>
            <h5 class="text-success">{{ number_format($stats['revenus_total']) }} FCFA</h5>
          </div>
          <div class="col-4">
            <p class="mb-0 text-muted">Total Colis</p>
            <h5 class="text-primary">{{ number_format($stats['revenus_colis']) }} FCFA</h5>
          </div>
          <div class="col-4">
            <p class="mb-0 text-muted">Total Bagages</p>
            <h5 class="text-info">{{ number_format($stats['revenus_bagages']) }} FCFA</h5>
          </div>
        </div>
        <hr class="my-3">
        <div class="row">
          <div class="col-4">
            <p class="mb-0 text-muted text-sm">Aujourd'hui</p>
            <h6 class="text-success mb-0">{{ number_format($stats['revenus_jour']) }} FCFA</h6>
          </div>
          <div class="col-4">
            <p class="mb-0 text-muted text-sm">Colis Aujourd'hui</p>
            <h6 class="text-primary mb-0">{{ number_format($stats['revenus_colis_jour']) }} FCFA</h6>
          </div>
          <div class="col-4">
            <p class="mb-0 text-muted text-sm">Bagages Aujourd'hui</p>
            <h6 class="text-info mb-0">{{ number_format($stats['revenus_bagages_jour']) }} FCFA</h6>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">

  <div class="col-md-12 col-xl-8">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="mb-0">Statistiques des Livraisons</h5>
      <ul class="nav nav-pills justify-content-end mb-0" id="chart-tab-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="chart-tab-jour-tab" data-bs-toggle="pill" data-bs-target="#chart-tab-jour" type="button" role="tab" aria-controls="chart-tab-jour" aria-selected="true">Jour</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="chart-tab-profile-tab" data-bs-toggle="pill" data-bs-target="#chart-tab-profile" type="button" role="tab" aria-controls="chart-tab-profile" aria-selected="false">Semaine</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="chart-tab-home-tab" data-bs-toggle="pill" data-bs-target="#chart-tab-home" type="button" role="tab" aria-controls="chart-tab-home" aria-selected="false">Mois</button>
        </li>
      </ul>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="tab-content" id="chart-tab-tabContent">
          <div class="tab-pane show active" id="chart-tab-jour" role="tabpanel" aria-labelledby="chart-tab-jour-tab" tabindex="0">
            <div id="visitor-chart-jour"></div>
          </div>
          <div class="tab-pane" id="chart-tab-profile" role="tabpanel" aria-labelledby="chart-tab-profile-tab" tabindex="0">
            <div id="visitor-chart"></div>
          </div>
          <div class="tab-pane" id="chart-tab-home" role="tabpanel" aria-labelledby="chart-tab-home-tab" tabindex="0">
            <div id="visitor-chart-1"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 col-xl-4">
    <h5 class="mb-3">Aperçu des Revenus</h5>
    <div class="card">
      <div class="card-body">
        <h6 class="mb-2 f-w-400 text-muted">Statistiques Cette Semaine</h6>
        <h3 class="mb-3">{{ number_format($stats['revenus_semaine']) }} FCFA</h3>
        <div id="income-overview-chart"></div>
      </div>
    </div>
  </div>

  <div class="col-md-12 col-xl-8">
    <h5 class="mb-3">Colis Récents</h5>
    <div class="card tbl-card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-borderless mb-0">
            <thead>
              <tr>
                <th>N° SUIVI</th>
                <th>DESTINATAIRE</th>
                <th>DESTINATION</th>
                <th>STATUT</th>
                <th class="text-end">MONTANT</th>
              </tr>
            </thead>
            <tbody>
              @forelse($colisRecents as $colis)
              <tr>
                <td><a href="{{ route('application.ecom-product-show', $colis->id) }}" class="text-muted">{{ $colis->numero_courrier }}</a></td>
                <td>{{ $colis->nom_beneficiaire }}</td>
                <td>{{ $colis->destination }}</td>
                <td>
                  <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-circle text-{{ $colis->statut_color }} f-10 m-r-5"></i>
                    {{ $colis->statut_livraison_label }}
                  </span>
                </td>
                <td class="text-end">{{ number_format($colis->montant) }} FCFA</td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted">Aucun colis récent</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 col-xl-4">
    <h5 class="mb-3">Rapport d'Activité</h5>
    <div class="card">
      <div class="list-group list-group-flush">
        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
          Taux de Livraison<span class="h5 mb-0">{{ $rapportActivite['taux_livraison'] }}%</span>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
          Satisfaction Client<span class="h5 mb-0">{{ $rapportActivite['satisfaction_client'] }}%</span>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
          Colis Problématiques<span class="h5 mb-0">{{ $rapportActivite['colis_problematiques'] }}%</span>
        </a>
      </div>
      <div class="card-body px-2">
        <div id="analytics-report-chart"></div>
      </div>
    </div>
  </div>

  <div class="col-md-12 col-xl-8">
    <h5 class="mb-3">Bagages Récents</h5>
    <div class="card tbl-card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-borderless mb-0">
            <thead>
              <tr>
                <th>N° BAGAGE</th>
                <th>NOM FAMILLE</th>
                <th>DESTINATION</th>
                <th>TICKET</th>
                <th class="text-end">MONTANT</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bagagesRecents as $bagage)
              <tr>
                <td><a href="{{ route('application.bagages.show', $bagage->id) }}" class="text-muted">{{ $bagage->numero }}</a></td>
                <td>{{ $bagage->nom_famille }} {{ $bagage->prenom }}</td>
                <td>{{ $bagage->destination }}</td>
                <td>
                  @if($bagage->possede_ticket)
                    <span class="badge bg-success">{{ $bagage->numero_ticket }}</span>
                  @else
                    <span class="badge bg-secondary">Sans ticket</span>
                  @endif
                </td>
                <td class="text-end">{{ number_format($bagage->montant) }} FCFA</td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted">Aucun bagage récent</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12 col-xl-4">
    <h5 class="mb-3">Détails Revenus</h5>
    <div class="card">
      <div class="list-group list-group-flush">
        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
          Revenus Colis<span class="h5 mb-0">{{ number_format($stats['revenus_colis']) }} FCFA</span>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
          Revenus Bagages<span class="h5 mb-0">{{ number_format($stats['revenus_bagages']) }} FCFA</span>
        </a>
        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between">
          Total Revenus<span class="h5 mb-0 text-success">{{ number_format($stats['revenus_total']) }} FCFA</span>
        </a>
      </div>
    </div>
  </div>

  <div class="col-md-12 col-xl-8">
    <h5 class="mb-3">Rapport des Ventes</h5>
    <div class="card">
      <div class="card-body">
        <h6 class="mb-2 f-w-400 text-muted">Statistiques Cette Semaine</h6>
        <h3 class="mb-0">{{ number_format($stats['revenus_semaine']) }} FCFA</h3>
        <div id="sales-report-chart"></div>
      </div>
    </div>
  </div>
  <div class="col-md-12 col-xl-4">
    <h5 class="mb-3">Transactions Récentes</h5>
    <div class="card">
      <div class="list-group list-group-flush">
        @forelse($transactionsRecentes as $transaction)
        <a href="{{ route('application.ecom-product-show', $transaction->id) }}" class="list-group-item list-group-item-action">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <div class="avtar avtar-s rounded-circle text-{{ $transaction->statut_color }} bg-light-{{ $transaction->statut_color }}">
                @if($transaction->statut_livraison == 'livre')
                  <i class="ti ti-package f-18"></i>
                @elseif($transaction->statut_livraison == 'en_transit')
                  <i class="ti ti-truck f-18"></i>
                @else
                  <i class="ti ti-clock f-18"></i>
                @endif
              </div>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-1">Colis #{{ $transaction->numero_courrier }}</h6>
              <p class="mb-0 text-muted">{{ $transaction->created_at->format('d/m/Y, H:i') }}</p>
            </div>
            <div class="flex-shrink-0 text-end">
              <h6 class="mb-1">+ {{ number_format($transaction->montant) }} FCFA</h6>
              <p class="mb-0 text-muted">{{ $transaction->statut_livraison_label }}</p>
            </div>
          </div>
        </a>
        @empty
        <div class="list-group-item text-center text-muted">
          Aucune transaction récente
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- [Page Specific JS] start -->
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script>
// Données dynamiques passées du contrôleur
const statistiquesLivraisons = @json($statistiquesLivraisons);
const apercuRevenus = @json($apercuRevenus);
</script>
<script src="{{ asset('assets/js/pages/dashboard-default.js') }}"></script>
<!-- [Page Specific JS] end -->
@endpush
