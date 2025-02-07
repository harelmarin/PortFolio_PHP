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
            
            // Valide la transaction
            $this->pdo->commit();
            return $success;
            
        } catch (\PDOException $e) {
            // En cas d'erreur, annule la transaction
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
        $sql = "DELETE FROM user_skills WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId]);
    }

    public function getUserProjects(int $userId): array
    {
        $sql = "SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function addProject(int $userId, string $title, string $description, string $imageData, ?string $externalLink = null): bool
    {
        try {
            $sql = "INSERT INTO projects (user_id, title, description, image_data, external_link) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $userId,
                $title,
                $description,
                $imageData,
                $externalLink
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de l'ajout du projet : " . $e->getMessage());
            return false;
        }
    }

    public function deleteProject(int $userId, int $projectId): bool
    {
        try {
            $sql = "DELETE FROM projects WHERE id = ? AND user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$projectId, $userId]);
            
            // Vérifie si une ligne a été supprimée
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression du projet : " . $e->getMessage());
            return false;
        }
    }

    public function findFiltered(string $search = '', string $role = ''): array
    {
        try {
            $sql = "SELECT * FROM users WHERE 1=1";
            $params = [];

            if (!empty($search)) {
                $sql .= " AND (username LIKE :search OR email LIKE :search)";
                $params['search'] = "%$search%";
            }

            if (!empty($role)) {
                $sql .= " AND role = :role";
                $params['role'] = $role;
            }

            $sql .= " ORDER BY created_at DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function addSkillById(int $userId, int $skillId, string $level): bool
    {
        $sql = "INSERT INTO user_skills (user_id, skill_id, level) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $skillId, $level]);
    }
}