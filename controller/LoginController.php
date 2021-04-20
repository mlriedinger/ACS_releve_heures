<?php

/* On charge automatiquement les classes pour accéder à leurs méthodes */
require 'autoloader.php';

class LoginController {

    /* Fonctions pour gérer l'affichage des pages de connexion et d'accueil */

    public function displayLoginPage($errorCode="", $errorMessage=""){
        $errorCode;
        $errorMessage;
        require('view/login.php');
    }

    public function displayHomePage(){
        require('view/home.php');
    }


    /* Fonction pour vérifier la combinaison login/mot de passe */

    public function verifyLogin($login, $password) {
        $loginManager = new LoginManager();
        $userData = $loginManager->getUserData($login, $password);

        if (!isset($password) || !$userData) {
            throw new AuthenticationException();
            
        } else if (!empty($userData)){
            $this->fillSessionData($userData);
            $this->displayHomePage();
        }
    }


    /* Fonction pour remplir les variables de session avec les données utilisateur */

    public function fillSessionData($userData){
        session_start();
        $_SESSION['login'] = $userData['Utilisateur'];
        $_SESSION['userId'] = $userData['ID'];
        $_SESSION['userGroup'] = $userData['id_groupe'];
        $_SESSION['name'] = $userData['Nom'];
        $_SESSION['firstname'] = $userData['Prenom'];
        $_SESSION['isAdmin'] = $userData['Administrateur'];
        $_SESSION['isActive'] = $userData['CompteActif'];
        $_SESSION['isDeleted'] = $userData['Supprimer'];
    }


    /* Fonction pour gérer la déconnexion de l'application */

    public function logout(){
        unset($_SESSION);
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: index.php');
    }
}