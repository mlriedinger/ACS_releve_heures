<?php

/* On appelle le modèle correspondant pour accéder à ses méthodes */

require_once('model/LoginManager.php');


/* Fonctions pour gérer l'affichage des pages de connexion et d'accueil */

function verifyLogin($login, $password) {
    $loginManager = new LoginManager();
    $userData = $loginManager->getUserData($login, $password);

    if (!isset($password) || !$userData) {
        require('view/login.php');
        
    } else {
        session_start();

        $_SESSION['login'] = $userData['Utilisateur'];
        $_SESSION['id'] = $userData['ID'];
        $_SESSION['id_group'] = $userData['id_groupe'];
        $_SESSION['name'] = $userData['Nom'];
        $_SESSION['firstname'] = $userData['Prenom'];
        $_SESSION['isAdmin'] = $userData['Administrateur'];
        $_SESSION['isActive'] = $userData['CompteActif'];
        $_SESSION['isDeleted'] = $userData['Supprimer'];

        require('view/home.php');
    }
}

function displayLoginPage(){
    require('view/login.php');
}

function displayHomePage(){
    require('view/home.php');
}


/* Fonction pour gérer la déconnexion de l'application */

function logout(){
    session_destroy();
    header('Location: index.php');
    exit();
}