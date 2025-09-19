<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Colis</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    <style>
        body {
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            margin: 20px;
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px 30px;
            text-align: center;
            position: relative;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="10" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="90" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="30" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(180deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }
        
        .logo-container {
            position: relative;
            z-index: 2;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .logo i {
            font-size: 2.5rem;
            color: white;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .form-floating {
            position: relative;
            margin-bottom: 20px;
        }
        
        .form-floating > .form-control {
            height: 58px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 20px 16px 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-floating > .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-floating > label {
            padding: 16px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            height: 58px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            border: none;
            border-radius: 12px;
            color: white;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
            border: none;
            border-radius: 12px;
            color: white;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
        
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .demo-accounts {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .demo-account {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .demo-account:hover {
            border-color: #667eea;
            transform: translateY(-1px);
        }
        
        .demo-account:last-child {
            margin-bottom: 0;
        }
        
        @media (max-width: 576px) {
            .login-card {
                margin: 10px;
                border-radius: 15px;
            }
            
            .login-header {
                padding: 30px 20px 20px;
            }
            
            .login-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <!-- Header -->
        <div class="login-header">
            <div class="logo-container">
                <div class="logo">
                    <i class="ti ti-package"></i>
                </div>
                <h4 class="mb-1">Gestion des Colis</h4>
                <p class="mb-0 opacity-75">Connectez-vous à votre compte</p>
            </div>
        </div>
        
        <!-- Body -->
        <div class="login-body">
            <!-- Messages d'erreur -->
            @if ($errors->any())
                <div class="alert-error">
                    <i class="ti ti-alert-circle me-2"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            
            <!-- Messages de succès -->
            @if (session('success'))
                <div class="alert-success">
                    <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            
            <!-- Formulaire de connexion -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="form-floating">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" placeholder="Votre email" required autofocus>
                    <label for="email">Adresse email</label>
                </div>
                
                <div class="form-floating">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="Votre mot de passe" required>
                    <label for="password">Mot de passe</label>
                </div>
                
                <div class="remember-forgot">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-login w-100 text-white" id="loginBtn">
                    <span class="btn-text">
                        <i class="ti ti-login me-2"></i>Se connecter
                    </span>
                    <span class="btn-loading d-none">
                        <span class="spinner me-2"></span>Connexion...
                    </span>
                </button>
            </form>
            
            <!-- Comptes de démonstration -->
            <div class="demo-accounts">
                <h6 class="mb-3"><i class="ti ti-users me-2"></i>Comptes de démonstration</h6>
                
                <div class="demo-account" onclick="fillCredentials('admin@gestioncolis.com', 'password123')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Super Admin</strong>
                            <small class="text-muted d-block">Accès complet au système</small>
                        </div>
                        <span class="badge bg-danger">Super Admin</span>
                    </div>
                </div>
                
                <div class="demo-account" onclick="fillCredentials('administrateur@gestioncolis.com', 'password123')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Administrateur</strong>
                            <small class="text-muted d-block">Gestion administrative</small>
                        </div>
                        <span class="badge bg-warning">Admin</span>
                    </div>
                </div>
                
                <div class="demo-account" onclick="fillCredentials('gestionnaire@gestioncolis.com', 'password123')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Gestionnaire</strong>
                            <small class="text-muted d-block">Gestion des colis et clients</small>
                        </div>
                        <span class="badge bg-primary">Gestionnaire</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    
    <script>
        // Remplir automatiquement les identifiants
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            
            // Animation des labels
            document.getElementById('email').focus();
            document.getElementById('password').focus();
            document.getElementById('email').focus();
        }
        
        // Gestion du formulaire
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');
            
            // Afficher le loader
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            btn.classList.add('loading');
            
            // Simuler un délai minimum pour l'UX
            setTimeout(() => {
                // Le formulaire sera soumis normalement
            }, 300);
        });
        
        // Animation des champs au focus
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
        
        // Masquer les alertes après 5 secondes
        setTimeout(() => {
            document.querySelectorAll('.alert-success, .alert-error').forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>

</html>
