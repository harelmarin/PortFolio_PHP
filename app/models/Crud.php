<?php
namespace App\Models;

use App\Config\Database;
use PDO;
use Exception;

class Crud {
    
    protected string  $table = self::class;
    private ?\PDO $pdo = null; 
    protected string $keyName = "id";
    
    public function __construct(string $table = "", string $key = "id") {
        $this->table = $table;
        $this->pdo = Database::getPDO();
    }
    
    /**
    * retrouve l'instance par son id
    *
    * @param integer $id l'id de l'instance que l'on souhaite trouver
    * @return array | bool un tableau associatif dont les clés sont les colonnes et les valeurs de chaque colonne associée; false si une erreur est rencontrée
    */
    public function find(int $id): array | bool{
        
        // préparer la requête de recherche$this::class
        $table = $this->table; 
        $key = $this->keyName;
        $sql = <<<sql
        SELECT * 
        FROM $table
        WHERE $key = :id
        sql;
        try {
            
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':id', $id, \PDO::PARAM_INT);
            $statement->execute();
        } catch(Exception $e) {
            echo($e->getMessage());
            return false;
        }
        return $statement->fetch(\PDO::FETCH_ASSOC); 
    }
    
    /**
    * Permet de récupérer la première ligne ayant pour valeur de colonne celle passée en paramètre
    *
    * @param string $column la colonne dans laquelle on recherche la valeur
    * @param mixed $value la valeur recherchée pour la colonne 
    * @return array | bool  un tableau associatif dont les clés sont les colonnes et les valeurs de chaque colonne associée pour la ligne renvoyée ; false si une erreur est rencontrée
    */
    public function findOneBy(string $column, mixed $value): array | bool  {
        $table = $this->table; 
        $sql = <<<sql
        SELECT * 
        FROM $table
        WHERE $column = :value
        sql;
        try {
            
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':value', $value);
            $statement->execute();
        } catch(Exception $e) {
            echo($e->getMessage());
            return false;
        }
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    
    /**
    * Permet de récupérer toutes les lignes répondant au critère de recherche 
    *
    * @param string $column la colonne dans laquelle on recherche la valeur
    * @param mixed $value la valeur recherchée pour la colonne 
    * @return array | bool un tableau indexé contenant toutes les lignes renvoyées; false si une erreur est rencontrée
    */
    public function findAllBy(string $column, mixed $value): array | bool  {
        $table = $this->table; 
        $sql = <<<sql
        SELECT * 
        FROM $table
        WHERE $column = :value
        sql;
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':value', $value);
            $statement->execute();
        } catch(Exception $e) {
            echo($e->getMessage());
            return false;
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
    * Renvoie toutes les lignes du modèle
    *
    * @return array | bool toutes les occurences de la table; false si une erreur est rencontrée
    */
    public function findAll(): array | bool {
        $table = $this->table; 
        $sql = <<<sql
        SELECT * 
        FROM $table
        sql;
        try {
            
            
            $statement = $this->pdo->prepare($sql);
            
            $statement->execute();
        } catch(Exception $e) {
            echo($e->getMessage());
            return false;
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
    * Insère une nouvelle ligne dans la base de données
    *
    * @param array $data
    * @return integer | false le nombre de ligne insérée (normalement une); false si une erreur est rencontrée
    */
    public function create(array $data): int|false
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array_values($data));
            return (int)$this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            // Pour le debug
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
    * met à jour une ligne 
    *
    * @param integer $id
    * @param array $values
    * @return integer | bool le nombre de ligne modifiée (normalement une ); false si une erreur est rencontrée
    */
    public function update(int $id, array $values): int | bool  {
        // UPDATE $table SET $col=$:col, ... WHERE $key = :id;
        $table = $this->table;
        $sql = "UPDATE $table SET ";
        $keys = array_keys($values);
        $arrayKeys = [];
        foreach($keys as $key) {
            $str =  $key ."=:" .$key; 
            $arrayKeys[] = $str; 
        }
        $keysStr = implode(', ', $arrayKeys);
        $key = $this->keyName;
        $sql .= $keysStr . " WHERE $key=:id";
        try {
            $statement = $this->pdo->prepare($sql);
            
            foreach($values as $key => $val) {
                $statement->bindParam(":$key", $val);
            }
            $statement->bindParam(":$key", $id);
            $statement->execute();
            return $statement->rowCount();
        } catch(Exception $e) {
            echo($e->getMessage());
            return false;
        }
    }        
    
    /**
    * Supprime la ligne correspondant à l'id
    *
    * @param integer $id
    * @param string $key le nom de la colonne primary key
    * @return int | bool le nombre de ligne affectée par l'opération (devrait être = 1 sinon, on n'est pas bon :( ); false si une erreur est rencontrée
    */
    public function delete(int $id): int | bool {
        // DELETE $table WHERE $key = :id;
        $table = $this->table;
        $key = $this->keyName;
        $sql = "DELETE $table WHERE $key = :id";
        try {   
            $statement = $this->pdo->prepare($sql);
            
            $statement->bindParam(":$key", $id);
            $statement->execute();
            return $statement->rowCount();
        } catch(Exception $e) {
            echo($e->getMessage());
            return false;
        }
    }
    
}
