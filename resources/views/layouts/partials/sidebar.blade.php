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
            <li class="pc-item"><a class="pc-link" href="{{ route('application.ecom-product-list') }}">Liste des Colis</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('application.ecom-product-add') }}">Ajouter un Colis</a></li>
          </ul>
        </li>
        
        <li class="pc-item pc-hasmenu">
          <a href="#!" class="pc-link">
            <span class="pc-micon"><i class="ti ti-settings"></i></span>
            <span class="pc-mtext">Gestion d'agence</span>
            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" href="{{ route('application.gestion.destinations') }}">Destinations</a></li>
            <li class="pc-item"><a class="pc-link" href="{{ route('application.gestion.agences') }}">Agences</a></li>
          </ul>
        </li>

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
        <li class="pc-item">
          <a href="{{ route('application.user-list') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
            <span class="pc-mtext">Utilisateurs</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('application.user-profile') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user"></i></span>
            <span class="pc-mtext">Profil</span>
          </a>
        </li>

        
        <li class="pc-item">
          <a href="{{ route('pages.login') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-lock"></i></span>
            <span class="pc-mtext">Login</span>
          </a>
        </li>
        <li class="pc-item">
          <a href="{{ route('pages.register') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
            <span class="pc-mtext">Register</span>
          </a>
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
