<?php

namespace App\Core;

class Router 
{
    private array $routes = [];
    
    /**
     * Ajoute une route GET
     */
    public function get(string $path, array $action): void
    {
        $this->routes['GET'][$path] = $action;
    }
    
    /**
     * Ajoute une route POST
     */
    public function post(string $path, array $action): void
    {
        $this->routes['POST'][$path] = $action;
    }
    
    /**
     * Récupère l'URL actuelle
     */
    private function getCurrentUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Retire les query parameters
        $uri = explode('?', $uri)[0];
        
        // Retire le chemin du script (index.php)
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $uri = str_replace($scriptName, '', $uri);
        
        // S'assure que l'URI commence par /
        return '/' . trim($uri, '/');
    }
    
    /**
     * Exécute la route correspondante
     */
    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getCurrentUri();
        
        // Vérifie si la route existe
        if (isset($this->routes[$method][$uri])) {
            [$controller, $action] = $this->routes[$method][$uri];
            
            // Instancie le controller et appelle l'action
            $controllerInstance = new $controller();
            $controllerInstance->$action();
        } else {
            // Route non trouvée
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }
}