<?php
session_start();

/* On appelle le modèle correspondant pour accéder à ses méthodes */

require_once('model/RecordManager.php');


/* Fonctions pour gérer l'affichage des pages :
    * displayNewRecordForm() : page de saisie d'un nouveau relevé, 
    * displayValidationForm() : page de validation de relevés en attente
    * displayPersonnalRecordsLog() : historique personnel, 
    * displayRecordsLog() : historique équipe ou global en fonction du type d'utilisateur 
*/

function displayNewRecordForm(){
    if(isset($_SESSION['id'])) require('view/addNewRecord.php');
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


/* Fonction pour récupérer le formulaire de saisie (uniquement le formulaire) */

function getRecordForm(){
    require('view/partials/recordForm.php');
}


/* Fonction pour enregistrer un nouveau relevé en BDD */

function registerNewRecord(){
    $recordManager = new RecordManager();
    $isSendingSuccessfull = $recordManager->sendNewRecord($_SESSION['id'], $_POST['datetime_start'], $_POST['datetime_end'], $_POST['comment'], $_SESSION['id_group']);
    
    if($isSendingSuccessfull) header('Location: index.php?action=showPersonnalRecordsLog');
    else require('view/addNewRecord.php');
}


/* Fonction pour modifier un relevé qui n'a pas encore été validé */

function updateRecord(){
    $recordManager = new RecordManager();
    $recordManager->updateRecord($_POST['record_id'], $_POST['datetime_start'], $_POST['datetime_end'], $_POST['comment']);
    
    require('view/personnalRecordsLog.php');
}


/* Fonction pour mettre à jour le statut des relevés (validation) en fonction de la sélection faite par le manager */

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


/* Fonctions pour récupérer les relevés :
    * getRecordData() : informations d'un relevé unique,
    * getUserRecords() : relevés personnels,
    * getTeamRecordsToCheck() : relevés en attente de validation, 
    * getTeamRecords() : relevés de l'équipe, 
    * getAllUsersRecords() : tous les relevés) 
    Params :
    * $type_of_records : type de relevés demandés (paramètre envoyé par la requête AJAX)
*/

function getRecordData($recordId){
    $recordManager = new RecordManager();
    $recordManager->getRecord($recordId);
}

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