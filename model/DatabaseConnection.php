<?php

/* Classe qui gère la connexion à la base de données.
    * dbConnect() initialise et renvoie un objet PDO
*/

class DatabaseConnection
{
    private $dbHost = "192.168.1.2";
    private $dbPort = "3306";
    private $dbName = "erp_acs";
    private $dbUser = "erpadmin";
    private $dbPassword = "Acs@73000";

    protected function dbConnect($dbUser = "", $dbPassword = "", $dbHost = "", $dbPort = "", $dbName = ""){
        if($dbUser != ""){
            $this->dbUser = $dbUser;
        }

        if($dbPassword != ""){
            $this->dbPassword = $dbPassword;
        }

        if($dbHost != ""){
            $this->dbHost = $dbHost;
        }

        if($dbPort != ""){
            $this->dbPort = $dbPort;
        }

        if($dbName != ""){
            $this->dbName = $dbName;
        }

        try{
            $pdo = new PDO('mysql:host=' . $this->dbHost . ';port=' . $this->dbPort . ';dbname=' . $this->dbName . ';charset=utf8', $this->dbUser, $this->dbPassword);
            // echo "Connexion réussie !";
        } catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        return $pdo;
    }
}



