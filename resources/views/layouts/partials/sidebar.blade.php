<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="{{ route('dashboard.index') }}" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        <img src="{{ asset('assets/images/logo-dark.svg') }}" class="img-fluid logo-lg" alt="logo">
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item">
          <a href="{{ route('dashboard.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('dashboard.analytics') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-chart-line"></i></span>
            <span class="pc-mtext">Analytics</span>
          </a>
        </li>

        @can('view_colis')
        <li class="pc-item pc-caption">
          <label>Gestion Des Colis</label>
          <i class="ti ti-package"></i>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link">
            <span class="pc-micon"><i class="ti ti-package"></i></span>
            <span class="pc-mtext">Gestion des Colis</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @can('view_colis')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.ecom-product-list') }}">Liste des Colis</a></li>
            @endcan
            @can('create_colis')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.ecom-product-add') }}">Ajouter un Colis</a></li>
            @endcan
            @can('view_colis_recuperes')
            <li class="pc-item"><a class="pc-link" href="{{ route('livreurs.colis.recuperes') }}">Colis Récupérés</a></li>
            @endcan
          </ul>
        </li>
        @endcan
        
        @canany(['view_agences', 'view_destinations'])
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link">
            <span class="pc-micon"><i class="ti ti-settings"></i></span>
            <span class="pc-mtext">Gestion d'agence</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @can('view_destinations')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.gestion.destinations') }}">Destinations</a></li>
            @endcan
            @can('view_agences')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.gestion.agences') }}">Agences</a></li>
            @endcan
          </ul>
        </li>
        @endcanany

        @can('view_clients')
        <li class="pc-item pc-caption">
          <label>Gestion Clients</label>
          <i class="ti ti-users"></i>
        </li>
        <li class="pc-item">
          <a href="{{ route('application.clients.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span>
            <span class="pc-mtext">Liste des Clients</span>
          </a>
        </li>
        @endcan

        @canany(['view_livreurs', 'scan_qr_colis'])
        <li class="pc-item pc-caption">
          <label>Gestion Livraisons</label>
          <i class="ti ti-truck"></i>
        </li>
        @can('view_livreurs')
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span>
            <span class="pc-mtext">Livreurs</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('livreurs.index') }}">Liste des Livreurs</a></li>
            @can('create_livreurs')
            <li class="pc-item"><a class="pc-link" href="{{ route('livreurs.create') }}">Ajouter Livreur</a></li>
            @endcan
          </ul>
        </li>
        @endcan
        @can('scan_qr_colis')
        <li class="pc-item">
          <a href="{{ route('livreur.dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Mon Tableau de Bord</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('scan.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-qrcode"></i></span>
            <span class="pc-mtext">Scan QR Code</span>
          </a>
        </li>
        @endcan
        @can('view_mes_colis')
        <li class="pc-item">
          <a href="{{ route('livreur.mes-colis') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user"></i></span>
            <span class="pc-mtext">Mes Colis</span>
          </a>
        </li>
        @endcan
        @endcanany

        @canany(['view_users', 'view_roles'])
        <li class="pc-item pc-caption">
          <label>Administration</label>
          <i class="ti ti-settings"></i>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users-group"></i></span>
            <span class="pc-mtext">Gestion des Utilisateurs</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @can('view_users')
            <li class="pc-item"><a class="pc-link" href="{{ route('admin.users.index') }}">Liste des Utilisateurs</a></li>
            @endcan
            @can('create_users')
            <li class="pc-item"><a class="pc-link" href="{{ route('admin.users.create') }}">Créer Utilisateur</a></li>
            @endcan
            @can('view_roles')
            <li class="pc-item"><a class="pc-link" href="{{ route('admin.roles.index') }}">Rôles & Permissions</a></li>
            @endcan
          </ul>
        </li>
        @endcanany

        <li class="pc-item">
          <a href="{{ route('application.user-profile') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user"></i></span>
            <span class="pc-mtext">Mon Profil</span>
          </a>
        </li>

        <li class="pc-item">
          <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="pc-link text-danger">
              <span class="pc-micon"><i class="ti ti-logout"></i></span>
              <span class="pc-mtext">Déconnexion</span>
            </a>
          </form>
        </li>
      
      <div class="card text-center">
        <div class="card-body">
          <img src="{{ asset('assets/images/img-navbar-card.png') }}" alt="images" class="img-fluid mb-2">
          <h5>Système de Gestion</h5>
          <p>Application de gestion des colis</p>
          <p>Developpe par : Al Amine Faye</p>
        </div>
      </div>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->
