# Checklist pour résoudre les problèmes de connexion en production

## 1. Vérifier la configuration .env sur le serveur
```bash
# Sur votre serveur, vérifiez ces variables dans .env :
DB_CONNECTION=mysql
DB_HOST=127.0.0.1  # ou l'host de votre BDD
DB_PORT=3306
DB_DATABASE=nom_de_votre_bdd
DB_USERNAME=votre_username
DB_PASSWORD=votre_password

APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...  # Doit être généré
```

## 2. Commandes de diagnostic sur le serveur
```bash
# Tester la connexion à la base de données
php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB OK';"

# Vérifier si les utilisateurs existent
php artisan tinker --execute="echo 'Users count: ' . App\Models\User::count();"

# Vérifier les rôles
php artisan tinker --execute="echo 'Roles count: ' . Spatie\Permission\Models\Role::count();"

# Générer une clé d'application si manquante
php artisan key:generate

# Nettoyer les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 3. Permissions des fichiers (sur serveur Linux)
```bash
# Donner les bonnes permissions
sudo chown -R www-data:www-data /path/to/your/project
sudo chmod -R 755 /path/to/your/project
sudo chmod -R 775 /path/to/your/project/storage
sudo chmod -R 775 /path/to/your/project/bootstrap/cache
```

## 4. Test final
Une fois les seeders exécutés, testez avec :
- **Email:** admin@gestioncolis.com
- **Mot de passe:** password123
