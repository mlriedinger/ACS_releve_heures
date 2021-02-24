<?php

/* On appelle le modèle pour avoir accès à ses méthodes */
require('model/loginManager.php');

function displayHomePage() {
    /* On récupère les données du modèle */
    $userData = getUserData($_POST['login']);
    $isPasswordCorrect = password_verify($_POST['password'], $data['password']);

    if (!isset($_POST['password']) || !$isPasswordCorrect || !$data) {
        require('view/login.php');
    } else {
        require('view/home.php');
    }

    
}