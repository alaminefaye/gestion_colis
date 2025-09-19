<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Gestion des colis
            'view_colis',
            'create_colis', 
            'edit_colis',
            'delete_colis',
            
            // Gestion des clients
            'view_clients',
            'create_clients',
            'edit_clients', 
            'delete_clients',
            
            // Gestion des utilisateurs
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Gestion des rôles et permissions
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_permissions',
            
            // Gestion des agences et destinations
            'view_agences',
            'create_agences',
            'edit_agences',
            'delete_agences',
            'view_destinations',
            'create_destinations',
            'edit_destinations',
            'delete_destinations',
            
            // Dashboard et Analytics
            'view_dashboard',
            'view_analytics',
            
            // Paramètres système
            'manage_settings',
            
            // Gestion des livreurs
            'view_livreurs',
            'create_livreurs',
            'edit_livreurs',
            'delete_livreurs',
            
            // Scan QR et livraison
            'scan_qr_colis',
            'ramasser_colis',
            'livrer_colis',
            'view_colis_recuperes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin - Tous les droits
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->syncPermissions(Permission::all());

        // Admin - Gestion complète sauf super admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'view_colis', 'create_colis', 'edit_colis', 'delete_colis',
            'view_clients', 'create_clients', 'edit_clients', 'delete_clients',
            'view_users', 'create_users', 'edit_users',
            'view_agences', 'create_agences', 'edit_agences', 'delete_agences',
            'view_destinations', 'create_destinations', 'edit_destinations', 'delete_destinations',
            'view_livreurs', 'create_livreurs', 'edit_livreurs', 'delete_livreurs',
            'view_colis_recuperes',
            'view_dashboard', 'view_analytics',
        ]);

        // Gestionnaire - Gestion des colis et clients
        $gestionnaireRole = Role::firstOrCreate(['name' => 'gestionnaire']);
        $gestionnaireRole->syncPermissions([
            'view_colis', 'create_colis', 'edit_colis',
            'view_clients', 'create_clients', 'edit_clients',
            'view_agences', 'view_destinations',
            'view_livreurs', 'view_colis_recuperes',
            'view_dashboard', 'view_analytics',
        ]);

        // Employé - Lecture seule + création de colis
        $employeRole = Role::firstOrCreate(['name' => 'employe']);
        $employeRole->syncPermissions([
            'view_colis', 'create_colis',
            'view_clients', 'create_clients',
            'view_agences', 'view_destinations',
            'view_dashboard',
        ]);

        // Livreur - Scan QR et livraison
        $livreurRole = Role::firstOrCreate(['name' => 'livreur']);
        $livreurRole->syncPermissions([
            'scan_qr_colis',
            'ramasser_colis', 
            'livrer_colis',
            'view_dashboard',
        ]);

        // Client - Accès limité
        $clientRole = Role::firstOrCreate(['name' => 'client']);
        $clientRole->syncPermissions([
            'view_dashboard',
        ]);

        // Créer quelques livreurs de test
        $livreurs = [
            [
                'nom' => 'DIOP',
                'prenom' => 'Moussa',
                'telephone' => '77123456',
                'email' => 'moussa.diop@gestioncolis.com',
                'cin' => 'CNI001',
                'adresse' => 'Dakar, Sénégal',
                'date_embauche' => now()->subMonths(6)
            ],
            [
                'nom' => 'FALL',
                'prenom' => 'Aminata',
                'telephone' => '78123456',
                'email' => 'aminata.fall@gestioncolis.com', 
                'cin' => 'CNI002',
                'adresse' => 'Thiès, Sénégal',
                'date_embauche' => now()->subMonths(3)
            ],
            [
                'nom' => 'NDIAYE',
                'prenom' => 'Ibrahima',
                'telephone' => '79123456',
                'email' => 'ibrahima.ndiaye@gestioncolis.com',
                'cin' => 'CNI003',
                'adresse' => 'Saint-Louis, Sénégal', 
                'date_embauche' => now()->subMonth()
            ]
        ];

        foreach ($livreurs as $livreurData) {
            $livreur = \App\Models\Livreur::firstOrCreate(
                ['email' => $livreurData['email']], 
                $livreurData
            );
            
            // Créer un utilisateur pour chaque livreur
            $user = User::firstOrCreate(
                ['email' => $livreurData['email']],
                [
                    'name' => $livreurData['prenom'] . ' ' . $livreurData['nom'],
                    'email' => $livreurData['email'],
                    'password' => Hash::make('livreur123'),
                    'email_verified_at' => now(),
                ]
            );
            
            if (!$user->hasRole('livreur')) {
                $user->assignRole('livreur');
            }
        }

        // Créer un utilisateur super admin par défaut
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@gestioncolis.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@gestioncolis.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }

        // Créer un utilisateur admin par défaut
        $admin = User::firstOrCreate(
            ['email' => 'administrateur@gestioncolis.com'],
            [
                'name' => 'Administrateur',
                'email' => 'administrateur@gestioncolis.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Créer un utilisateur gestionnaire par défaut
        $gestionnaire = User::firstOrCreate(
            ['email' => 'gestionnaire@gestioncolis.com'],
            [
                'name' => 'Gestionnaire',
                'email' => 'gestionnaire@gestioncolis.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        if (!$gestionnaire->hasRole('gestionnaire')) {
            $gestionnaire->assignRole('gestionnaire');
        }

        $this->command->info('Rôles et permissions créés avec succès!');
        $this->command->info('Utilisateurs par défaut:');
        $this->command->info('Super Admin: admin@gestioncolis.com / password123');
        $this->command->info('Admin: administrateur@gestioncolis.com / password123');
        $this->command->info('Gestionnaire: gestionnaire@gestioncolis.com / password123');
        $this->command->info('');
        $this->command->info('Comptes livreurs:');
        $this->command->info('Moussa DIOP: moussa.diop@gestioncolis.com / livreur123');
        $this->command->info('Aminata FALL: aminata.fall@gestioncolis.com / livreur123');
        $this->command->info('Ibrahima NDIAYE: ibrahima.ndiaye@gestioncolis.com / livreur123');
    }
}
