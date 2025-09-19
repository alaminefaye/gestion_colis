@extends('layouts.app')

@section('title', 'Tableau de Bord Livreur')

@push('styles')
<style>
.gradient-bg {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.stat-card {
  border-radius: 15px;
  border: none;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}
.stat-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
}
.action-btn {
  border-radius: 12px;
  padding: 15px 20px;
  font-weight: 600;
  border: none;
  transition: all 0.3s ease;
}
.action-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}
</style>
@endpush

@section('content')
  
<!-- Statistiques Modernes -->
<div class="row mb-4">
  <div class="col-lg-3 col-md-6 mb-4">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center p-4">
        <div class="stat-icon mx-auto mb-3" style="background: linear-gradient(135deg, #ff6b6b, #ff8e8e);">
          <i class="ti ti-package"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['total_ramasse'] }}</h3>
        <p class="text-muted mb-0 fw-500">Colis Ramassés</p>
        <div class="progress mt-3" style="height: 4px;">
          <div class="progress-bar bg-danger" style="width: 85%;"></div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 mb-4">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center p-4">
        <div class="stat-icon mx-auto mb-3" style="background: linear-gradient(135deg, #51cf66, #69db7c);">
          <i class="ti ti-check-circle"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['total_livre'] }}</h3>
        <p class="text-muted mb-0 fw-500">Colis Livrés</p>
        <div class="progress mt-3" style="height: 4px;">
          <div class="progress-bar bg-success" style="width: 92%;"></div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 mb-4">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center p-4">
        <div class="stat-icon mx-auto mb-3" style="background: linear-gradient(135deg, #74c0fc, #91d7ff);">
          <i class="ti ti-clock"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['en_cours'] }}</h3>
        <p class="text-muted mb-0 fw-500">En Cours</p>
        <div class="progress mt-3" style="height: 4px;">
          <div class="progress-bar bg-info" style="width: 70%;"></div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 mb-4">
    <div class="card stat-card border-0 h-100">
      <div class="card-body text-center p-4">
        <div class="stat-icon mx-auto mb-3" style="background: linear-gradient(135deg, #845ec2, #9775fa);">
          <i class="ti ti-truck"></i>
        </div>
        <h3 class="mb-1 fw-bold text-dark">{{ $stats['en_transit'] }}</h3>
        <p class="text-muted mb-0 fw-500">En Transit</p>
        <div class="progress mt-3" style="height: 4px;">
          <div class="progress-bar bg-primary" style="width: 60%;"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Actions Rapides Modernes -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card stat-card border-0">
      <div class="card-header bg-transparent border-0 pb-0">
        <h5 class="mb-0 fw-bold">
          <i class="ti ti-bolt me-2 text-primary"></i>Actions Rapides
        </h5>
      </div>
      <div class="card-body pt-3">
        <div class="row g-3">
          <div class="col-lg-4 col-md-6">
            <a href="{{ route('scan.index') }}" class="btn action-btn w-100 text-start" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
              <div class="d-flex align-items-center">
                <div class="stat-icon me-3" style="width: 50px; height: 50px; font-size: 20px; background: rgba(255,255,255,0.2);">
                  <i class="ti ti-qrcode"></i>
                </div>
                <div>
                  <h6 class="mb-0 text-white">Scanner QR</h6>
                  <small class="text-white-50">Scannez un colis</small>
                </div>
              </div>
            </a>
          </div>
          
          @can('view_mes_colis')
          <div class="col-lg-4 col-md-6">
            <a href="{{ route('livreur.mes-colis') }}" class="btn action-btn w-100 text-start" style="background: linear-gradient(135deg, #74c0fc 0%, #91d7ff 100%); color: white;">
              <div class="d-flex align-items-center">
                <div class="stat-icon me-3" style="width: 50px; height: 50px; font-size: 20px; background: rgba(255,255,255,0.2);">
                  <i class="ti ti-package"></i>
                </div>
                <div>
                  <h6 class="mb-0 text-white">Mes Colis</h6>
                  <small class="text-white-50">{{ $stats['en_cours'] }} en cours</small>
                </div>
              </div>
            </a>
          </div>
          @endcan
          
          <div class="col-lg-4 col-md-6">
            <a href="{{ route('application.user-profile') }}" class="btn action-btn w-100 text-start" style="background: linear-gradient(135deg, #51cf66 0%, #69db7c 100%); color: white;">
              <div class="d-flex align-items-center">
                <div class="stat-icon me-3" style="width: 50px; height: 50px; font-size: 20px; background: rgba(255,255,255,0.2);">
                  <i class="ti ti-user"></i>
                </div>
                <div>
                  <h6 class="mb-0 text-white">Mon Profil</h6>
                  <small class="text-white-50">Voir mes infos</small>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Section Colis Récents -->
<div class="row">
  <div class="col-12">
    <div class="card stat-card border-0">
      <div class="card-header bg-transparent border-0 pb-0">
        <h5 class="mb-0 fw-bold">
          <i class="ti ti-clock me-2 text-success"></i>Mes Colis En Cours 
          <span class="badge bg-light-success text-success ms-2">{{ $colisRamasses->count() }}</span>
        </h5>
      </div>
      <div class="card-body">
        @if($colisRamasses->count() > 0)
          <div class="row">
            @foreach($colisRamasses as $colis)
            <div class="col-lg-6 mb-3">
              <div class="card border-0 bg-light">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="mb-0 fw-bold text-primary"># {{ $colis->numero_courrier }}</h6>
                    <span class="badge bg-{{ $colis->statut_color }}">{{ $colis->statut_livraison_label }}</span>
                  </div>
                  <div class="d-flex align-items-center mb-2">
                    <i class="ti ti-user me-2 text-muted"></i>
                    <div>
                      <small class="fw-500">{{ $colis->nom_beneficiaire }}</small><br>
                      <small class="text-muted">{{ $colis->telephone_beneficiaire }}</small>
                    </div>
                  </div>
                  <div class="d-flex align-items-center text-muted">
                    <i class="ti ti-calendar me-2"></i>
                    <small>Ramassé le {{ $colis->ramasse_le?->format('d/m/Y à H:i') }}</small>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-5">
            <div class="mb-4">
              <i class="ti ti-package" style="font-size: 4rem; color: #e9ecef;"></i>
            </div>
            <h6 class="text-muted mb-2">Aucun colis en cours</h6>
            <p class="text-muted mb-4">Commencez par scanner des colis pour vos livraisons</p>
            <a href="{{ route('scan.index') }}" class="btn btn-primary">
              <i class="ti ti-qrcode me-2"></i>Scanner maintenant
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
