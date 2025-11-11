@extends('layouts.app')

@section('title', 'Reçu Colis - ' . $colis->numero_courrier)

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <!-- Breadcrumb -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('application.ecom-product-list') }}">Liste des Colis</a></li>
                            <li class="breadcrumb-item" aria-current="page">Reçu Colis</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">📄 Reçu Colis</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- Success Message -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check me-2"></i>
                    <strong>Succès!</strong> Le colis <strong>{{ $colis->numero_courrier }}</strong> a été créé avec succès.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Receipt Preview -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">📋 Aperçu du Reçu</h5>
                        <div>
                            <button onclick="printReceipt()" class="btn btn-primary btn-sm me-2">
                                <i class="ti ti-printer me-1"></i> Imprimer
                            </button>
                            <a href="{{ route('application.ecom-product-list') }}" class="btn btn-secondary btn-sm">
                                <i class="ti ti-arrow-left me-1"></i> Retour à la liste
                            </a>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-center" style="background-color: #f5f5f5; padding: 30px;">
                        <!-- Receipt Container -->
                        <div id="receipt-container" class="receipt-wrapper">
                            <div class="receipt-content">
                                <!-- Header -->
                                <div class="receipt-header">
                                    <div class="logo-section">
                                        <img src="{{ asset('assets/images/logo.jpeg') }}" alt="IvoryShip Logo" style="height: 45px; max-width: 150px; object-fit: contain;">
                                    </div>
                                    <h3 style="margin: 8px 0; font-size: 16px; font-weight: bold;">IvoryShip</h3>
                                    <p style="margin: 3px 0; font-size: 10px;">Transport & Services Rapides</p>
                                    <div class="divider"></div>
                                </div>

                                <!-- Receipt Title -->
                                <div class="receipt-title">
                                    <h4>REÇU DE COLIS</h4>
                                    <p style="font-size: 9px; margin-top: 3px;">{{ now()->format('d/m/Y à H:i') }}</p>
                                </div>

                                <!-- Colis Information -->
                                <div class="receipt-section">
                                    <div class="section-title">📦 Informations Colis</div>
                                    <table class="receipt-table">
                                        <tr>
                                            <td class="label">N° Courrier:</td>
                                            <td class="value"><strong>{{ $colis->numero_courrier }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="label">Type:</td>
                                            <td class="value">{{ $colis->type_colis }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label">Destination:</td>
                                            <td class="value">{{ $colis->destination }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label">Agence:</td>
                                            <td class="value">{{ $colis->agence_reception }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="divider"></div>

                                <!-- Sender Information -->
                                <div class="receipt-section">
                                    <div class="section-title">📤 Expéditeur</div>
                                    <table class="receipt-table">
                                        <tr>
                                            <td class="label">Nom:</td>
                                            <td class="value">{{ $colis->nom_expediteur }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label">Téléphone:</td>
                                            <td class="value">{{ $colis->telephone_expediteur }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="divider"></div>

                                <!-- Recipient Information -->
                                <div class="receipt-section">
                                    <div class="section-title">📥 Bénéficiaire</div>
                                    <table class="receipt-table">
                                        <tr>
                                            <td class="label">Nom:</td>
                                            <td class="value">{{ $colis->nom_beneficiaire }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label">Téléphone:</td>
                                            <td class="value">{{ $colis->telephone_beneficiaire }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="divider"></div>

                                <!-- Financial Information -->
                                <div class="receipt-section">
                                    <div class="section-title">💰 Détails Financiers</div>
                                    <table class="receipt-table">
                                        <tr>
                                            <td class="label">Valeur Colis:</td>
                                            <td class="value">{{ number_format($colis->valeur_colis, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                        <tr style="border-top: 1px dashed #ccc;">
                                            <td class="label"><strong>Montant à Payer:</strong></td>
                                            <td class="value"><strong>{{ number_format($colis->montant, 0, ',', ' ') }} FCFA</strong></td>
                                        </tr>
                                    </table>
                                </div>

                                @if($colis->description)
                                <div class="divider"></div>
                                <div class="receipt-section">
                                    <div class="section-title">📝 Description</div>
                                    <p style="font-size: 9px; margin: 5px 0; word-wrap: break-word;">{{ $colis->description }}</p>
                                </div>
                                @endif

                                <!-- QR Code Section -->
                                <div class="receipt-section" style="text-align: center; margin-top: 15px;">
                                    <div class="qr-code-container">
                                        {!! $colis->generateQrCode(110) !!}
                                    </div>
                                    <p style="font-size: 8px; margin-top: 5px;">Scannez ce code pour le suivi</p>
                                </div>

                                <!-- Footer -->
                                <div class="receipt-footer">
                                    <div class="divider"></div>
                                    <p style="font-size: 8px; margin: 8px 0; text-align: center;">
                                        Merci de votre confiance!<br>
                                        Pour toute réclamation, contactez-nous<br>
                                        📞 +221 XX XXX XX XX<br>
                                        📧 contact@ivoryship.com
                                    </p>
                                    <p style="font-size: 7px; margin: 5px 0; text-align: center; color: #999;">
                                        Conservez ce reçu jusqu'à la livraison
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* Receipt Container - Format 58mm */
.receipt-wrapper {
    background: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    overflow: hidden;
    width: 58mm; /* Largeur exacte format ticket 58mm */
    max-width: 100%;
}

.receipt-content {
    padding: 12px;
    font-family: 'Courier New', Courier, monospace;
    color: #000;
}

/* Header */
.receipt-header {
    text-align: center;
    margin-bottom: 12px;
}

.logo-section {
    margin-bottom: 8px;
}

/* Receipt Title */
.receipt-title {
    text-align: center;
    margin: 10px 0;
}

.receipt-title h4 {
    font-size: 13px;
    font-weight: bold;
    margin: 0;
    padding: 5px 0;
    background: #f0f0f0;
    border-radius: 4px;
}

/* Divider */
.divider {
    border-top: 1px dashed #999;
    margin: 10px 0;
}

/* Section */
.receipt-section {
    margin: 10px 0;
}

.section-title {
    font-size: 10px;
    font-weight: bold;
    margin-bottom: 6px;
    padding: 3px 0;
    border-bottom: 1px solid #ddd;
}

/* Table */
.receipt-table {
    width: 100%;
    font-size: 9px;
    margin: 5px 0;
}

.receipt-table tr {
    margin: 3px 0;
}

.receipt-table td {
    padding: 2px 0;
    vertical-align: top;
}

.receipt-table .label {
    width: 40%;
    font-weight: 600;
}

.receipt-table .value {
    width: 60%;
    text-align: right;
}

/* QR Code */
.qr-code-container {
    display: flex;
    justify-content: center;
    margin: 10px 0;
}

.qr-code-container svg {
    max-width: 120px;
    height: auto;
}

/* Footer */
.receipt-footer {
    margin-top: 15px;
}

/* Print Styles */
@media print {
    body * {
        visibility: hidden;
    }
    
    #receipt-container, 
    #receipt-container * {
        visibility: visible;
    }
    
    #receipt-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 58mm;
        box-shadow: none;
        border-radius: 0;
    }
    
    .receipt-content {
        padding: 8px;
    }
    
    /* Hide non-printable elements */
    .page-header,
    .breadcrumb,
    .btn,
    .card-header,
    .alert {
        display: none !important;
    }
    
    /* Optimize for thermal printer */
    @page {
        size: 58mm auto;
        margin: 0;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .receipt-wrapper {
        width: 100%;
    }
}
</style>

<script>
function printReceipt() {
    window.print();
}

// Optional: Auto-print on load (can be commented out if not desired)
// window.onload = function() {
//     setTimeout(function() {
//         if (confirm('Voulez-vous imprimer le reçu maintenant?')) {
//             printReceipt();
//         }
//     }, 500);
// };
</script>
@endsection
