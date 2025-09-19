-- Script SQL pour créer manuellement un compte admin
-- À exécuter dans votre base de données de production

-- 1. Créer l'utilisateur admin
INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) 
VALUES (
    'Super Admin', 
    'admin@gestioncolis.com', 
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password123
    NOW(), 
    NOW(), 
    NOW()
) ON DUPLICATE KEY UPDATE id=id;

-- 2. Créer les rôles (si pas déjà fait)
INSERT IGNORE INTO roles (name, guard_name, created_at, updated_at) VALUES
('super-admin', 'web', NOW(), NOW()),
('admin', 'web', NOW(), NOW()),
('gestionnaire', 'web', NOW(), NOW()),
('employe', 'web', NOW(), NOW()),
('client', 'web', NOW(), NOW());

-- 3. Assigner le rôle super-admin à l'utilisateur
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'App\\Models\\User', u.id
FROM roles r, users u 
WHERE r.name = 'super-admin' AND u.email = 'admin@gestioncolis.com'
ON DUPLICATE KEY UPDATE role_id=role_id;
