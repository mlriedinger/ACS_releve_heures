<?php

/* Classe qui gère la connexion à la base de données. */

class DatabaseConnection
{
    private $_dbHost;
    private $_dbPort;
    private $_dbName;
    private $_dbUser;
    private $_dbPassword;

    public function __construct(){
        $config = parse_ini_file("config.ini");
        $this->_dbHost = $config['dbHost'];
        $this->_dbPort = $config['dbPort'];
        $this->_dbName = $config['dbName'];
        $this->_dbUser = $config['dbUser'];
        $this->_dbPassword = $config['dbPassword'];
    }

    /* Méthode qui initialise une connection à la base de données. Elle retourne un objet PDO en cas de succès, sinon une erreur.
        Params:
        * $dbUser : identifiant
        * $dbPassword : mot de passe
        * $dbHost : URL de l'hôte
        * $dbPort : numéro de port pour accéder à la BDD
        * $dbName : nom de la BDD
    
    */

    protected function dbConnect($dbUser = "", $dbPassword = "", $dbHost = "", $dbPort = "", $dbName = ""){
        if($dbUser != "") $this->_dbUser = $dbUser;
        if($dbPassword != "") $this->_dbPassword = $dbPassword;
        if($dbHost != "") $this->_dbHost = $dbHost;
        if($dbPort != "") $this->_dbPort = $dbPort;
        if($dbName != "") $this->_dbName = $dbName;

        $pdo = new PDO('mysql:host=' . $this->_dbHost . ';port=' . $this->_dbPort . ';dbname=' . $this->_dbName . ';charset=utf8', $this->_dbUser, $this->_dbPassword);

        return $pdo;
    }
}



