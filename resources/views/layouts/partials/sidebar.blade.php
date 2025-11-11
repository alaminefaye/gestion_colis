<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
        <a href="{{ dashboard_route() }}" class="b-brand text-primary d-flex align-items-center justify-content-center tsr-logo-only" style="padding: 15px 0;">
        <!-- ========   Logo TSR uniquement - Suppression complète de Mantis   ============ -->
        <img src="{{ asset('assets/images/logo.jpeg') }}" class="img-fluid" alt="TSR Logo" style="height: 90px; max-width: 110%; object-fit: contain; display: block; margin: 0 auto;">
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin'))
        @can('view_dashboard')
        <li class="pc-item">
          <a href="{{ route('dashboard.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>
        @endcan
        @endif
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin'))
        <!-- @can('view_analytics')
        <li class="pc-item">
          <a href="{{ route('dashboard.analytics') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-chart-line"></i></span>
            <span class="pc-mtext">Analyse des données</span>
          </a>
        </li>
        @endcan -->
        @can('view_livreurs')
        <!-- <li class="pc-item pc-caption">
          <label>Données & Analytics</label>
          <i class="ti ti-chart-bar"></i>
        </li> -->
        <li class="pc-item">
          <a href="{{ route('admin.performances-livreurs.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-chart-pie"></i></span>
            <span class="pc-mtext">Données Livreurs</span>
          </a>
        </li>
        @endcan
        @endif

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
            <li class="pc-item"><a class="pc-link" href="{{ route('application.gestionnaire.dashboard') }}">Mon Tableau de Bord</a></li>
            @endcan
            @can('view_colis')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.ecom-product-list') }}">Liste des Colis</a></li>
            @endcan
            @can('view_colis')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.scan-colis') }}">Scan Colis</a></li>
            @endcan
            @can('create_colis')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.ecom-product-add') }}">Ajouter un Colis</a></li>
            @endcan
            @can('voir_colis_ramasses')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.ecom-product-list-ramasses') }}">Colis Ramassés</a></li>
            @endcan
            @can('view_colis_recuperes')
            <li class="pc-item"><a class="pc-link" href="{{ route('livreurs.colis.recuperes') }}">Colis Récupérés</a></li>
            @endcan
            
            @can('view_colis')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.reception.colis-receptionnes') }}">Colis Réceptionnés</a></li>
            @endcan
            @can('view_colis')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.colis-livres') }}">Colis Livrés</a></li>
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

        @can('view_bagages')
        <!-- Gestion des Bagages -->
        <li class="pc-item pc-caption">
          <label>Gestion Des Bagages</label>
          <i class="ti ti-luggage"></i>
        </li>
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link">
            <span class="pc-micon"><i class="ti ti-luggage"></i></span>
            <span class="pc-mtext">Gestion des Bagages</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @can('view_bagages')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.bagages.dashboard') }}">Tableau de Bord</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('application.bagages.index') }}">Liste des Bagages</a></li>
            @endcan
            @can('create_bagages')
            <li class="pc-item"><a class="pc-link" href="{{ route('application.bagages.create') }}">Ajouter un Bagage</a></li>
            @endcan
          </ul>
        </li>
        @endcan
        
       

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
        
        <!-- Pages spécifiques aux livreurs SEULEMENT -->
        @hasrole('livreur')
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
        @endhasrole
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
<!-- 
        <li class="pc-item">
          <a href="{{ route('application.user-profile') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user"></i></span>
            <span class="pc-mtext">Mon Profil</span>
          </a>
        </li> -->

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
          <img src="{{ asset('assets/images/logo.jpeg') }}" alt="TSR Logo" class="img-fluid mb-2" style="height: 50px;">
          <h5>TSR Système</h5>
          <p>Application de gestion des colis</p>
          <p>Développé par : Al Amine Faye</p>
        </div>
      </div>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->
