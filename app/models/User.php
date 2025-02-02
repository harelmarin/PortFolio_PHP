<?php

namespace App\Models;

use App\Models\Crud; 


class User extends Crud
{
    public function __construct()
    {
        // On passe le nom de la table au constructeur parent
        parent::__construct('users');
    }

    /**
     * Trouve un utilisateur par son email
     */
    public function findByEmail(string $email): array|false
    {
        return $this->findOneBy('email', $email);
    }

    /**
     * Trouve un utilisateur par son username
     */
    public function findByUsername(string $username): array|false
    {
        return $this->findOneBy('username', $username);
    }

    /**
     * Vérifie si l'email existe déjà
     */
    public function emailExists(string $email): bool
    {
        return (bool) $this->findByEmail($email);
    }
}