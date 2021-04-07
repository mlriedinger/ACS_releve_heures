<?php

/* On appelle le modèle correspondant pour accéder à ses méthodes */

require_once('model/LoginManager.php');


/* Fonctions pour gérer l'affichage des pages de connexion et d'accueil */

function displayLoginPage($error=""){
    $error;
    require('view/login.php');
}

function displayHomePage(){
    require('view/home.php');
}


/* Fonction pour vérifier la combinaison login/mot de passe */

function verifyLogin($login, $password) {
    $loginManager = new LoginManager();
    $userData = $loginManager->getUserData($login, $password);

    if (!isset($password) || !$userData) {
        displayLoginPage();
        
    } else if (!empty($userData)){
        fillSessionData($userData);
        displayHomePage();
    }
    else throw New Exception('Mauvais identifiant ou mot de passe.');
}


/* Fonction pour remplir les variables de session avec les données utilisateur */

function fillSessionData($userData){
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

function logout(){
    $_SESSION = array();
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