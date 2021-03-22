<?php

/* Classe qui gère la connexion à la base de données. */

class DatabaseConnection
{
    private $dbHost = "mariadb.acskm.fr";
    private $dbPort = "3306";
    private $dbName = "erp_acs";
    private $dbUser = "erpadmin";
    private $dbPassword = "Acs@73000";


    /* Méthode qui initialise une connection à la base de données. Elle retourne un objet PDO en cas de succès, sinon une erreur.
        Params:
        * $dbUser : identifiant
        * $dbPassword : mot de passe
        * $dbHost : URL de l'hôte
        * $dbPort : numéro de port pour accéder à la BDD
        * $dbName : nom de la BDD
    
    */

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

        $pdo = new PDO('mysql:host=' . $this->dbHost . ';port=' . $this->dbPort . ';dbname=' . $this->dbName . ';charset=utf8', $this->dbUser, $this->dbPassword);
        // echo "Connexion réussie !";

        return $pdo;
    }
}



