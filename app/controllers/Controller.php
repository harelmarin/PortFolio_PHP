<?php

namespace App\Controllers;

abstract class Controller 
{
    /**
     * Rend une vue
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        
        require_once __DIR__ . "/../views/{$view}.php";
    }
}