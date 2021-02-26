<?php

require_once('model/LoginManager.php');

function displayHomePage() {
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