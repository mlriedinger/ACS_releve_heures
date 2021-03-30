<?php
session_start();

/* On appelle les modèles correspondants pour accéder à leurs méthodes */
require_once 'model/RecordManager.php';
require_once 'model/ExportManager.php';


/* Fonctions pour gérer l'affichage des pages :
    * displayNewRecordForm() : page de saisie d'un nouveau relevé, 
    * displayValidationForm() : page de validation de relevés en attente
    * displayPersonalRecordsLog() : historique personnel, 
    * displayTeamRecordsLog() : historique équipe
    * displayAllRecordsLog() : historique global
    * display ExportForm() : page export de données 
*/

function displayNewRecordForm(){
    require('view/addNewRecord.php');
}

function displayValidationForm(){
    require('view/recordsToCheck.php');
}

function displayPersonalRecordsLog(){
    require('view/personalRecordsLog.php');
}

function displayTeamRecordsLog(){
    require('view/teamRecordsLog.php');
}

function displayAllRecordsLog(){
    require('view/allUsersRecordsLog.php');
}

function displayExportForm(){
    require('view/exportRecordsForm.php');
}


/* Fonction pour récupérer le formulaire de saisie (uniquement le formulaire) */

function getRecordForm($recordInfo){
    $recordId = $recordInfo->getRecordId();
    require('view/partials/recordForm.php');
}


/* Fonction pour récupérer le formulaire de confirmation de suppression (uniquement le formulaire) */

function getDeleteConfirmationForm(){
    require('view/partials/deleteConfirmationForm.php');
}


/* Fonction pour enregistrer un nouveau relevé en BDD */

function addNewRecord($recordInfo){
    $recordManager = new RecordManager();
    $isSendingSuccessfull = $recordManager->sendNewRecord($recordInfo);

    if($isSendingSuccessfull) {
        $_SESSION['success'] = true;
        header('Location: index.php?action=showPersonalRecordsLog');
    }
    else {
        $_SESSION['success'] = false;
        require('view/addNewRecord.php');
    }
}


/* Fonction pour modifier un relevé qui n'a pas encore été validé */

function updateRecord($recordInfo){
    $recordManager = new RecordManager();
    $isUpdateSuccessfull = $recordManager->updateRecord($recordInfo);
    
    $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
    // Renvoie sur la dernière page visitée avant l'envoi du formulaire
    echo '<script>window.history.go(-1);</script>';
}


/* Fonction pour mettre à jour le statut des relevés (validation) en fonction de la sélection faite par le manager */

function updateRecordStatus($recordsCheckList){   
    $recordManager = new RecordManager();
    $updateResults = [];

    foreach($recordsCheckList as $recordChecked){
        $updateAttempt = $recordManager->updateRecordStatus($recordChecked); 
        if($updateAttempt) array_push($updateResults, $updateAttempt);
    }

    if(count($recordsCheckList) == count($updateResults)) $isUpdateSuccessfull = true;      

    $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
    // Renvoie sur la dernière page visitée avant l'envoi du formulaire
    echo '<script>window.history.go(-1);</script>';
}


/* Fonction pour "supprimer" un relevé d'heure (en réalité le rendre inactif) */

function deleteRecord($recordInfo){
    $recordManager = new RecordManager();
    $isDeleteSuccessfull = $recordManager->deleteRecord($recordInfo);

    $isDeleteSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
    // Renvoie sur la dernière page visitée avant l'envoi du formulaire
    echo '<script>window.history.go(-1);</script>';
}


/* Fonctions pour récupérer les relevés :
    * getRecordData() : informations d'un relevé unique,
    * getUserRecords() : relevés personnels,
    * getTeamRecords() : relevés de l'équipe, 
    * getAllUsersRecords() : tous les relevés
    * exportRecords() : exporte les données au format CSV
    * getOptionsData() : récupère la liste des managers et des salariés pour les afficher dans le formulaire d'export
    Params :
    * $typeOfRecords : type de relevés demandés (paramètre envoyé par la requête AJAX)
    * $scope : périmètre de la requête (tout ou seulement une partie des relevés)
    * $typeOfData : chaîne de caractères ("managers" ou "users")
*/

function getRecordData($recordInfo){
    $recordManager = new RecordManager();
    $recordManager->getRecord($recordInfo);
}

function getUserRecords($recordInfo){
    $recordManager = new RecordManager();
    $recordManager->getRecordsFromUser($recordInfo);   
}

function getTeamRecords($recordInfo){
    $recordManager = new RecordManager();
    $recordManager->getRecordsFromTeam($recordInfo);
}

function getAllUsersRecords($recordInfo){
    $recordManager = new RecordManager();
    $recordManager->getAllRecords($recordInfo);
}

function exportRecords($recordInfo){
    $exportManager = new ExportManager();
    $exportManager->exportRecords($recordInfo);
}

function getOptionsData($typeOfData, $userId=""){
    $recordManager = new RecordManager();
    $recordManager->getDataForOptionSelect($typeOfData, $userId);
}