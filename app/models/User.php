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

    public function addSkill(int $userId, string $skillName, string $skillLevel): bool
    {
        try {
            // Commencer une transaction
            $this->pdo->beginTransaction();
            
            // D'abord, vérifier si la compétence existe déjà dans la table skills
            $sql = "SELECT id FROM skills WHERE name = :name";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['name' => $skillName]);
            $skill = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si la compétence n'existe pas, l'ajouter
            if (!$skill) {
                $sql = "INSERT INTO skills (name) VALUES (:name)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['name' => $skillName]);
                $skillId = $this->pdo->lastInsertId();
            } else {
                $skillId = $skill['id'];
            }
            
            // Vérifier si l'utilisateur a déjà cette compétence
            $sql = "SELECT id FROM user_skills WHERE user_id = :user_id AND skill_id = :skill_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'skill_id' => $skillId
            ]);
            
            if ($stmt->fetch()) {
                // La compétence existe déjà pour cet utilisateur
                $this->pdo->rollBack();
                return false;
            }
            
            // Ajouter la relation user_skill
            $sql = "INSERT INTO user_skills (user_id, skill_id, level) 
                    VALUES (:user_id, :skill_id, :level)";
            $stmt = $this->pdo->prepare($sql);
            $success = $stmt->execute([
                'user_id' => $userId,
                'skill_id' => $skillId,
                'level' => $skillLevel
            ]);
            
            // Valider la transaction
            $this->pdo->commit();
            return $success;
            
        } catch (\PDOException $e) {
            // En cas d'erreur, annuler la transaction
            $this->pdo->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateSkill(int $userId, int $skillId, string $skillName, string $skillLevel): bool
    {
        try {
            $sql = "UPDATE user_skills 
                    SET name = :name, level = :level 
                    WHERE id = :skill_id AND user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'name' => $skillName,
                'level' => $skillLevel,
                'skill_id' => $skillId,
                'user_id' => $userId
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function deleteSkill(int $userId, int $skillId): bool
    {
        try {
            $sql = "DELETE FROM user_skills 
                    WHERE id = :skill_id AND user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'skill_id' => $skillId,
                'user_id' => $userId
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère les compétences d'un utilisateur
     */
    public function getUserSkills(int $userId): array
    {
        try {
            $sql = "SELECT s.id, s.name, us.level 
                    FROM user_skills us 
                    JOIN skills s ON us.skill_id = s.id 
                    WHERE us.user_id = :user_id";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function deleteAllUserSkills(int $userId): bool
    {
        try {
            $sql = "DELETE FROM user_skills WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(['user_id' => $userId]);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}