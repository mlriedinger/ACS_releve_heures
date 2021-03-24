<?php
require_once 'DatabaseConnection.php';

/* Classe qui gère la vérification du login/mot de passe.
    * [INFO] Classe-fille de DatabaseConnection pour pouvoir hériter de la méthode dbConnect()  
*/

class LoginManager extends DatabaseConnection
{
    /* Méthode qui permet de tester la combinaison login/mot de passe saisie par l'utilisateur. Renvoie les informations de l'utilisateur en cas de succès.
        Params :
        * $login : identifiant
        * $password : mot de passe 
    */
    public function getUserData($login, $password){
        $pdo = $this->dbConnect($login, $password);
     
        $query = $pdo->prepare('SELECT * FROM t_login WHERE Utilisateur = :login');
        $query->execute(array('login' => $login));
        $userData = $query->fetch();
 
        return $userData;
    }
}




