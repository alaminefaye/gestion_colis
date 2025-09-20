<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi du Colis {{ $colis->numero_courrier }} - Gestion des Colis</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@2.47.0/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
        }
        
        .tracking-container {
            min-height: 100vh;
            padding: 20px;
        }
        
        .tracking-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            padding: 32px;
            text-align: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .tracking-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
        }
        
        .colis-info {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .timeline-container {
            padding: 32px;
        }
        
        .timeline {
            position: relative;
            padding: 0;
            list-style: none;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 30px;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #e9ecef, #dee2e6);
        }
        
        .timeline-item {
            position: relative;
            padding: 0 0 32px 70px;
            margin: 0;
        }
        
        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 2;
        }
        
        .timeline-content {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-left: 4px solid transparent;
        }
        
        .timeline-item.completed .timeline-content {
            border-left-color: #28a745;
        }
        
        .timeline-item:not(.completed) .timeline-content {
            border-left-color: #6c757d;
            opacity: 0.6;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .progress-bar-custom {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin: 16px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            border-radius: 4px;
            transition: width 0.6s ease;
        }
        
        .contact-section {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 24px;
            margin: 24px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .tracking-container { padding: 10px; }
            .header-section { padding: 24px 20px; }
            .colis-info, .timeline-container, .contact-section { padding: 20px; margin: 16px; }
            .timeline-item { padding-left: 50px; }
            .timeline::before { left: 20px; }
            .timeline-icon { width: 40px; height: 40px; font-size: 16px; }
        }
    </style>
</head>

<body>
    <div class="tracking-container">
        <div class="tracking-card">
            <!-- Header -->
            <div class="header-section">
                <div class="tracking-logo">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" fill="white"/>
                        <path d="M2 17l10 5 10-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12l10 5 10-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="mb-2">Suivi de votre colis</h1>
                <p class="text-muted mb-0">Suivez l'état de votre livraison en temps réel</p>
            </div>

            <!-- Informations du colis -->
            <div class="colis-info">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="mb-3">
                            <i class="ti ti-package text-primary me-2"></i>Colis {{ $colis->numero_courrier }}
                        </h3>
                        <div class="mb-3">
                            <span class="status-badge bg-{{ $currentStatus['color'] }} text-white">
                                <i class="ti ti-circle-check"></i>
                                {{ $currentStatus['label'] }}
                            </span>
                        </div>
                        <div class="progress-bar-custom">
                            <div class="progress-fill" style="width: {{ $currentStatus['progress'] }}%"></div>
                        </div>
                        <small class="text-muted">Progression : {{ $currentStatus['progress'] }}%</small>
                    </div>
                    <div class="col-md-6">
                        <div class="row g-3">
                            <div class="col-12">
                                <h6 class="text-muted mb-1">Expéditeur</h6>
                                <p class="mb-0 fw-500">{{ $colis->nom_expediteur }}</p>
                                <small class="text-muted">{{ $colis->telephone_expediteur }}</small>
                            </div>
                            <div class="col-12">
                                <h6 class="text-muted mb-1">Destinataire</h6>
                                <p class="mb-0 fw-500">{{ $colis->nom_beneficiaire }}</p>
                                <small class="text-muted">{{ $colis->telephone_beneficiaire }}</small>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Destination</h6>
                                <p class="mb-0">{{ $colis->destination }}</p>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Type</h6>
                                <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $colis->type_colis)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="timeline-container">
                <h4 class="mb-4">
                    <i class="ti ti-clock-history text-primary me-2"></i>Historique du colis
                </h4>
                
                <ul class="timeline">
                    @foreach($timeline as $event)
                    <li class="timeline-item {{ $event['completed'] ? 'completed' : '' }}">
                        <div class="timeline-icon bg-{{ $event['color'] }}">
                            <i class="{{ $event['icon'] }}"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">{{ $event['title'] }}</h6>
                                @if($event['date'])
                                <small class="text-muted">
                                    {{ $event['date']->format('d/m/Y à H:i') }}
                                </small>
                                @endif
                            </div>
                            <p class="text-muted mb-0">{{ $event['description'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- QR Code et informations supplémentaires -->
            <div class="colis-info">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-3">Informations additionnelles</h5>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <h6 class="text-muted mb-1">Date de création</h6>
                                <p class="mb-0">{{ $colis->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div class="col-sm-6">
                                <h6 class="text-muted mb-1">Valeur déclarée</h6>
                                <p class="mb-0 text-success fw-600">{{ number_format($colis->valeur_colis, 0, ',', ' ') }} FCFA</p>
                            </div>
                            @if($colis->description)
                            <div class="col-12">
                                <h6 class="text-muted mb-1">Description</h6>
                                <p class="mb-0">{{ $colis->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <h6 class="text-muted mb-3">QR Code de suivi</h6>
                        <div class="d-inline-block p-3 bg-white rounded-3 shadow-sm">
                            {!! $colis->generateQrCode(120) !!}
                        </div>
                        <p class="small text-muted mt-2">{{ $colis->qr_code }}</p>
                    </div>
                </div>
            </div>

            <!-- Section contact -->
            <div class="contact-section">
                <h5 class="mb-3">
                    <i class="ti ti-headset text-primary me-2"></i>Besoin d'aide ?
                </h5>
                <p class="text-muted mb-3">
                    Notre équipe est à votre disposition pour répondre à toutes vos questions concernant votre livraison.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="tel:+221123456789" class="btn btn-outline-primary">
                        <i class="ti ti-phone me-2"></i>Nous appeler
                    </a>
                    <a href="https://wa.me/221123456789" class="btn btn-outline-success" target="_blank">
                        <i class="ti ti-brand-whatsapp me-2"></i>WhatsApp
                    </a>
                </div>
                <hr class="my-4">
                <small class="text-muted">
                    <i class="ti ti-shield-check text-success me-1"></i>
                    Ce lien de suivi est sécurisé et unique à votre colis
                </small>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script>
        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            // Animation de la barre de progression
            const progressBar = document.querySelector('.progress-fill');
            if (progressBar) {
                setTimeout(() => {
                    progressBar.style.width = '{{ $currentStatus["progress"] }}%';
                }, 500);
            }

            // Animation des éléments de timeline
            const timelineItems = document.querySelectorAll('.timeline-item');
            timelineItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    item.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, 50);
                }, index * 200);
            });

            // Actualisation automatique toutes les 30 secondes
            setInterval(() => {
                window.location.reload();
            }, 30000);
        });

        // Copier le lien de suivi
        function copyTrackingLink() {
            navigator.clipboard.writeText(window.location.href);
            alert('Lien de suivi copié !');
        }
    </script>
</body>
</html>
