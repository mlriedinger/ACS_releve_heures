<?php
session_start();

/* On appelle le modèle correspondant pour accéder à ses méthodes */

require_once('model/RecordManager.php');


/* Fonctions pour gérer l'affichage des pages de saisie, de validation et d'historique */

function displayNewRecordForm(){
    if(isset($_SESSION['id'])) require('view/newRecordForm.php');
}

function displayValidationForm(){
    if(isset($_SESSION['id'])) require('view/recordsToCheck.php');
}

function displayPersonnalRecordsLog(){
    if(isset($_SESSION['id'])) require('view/personnalRecordsLog.php');
}

function displayRecordsLog(){
    switch($_SESSION['id_group']){
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
    $recordManager = new RecordManager();
    $isSendingSuccessfull = $recordManager->sendNewRecord($_POST['user_id'], $_POST['datetime_start'], $_POST['datetime_end'], $_POST['comment']);
    
    if($isSendingSuccessfull) header('Location: index.php?action=showPersonnalRecordsLog');
    else require('view/newRecordform.php');
}


/* Fonction pour mettre à jour le statut des relevés (validation) */

function updateRecordStatus(){
    $recordManager = new RecordManager();
    $isUpdateSuccessfull = false;

    if(!empty($_POST['check_list'])){
        try {
            foreach($_POST['check_list'] as $lineChecked){
                $recordManager->updateRecordStatus($lineChecked);  
            }
            $isUpdateSuccessfull = true;      
        } catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    if($isUpdateSuccessfull) header('Location: index.php?action=showHomePage');
    else require('view/recordsToCheck');

}


/* Fonctions pour récupérer des relevés */

function getUserRecords($typeOfRecords){
    $recordManager = new RecordManager();
    $recordManager->getRecordsFromUser($_SESSION['id'], $typeOfRecords);   
}

function getTeamRecordsToCheck($typeOfRecords){
    $recordManager = new RecordManager();
    $recordManager->getTeamRecordsToCheck($_SESSION['id'], $typeOfRecords);
}

function getTeamRecords($typeOfRecords){
    $recordManager = new RecordManager();
    $recordManager->getRecordsFromTeam($_SESSION['id'], $typeOfRecords);
}

function getAllUsersRecords($typeOfRecords){
    $recordManager = new RecordManager();
    $recordManager->getAllRecords($typeOfRecords);
}