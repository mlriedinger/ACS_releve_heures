<?php
session_start();

/* On charge automatiquement les classes pour accéder à leurs méthodes */
require 'autoloader.php';

class RecordController {

    /* Fonctions pour gérer l'affichage des pages :
        * displayNewRecordForm() : page de saisie d'un nouveau relevé, 
        * displayValidationForm() : page de validation de relevés en attente
        * displayPersonalRecordsLog() : historique personnel, 
        * displayTeamRecordsLog() : historique équipe
        * displayAllRecordsLog() : historique global
        * display ExportForm() : page export de données 
    */

    public function displayNewRecordForm(){
        require('view/addNewRecord.php');
    }

    public function displayValidationForm(){
        require('view/recordsToCheck.php');
    }

    public function displayPersonalRecordsLog(){
        require('view/personalRecordsLog.php');
    }

    public function displayTeamRecordsLog(){
        require('view/teamRecordsLog.php');
    }

    public function displayAllRecordsLog(){
        require('view/allUsersRecordsLog.php');
    }

    public function displayExportForm(){
        require('view/exportRecordsForm.php');
    }


    /* Fonction pour récupérer le formulaire de saisie (uniquement le formulaire) */

    public function getRecordForm($recordInfo){
        $recordId = $recordInfo->getRecordId();
        $userId = $recordInfo->getUserId();
        require('view/partials/recordForm.php');
    }


    /* Fonction pour récupérer le formulaire de confirmation de suppression (uniquement le formulaire) */

    public function getDeleteConfirmationForm(){
        require('view/partials/deleteConfirmationForm.php');
    }


    /* Fonction pour enregistrer un nouveau relevé en BDD */

    public function addNewRecord($recordInfo){
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

    public function updateRecord($recordInfo){
        $recordManager = new RecordManager();
        $isUpdateSuccessfull = $recordManager->updateRecord($recordInfo);
        
        $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
        // Renvoie sur la dernière page visitée avant l'envoi du formulaire
        echo '<script>window.history.go(-1);</script>';
    }


    /* Fonction pour mettre à jour le statut des relevés (validation) en fonction de la sélection faite par le manager */

    public function updateRecordStatus($recordsCheckList){   
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

    public function deleteRecord($recordInfo){
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

    public function getRecordData($recordInfo){
        $recordManager = new RecordManager();
        $recordManager->getRecord($recordInfo);
    }

    public function getUserRecords($recordInfo){
        $recordManager = new RecordManager();
        $recordManager->getRecordsFromUser($recordInfo);   
    }

    public function getTeamRecords($recordInfo){
        $recordManager = new RecordManager();
        $recordManager->getRecordsFromTeam($recordInfo);
    }

    public function getAllUsersRecords($recordInfo){
        $recordManager = new RecordManager();
        $recordManager->getAllRecords($recordInfo);
    }

    public function exportRecords($recordInfo){
        $exportManager = new ExportManager();
        $exportManager->exportRecords($recordInfo);
    }

    public function getOptionsData($typeOfData, $userId=""){
        $recordManager = new RecordManager();
        $recordManager->getDataForOptionSelect($typeOfData, $userId);
    }
}