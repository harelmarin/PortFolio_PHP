<?php

namespace App\Controllers;

use App\Models\User;

class RegisterController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function register(): void
    {
        $this->render('register', [
            'title' => 'Inscription'
        ]);
    }

    /**
     * Traite le formulaire d'inscription
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        // Récupération et nettoyage des données
        $username = htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $errors = [];

        // Vérification des données qui nous sont envoyés
        if (empty($username)) {
            $errors['username'] = "Le nom d'utilisateur est requis";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide";
        }

        if (strlen($password) < 8) {
            $errors['password'] = "Le mot de passe doit faire au moins 8 caractères";
        }

        // Vérifie si l'email existe déjà
        if ($this->userModel->findByEmail($email)) {
            $errors['email'] = "Cet email est déjà utilisé";
        }

        // S'il y a des erreurs ça réaffiche le formulaire 
        if (!empty($errors)) {
            $this->render('register', [
                'title' => 'Inscription',
                'errors' => $errors,
                'old' => compact('username', 'email')
            ]);
            return;
        }

        // Crée l'utilisateur d'après les données envoyés et le model User 
        $userId = $this->userModel->create([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user'
        ]);

        if ($userId) {
            // Démarre la session
            session_start();
            
            // Ajoute un message de succès dans la session si le compte est bien créé
            $_SESSION['success'] = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            
            // Redirection vers l'index pour ce login
            header('Location: /');
            exit;
        }

        // En cas d'erreur
        $this->render('register', [
            'title' => 'Inscription',
            'errors' => ['general' => "Une erreur est survenue"],
            'old' => compact('username', 'email')
        ]);
    }
}