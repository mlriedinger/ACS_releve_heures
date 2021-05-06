<?php

/**
 * Classe qui gère la connexion à la base de données.
 */
class DatabaseConnection {
    
    private $_dbHost;
    private $_dbPort;
    private $_dbName;
    private $_dbUser;
    private $_dbPassword;
    protected $_config;
        
    /**
     * Constructeur qui lit le fichier "config.ini" et récupère les informations nécessaires à la connexion à la base de données.
     */
    public function __construct(){
        $this->_config = parse_ini_file("config.ini");
        $this->_dbHost = $this->_config['dbHost'];
        $this->_dbPort = $this->_config['dbPort'];
        $this->_dbName = $this->_config['dbName'];
        $this->_dbUser = $this->_config['dbUser'];
        $this->_dbPassword = $this->_config['dbPassword'];
    }

    /**
     * Permet de se connecter à la base de données.
     * Retourne un objet de type PDO en cas de succès, sinon une exception PDO est levée.
     *
     * @param  string $dbUser (optionnel)
     * @param  string $dbPassword (optionnel)
     * @param  string $dbHost (optionnel)
     * @param  string $dbPort (optionnel)
     * @param  string $dbName (optionnel)
     * @return PDO $pdo
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



