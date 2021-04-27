<?php
session_start();

require 'autoloader.php';

/**
 * Classe qui permet de gérer l'enregistrement, la modification/suppression et l'affichage des relevés d'heures
 */
class RecordController {
    
    /**
     * Rend la vue de saisie d'un nouveau relevé
     */
    public function displayNewRecordForm(){
        require('view/addNewRecord.php');
    }
    

    /**
     * Rend la vue de validation de relevés en attente
     */
    public function displayValidationForm(){
        require('view/recordsToCheck.php');
    }
    

    /**
     * Rend la vue d'historique personnel
     */
    public function displayPersonalRecordsLog(){
        require('view/personalRecordsLog.php');
    }
    

    /**
     * Rend la vue d'historique équipe
     */
    public function displayTeamRecordsLog(){
        require('view/teamRecordsLog.php');
    }
    

    /**
     * Rend la vue d'historique global
     */
    public function displayAllRecordsLog(){
        require('view/allUsersRecordsLog.php');
    }
    
    
    /**
     * Rend la vue d'export de données
     */
    public function displayExportForm(){
        require('view/exportRecordsForm.php');
    }
    
       
    /**
     * Rend le formulaire de saisie de relevé (uniquement le formulaire)
     *
     * @param  Record $recordInfo
     */
    public function getRecordForm(Record $recordInfo){
        $recordId = $recordInfo->getRecordId();
        $userId = $recordInfo->getUserId();
        require('view/partials/recordForm.php');
    }
    
    
    /**
     * Rend le formulaire de confirmation de suppression (uniquement le formulaire)
     */
    public function getDeleteConfirmationForm(){
        require('view/partials/deleteConfirmationForm.php');
    }

    
    /**
     * Permet l'enregistrement d'un nouveau relevé d'heure
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur
     *
     * @param  Record $recordInfo
     */
    public function addNewRecord(Record $recordInfo){
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

   
    /**
     * Permet la modification d'un relevé qui n'a pas encore été validé
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur
     *
     * @param  Record $recordInfo
     */
    public function updateRecord(Record $recordInfo){
        $recordManager = new RecordManager();
        $isUpdateSuccessfull = $recordManager->updateRecord($recordInfo);
        
        $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
        // Renvoie sur la dernière page visitée avant l'envoi du formulaire
        echo '<script>window.history.go(-1);</script>';
    }

    
    /**
     * Permet de mettre à jour le statut des relevés (validation) en fonction de la sélection faite par le manager
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur
     *
     * @param  Array $recordsCheckList
     */
    public function updateRecordStatus(Array $recordsCheckList){   
        $recordManager = new RecordManager();
        $updateResults = [];

        foreach($recordsCheckList as $recordChecked){
            $updateAttempt = $recordManager->updateRecordStatus($recordChecked); 
            if($updateAttempt) array_push($updateResults, $updateAttempt);
        }

        if(count($recordsCheckList) == count($updateResults)) $isUpdateSuccessfull = true;      

        $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
        echo '<script>window.history.go(-1);</script>';
    }

    
    /**
     * Permet de "supprimer" un relevé d'heure (en réalité le rendre inactif)
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur
     *
     * @param  Record $recordInfo
     */
    public function deleteRecord(Record $recordInfo){
        $recordManager = new RecordManager();
        $isDeleteSuccessfull = $recordManager->deleteRecord($recordInfo);

        $isDeleteSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
        echo '<script>window.history.go(-1);</script>';
    }

    
    /**
     * Permet de récupérer les informations d'un relevé
     *
     * @param  Record $recordInfo
     */
    public function getRecordData(Record $recordInfo){
        $recordManager = new RecordManager();
        $recordManager->getRecord($recordInfo);
    }
    

    /**
     * Permet de récupérer les relevés personnels de l'utilisateur
     *
     * @param  Record $recordInfo
     */
    public function getUserRecords(Record $recordInfo){
        $recordManager = new RecordManager();
        $recordManager->getRecordsFromUser($recordInfo);   
    }
    

    /**
     * Permet de récupérer les relevés des salariés appartenant à l'équipe dont l'utilisateur est le manager
     *
     * @param  Record $recordInfo
     */
    public function getTeamRecords(Record $recordInfo){
        $recordManager = new RecordManager();
        $recordManager->getRecordsFromTeam($recordInfo);
    }
    

    /**
     * Permet de récupérer tous les relevés
     *
     * @param  Record $recordInfo
     */
    public function getAllUsersRecords(Record $recordInfo){
        $recordManager = new RecordManager();
        $recordManager->getAllRecords($recordInfo);
    }
    

    /**
     * Permet d'exporter les relevés souhaités au format CSV
     *
     * @param  Record $recordInfo
     */
    public function exportRecords(Record $recordInfo){
        $exportManager = new ExportManager();
        $exportManager->exportRecords($recordInfo);
    }
    

    /**
     * Permet de récupérer (au choix) la liste des managers, des salariés ou des chantiers pour les afficher dans un <select>
     *
     * @param  String $typeOfData : "managers", "users" ou "worksites"
     * @param  int $userId (optionnel) : ID du salarié nécessaire uniquement à la récupération des chantiers auxquels le salarié est affecté
     */
    public function getOptionsData($typeOfData, $userId=""){
        $recordManager = new RecordManager();
        $recordManager->getDataForOptionSelect($typeOfData, $userId);
    }
}