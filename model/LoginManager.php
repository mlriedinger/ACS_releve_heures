<?php
require_once 'DatabaseConnection.php';

/**
 * Classe qui permet de gérer l'authentification à l'application
 * Hérite de DatabaseConnection pour accéder à la méthode dbConnect()
 */
class LoginManager extends DatabaseConnection
{
    public function __construct() {
        parent::__construct();
    }

        
    /**
     * Permet de tester la combinaison login/mot de passe pour se connecter à la base de données.
     * Renvoie un tableau contenant les informations de l'utilisateur en cas de succès, sinon un tableau vide.
     *
     * @param  String $login
     * @param  String $password
     * @return Array $userData
     */
    public function getUserData(String $login, String $password){
        $pdo = $this->dbConnect($login, $password);
     
        $query = $pdo->prepare('SELECT * FROM t_login WHERE Utilisateur = :login');
        $query->execute(array('login' => $login));
        $userData = $query->fetch();
 
        return $userData;
    }
}




