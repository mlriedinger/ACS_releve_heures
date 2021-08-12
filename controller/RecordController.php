<?php
session_start();

require_once 'AbstractController.php';
require 'autoloader.php';

/**
 * Classe qui permet de gérer l'enregistrement, la modification/suppression et l'affichage des relevés d'heures. Classe-fille d'AbstractController pour hériter des méthodes permettant de rendre une vue.
 */
class RecordController extends AbstractController {

    private $_recordManager;

    public function __construct() {
        $this->_recordManager = new RecordManager();
    }
     
    /**
     * Rend le formulaire de saisie de relevé (uniquement le formulaire).
     *
     * @param  Record $recordInfo
     */
    public function getRecordForm(int $record, int $user){
        $recordId = $record;
        $userId = $user;
        $this->displayPartial('recordForm');
    }

    /**
     * Permet l'enregistrement d'un nouveau relevé d'heure.
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur.
     * Renvoie vers la page d'historique personnel en cas de succès, sinon vers le formulaire de saisie d'un nouveau relevé en cas.
     *
     * @param  Record $recordInfo
     */
    public function addNewRecord(Record $recordInfo){
        $lastInsertRecordId = $this->_recordManager->addNewRecord($recordInfo);
        $addRecordDetailsAttempt = $this->_recordManager->addRecordDetails($recordInfo, $lastInsertRecordId);

        if($addRecordDetailsAttempt) {
            $_SESSION['success'] = true;
            $this->displayView('personalRecordsLog');
        }
        else {
            $_SESSION['success'] = false;
            $this->displayView('addNewRecord');
        }
    }

    /**
     * Permet la modification d'un relevé qui n'a pas encore été validé.
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur.
     * Renvoie vers la dernière page visitée avant l'envoi du formulaire.
     *
     * @param  Record $recordInfo
     */
    public function updateRecord(Record $recordInfo){
        $isRecordUpdateSuccessfull = $this->_recordManager->updateRecord($recordInfo);
        $isDetailUpdateSuccessfull = $this->_recordManager->updateRecordDetails($recordInfo);
        
        // echo "RecordUpdate : " . $isRecordUpdateSuccessfull;
        // echo "<br>";
        // echo "DetailUpdate : " .$isDetailUpdateSuccessfull;
        $isRecordUpdateSuccessfull && $isDetailUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
        echo '<script>window.history.go(-1);</script>';
    }

    /**
     * Permet de mettre à jour le statut des relevés (validation) en fonction de la sélection faite par le manager.
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur.
     * Renvoie vers la dernière page visitée avant l'envoi du formulaire.
     *
     * @param  array $recordsCheckList
     */
    public function updateRecordStatus(array $recordsCheckList){   
        $updateResults = [];

        foreach($recordsCheckList as $recordChecked){
            $updateAttempt = $this->_recordManager->updateRecordStatus($recordChecked); 
            if($updateAttempt) array_push($updateResults, $updateAttempt);
        }

        if(count($recordsCheckList) == count($updateResults)) $isUpdateSuccessfull = true;      

        $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
        echo '<script>window.history.go(-1);</script>';
    }

    /**
     * Permet de "supprimer" un relevé d'heure (en réalité le rendre inactif).
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur.
     * Renvoie vers la dernière page visitée avant l'envoi du formulaire.
     *
     * @param  Record $recordInfo
     */
    public function deleteRecord(Record $recordInfo){
        $isDeleteSuccessfull = $this->_recordManager->deleteRecord($recordInfo);

        $isDeleteSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
        echo '<script>window.history.go(-1);</script>';
    }

    /**
     * Permet de récupérer les informations d'un relevé.
     *
     * @param  Record $recordInfo
     */
    public function getRecordData(Record $recordInfo){
        $this->_recordManager->getRecord($recordInfo);
    }
    
    /**
     * Permet de récupérer une liste de relevés selon un périmètre passé en second paramètre.
     * Par exemple, getRecords($recordInfo, 'user') permet de récupérer les relevés d'un utilisateur.
     *
     * @param  Record $recordInfo
     * @param  string $scope : "user", "team" ou "all", correspond au périmètre de la recherche
     */
    public function getRecords(Record $recordInfo, string $scope) {

        switch($scope) {
            case "user":
                $this->_recordManager->getRecordsFromUser($recordInfo);
                break;
            case "team":
                $this->_recordManager->getRecordsFromTeam($recordInfo);
                break;
            case "all":
                $this->_recordManager->getAllRecords($recordInfo);
                break;
            default:
                throw new InvalidParameterException();
        }
    }

    /**
     * Permet de récupérer (au choix) la liste des managers, des salariés ou des chantiers pour les afficher dans un <select>
     *
     * @param  Record $recordInfo : "managers", "users" ou "worksites"
     */
    public function getOptionsData(Record $recordInfo) {
        $this->_recordManager->getDataForOptionSelect($recordInfo);
    }

    public function getWorkCategories() {
        $this->_recordManager->getWorkCategories();
    }

    public function getWorkSubCategories() {
        $this->_recordManager->getWorkSubCategories();
    }
}