<!DOCTYPE html>
<html lang="en">
<head>
  <title>Connexion - Gestion des Colis</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  
  <!-- [Favicon] icon -->
  <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
  
  <!-- [Google Font] Family -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
  
  <!-- [Tabler Icons] -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
  
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <div class="auth-main">
    <div class="auth-wrapper v1">
      <div class="auth-form">
        <div class="card my-5">
          <div class="card-body">
            <div class="text-center">
              <a href="{{ route('dashboard.index') }}"><img src="{{ asset('assets/images/logo-dark.svg') }}" alt="images" class="img-fluid mb-3"></a>
            </div>
            <h4 class="text-center f-w-500 mb-3">Connexion à votre compte</h4>
            
            <form action="{{ route('dashboard.index') }}" method="GET">
              <div class="mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="Adresse email" required>
              </div>
              <div class="mb-3">
                <input type="password" class="form-control" id="floatingInput1" placeholder="Mot de passe" required>
              </div>
              
              <div class="d-flex mt-1 justify-content-between align-items-center">
                <div class="form-check">
                  <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="">
                  <label class="form-check-label text-muted" for="customCheckc1">Se souvenir de moi</label>
                </div>
                <h6 class="text-secondary f-w-400 mb-0">Mot de passe oublié ?</h6>
              </div>
              
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Se connecter</button>
              </div>
            </form>
            
            <div class="d-flex justify-content-between align-items-end mt-4">
              <h6 class="f-w-500 mb-0">Pas encore de compte ?</h6>
              <a href="{{ route('pages.register') }}" class="link-primary">Créer un compte</a>
            </div>
          </div>
        </div>
      </div>
      <div class="auth-sidefooter">
        <img src="{{ asset('assets/images/img-auth-side.jpg') }}" alt="images" class="img-fluid img-auth-side">
      </div>
    </div>
  </div>

  <!-- Required Js -->
  <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
  <script src="{{ asset('assets/js/pcoded.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

  <script>layout_change('light');</script>
  <script>change_box_container('false');</script>
  <script>layout_rtl_change('false');</script>
  <script>preset_change("preset-1");</script>
  <script>font_change("Public-Sans");</script>
</body>
</html>
