@extends('layouts.app')

@section('title', 'Analytics - Gestion des Colis')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">Analytics</h5>
        </div>
        <ul class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
          <li class="breadcrumb-item" aria-current="page">Analytics</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
  <!-- Metrics cards -->
  <div class="col-md-6 col-xl-3">
    <div class="card bg-c-blue order-card">
      <div class="card-body">
        <h6 class="text-white">Colis Traités Aujourd'hui</h6>
        <h2 class="text-white">87</h2>
        <p class="m-b-0 text-white">+24% depuis hier</p>
        <i class="ti ti-package card-icon text-white"></i>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card bg-c-green order-card">
      <div class="card-body">
        <h6 class="text-white">Livraisons Réussies</h6>
        <h2 class="text-white">82</h2>
        <p class="m-b-0 text-white">94.3% de réussite</p>
        <i class="ti ti-truck card-icon text-white"></i>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card bg-c-yellow order-card">
      <div class="card-body">
        <h6 class="text-white">Revenus Jour</h6>
        <h2 class="text-white">€2,845</h2>
        <p class="m-b-0 text-white">+18% depuis hier</p>
        <i class="ti ti-currency-euro card-icon text-white"></i>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-xl-3">
    <div class="card bg-c-red order-card">
      <div class="card-body">
        <h6 class="text-white">Problèmes</h6>
        <h2 class="text-white">5</h2>
        <p class="m-b-0 text-white">-2 depuis hier</p>
        <i class="ti ti-alert-triangle card-icon text-white"></i>
      </div>
    </div>
  </div>

  <!-- Charts section -->
  <div class="col-xl-8 col-md-12">
    <div class="card">
      <div class="card-header">
        <h5>Évolution des Livraisons (7 derniers jours)</h5>
      </div>
      <div class="card-body">
        <div id="deliveries-chart"></div>
      </div>
    </div>
  </div>

  <div class="col-xl-4 col-md-12">
    <div class="card">
      <div class="card-header">
        <h5>Répartition par Statut</h5>
      </div>
      <div class="card-body">
        <div id="status-pie-chart"></div>
      </div>
    </div>
  </div>

  <!-- Performance metrics -->
  <div class="col-xl-6 col-md-12">
    <div class="card">
      <div class="card-header">
        <h5>Performance Mensuelle</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="text-center">
              <h3 class="text-primary">1,247</h3>
              <p class="text-muted mb-0">Colis Livrés</p>
            </div>
          </div>
          <div class="col-6">
            <div class="text-center">
              <h3 class="text-success">96.8%</h3>
              <p class="text-muted mb-0">Taux de Réussite</p>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-6">
            <div class="text-center">
              <h3 class="text-warning">€45,678</h3>
              <p class="text-muted mb-0">Revenus Total</p>
            </div>
          </div>
          <div class="col-6">
            <div class="text-center">
              <h3 class="text-info">2.8j</h3>
              <p class="text-muted mb-0">Délai Moyen</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-6 col-md-12">
    <div class="card">
      <div class="card-header">
        <h5>Top Destinations</h5>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-primary">
              <i class="ti ti-map-pin text-primary"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Paris</h6>
            <p class="text-muted mb-0">342 colis</p>
          </div>
          <div class="flex-shrink-0">
            <span class="badge bg-primary">28%</span>
          </div>
        </div>
        <div class="d-flex align-items-center mb-3">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-success">
              <i class="ti ti-map-pin text-success"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Lyon</h6>
            <p class="text-muted mb-0">198 colis</p>
          </div>
          <div class="flex-shrink-0">
            <span class="badge bg-success">16%</span>
          </div>
        </div>
        <div class="d-flex align-items-center mb-3">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-warning">
              <i class="ti ti-map-pin text-warning"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Marseille</h6>
            <p class="text-muted mb-0">156 colis</p>
          </div>
          <div class="flex-shrink-0">
            <span class="badge bg-warning">13%</span>
          </div>
        </div>
        <div class="d-flex align-items-center">
          <div class="flex-shrink-0">
            <div class="avtar avtar-s bg-light-info">
              <i class="ti ti-map-pin text-info"></i>
            </div>
          </div>
          <div class="flex-grow-1 ms-3">
            <h6 class="mb-0">Toulouse</h6>
            <p class="text-muted mb-0">124 colis</p>
          </div>
          <div class="flex-shrink-0">
            <span class="badge bg-info">10%</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent activities -->
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5>Activités Récentes</h5>
      </div>
      <div class="card-body">
        <div class="timeline">
          <div class="timeline-item">
            <div class="timeline-marker bg-success"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Colis COL-2024-125 livré avec succès</h6>
              <p class="text-muted mb-0">Il y a 15 minutes • Paris, France</p>
            </div>
          </div>
          <div class="timeline-item">
            <div class="timeline-marker bg-warning"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Nouveau colis en préparation</h6>
              <p class="text-muted mb-0">Il y a 1 heure • COL-2024-126</p>
            </div>
          </div>
          <div class="timeline-item">
            <div class="timeline-marker bg-primary"></div>
            <div class="timeline-content">
              <h6 class="mb-1">5 nouveaux clients enregistrés</h6>
              <p class="text-muted mb-0">Il y a 2 heures</p>
            </div>
          </div>
          <div class="timeline-item">
            <div class="timeline-marker bg-info"></div>
            <div class="timeline-content">
              <h6 class="mb-1">Mise à jour système effectuée</h6>
              <p class="text-muted mb-0">Il y a 4 heures</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
.order-card {
  position: relative;
  overflow: hidden;
}
.order-card .card-icon {
  position: absolute;
  right: 20px;
  top: 20px;
  font-size: 60px;
  opacity: 0.3;
}
.bg-c-blue {
  background: linear-gradient(45deg, #4099ff, #73b4ff);
}
.bg-c-green {
  background: linear-gradient(45deg, #2ed8b6, #59e0c5);
}
.bg-c-yellow {
  background: linear-gradient(45deg, #FFB64D, #ffcb80);
}
.bg-c-red {
  background: linear-gradient(45deg, #FF5370, #ff869a);
}
.timeline {
  position: relative;
  padding-left: 30px;
}
.timeline-item {
  position: relative;
  margin-bottom: 20px;
}
.timeline-marker {
  position: absolute;
  left: -35px;
  top: 5px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
}
.timeline::before {
  content: '';
  position: absolute;
  left: -31px;
  top: 0;
  bottom: 0;
  width: 2px;
  background: #e9ecef;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Deliveries Chart
  var deliveriesOptions = {
    series: [{
      name: 'Livraisons',
      data: [78, 85, 92, 88, 95, 89, 102]
    }],
    chart: {
      type: 'area',
      height: 350,
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    xaxis: {
      categories: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']
    },
    colors: ['#4099ff'],
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.1,
        stops: [0, 90, 100]
      }
    }
  };
  var deliveriesChart = new ApexCharts(document.querySelector("#deliveries-chart"), deliveriesOptions);
  deliveriesChart.render();

  // Status Pie Chart
  var statusOptions = {
    series: [85, 10, 5],
    chart: {
      width: 380,
      type: 'pie',
    },
    labels: ['Livrés', 'En Transit', 'Problèmes'],
    colors: ['#2ed8b6', '#FFB64D', '#FF5370'],
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          width: 200
        },
        legend: {
          position: 'bottom'
        }
      }
    }]
  };
  var statusChart = new ApexCharts(document.querySelector("#status-pie-chart"), statusOptions);
  statusChart.render();
});
</script>
@endpush
