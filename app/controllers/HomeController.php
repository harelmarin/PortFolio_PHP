<?php

namespace App\Controllers;

/**
 * Contrôleur gérant la page d'accueil
 */
class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil
     * 
     * @return void
     */
    public function index(): void
    {
        $this->render('index', [
            'title' => 'Index'
        ]);
    }
}