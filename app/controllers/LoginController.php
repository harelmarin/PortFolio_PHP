<?php

namespace App\Controllers;

use App\Models\User;

/**
 * Contrôleur gérant l'authentification des utilisateurs
 */
class LoginController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Affiche le formulaire de connexion
     * 
     * @return void
     */
    public function login(): void
    {
        // Récupère le message de succès si présent
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);
        
        $this->render('login', [
            'title' => 'Connexion',
            'success' => $success
        ]);
    }

    /**
     * Authentifie l'utilisateur
     * Vérifie les identifiants et crée la session
     * 
     * @return void
     */
    public function authenticate(): void
    {
        // Vérification du CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header('Location: /');
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        // Protection contre les attaques par force brute
        if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] > 3) {
            if (time() - $_SESSION['last_attempt'] < 300) { // 5 minutes
                $_SESSION['error'] = "Trop de tentatives. Réessayez dans 5 minutes.";
                header('Location: /');
                exit;
            }
            $_SESSION['login_attempts'] = 0;
        }

        $errors = [];

        // Validation des infos envoyés
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide";
        }
        if (empty($password)) {
            $errors['password'] = "Le mot de passe est requis";
        }

        // Vérifie les infos envoyés
        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            $errors['auth'] = "Email ou mot de passe incorrect";
        }

        // Si il y a des erreurs ça affiche le formulaire de connexion
        if (!empty($errors)) {
            $this->render('login', [
                'title' => 'Connexion',
                'errors' => $errors,
                'old' => compact('email')
            ]);
            return;
        }

        // Connexion réussie
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['login_attempts'] = 0;
        
        // Redirection vers le profil
        header('Location: /profile');
        exit;
    }

    /**
     * Déconnecte l'utilisateur
     * Détruit la session et redirige vers la page d'accueil
     * 
     * @return void
     */
    public function logout(): void
    {
        session_start();
        session_destroy();
        
        header('Location: /');
        exit;
    }
}