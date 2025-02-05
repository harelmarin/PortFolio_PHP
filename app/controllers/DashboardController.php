<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Skill;

class DashboardController extends Controller
{
    private User $userModel;
    private Skill $skillModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->skillModel = new Skill();
    }

    public function index(): void
    {
        // Vérification de l'authentification
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        $user = $this->userModel->find((int)$_SESSION['user_id']);
        $skills = $this->userModel->getUserSkills((int)$_SESSION['user_id']);

        $this->render('dashboard', [
            'title' => 'Tableau de bord',
            'user' => $user,
            'skills' => $skills
        ]);
    }

    public function addSkill(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Non autorisé']);
            exit;
        }

        $skillName = trim(htmlspecialchars($_POST['skill_name'] ?? '', ENT_QUOTES, 'UTF-8'));
        $skillLevel = trim(htmlspecialchars($_POST['skill_level'] ?? '', ENT_QUOTES, 'UTF-8'));
        $userId = (int)$_SESSION['user_id'];

        // Validation des données
        if (empty($skillName)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Le nom de la compétence est requis']);
            exit;
        }

        if (!in_array($skillLevel, ['Débutant', 'Intermédiaire', 'Avancé', 'Expert'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Niveau de compétence invalide']);
            exit;
        }

        $success = $this->userModel->addSkill($userId, $skillName, $skillLevel);

        header('Content-Type: application/json');
        if ($success) {
            echo json_encode([
                'success' => true,
                'message' => 'Compétence ajoutée avec succès'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Cette compétence existe déjà ou une erreur est survenue'
            ]);
        }
        exit;
    }

    public function updateSkills(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        $selectedSkills = $_POST['skills'] ?? [];
        $skillLevels = $_POST['skill_levels'] ?? [];
        $userId = (int)$_SESSION['user_id'];

        try {
            // Supprimer toutes les compétences actuelles de l'utilisateur
            $this->userModel->deleteAllUserSkills($userId);

            // Ajouter les compétences sélectionnées
            foreach ($selectedSkills as $skillName => $value) {
                if (isset($skillLevels[$skillName])) {
                    $level = $skillLevels[$skillName];
                    if (in_array($level, ['Débutant', 'Intermédiaire', 'Avancé', 'Expert'])) {
                        $this->userModel->addSkill($userId, $skillName, $level);
                    }
                }
            }

            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Compétences mises à jour avec succès'
            ];
        } catch (\Exception $e) {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Erreur lors de la mise à jour des compétences'
            ];
        }

        header('Location: /profile');
        exit;
    }

    public function deleteSkill(int $skillId): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false]);
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $success = $this->userModel->deleteSkill($userId, $skillId);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }
} 