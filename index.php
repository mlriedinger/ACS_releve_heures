<?php

require 'autoloader.php';
require 'utils.php';

// Initialisation des contrôleurs pour être utilisés par le routeur
$loginController = new LoginController();
$recordController = new RecordController();
$settingController = new SettingController();
$exportController = new ExportController();

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
                    isset($_GET['worksiteId']) ? $_SESSION['worksiteId'] = $_GET['worksiteId'] : $_SESSION['worksiteId'] = 0 ;
                    $recordController->displayView('newRecord');
                } else throw new AuthenticationException();
                break;

            // Vue "Validation des relevés en attente"
            case "showPendingRecordsLog":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) {
                    $recordController->displayView('pendingRecordsLog');
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
                    $recordController->displayView('globalRecordsLog');
                } else throw new AuthenticationException();
                break;

            // Vue export
            case "showExportForm":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) {
                    $exportController->displayView('export');
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
                        echo 'index.php';

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
            case "getForm":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1') {
                    if (isset($_POST['formFile'])){
                        $recordController->displayPartial($_POST['formFile']);
                    }
                } else throw new AuthenticationException();
                break;

            // Renvoyer le formulaire de confirmation de suppression
            case "getDeleteConfirmationForm":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1') {
                    $recordController->displayPartial('deleteForm');
                } else throw new AuthenticationException();
                break;


            // Récupérer les données d'un relevé
            case "getRecord":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    if(isset($_POST['recordId'])){
                        $recordInfo = new Record();
                        $recordInfo->setRecordId(intval(inputValidation($_POST['recordId'])));
                        $recordController->getRecord($recordInfo);
                    } 
                    else throw new NoDataFoundException();
                } else throw new AuthenticationException();
                break;

            // Récupérer les données de l'historique personnel
            case "getRecords":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    if(isset($_POST['scope']) && isset($_POST['status'])) {
                        $recordInfo = new Record();
                        $recordInfo->setUserId($_SESSION['userId']);
                        $recordInfo->setUserGroup($_SESSION['userGroup']);
                        $recordInfo->setScope(inputValidation($_POST['scope']));
                        $recordInfo->setStatus(inputValidation($_POST['status']));
                        
                        $recordController->getRecords($recordInfo);
                    }
                    else throw new NoDataFoundException();
                } else throw new AuthenticationException();
                break;


            // Exporter les données en CSV
            case "exportRecords":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1' && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) { 
                        // if(isset($_GET['scope']) && $_GET['scope'] == 'export') {
                            if(isset($_POST['status']) && isset($_POST['periodStart']) && isset($_POST['periodEnd']) && isset($_POST['user'])) {
                                $exportInfo = new Export();
                                $exportInfo->setScope(inputValidation($_GET['scope']));
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
                        // } 
                    } else throw new AuthenticationException();
                } else throw new AuthenticationException(); 
                break;


            // Récupérer la liste des salariés
            case "getUsers":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    if($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2') {
                        $recordInfo = new Record();
                        $recordInfo->setUserGroup($_SESSION['userGroup']);
                        $recordInfo->setUserId($_SESSION['userId']);
                        $recordController->getUsers($recordInfo);
                    }
                } else throw new AuthenticationException();
                break;

            // Récupérer la liste des managers
            case "getManagers":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    if($_SESSION['userGroup'] == '1') {
                        $recordController->getManagers();
                    }
                } else throw new AuthenticationException();
                break;
            
            // Récupérer la liste des chantiers
            case "getWorksites":
                if(isset($_SESSION['userId']) && $_SESSION['isActive'] == '1'){
                    if(inputValidation($_POST['userId'] !== null)) {
                        $recordInfo = new Record();
                        $recordInfo->setUserId($_SESSION['userId']);

                        $recordController->getWorksites($recordInfo);

                    }
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
		$recordController->displayView('pendingRecordsLog', $errorCode, $errorMessage);
    } catch (Exception $e){
        $errorMessage = $e->getMessage();
        echo "Exception : " . $errorMessage;
    } catch(Error $e){
        echo "Erreur : " . $e->getMessage();
    }
} else {
    $loginController->displayView('login');
}