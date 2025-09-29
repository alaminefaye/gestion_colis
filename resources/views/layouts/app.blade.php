<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->
<head>
  <title>@yield('title', 'TSR - Système de Gestion des Colis')</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="TSR - Système de gestion des colis moderne et professionnel">
  <meta name="keywords" content="Gestion Colis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard">
  <meta name="author" content="Gestion Colis Team">

  <!-- [Favicon] icon -->
  <link rel="icon" href="{{ asset('assets/images/logo.jpeg') }}" type="image/x-icon">
  
  <!-- [Google Font] Family -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
  
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
  
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
  
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
  
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
  <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
  
  @stack('styles')
  
  <!-- CSS pour supprimer complètement Mantis et afficher uniquement TSR -->
  <style>
    /* Suppression de tout texte Mantis ajouté automatiquement par le template */
    .b-brand::after, .b-brand::before, 
    .b-brand .logo-lg::after, .b-brand .logo-lg::before,
    .pc-sidebar .m-header .b-brand::after,
    .pc-sidebar .m-header .b-brand::before,
    .tsr-logo-only::after, .tsr-logo-only::before {
      display: none !important;
      content: none !important;
      visibility: hidden !important;
      opacity: 0 !important;
    }
    
    /* Force l'affichage uniquement de l'image TSR */
    .tsr-logo-only {
      overflow: hidden !important;
      position: relative !important;
    }
    
    .tsr-logo-only *:not(img) {
      display: none !important;
    }
    
    .tsr-logo-only img {
      display: block !important;
      visibility: visible !important;
      opacity: 1 !important;
    }
    
    /* Suppression de tous les pseudo-éléments qui pourraient ajouter "Mantis" */
    .pc-sidebar .navbar-wrapper .m-header .b-brand::after,
    .pc-sidebar .navbar-wrapper .m-header .b-brand::before {
      content: none !important;
      display: none !important;
    }
  </style>
</head>
<!-- [Head] end -->

<!-- [Body] Start -->
<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  @include('layouts.partials.sidebar')
  @include('layouts.partials.header')

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      @yield('content')
    </div>
  </div>
  <!-- [ Main Content ] end -->

  @include('layouts.partials.footer')

  <!-- Required Js -->
  <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
  <script src="{{ asset('assets/js/pcoded.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

  <!-- [Additional compatibility scripts removed - files don't exist] -->
  
  @stack('scripts')

  <script>layout_change('light');</script>
  <script>change_box_container('false');</script>
  <script>layout_rtl_change('false');</script>
  <script>preset_change("preset-1");</script>
  <script>font_change("Public-Sans");</script>
</body>
<!-- [Body] end -->
</html>
