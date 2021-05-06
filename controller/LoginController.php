<?php

require 'autoloader.php';

/**
 * Classe qui permet de gérer l'authentification d'un utilisateur.
 */
class LoginController {
    
    /**
     * Rend la vue de la page de connexion.
     *
     * @param  string $errorCode (optionnel) : message d'erreur récupéré suite à une levée d'exception
     * @param  string $errorMessage (optionnel) : code d'erreur récupéré suite à une levée d'exception
     */
    public function displayLoginPage(string $errorCode="", string $errorMessage=""){
        $errorCode;
        $errorMessage;
        require 'view/login.php';
    }

    /**
     * Rend la vue "Accueil" de l'application.
     */
    public function displayHomePage(){
        require 'view/home.php';
    }
    
    /**
     * Permet d'appeler le modèle pour vérifier la combinaison login/mot de passe.
     * Renvoie vers la page de connexion avec une levée d'exception en cas d'échec d'authentification.
     * Renvoie vers la page d'accueil et remplit les variables de session en cas de succès.
     *
     * @param  string $login
     * @param  string $password
     */
    public function verifyLogin(string $login, string $password) {
        $loginManager = new LoginManager();
        $userData = $loginManager->getUserData($login, $password);

        if (!isset($password) || !$userData) {
            throw new AuthenticationException();
            
        } else if (!empty($userData)){
            $this->fillSessionData($userData);
            $this->displayHomePage();
        }
    }
    
    /**
     * Permet de remplir les variables de session avec les données utilisateur lors de la connexion à l'application.
     * Génère un token CSRF aléatoire pour sécuriser les formulaires qui seront ultérieurement remplis par l'utilisateur.
     *
     * @param  array $userData
     */
    public function fillSessionData(array $userData){
        session_start(); 
        $_SESSION['login'] = $userData['Utilisateur'];
        $_SESSION['csrfToken'] = bin2hex(random_bytes(32));
        $_SESSION['userId'] = $userData['ID'];
        $_SESSION['userGroup'] = $userData['id_groupe'];
        $_SESSION['name'] = $userData['Nom'];
        $_SESSION['firstname'] = $userData['Prenom'];
        $_SESSION['isAdmin'] = $userData['Administrateur'];
        $_SESSION['isActive'] = $userData['CompteActif'];
    }
    
    /**
     * Permet de gérer la déconnexion de l'application.
     * Renvoie vers la page de connexion.
     *
     */
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