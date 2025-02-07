<?php

namespace App\Controllers;

use App\Models\Skill;
use App\Models\User;

class AdminController extends Controller
{
    private Skill $skillModel;
    private User $userModel;

    public function __construct()
    {
        $this->skillModel = new Skill();
        $this->userModel = new User();
    }

    public function index(): void
    {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /');
            exit;
        }

        // Récupérer les paramètres de filtrage
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';

        // Récupérer toutes les compétences
        $skills = $this->skillModel->findAll();
        
        // Récupérer les utilisateurs filtrés
        $users = $this->userModel->findFiltered($search, $role);

        $this->render('admin', [
            'title' => 'Administration',
            'skills' => $skills,
            'users' => $users
        ]);
    }

    public function addSkill(): void
    {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /');
            exit;
        }

        // Vérifier le token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header('Location: /admin');
            exit;
        }

        $skillName = trim(htmlspecialchars($_POST['skill_name'] ?? '', ENT_QUOTES, 'UTF-8'));

        if (empty($skillName)) {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Le nom de la compétence est requis'
            ];
            header('Location: /admin');
            exit;
        }

        // Ajouter la compétence
        $success = $this->skillModel->create(['name' => $skillName]);

        if ($success) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Compétence ajoutée avec succès'
            ];
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Erreur lors de l\'ajout de la compétence'
            ];
        }

        header('Location: /admin');
        exit;
    }
} 