<?php
session_start();

/* On appelle le modèle correspondant pour accéder à ses méthodes */

require_once('model/RecordManager.php');


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

function getRecordForm($recordId){
    $recordId = $recordId;
    require('view/partials/recordForm.php');
}


/* Fonction pour récupérer le formulaire de confirmation de suppression (uniquement le formulaire) */

function getDeleteConfirmationForm(){
    require('view/partials/deleteConfirmationForm.php');
}


/* Fonction pour enregistrer un nouveau relevé en BDD */

function addNewRecord($userId, $dateTimeStart, $dateTimeEnd, $comment, $groupId){
    $recordManager = new RecordManager();
    $isSendingSuccessfull = $recordManager->sendNewRecord($userId, $dateTimeStart, $dateTimeEnd, $comment, $groupId);

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

function updateRecord($recordId, $dateTimeStart, $dateTimeEnd, $comment){
    $recordManager = new RecordManager();
    $isUpdateSuccessfull = $recordManager->updateRecord($recordId, $dateTimeStart, $dateTimeEnd, $comment);
    
    $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
    // Renvoie sur la dernière page visitée avant l'envoi du formulaire
    echo '<script>window.history.go(-1);</script>';
}


/* Fonction pour mettre à jour le statut des relevés (validation) en fonction de la sélection faite par le manager */

function updateRecordStatus($checkList){   
    $recordManager = new RecordManager();
    $updateResults = [];

    foreach($checkList as $lineChecked){
        $updateAttempt = $recordManager->updateRecordStatus($lineChecked); 
        if($updateAttempt) array_push($updateResults, $updateAttempt);
    }

    if(count($checkList) == count($updateResults)) $isUpdateSuccessfull = true;      

    $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
    // Renvoie sur la dernière page visitée avant l'envoi du formulaire
    echo '<script>window.history.go(-1);</script>';
}


/* Fonction pour "supprimer" un relevé d'heure (en réalité le rendre inactif) */

function deleteRecord($recordId, $comment){
    $recordManager = new RecordManager();
    $isDeleteSuccessfull = $recordManager->deleteRecord($recordId, $comment);

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

function getRecordData($recordId){
    $recordManager = new RecordManager();
    $recordManager->getRecord($recordId);
}

function getUserRecords($userId, $typeOfRecords, $scope){
    $recordManager = new RecordManager();
    $recordManager->getRecordsFromUser($userId, $typeOfRecords, $scope);   
}

function getTeamRecords($managerID, $typeOfRecords, $scope){
    $recordManager = new RecordManager();
    $recordManager->getRecordsFromTeam($managerID, $typeOfRecords, $scope);
}

function getAllUsersRecords($typeOfRecords, $scope){
    $recordManager = new RecordManager();
    $recordManager->getAllRecords($typeOfRecords, $scope);
}

function exportRecords($typeOfRecords, $scope, $dateStart, $dateEnd, $managerID, $userId){
    $recordManager = new RecordManager();
    $recordManager->exportRecords($typeOfRecords, $scope, $dateStart, $dateEnd, $managerID, $userId);
}

function getOptionsData($typeOfData){
    $recordManager = new RecordManager();
    $recordManager->getDataForOptionSelect($typeOfData);
}