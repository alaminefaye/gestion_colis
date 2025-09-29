<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - TSR Gestion des colis</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <!-- Fallback CDN pour les icônes Tabler -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@2.47.0/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            animation: drift 60s infinite linear;
        }
        
        @keyframes drift {
            0% { transform: translateX(0) translateY(0); }
            25% { transform: translateX(-20px) translateY(-20px); }
            50% { transform: translateX(20px) translateY(-10px); }
            75% { transform: translateX(-10px) translateY(20px); }
            100% { transform: translateX(0) translateY(0); }
        }
        
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            margin: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 
                        0 0 0 1px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            text-align: center;
            padding: 48px 32px 32px;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        }
        
        .logo-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 24px;
        }
        
        .logo {
            width: 90px;
            height: 90px;
            background: transparent;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: none;
            position: relative;
            overflow: hidden;
        }
        
        .logo::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            50% { transform: translateX(100%) translateY(100%) rotate(45deg); }
            100% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        }
        
        .logo svg {
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }
        
        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }
        
        .login-subtitle {
            font-size: 16px;
            color: #64748b;
            font-weight: 400;
            margin: 0;
        }
        
        .login-body {
            padding: 0 32px 48px;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 24px;
        }
        
        .form-input {
            width: 100%;
            height: 56px;
            background: rgba(248, 250, 252, 0.8);
            border: 2px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            padding: 16px 20px 16px 52px;
            font-size: 16px;
            font-weight: 400;
            color: #1a1a1a;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .form-input:focus {
            background: rgba(255, 255, 255, 0.9);
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }
        
        .form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        
        .form-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        
        .form-group:focus-within .form-icon {
            color: #667eea;
            transform: translateY(-50%) scale(1.1);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 32px 0;
        }
        
        .form-check {
            display: flex;
            align-items: center;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            border-radius: 6px;
            border: 2px solid #d1d5db;
            background: transparent;
        }
        
        .form-check-input:checked {
            background: #667eea;
            border-color: #667eea;
        }
        
        .form-check-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
            cursor: pointer;
        }
        
        .btn-login {
            width: 100%;
            height: 56px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 8px;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            border: none;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(21, 128, 61, 0.1) 100%);
            color: #15803d;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .login-container {
                margin: 16px;
            }
            
            .login-card {
                border-radius: 20px;
            }
            
            .login-header {
                padding: 40px 24px 24px;
            }
            
            .login-body {
                padding: 0 24px 40px;
            }
            
            .login-title {
                font-size: 24px;
            }
            
            .form-input {
                height: 52px;
                padding: 14px 18px 14px 48px;
                font-size: 15px;
            }
            
            .form-icon {
                left: 16px;
                font-size: 16px;
            }
            
            .btn-login {
                height: 52px;
                font-size: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 12px;
            }
            
            .login-header {
                padding: 32px 20px 20px;
            }
            
            .login-body {
                padding: 0 20px 32px;
            }
            
            .logo {
                width: 64px;
                height: 64px;
                border-radius: 16px;
            }
            
            .logo i {
                font-size: 28px;
            }
            
            .login-title {
                font-size: 22px;
            }
            
            .login-subtitle {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="logo-wrapper">
                    <div class="logo">
                        <img src="{{ asset('assets/images/logo.jpeg') }}" alt="TSR Logo" style="width: 70px; height: 70px; border-radius: 12px; object-fit: cover;">
                    </div>
                </div>
                <h1 class="login-title">TSR Gestion des colis</h1>
                <p class="login-subtitle">Connectez-vous pour accéder à votre espace</p>
            </div>
            
            <!-- Body -->
            <div class="login-body">
                <!-- Messages d'erreur -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-circle me-2"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
                
                <!-- Messages de succès -->
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif
                
                <!-- Formulaire de connexion -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <div class="form-group">
                        <i class="form-icon ti ti-mail"></i>
                        <input type="email" class="form-input @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="Votre adresse email" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <i class="form-icon ti ti-lock"></i>
                        <input type="password" class="form-input @error('password') is-invalid @enderror" 
                               id="password" name="password" 
                               placeholder="Votre mot de passe" required>
                    </div>
                    
                    <div class="remember-forgot">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-login" id="loginBtn">
                        <span class="btn-text">
                            <i class="ti ti-login me-2"></i>Se connecter
                        </span>
                        <span class="btn-loading d-none">
                            <span class="spinner"></span>Connexion...
                    </span>
                </button>
            </form>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    
    <script>
        // Gestion moderne du formulaire
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');
            
            // Animation des inputs au focus
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        this.parentElement.classList.remove('focused');
                    }
                });
                
                // Si l'input a déjà une valeur au chargement
                if (input.value.trim()) {
                    input.parentElement.classList.add('focused');
                }
            });
            
            // Gestion de la soumission du formulaire
            form.addEventListener('submit', function(e) {
                // Validation côté client
                const email = document.getElementById('email');
                const password = document.getElementById('password');
                
                if (!email.value.trim() || !password.value.trim()) {
                    e.preventDefault();
                    return;
                }
                
                // Animation du bouton
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
                btn.disabled = true;
                btn.style.opacity = '0.8';
                
                // Délai minimum pour l'UX (300ms)
                setTimeout(() => {
                    // Le formulaire continue sa soumission normale
                }, 300);
            });
            
            // Masquer automatiquement les alertes
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.style.transition = 'all 0.5s ease';
                    alert.style.transform = 'translateY(-20px)';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                });
            }, 5000);
            
            // Animation d'apparition de la carte
            const loginCard = document.querySelector('.login-card');
            setTimeout(() => {
                loginCard.style.transform = 'translateY(0)';
                loginCard.style.opacity = '1';
            }, 100);
        });
    </script>
</body>

</html>
