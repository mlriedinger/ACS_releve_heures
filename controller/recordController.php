<?php
session_start();

/* On appelle le modèle correspondant pour accéder à ses méthodes */

require_once('model/RecordManager.php');


/* Fonctions pour gérer l'affichage des pages de saisie et d'historique */

function displayNewRecordForm(){
    require('view/newRecordForm.php');
}

function displayPersonnalRecordsLog(){
    require('view/personnalRecordsLog.php');
}

function displayRecordsLog(){
    switch($_SESSION['id_groupe']){
        case 1:
            require('view/allUsersRecordsLog.php');
            break;
        case 2:
            require('view/teamRecordsLog.php');
            break;
    }
}


/* Fonction pour enregistrer un nouveau relevé en BDD */

function registerNewRecord(){
    $recordManager = new recordManager();
    $isSendingSuccessfull = $recordManager->sendNewRecord($_POST['user_id'], $_POST['datetime_start'], $_POST['datetime_end'], $_POST['comment']);
    
    if($isSendingSuccessfull) require('view/personnalRecordsLog.php');
    else require('view/newRecordform.php');
}


/* Fonctions pour récupérer des relevés */

function getUserRecords(){
    $recordManager = new recordManager();
    $recordManager->getAllRecordsFromUser($_SESSION['id']);
}