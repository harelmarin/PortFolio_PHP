<?php

namespace App\Controllers;

use App\Models\User;

class ProfileController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_agent'])) {
            header('Location: /');
            exit;
        }

        // Vérifie que le user_agent n'a pas changé
        if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            session_destroy();
            header('Location: /');
            exit;
        }

        // Récupère les informations (déjà protégé par PDO::prepare)
        $user = $this->userModel->find((int)$_SESSION['user_id']);
        $skills = $this->userModel->getUserSkills((int)$_SESSION['user_id']);
        $projects = $this->userModel->getUserProjects((int)$_SESSION['user_id']);

        $this->render('profile', [
            'title' => 'Mon Profil',
            'user' => $user,
            'skills' => $skills,
            'projects' => $projects
        ]);
    }
}