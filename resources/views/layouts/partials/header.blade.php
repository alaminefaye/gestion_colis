<!-- [ Header Topbar ] start -->
<header class="pc-header">
  <div class="header-wrapper">
    <!-- [Mobile Media Block] start -->
    <div class="me-auto pc-mob-drp">
      <ul class="list-unstyled">
        <!-- ======= Menu collapse Icon ===== -->
        <li class="pc-h-item pc-sidebar-collapse">
          <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>
        <li class="pc-h-item pc-sidebar-popup">
          <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>
        <li class="dropdown pc-h-item d-inline-flex d-md-none">
          <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
            <i class="ti ti-search"></i>
          </a>
          <div class="dropdown-menu pc-h-dropdown drp-search">
            <form class="px-3">
              <div class="form-group mb-0 d-flex align-items-center">
                <i data-feather="search"></i>
                <input type="search" class="form-control border-0 shadow-none" placeholder="Rechercher...">
              </div>
            </form>
          </div>
        </li>
        <li class="pc-h-item d-none d-md-inline-flex">
          <form class="header-search">
            <i data-feather="search" class="icon-search"></i>
            <input type="search" class="form-control" placeholder="Rechercher un colis...">
          </form>
        </li>
      </ul>
    </div>
    <!-- [Mobile Media Block end] -->

    <div class="ms-auto">
      <ul class="list-unstyled">
        <li class="dropdown pc-h-item">
          <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
            <i class="ti ti-bell"></i>
            <span class="badge bg-success pc-h-badge">3</span>
          </a>
          <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
            <div class="dropdown-header d-flex align-items-center justify-content-between">
              <h5 class="m-0">Notifications</h5>
              <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-x text-danger"></i></a>
            </div>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
              <div class="list-group list-group-flush w-100">
                <a class="list-group-item list-group-item-action">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <div class="avtar avtar-s rounded-circle bg-primary">
                        <i class="ti ti-package text-white"></i>
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <span class="float-end text-muted">3 min</span>
                      <p class="text-body mb-1"><b>Nouveau colis</b> ajouté au système</p>
                      <span class="text-muted">Colis #COL-2024-001</span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <div class="avtar avtar-s rounded-circle bg-success">
                        <i class="ti ti-truck text-white"></i>
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <span class="float-end text-muted">1h</span>
                      <p class="text-body mb-1"><b>Livraison</b> effectuée avec succès</p>
                      <span class="text-muted">Colis #COL-2024-002</span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <div class="avtar avtar-s rounded-circle bg-warning">
                        <i class="ti ti-alert-triangle text-white"></i>
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <span class="float-end text-muted">2h</span>
                      <p class="text-body mb-1"><b>Retard de livraison</b> détecté</p>
                      <span class="text-muted">Colis #COL-2024-003</span>
                    </div>
                  </div>
                </a>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <div class="text-center py-2">
              <a href="#!" class="link-primary">Voir toutes les notifications</a>
            </div>
          </div>
        </li>

        <li class="dropdown pc-h-item">
          <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
            <i class="ti ti-mail"></i>
          </a>
          <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
            <div class="dropdown-header d-flex align-items-center justify-content-between">
              <h5 class="m-0">Messages</h5>
              <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-x text-danger"></i></a>
            </div>
            <div class="dropdown-divider"></div>
            <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
              <div class="list-group list-group-flush w-100">
                <a class="list-group-item list-group-item-action">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
                    </div>
                    <div class="flex-grow-1 ms-1">
                      <span class="float-end text-muted">10:00</span>
                      <p class="text-body mb-1">Message de <b>Client Dupont</b></p>
                      <span class="text-muted">Demande de suivi de colis</span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img src="{{ asset('assets/images/user/avatar-1.jpg') }}" alt="user-image" class="user-avtar">
                    </div>
                    <div class="flex-grow-1 ms-1">
                      <span class="float-end text-muted">09:30</span>
                      <p class="text-body mb-1">Message de <b>Client Martin</b></p>
                      <span class="text-muted">Réclamation livraison</span>
                    </div>
                  </div>
                </a>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <div class="text-center py-2">
              <a href="#!" class="link-primary">Voir tous les messages</a>
            </div>
          </div>
        </li>

        <li class="dropdown pc-h-item header-user-profile">
          <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
            <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
            <span>{{ Auth::user()->name }}</span>
          </a>
          <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
            <div class="dropdown-header">
              <div class="d-flex mb-1">
                <div class="flex-shrink-0">
                  <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar wid-35">
                </div>
                <div class="flex-grow-1 ms-3">
                  <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                  <p class="text-muted mb-1" style="font-size: 0.8rem;">{{ Auth::user()->email }}</p>
                  <span class="badge bg-light-primary border border-primary text-primary">
                    @if(Auth::user()->hasRole('super-admin'))
                      Super Administrateur
                    @elseif(Auth::user()->hasRole('admin'))
                      Administrateur
                    @elseif(Auth::user()->hasRole('gestionnaire'))
                      Gestionnaire
                    @elseif(Auth::user()->hasRole('employe'))
                      Employé
                    @elseif(Auth::user()->hasRole('livreur'))
                      Livreur
                    @elseif(Auth::user()->hasRole('client'))
                      Client
                    @else
                      Utilisateur
                    @endif
                  </span>
                </div>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logoutFormHeader').submit();" class="pc-head-link bg-transparent" title="Déconnexion"><i class="ti ti-power text-danger"></i></a>
              </div>
            </div>
            <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="drp-t1" data-bs-toggle="tab" data-bs-target="#drp-tab-1" type="button" role="tab" aria-controls="drp-tab-1" aria-selected="true">
                  <i class="ti ti-user"></i> Profil
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="drp-t2" data-bs-toggle="tab" data-bs-target="#drp-tab-2" type="button" role="tab" aria-controls="drp-tab-2" aria-selected="false">
                  <i class="ti ti-settings"></i> Paramètres
                </button>
              </li>
            </ul>
            <div class="tab-content" id="mysrpTabContent">
              <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel" aria-labelledby="drp-t1" tabindex="0">
                <a href="{{ route('application.user-profile-edit') }}" class="dropdown-item">
                  <i class="ti ti-edit-circle"></i>
                  <span>Modifier Profil</span>
                </a>
                <a href="{{ route('application.user-profile') }}" class="dropdown-item">
                  <i class="ti ti-user"></i>
                  <span>Voir Profil</span>
                </a>
                <form id="logoutFormHeader" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logoutFormHeader').submit();" class="dropdown-item text-danger">
                  <i class="ti ti-power"></i>
                  <span>Déconnexion</span>
                </a>
              </div>
              <div class="tab-pane fade" id="drp-tab-2" role="tabpanel" aria-labelledby="drp-t2" tabindex="0">
                <a href="#!" class="dropdown-item">
                  <i class="ti ti-help"></i>
                  <span>Support</span>
                </a>
                <a href="#!" class="dropdown-item">
                  <i class="ti ti-user"></i>
                  <span>Paramètres Compte</span>
                </a>
                <a href="#!" class="dropdown-item">
                  <i class="ti ti-lock"></i>
                  <span>Confidentialité</span>
                </a>
                <a href="#!" class="dropdown-item">
                  <i class="ti ti-messages"></i>
                  <span>Feedback</span>
                </a>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</header>
<!-- [ Header ] end -->
