<?php

/* On appelle le modèle correspondant pour accéder à ses méthodes */

require_once('model/LoginManager.php');


/* Fonctions pour gérer l'affichage des pages de connexion et d'accueil */

function verifyLogin() {
    $loginManager = new LoginManager();

    $userData = $loginManager->getUserData($_POST['login'], $_POST['password']);

    if (!isset($_POST['password']) || !$userData) {
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
        $_SESSION['isDeleted'] = $dauserDatata['Supprimer'];

        require('view/home.php');
    }
}

function displayHomePage(){
    session_start();

    if(isset($_SESSION['id'])) require('view/home.php');
}

function logout(){
    session_destroy();
    header('Location: index.php?action=login');
    exit();
}