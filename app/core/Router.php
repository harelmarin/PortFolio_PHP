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
    
    private function matchRoute(string $requestUri, string $routePath): array|false
    {
        // Convertir les paramètres de route en pattern regex
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $routePath);
        $pattern = "#^" . $pattern . "$#";
        
        if (preg_match($pattern, $requestUri, $matches)) {
            // Filtrer les matches pour ne garder que les paramètres nommés
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }
        
        return false;
    }
    
    /**
     * Exécute la route correspondante
     */
    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getCurrentUri();
        
        // Parcourir toutes les routes enregistrées
        foreach ($this->routes[$method] ?? [] as $route => $action) {
            $matches = $this->matchRoute($uri, $route);
            
            if ($matches !== false) {
                [$controller, $action] = $action;
                $controllerInstance = new $controller();
                
                // Extraire les paramètres de l'URL
                $params = array_slice($matches, 1);
                
                // Appeler la méthode du controller avec les paramètres
                call_user_func_array([$controllerInstance, $action], $params);
                return;
            }
        }
        
        // Route non trouvée
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
    
    private function getCurrentUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = explode('?', $uri)[0];
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $uri = str_replace($scriptName, '', $uri);
        return '/' . trim($uri, '/');
    }
}