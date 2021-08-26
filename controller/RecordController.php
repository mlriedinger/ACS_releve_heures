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
     * Permet l'enregistrement d'un nouveau relevé d'heure.
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur.
     * Renvoie vers la page d'historique personnel en cas de succès, sinon vers le formulaire de saisie d'un nouveau relevé en cas.
     *
     * @param  Record $recordInfo
     */
    public function addNewRecord(Record $recordInfo){
        $isSendingSuccessfull = $this->recordManager->addNewRecord($recordInfo);

        if($isSendingSuccessfull) {
            $_SESSION['success'] = true;
            header('Location: index.php?action=showPersonalRecordsLog');
        }
        else {
            $_SESSION['success'] = false;
            $this->displayView('newRecord');
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
        $isUpdateSuccessfull = $this->recordManager->updateRecord($recordInfo);
        
        $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
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
            $updateAttempt = $this->recordManager->updateRecordStatus($recordChecked); 
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
        $isDeleteSuccessfull = $this->recordManager->deleteRecord($recordInfo);

        $isDeleteSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
        echo '<script>window.history.go(-1);</script>';
    }

    /**
     * Permet de récupérer les informations d'un relevé.
     *
     * @param  Record $recordInfo
     */
    public function getRecord(Record $recordInfo) {
        $this->_recordManager->getRecord($recordInfo);
    }
    
    /**
     * Permet de récupérer une liste de relevés.
     *
     * @param  Record $recordInfo
     */

    public function getRecords(Record $recordInfo) {
        $scope = $recordInfo->getScope();
        $userGroup = $recordInfo->getUserGroup();

        switch($scope) {
            case "user":
                $this->_recordManager->getUserRecords($recordInfo);
                break;
            case "team":
                $this->recordManager->getTeamRecords($recordInfo);
                break;
            case "global":
                if($userGroup === 1) {
                    $this->_recordManager->getAllRecords($recordInfo);
                } else throw new AccessDeniedException();
                break;
            default:
                throw new InvalidParameterException();
        }
    }

    public function getUsers() {
        $this->_recordManager->getUsers();
    }

    public function getWorksites(Record $recordInfo) {
        $this->_recordManager->getWorksites($recordInfo);
    }

    public function getWorkCategories() {
        $this->_recordManager->getWorkCategories();
    }

    public function getWorkSubCategories() {
        $this->_recordManager->getWorkSubCategories();
    }
}