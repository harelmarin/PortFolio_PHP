-- Création de la base de données 
CREATE DATABASE IF NOT EXISTS projetb2 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Création de l'utilisateur 'projetb2' avec le mot de passe 'password'
CREATE USER IF NOT EXISTS 'projetb2'@'localhost' IDENTIFIED BY 'password';

-- Attribution des droits
GRANT ALL PRIVILEGES ON projetb2.* TO 'projetb2'@'localhost';


FLUSH PRIVILEGES;


USE projetb2;


-- Création de la table users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Création de la table skills
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Création de la table user_skills
CREATE TABLE user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_id INT NOT NULL,
    level ENUM('Débutant', 'Intermédiaire', 'Avancé', 'Expert') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
);
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255),
    external_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);



INSERT INTO skills (name) VALUES 
('HTML'),
('CSS'),
('JavaScript'),
('PHP'),
('MySQL'),
('React'),
('Node.js'),
('Python'); 



