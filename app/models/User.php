<?php

namespace App\Models;

use App\Models\Crud; 
use PDO;
use App\Config\Database;


class User extends Crud
{
    protected PDO $pdo;

    public function __construct()
    {
        // On passe le nom de la table au constructeur parent
        parent::__construct('users');
        $this->pdo = Database::getPDO(); 
    }

    /**
     * Trouve un utilisateur par son email
     */
    public function findByEmail(string $email): array|false
    {
        return $this->findOneBy('email', $email);
    }

    /**
     * Trouve un utilisateur par son username
     */
    public function findByUsername(string $username): array|false
    {
        return $this->findOneBy('username', $username);
    }

    /**
     * Vérifie si l'email existe déjà
     */
    public function emailExists(string $email): bool
    {
        return (bool) $this->findByEmail($email);
    }

    public function addSkill(int $userId, int $skillId, string $level): bool
    {
        $sql = "INSERT INTO user_skills (user_id, skill_id, level) VALUES (?, ?, ?)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$userId, $skillId, $level]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère les compétences d'un utilisateur
     */
    public function getUserSkills(int $userId): array
    {
        $sql = "SELECT s.name, us.level 
                FROM user_skills us 
                JOIN skills s ON us.skill_id = s.id 
                WHERE us.user_id = ?";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}