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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            animation: float 30s infinite linear;
            z-index: 1;
        }
        
        @keyframes float {
            0% { transform: translateX(0) translateY(0); }
            50% { transform: translateX(-10px) translateY(-10px); }
            100% { transform: translateX(0) translateY(0); }
        }
        
        .tracking-container {
            min-height: 100vh;
            padding: 24px;
            position: relative;
            z-index: 2;
        }
        
        .tracking-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15), 
                        0 0 0 1px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            max-width: 1000px;
            margin: 0 auto;
            animation: slideInUp 0.8s ease-out;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 48px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .tracking-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 2;
        }
        
        .colis-info {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 32px;
            margin: 32px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .timeline-container {
            padding: 40px 32px;
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.3) 0%, rgba(255, 255, 255, 0.1) 100%);
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
            left: 35px;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, #667eea, #764ba2, #e9ecef);
            border-radius: 2px;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }
        
        .timeline-item {
            position: relative;
            padding: 0 0 40px 90px;
            margin: 0;
            animation: fadeInLeft 0.6s ease-out forwards;
            opacity: 0;
        }
        
        .timeline-item:nth-child(1) { animation-delay: 0.1s; }
        .timeline-item:nth-child(2) { animation-delay: 0.2s; }
        .timeline-item:nth-child(3) { animation-delay: 0.3s; }
        .timeline-item:nth-child(4) { animation-delay: 0.4s; }
        .timeline-item:nth-child(5) { animation-delay: 0.5s; }
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            z-index: 3;
            border: 4px solid white;
            transition: all 0.3s ease;
        }
        
        .timeline-item.completed .timeline-icon {
            transform: scale(1.05);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        }
        
        .timeline-content {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .timeline-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #28a745, #20c997);
            border-radius: 0 2px 2px 0;
        }
        
        .timeline-item.completed .timeline-content {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        
        .timeline-item:not(.completed) .timeline-content {
            opacity: 0.7;
            filter: grayscale(0.3);
        }
        
        .timeline-item:not(.completed) .timeline-content::before {
            background: linear-gradient(to bottom, #6c757d, #adb5bd);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .status-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .progress-bar-custom {
            height: 12px;
            background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
            border-radius: 8px;
            overflow: hidden;
            margin: 20px 0;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .progress-bar-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.3), transparent);
            border-radius: 8px 8px 0 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #28a745 100%);
            border-radius: 8px;
            transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .progress-fill::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: progressShine 2s infinite;
        }
        
        @keyframes progressShine {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .contact-section {
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.9) 0%, rgba(255, 255, 255, 0.8) 100%);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            padding: 32px;
            margin: 32px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }
        
        .info-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.8) 100%);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }
        
        .qr-container {
            background: white;
            padding: 16px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .qr-container:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
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
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" fill="white"/>
                        <path d="M2 17l10 5 10-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12l10 5 10-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="mb-3" style="font-size: 2.5rem; font-weight: 700; letter-spacing: -0.02em; position: relative; z-index: 2;">
                    Suivi de votre colis
                </h1>
                <p class="mb-0" style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.8); position: relative; z-index: 2;">
                    Suivez l'état de votre livraison en temps réel
                </p>
            </div>

            <!-- Informations du colis -->
            <div class="colis-info">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="info-card mb-4">
                            <h3 class="mb-4" style="color: #2d3748; font-weight: 700;">
                                <i class="ti ti-package me-2" style="color: #667eea;"></i>
                                Colis {{ $colis->numero_courrier }}
                            </h3>
                            <div class="mb-4">
                                <span class="status-badge bg-{{ $currentStatus['color'] }} text-white">
                                    <i class="ti ti-circle-check"></i>
                                    {{ $currentStatus['label'] }}
                                </span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill" style="width: 0%; --target-width: {{ $currentStatus['progress'] }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted fw-500">Progression du colis</small>
                                <small class="fw-600" style="color: #667eea;">{{ $currentStatus['progress'] }}%</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="info-card">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-user me-2" style="color: #667eea;"></i>
                                        <h6 class="mb-0 fw-600" style="color: #4a5568;">Expéditeur</h6>
                                    </div>
                                    <p class="mb-1 fw-500" style="color: #2d3748;">{{ $colis->nom_expediteur }}</p>
                                    <small class="text-muted">{{ $colis->telephone_expediteur }}</small>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-user-check me-2" style="color: #667eea;"></i>
                                        <h6 class="mb-0 fw-600" style="color: #4a5568;">Destinataire</h6>
                                    </div>
                                    <p class="mb-1 fw-500" style="color: #2d3748;">{{ $colis->nom_beneficiaire }}</p>
                                    <small class="text-muted">{{ $colis->telephone_beneficiaire }}</small>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-map-pin me-2" style="color: #667eea;"></i>
                                        <h6 class="mb-0 fw-600" style="color: #4a5568;">Destination</h6>
                                    </div>
                                    <p class="mb-0 fw-500" style="color: #2d3748;">{{ $colis->destination }}</p>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-package me-2" style="color: #667eea;"></i>
                                        <h6 class="mb-0 fw-600" style="color: #4a5568;">Type</h6>
                                    </div>
                                    <p class="mb-0 fw-500" style="color: #2d3748;">{{ ucfirst(str_replace('_', ' ', $colis->type_colis)) }}</p>
                                </div>
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
                    <div class="col-lg-8">
                        <div class="info-card">
                            <h5 class="mb-4" style="color: #2d3748; font-weight: 700;">
                                <i class="ti ti-info-circle me-2" style="color: #667eea;"></i>
                                Informations additionnelles
                            </h5>
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-calendar me-2" style="color: #667eea;"></i>
                                        <h6 class="mb-0 fw-600" style="color: #4a5568;">Date de création</h6>
                                    </div>
                                    <p class="mb-0 fw-500" style="color: #2d3748;">{{ $colis->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-coins me-2" style="color: #667eea;"></i>
                                        <h6 class="mb-0 fw-600" style="color: #4a5568;">Valeur déclarée</h6>
                                    </div>
                                    <p class="mb-0 fw-600" style="color: #28a745; font-size: 1.1rem;">
                                        {{ number_format($colis->valeur_colis, 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                                @if($colis->description)
                                <div class="col-12">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-file-text me-2" style="color: #667eea;"></i>
                                        <h6 class="mb-0 fw-600" style="color: #4a5568;">Description</h6>
                                    </div>
                                    <p class="mb-0 fw-500" style="color: #2d3748;">{{ $colis->description }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="info-card">
                            <h6 class="mb-3 fw-600" style="color: #4a5568;">
                                <i class="ti ti-qrcode me-2" style="color: #667eea;"></i>
                                QR Code de suivi
                            </h6>
                            <div class="qr-container d-inline-block">
                                {!! $colis->generateQrCode(120) !!}
                            </div>
                            <p class="small text-muted mt-3 fw-500">{{ $colis->qr_code }}</p>
                            <small class="text-muted">Scannez pour un accès rapide</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section contact -->
            <div class="contact-section">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);">
                        <i class="ti ti-headset text-white" style="font-size: 24px;"></i>
                    </div>
                    <h5 class="mb-3" style="color: #2d3748; font-weight: 700;">Besoin d'aide ?</h5>
                    <p class="text-muted mb-4" style="font-size: 1.05rem;">
                        Notre équipe est à votre disposition pour répondre à toutes vos questions concernant votre livraison.
                    </p>
                </div>
                
                <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                    <a href="tel:+225123456789" class="btn btn-outline-primary btn-lg" style="border-radius: 50px; padding: 12px 24px;">
                        <i class="ti ti-phone me-2"></i>Nous appeler
                    </a>
                    <a href="https://wa.me/225123456789" class="btn btn-outline-success btn-lg" target="_blank" style="border-radius: 50px; padding: 12px 24px;">
                        <i class="ti ti-brand-whatsapp me-2"></i>WhatsApp
                    </a>
                </div>
                
                <div class="d-flex align-items-center justify-content-center">
                    <div class="d-flex align-items-center" style="background: rgba(40, 167, 69, 0.1); padding: 12px 20px; border-radius: 50px; border: 1px solid rgba(40, 167, 69, 0.2);">
                        <i class="ti ti-shield-check me-2" style="color: #28a745;"></i>
                        <small class="fw-500" style="color: #28a745;">
                            Ce lien de suivi est sécurisé et unique à votre colis
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script>
        // Animation d'entrée et gestion des interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Animation de la barre de progression avec effet de brillance
            const progressBar = document.querySelector('.progress-fill');
            if (progressBar) {
                setTimeout(() => {
                    const targetWidth = progressBar.style.getPropertyValue('--target-width') || '{{ $currentStatus["progress"] }}%';
                    progressBar.style.width = targetWidth;
                }, 800);
            }

            // Animation en cascade des éléments
            const animatedElements = [
                { selector: '.info-card', delay: 100 },
                { selector: '.timeline-item', delay: 200 },
                { selector: '.qr-container', delay: 300 },
                { selector: '.contact-section', delay: 400 }
            ];

            animatedElements.forEach(({ selector, delay }) => {
                const elements = document.querySelectorAll(selector);
                elements.forEach((element, index) => {
                    setTimeout(() => {
                        element.style.opacity = '0';
                        element.style.transform = 'translateY(20px)';
                        element.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                        
                        setTimeout(() => {
                            element.style.opacity = '1';
                            element.style.transform = 'translateY(0)';
                        }, 50);
                    }, delay + (index * 100));
                });
            });

            // Effet hover sur les cartes d'information
            document.querySelectorAll('.info-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                    this.style.boxShadow = '0 16px 48px rgba(0, 0, 0, 0.15)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                    this.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.1)';
                });
            });

            // Animation de pulsation pour les éléments complétés
            document.querySelectorAll('.timeline-item.completed .timeline-icon').forEach(icon => {
                setInterval(() => {
                    icon.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1.05)';
                    }, 200);
                }, 3000);
            });

            // Actualisation automatique toutes les 60 secondes avec notification
            let refreshCounter = 60;
            const refreshInterval = setInterval(() => {
                refreshCounter--;
                if (refreshCounter <= 0) {
                    // Afficher une notification avant le refresh
                    const notification = document.createElement('div');
                    notification.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: linear-gradient(135deg, #667eea, #764ba2);
                        color: white;
                        padding: 12px 20px;
                        border-radius: 8px;
                        z-index: 9999;
                        font-weight: 500;
                        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
                    `;
                    notification.textContent = '🔄 Mise à jour des informations...';
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            }, 1000);

            // Fonction pour copier le lien
            window.copyTrackingLink = function() {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const toast = document.createElement('div');
                    toast.style.cssText = `
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        background: #28a745;
                        color: white;
                        padding: 12px 20px;
                        border-radius: 8px;
                        z-index: 9999;
                        font-weight: 500;
                        transform: translateY(100px);
                        transition: transform 0.3s ease;
                    `;
                    toast.textContent = '✅ Lien de suivi copié !';
                    document.body.appendChild(toast);
                    
                    setTimeout(() => toast.style.transform = 'translateY(0)', 100);
                    setTimeout(() => {
                        toast.style.transform = 'translateY(100px)';
                        setTimeout(() => toast.remove(), 300);
                    }, 2000);
                });
            };

            // Double-clic sur le QR code pour copier le lien
            document.querySelector('.qr-container')?.addEventListener('dblclick', copyTrackingLink);
        });
    </script>
</body>
</html>
