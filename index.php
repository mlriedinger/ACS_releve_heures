<?php

require 'autoloader.php';
require 'utils.php';

// Initialisation des contrôleurs pour être utilisés par le routeur
$loginController = new LoginController();
$recordController = new RecordController();
$settingController = new SettingController();
$exportController = new ExportController();
$eventController = new EventController();

/**
 * Routeur de l'application
 * */
if(isset($_GET['action'])) {
    try {
        switch(inputValidation($_GET['action'])) {
            // Connexion
            case "login":
                if(isset($_POST['login']) && isset($_POST['password']) || $_POST['login'] != "" || $_POST['password'] != "") {
                    $settingController->getSettings();
                    $loginController->verifyLogin(inputValidation($_POST['login']), inputValidation($_POST['password']));
                } else throw new InvalidParameterException();
                break;

            // Déconnexion
            case "logout":
                $loginController->logout();
                break;

            // Vue "Accueil"
            case "showHomePage":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1') {
                    $loginController->displayView('home');
                } else throw new AuthenticationException();
                break;

            // Vue "Nouveau Relevé"
            case "showNewRecordForm":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1') {
                    $recordController->displayView('addNewRecord');
                } else throw new AuthenticationException();
                break;

            // Vue "Validation des relevés en attente"
            case "showRecordsToCheck":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) {
                    $recordController->displayView('recordsToCheck');
                } else throw new AuthenticationException();
                break;

            // Vue "Historique personnel"
            case "showPersonalRecordsLog":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1') {
                    $recordController->displayView('personalRecordsLog');
                } else throw new AuthenticationException();
                break;

            // Vue historique équipe
            case "showTeamRecordsLog":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) {
                    $recordController->displayView('teamRecordsLog');
                } else throw new AuthenticationException();
                break;

            // Vue historique global
            case "showAllRecordsLog":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && $_SESSION['userGroup'] == '1') {
                    $recordController->displayView('allUsersRecordsLog');
                } else throw new AuthenticationException();
                break;

            // Vue export
            case "showExportForm":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) {
                    $exportController->displayView('exportRecordsForm');
                } else throw new AuthenticationException();
                break;

            // Vue paramètres de saisie
            case "showSettingsForm":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && $_SESSION['userGroup'] == '1') {
                    $settingController->displayView('settingsForm');
                } else throw new AuthenticationException();
                break;
            
            // Mise à jour des paramètres de saisie
            case "updateSettings":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && $_SESSION['userGroup'] == '1') {
                    $settingInfo = new Setting();
                    $settingInfo = fillSettingInfos($settingInfo);

                    $settingController->updateSettings($settingInfo);   
                } else throw new AuthenticationException();
                break;


            // Ajout d'un nouveau relevé
            case "addNewRecord":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && isset($_SESSION['userGroup'])) {
                        $recordInfo = new Record();
                        $recordInfo = fillBasicRecordInfos($recordInfo);
                        $recordInfo->setUserId($_SESSION['userId']);
                        $recordInfo->setUserGroup($_SESSION['userGroup']);

                        $recordController->addNewRecord($recordInfo);
                    } else throw new AuthenticationException();
                } else throw new AuthenticationException(); 
                break;

            // Modification d'un relevé non validé
            case "updateRecord":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                        if(isset($_POST['recordId']) && is_numeric($_POST['recordId'])) {
                            $recordInfo = new Record();
                            $recordInfo = fillBasicRecordInfos($recordInfo);
                            $recordInfo->setRecordId(intval(inputValidation($_POST['recordId'])));

                            $recordController->updateRecord($recordInfo);
                        } else throw new UpdateProblemException();
                    } else throw new AuthenticationException();
                } else throw new AuthenticationException(); 
                break;
                
            // Modification du statut du relevé
            case "updateRecordStatus":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                        if(!empty($_POST['checkList'])){
                            $recordController->updateRecordStatus($_POST['checkList']);
                        } else throw new InvalidParameterException('Veuillez sélectionner un ou plusieurs relevé(s) à valider.');
                    } else throw new AuthenticationException();
                } else throw new AuthenticationException(); 
                break;

            // Supprimer un relevé
            case "deleteRecord":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                        if($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2'){
                            if(isset($_POST['recordId']) && is_numeric($_POST['recordId']) && !empty($_POST['comment']) && inputValidation($_POST['comment'] != " ")) {
                                $recordInfo = new Record();
                                $recordInfo->setRecordId(intval(inputValidation($_POST['recordId'])));
                                $recordInfo->setComment(inputValidation($_POST['comment']));

                                $recordController->deleteRecord($recordInfo);
                            } else throw new UpdateProblemException();
                        } else {
                            if(isset($_POST['recordId']) && is_numeric($_POST['recordId'])) {
                                $recordInfo = new Record();
                                $recordInfo->setRecordId(intval(inputValidation($_POST['recordId'])));
                                $recordInfo->setComment(inputValidation($_POST['comment']));

                                $recordController->deleteRecord($recordInfo);
                            } else throw new UpdateProblemException();
                        }
                    } else throw new AuthenticationException();
                } else throw new AuthenticationException(); 
                break;


            // Renvoyer le formulaire de saisie
            case "getRecordForm":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1') {
                    if (isset($_POST['recordId'])){
                        $recordId = intval(inputValidation($_POST['recordId']));
                        $userId = intval(inputValidation($_POST['userId']));

                        $recordController->getRecordForm($recordId, $userId);
                    }
                } else throw new AuthenticationException();
                break;

            // Renvoyer le formulaire de confirmation de suppression
            case "getDeleteConfirmationForm":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1') {
                    $recordController->displayPartial('deleteConfirmationForm');
                } else throw new AuthenticationException();
                break;


            // Récupérer les données d'un relevé
            case "getRecordData":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    if(isset($_POST['recordId'])){
                        $recordInfo = new Record();
                        $recordInfo->setRecordId(intval(inputValidation($_POST['recordId'])));
                        $recordController->getRecordData($recordInfo);
                    } 
                    else throw new NoDataFoundException();
                } else throw new AuthenticationException();
                break;

            // Récupérer les données de l'historique personnel
            case "getPersonalRecordsLog":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    if(isset($_POST['typeOfRecords']) && isset($_POST['status'])) {
                        $recordInfo = new Record();
                        $recordInfo->setUserId($_SESSION['userId']);
                        $recordInfo->setTypeOfRecords(inputValidation($_POST['typeOfRecords']));
                        $recordInfo->setStatus(inputValidation($_POST['status']));
                        
                        $recordController->getRecords($recordInfo, 'user');
                    }
                    else throw new NoDataFoundException();
                } else throw new AuthenticationException();
                break;

            // Récupérer les données de l'historique équipe
            case "getTeamRecordsLog":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    if(isset($_POST['typeOfRecords']) && isset($_POST['status']) && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) {
                        $recordInfo = new Record();
                        $recordInfo->setUserId($_SESSION['userId']);
                        $recordInfo->setTypeOfRecords(inputValidation($_POST['typeOfRecords']));
                        $recordInfo->setStatus(inputValidation($_POST['status']));
                        
                        $recordController->getRecords($recordInfo, 'team');
                    }
                    else throw new NoDataFoundException();
                } else throw new AuthenticationException();
                break;

            // Récupérer les données de l'historique global
            case "getAllUsersRecordsLog":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    if(isset($_POST['typeOfRecords']) && isset($_POST['status']) && $_SESSION['userGroup'] == '1') {
                        $recordInfo = new Record();
                        $recordInfo->setUserId($_SESSION['userId']);
                        $recordInfo->setTypeOfRecords(inputValidation($_POST['typeOfRecords']));
                        $recordInfo->setStatus(inputValidation($_POST['status']));

                        $recordController->getRecords($recordInfo, 'all');
                    }
                    else throw new NoDataFoundException();
                } else throw new AuthenticationException();
                break;


            // Exporter les données en CSV
            case "exportRecords":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) { 
                        if(isset($_GET['typeOfRecords']) && $_GET['typeOfRecords'] == 'export') {
                            if(isset($_POST['status']) && isset($_POST['periodStart']) && isset($_POST['periodEnd']) && isset($_POST['user'])) {
                                $exportInfo = new Export();
                                $exportInfo->setTypeOfRecords(inputValidation($_GET['typeOfRecords']));
                                $exportInfo->setStatus(inputValidation($_POST['status']));
                                $exportInfo->setUserId(intval(inputValidation($_POST['user'])));
                                $exportInfo->setUserGroup(intval(inputValidation($_SESSION['userGroup'])));
                                $exportInfo->setPeriodStart(inputValidation($_POST['periodStart']));
                                $exportInfo->setPeriodEnd(inputValidation($_POST['periodEnd']));

                                if ($_SESSION['userGroup'] == '2') {
                                    $exportInfo->setManagerId(intval(inputValidation($_SESSION['userId'])));
                                } else {
                                    if(isset($_POST['manager'])) {
                                        $exportInfo->setManagerId(intval(inputValidation($_POST['manager'])));
                                    }
                                }

                                $exportController->exportRecords($exportInfo);
                            }
                        } 
                    } else throw new AuthenticationException();
                } else throw new AuthenticationException(); 
                break;


            // Récupérer les listes des managers et des salariés pour le formulaire d'export
            case "getOptionsData":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    if(isset($_POST['typeOfData']) && isset($_POST['scope']) && inputValidation($_POST['userId'] !== null)) {
                        $recordInfo = new Record();
                        $recordInfo->setUserId($_SESSION['userId']);
                        $recordInfo->setUserGroup($_SESSION['userGroup']);
                        $recordInfo->setTypeOfRecords(inputValidation($_POST['typeOfData']));
                        
                        //if(inputValidation($_POST['status']) === "export"){
                            //$recordController->getOptionsData(inputValidation($_POST['typeOfData']));
                        //}
                        //if(inputValidation($_POST['status']) === "add" && inputValidation($_POST['userId'] !== null)) {
                        $recordController->getOptionsData($recordInfo);
                        //}
                    }
                } else throw new AuthenticationException();
                break;


            // Récupérer la liste des catégories de postes de travail
            case "getWorkCategories":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    $recordController->getWorkCategories();
                } else throw new AuthenticationException();
                break;

            // Récupérer la liste des sous-catégories de postes de travail
            case "getWorkSubCategories":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    $recordController->getWorkSubCategories();
                } else throw new AuthenticationException();
                break;

            // Récupérer les événements du planning
            case "getEventsFromPlanning":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && isset($_POST['userId'] ) && inputValidation($_POST['userId'] !== null)){
                    $eventController->getEventsFromPlanning(intval(inputValidation($_POST['userId'])));
                } else throw new AuthenticationException();
                break;
        }
    } catch (PDOException $e){
        $errorCode = $e->getCode();
        $loginController->displayView('login', $errorCode);
    } catch (AuthenticationException $e){
        $errorCode = $e->getCode();
        $errorMessage = $e->getMessage();
        $loginController->displayView('login',$errorCode, $errorMessage);
	} catch (InvalidParameterException $e){
		$errorCode = $e->getCode();
        $errorMessage = $e->getMessage();
		$recordController->displayView('recordsToCheck', $errorCode, $errorMessage);
    } catch (Exception $e){
        $errorMessage = $e->getMessage();
        echo "Exception : " . $errorMessage;
    } catch(Error $e){
        echo "Erreur : " . $e->getMessage();
    }
} else {
    $loginController->displayView('login');
}