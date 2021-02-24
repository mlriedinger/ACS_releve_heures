<?php
// Inclure la connexion à la BDD
include('database.php');

// Envoie la requête pour vérifier le mot de passe et le login
function getUserData($login){
    $pdo = dbConnect();
    $query = $pdo->prepare('SELECT * FROM user WHERE login = :login');
    $query->execute(array('login' => $login));
    $data = $query->fetch();

    return $data;
}


