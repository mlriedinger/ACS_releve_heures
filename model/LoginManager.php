<?php
require_once('database.php');

class LoginManager extends DatabaseConnection
{
    public function getUserData($login, $password){
        $pdo = $this->dbConnect($login, $password);
     
        $query = $pdo->prepare('SELECT * FROM t_login WHERE Utilisateur = :login');
        $query->execute(array('login' => $login));
        $userData = $query->fetch();
 
        return $userData;
    }
}




