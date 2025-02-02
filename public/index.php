<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\RegisterController;

// Initialisation du router
$router = new Router();

// Définition des routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/register', [RegisterController::class, 'register']);
$router->post('/register', [RegisterController::class, 'store']);

// Exécution du router
$router->run();