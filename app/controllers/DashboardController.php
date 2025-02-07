<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Skill;

/**
 * Contrôleur gérant le tableau de bord utilisateur
 * Permet la gestion des compétences et des projets
 */
class DashboardController extends Controller
{
    private User $userModel;
    private Skill $skillModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->skillModel = new Skill();
    }

    /**
     * Affiche le tableau de bord avec les compétences de l'utilisateur
     * 
     * @return void
     */
    public function index(): void
    {
        // Vérification de l'authentification
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        $user = $this->userModel->find((int)$_SESSION['user_id']);
        $skills = $this->userModel->getUserSkills((int)$_SESSION['user_id']);
        $availableSkills = $this->skillModel->findAll();

        $this->render('dashboard', [
            'title' => 'Tableau de bord',
            'user' => $user,
            'skills' => $skills,
            'availableSkills' => $availableSkills
        ]);
    }

    /**
     * Ajoute une nouvelle compétence pour l'utilisateur
     * 
     * @return void
     */
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

    /**
     * Met à jour les compétences de l'utilisateur
     * 
     * @return void
     */
    public function updateSkills(): void
    {
        // Vérifications CSRF et session
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header('Location: /dashboard');
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        $userId = (int)$_SESSION['user_id'];

        try {
            // Commencer par supprimer toutes les compétences existantes de l'utilisateur
            $this->userModel->deleteAllUserSkills($userId);

            // Vérifier si on a reçu des compétences
            if (!isset($_POST['skills']) || !isset($_POST['skill_levels'])) {
                throw new \Exception('Aucune compétence sélectionnée');
            }

            // Ajouter les nouvelles compétences
            foreach ($_POST['skills'] as $skillId => $value) {
                $skillId = (int)$skillId;
                $level = $_POST['skill_levels'][$skillId] ?? null;

                if (!$level || !in_array($level, ['Débutant', 'Intermédiaire', 'Avancé', 'Expert'])) {
                    continue;
                }

                $this->userModel->addSkillById($userId, $skillId, $level);
            }

            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Compétences mises à jour avec succès'
            ];

        } catch (\Exception $e) {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Erreur : ' . $e->getMessage()
            ];
        }

        header('Location: /dashboard');
        exit;
    }

    /**
     * Affiche la page de gestion des projets
     * 
     * @return void
     */
    public function projects(): void
    {
        // Vérification de l'authentification
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        // Récupération des projets de l'utilisateur
        $projects = $this->userModel->getUserProjects((int)$_SESSION['user_id']);

        $this->render('projects', [
            'title' => 'Gérer mes projets',
            'projects' => $projects
        ]);
    }

    /**
     * Ajoute un nouveau projet
     * 
     * @return void
     */
    public function addProject(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim(htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8'));
            $description = trim(htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8'));
            $externalLink = trim(htmlspecialchars($_POST['external_link'] ?? '', ENT_QUOTES, 'UTF-8'));

            // Traitement de l'image
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageData = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
                
                $success = $this->userModel->addProject(
                    (int)$_SESSION['user_id'],
                    $title,
                    $description,
                    $imageData,
                    $externalLink
                );

                if ($success) {
                    $_SESSION['message'] = ['type' => 'success', 'text' => 'Projet ajouté avec succès'];
                } else {
                    $_SESSION['message'] = ['type' => 'error', 'text' => 'Erreur lors de l\'ajout du projet'];
                }
            }
        }

        header('Location: /dashboard/projects');
        exit;
    }

    /**
     * Supprime un projet spécifique
     * 
     * @param string $id Identifiant du projet à supprimer
     * @return void
     */
    public function deleteProject(string $id): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        // Vérification CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header('Location: /dashboard/projects');
            exit;
        }

        $projectId = (int)$id;
        $userId = (int)$_SESSION['user_id'];

        // Ajoutez un log pour déboguer
        error_log("Tentative de suppression du projet $projectId par l'utilisateur $userId");

        $success = $this->userModel->deleteProject($userId, $projectId);

        if ($success) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Projet supprimé avec succès'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Erreur lors de la suppression du projet'];
        }

        header('Location: /dashboard/projects');
        exit;
    }
} 