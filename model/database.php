<?php

// Variables de connexion à la base de données

$dbHost = "192.168.1.2";
$dbPort = "3306";
$dbName = "erp_acs";
$dbUser = "erpadmin";
$dbPassword = "Acs@73000";


// Test de connexion à la base

function dbConnect(){
	global $dbHost, $dbPort, $dbName, $dbUser, $dbPassword;
    try{
        $pdo = new PDO('mysql:host=' . $dbHost . ';port=' . $dbPort . ';dbname=' . $dbName . ';charset=utf8', $dbUser, $dbPassword);
        echo("Connexion réussie !");
    } catch(Exception $e) {
        die('Erreur : ' . $e->getMessage() . "<br>dbHost => " . $dbHost . "<br>dbPort => " . $dbPort . "<br>dbName => " . $dbName . "<br>dbUser => " . $dbUser . "<br>dbPassword => " . $dbPassword);
    }
    return $pdo;
}



