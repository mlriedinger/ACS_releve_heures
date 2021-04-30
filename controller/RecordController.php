<?php
session_start();

require 'autoloader.php';

/**
 * Classe qui permet de gérer l'enregistrement, la modification/suppression et l'affichage des relevés d'heures.
 */
class RecordController {
    
    /**
     * Rend la vue dont le nom est passé en paramètre.
     *
     * @param  String $viewFile
     */
    public function displayView(String $viewFile) {
        require 'view/'.$viewFile.'.php';
    }
    
    /**
     * Rend la vue partielle dont le nom est passé en paramètre.
     *
     * @param  String $partialFile
     */
    public function displayPartial(String $partialFile) {
        require 'view/partials/'.$partialFile.'.php';
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
     * Permet la modification d'un relevé qui n'a pas encore été validé.
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur.
     * Renvoie vers la dernière page visitée avant l'envoi du formulaire.
     *
     * @param  Record $recordInfo
     */
    public function updateRecord(Record $recordInfo){
        $recordManager = new RecordManager();
        $isUpdateSuccessfull = $recordManager->updateRecord($recordInfo);
        
        $isUpdateSuccessfull ? $_SESSION['success'] = true : $_SESSION['success'] = false;
        echo '<script>window.history.go(-1);</script>';
    }

    /**
     * Permet de mettre à jour le statut des relevés (validation) en fonction de la sélection faite par le manager.
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur.
     * Renvoie vers la dernière page visitée avant l'envoi du formulaire.
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
     * Permet de "supprimer" un relevé d'heure (en réalité le rendre inactif).
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur
     * Renvoie vers la dernière page visitée avant l'envoi du formulaire.
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
     * Permet de récupérer les informations d'un relevé.
     *
     * @param  Record $recordInfo
     */
    public function getRecordData(Record $recordInfo){
        $recordManager = new RecordManager();
        $recordManager->getRecord($recordInfo);
    }
    
    /**
     * Permet de récupérer une liste de relevés selon un périmètre passé en second paramètre.
     * Par exemple, getRecords($recordInfo, 'user') permet de récupérer les relevés d'un utilisateur.
     *
     * @param  Record $recordInfo
     * @param  String $scope : "user", "team" ou "all", correspond au périmètre de la recherche
     */
    public function getRecords(Record $recordInfo, String $scope) {
        $recordManager = new RecordManager();

        switch($scope) {
            case "user":
                $recordManager->getRecordsFromUser($recordInfo);
                break;
            case "team":
                $recordManager->getRecordsFromTeam($recordInfo);
                break;
            case "all":
                $recordManager->getAllRecords($recordInfo);
                break;
            default:
                throw new InvalidParameterException();
        }
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