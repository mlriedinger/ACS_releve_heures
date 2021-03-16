<?php
session_start();

/* On appelle le modèle correspondant pour accéder à ses méthodes */

require_once('model/RecordManager.php');


/* Fonctions pour gérer l'affichage des pages :
    * displayNewRecordForm() : page de saisie d'un nouveau relevé, 
    * displayValidationForm() : page de validation de relevés en attente
    * displayPersonalRecordsLog() : historique personnel, 
    * displayRecordsLog() : historique équipe ou global en fonction du type d'utilisateur 
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

function getRecordForm(){
    require('view/partials/recordForm.php');
}


/* Fonction pour récupérer le formulaire de confirmation de suppression (uniquement le formulaire) */

function getDeleteConfirmationForm(){
    require('view/partials/deleteConfirmation.php');
}


/* Fonction pour enregistrer un nouveau relevé en BDD */

function addNewRecord($id_user, $start_time, $end_time, $comment, $id_group){
    $recordManager = new RecordManager();
    $isSendingSuccessfull = $recordManager->sendNewRecord($id_user, $start_time, $end_time, $comment, $id_group);

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

function updateRecord($id_record, $start_time, $end_time, $comment){
    $recordManager = new RecordManager();
    $isUpdateSuccessfull = $recordManager->updateRecord($id_record, $start_time, $end_time, $comment);
    
    $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
    // Renvoie sur la dernière page visitée avant l'envoi du formulaire
    echo '<script>window.history.go(-1);</script>';
}


/* Fonction pour mettre à jour le statut des relevés (validation) en fonction de la sélection faite par le manager */

function updateRecordStatus($check_list){   
    $recordManager = new RecordManager();
    $updateResults = [];

    foreach($check_list as $lineChecked){
        $updateAttempt = $recordManager->updateRecordStatus($lineChecked); 
        if($updateAttempt) array_push($updateResults, $updateAttempt);
    }

    if(count($check_list) == count($updateResults)) $isUpdateSuccessfull = true;      

    $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
    // Renvoie sur la dernière page visitée avant l'envoi du formulaire
    echo '<script>window.history.go(-1);</script>';
}


/* Fonction pour "supprimer" un relevé d'heure (en réalité le rendre inactif) */

function deleteRecord($id_record, $comment){
    $recordManager = new RecordManager();
    $isDeleteSuccessfull = $recordManager->deleteRecord($id_record, $comment);

    $isDeleteSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
    // Renvoie sur la dernière page visitée avant l'envoi du formulaire
    echo '<script>window.history.go(-1);</script>';
}


/* Fonctions pour récupérer les relevés :
    * getRecordData() : informations d'un relevé unique,
    * getUserRecords() : relevés personnels,
    * getTeamRecords() : relevés de l'équipe, 
    * getAllUsersRecords() : tous les relevés) 
    Params :
    * $type_of_records : type de relevés demandés (paramètre envoyé par la requête AJAX)
*/

function getRecordData($recordId){
    $recordManager = new RecordManager();
    $recordManager->getRecord($recordId);
}

function getUserRecords($id_user, $typeOfRecords, $scope){
    $recordManager = new RecordManager();
    $recordManager->getRecordsFromUser($id_user, $typeOfRecords, $scope);   
}

function getTeamRecords($id_manager, $typeOfRecords, $scope){
    $recordManager = new RecordManager();
    $recordManager->getRecordsFromTeam($id_manager, $typeOfRecords, $scope);
}

function getAllUsersRecords($typeOfRecords, $scope){
    $recordManager = new RecordManager();
    $recordManager->getAllRecords($typeOfRecords, $scope);
}

function exportRecords($typeOfRecords, $scope, $date_start, $date_end, $id_manager, $id_user){
    $recordManager = new RecordManager();
    $recordManager->getAllRecords($typeOfRecords, $scope, $date_start, $date_end, $id_manager, $id_user);
}

function getOptionsData($typeOfData){
    $recordManager = new RecordManager();
    $recordManager->getDataForOptionSelect($typeOfData);
}