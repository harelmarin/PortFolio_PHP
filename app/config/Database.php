<?php

namespace App\Config;

use PDOException;

/**
 * Classe Database
 * 
 * Gère la connexion à la base de données en utilisant le pattern Singleton
 * 
 * @package App
 */
class Database {
    /**
     * Instance PDO pour la connexion à la base de données
     * 
     * @var \PDO|null
     */
    private static ?\PDO $pdo = null;

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     * Pattern Singleton
     */
    private function __construct() {

    }

    /**
     * Retourne une connexion à la base de données (singleton)
     * 
     * @throws PDOException Si la connexion à la base de données échoue
     * @return \PDO Instance de connexion à la BDD (singleton)
     */
    public static function getPDO(): \PDO {
        if(isset(self::$pdo) && !empty(self::$pdo)) {
            return self::$pdo;
        }
        
        $DB_HOST = $_ENV["DB_HOST"];
        $DB_NAME = $_ENV["DB_NAME"];
        $DB_USER = $_ENV["DB_USER"];
        $DB_PASS = $_ENV["DB_PASS"];
        $DB_PORT = $_ENV["DB_PORT"];
        
        try {
            $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;port=$DB_PORT";
            self::$pdo = new \PDO($dsn, $DB_USER, $DB_PASS);
            return self::$pdo;
            
        } catch(PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }
}
