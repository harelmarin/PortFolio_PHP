-- Création de la base de données
CREATE DATABASE IF NOT EXISTS projetb2;

-- Création de l'utilisateur avec les droits
CREATE USER IF NOT EXISTS 'projetb2'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON projetb2.* TO 'projetb2'@'localhost';
FLUSH PRIVILEGES;

-- Utilisation de la base
USE projetb2;

-- Création des tables
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_skills (
    user_id INT,
    skill_id INT,
    level ENUM('débutant', 'intermédiaire', 'avancé', 'expert') NOT NULL,
    PRIMARY KEY (user_id, skill_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_data LONGBLOB,
    external_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insertion des données de test
-- Utilisateurs (password hashé = 'password')
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('user1', 'user1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('user2', 'user2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Compétences
INSERT INTO skills (name) VALUES
('HTML/CSS'),
('JavaScript'),
('PHP'),
('MySQL'),
('React'),
('Node.js'),
('Python'),
('Java'),
('Git');


-- Projets avec images en base64 pour tous les utilisateurs
INSERT INTO projects (user_id, title, description, image_data, external_link) VALUES 
-- Projets admin (user_id = 1)
(1, 'Portfolio Admin', 'Un portfolio professionnel avec administration', 
    FROM_BASE64('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
    'https://example.com/portfolio1'),
(1, 'E-commerce', 'Une plateforme de vente en ligne', 
    FROM_BASE64('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
    'https://example.com/ecommerce'),
(1, 'Blog Tech', 'Un blog sur les nouvelles technologies', 
    FROM_BASE64('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
    'https://example.com/blog'),

-- Projets user1 (user_id = 2)
(2, 'Application Mobile', 'Une application de gestion de tâches', 
    FROM_BASE64('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
    'https://example.com/app1'),
(2, 'Site Vitrine', 'Site vitrine pour une entreprise', 
    FROM_BASE64('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
    'https://example.com/site1'),
(2, 'Jeu Web', 'Un jeu en JavaScript', 
    FROM_BASE64('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
    'https://example.com/game'),

-- Projets user2 (user_id = 3)
(3, 'Dashboard', 'Interface d''administration', 
    FROM_BASE64('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
    'https://example.com/dashboard'),
(3, 'API REST', 'API pour une application mobile', 
    FROM_BASE64('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
    'https://example.com/api'),
(3, 'CMS', 'Système de gestion de contenu', 
    FROM_BASE64('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
    'https://example.com/cms');