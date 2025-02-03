<?php

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// Chargement des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Core\Router;
use App\Controllers\RegisterController;
use App\Controllers\LoginController;
use App\Controllers\ProfileController;

// Initialisation du router
$router = new Router();

// Définition des routes
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'authenticate']);
$router->get('/logout', [LoginController::class, 'logout']);
$router->get('/register', [RegisterController::class, 'register']);
$router->post('/register', [RegisterController::class, 'store']);
$router->get('/profile', [ProfileController::class, 'index']);

// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Exécution du router
$router->run();