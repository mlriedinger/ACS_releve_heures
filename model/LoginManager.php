<?php

require_once 'DatabaseConnection.php';

/**
 * Classe qui permet de gérer l'authentification à l'application.
 * Hérite de DatabaseConnection pour accéder à la méthode dbConnect().
 */
class LoginManager extends DatabaseConnection {
    
    public function __construct() {
        parent::__construct();
    }
        
    /**
     * Permet de tester la combinaison login/mot de passe pour se connecter à la base de données.
     * Renvoie un tableau contenant les informations de l'utilisateur en cas de succès, sinon un tableau vide.
     *
     * @param  string $login
     * @param  string $password
     * @return array $userData
     */
    public function getUserData(string $login, string $password){
        $pdo = $this->dbConnect($login, $password);

        $sql = "SELECT ID_CHAR AS 'ID',
            ID_CHAR_GROUPE AS 'id_groupe',
            Utilisateur,
            Administrateur,
            CompteActif,
            Nom,
            Prenom,
            Supprimer 
        FROM t_login 
        WHERE Utilisateur = :login";
     
        $query = $pdo->prepare($sql);
        $query->execute(array('login' => $login));
        $userData = $query->fetch();
 
        return $userData;
    }
}