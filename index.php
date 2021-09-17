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
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1") {
                    $loginController->displayView('home');
                } else throw new AuthenticationException();
                break;

            // Vue "Nouveau Relevé"
            case "showNewRecordForm":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1") {
                    isset($_GET['worksiteUUID']) ? $_SESSION['worksiteUUID'] = $_GET['worksiteUUID'] : $_SESSION['worksiteUUID'] = 0 ;
                    isset($_GET['eventType']) ? $_SESSION['eventType'] = $_GET['eventType'] : $_SESSION['eventType'] = "" ;
                    $recordController->displayView('newRecord');
                } else throw new AuthenticationException();
                break;

            // Vue "Validation des relevés en attente"
            case "showPendingRecordsLog":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && ($_SESSION['userGroup'] === $_SESSION['groupAdmin'] || $_SESSION['userGroup'] === $_SESSION['groupManager'])) {
                    $recordController->displayView('pendingRecordsLog');
                } else throw new AuthenticationException();
                break;

            // Vue "Historique personnel"
            case "showPersonalRecordsLog":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1") {
                    $recordController->displayView('personalRecordsLog');
                } else throw new AuthenticationException();
                break;

            // Vue historique global
            case "showAllRecordsLog":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && $_SESSION['userGroup'] === $_SESSION['groupAdmin']) {
                    $recordController->displayView('globalRecordsLog');
                } else throw new AuthenticationException();
                break;

            // Vue export
            case "showExportForm":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && ($_SESSION['userGroup'] === $_SESSION['groupAdmin'] || $_SESSION['userGroup'] === $_SESSION['groupManager'])) {
                    $exportController->displayView('export');
                } else throw new AuthenticationException();
                break;

            // Vue paramètres de saisie
            case "showSettingsForm":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && $_SESSION['userGroup'] === $_SESSION['groupAdmin']) {
                    $settingController->displayView('settingsForm');
                } else throw new AuthenticationException();
                break;
            
            // Mise à jour des paramètres de saisie
            case "updateSettings":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && $_SESSION['userGroup'] === $_SESSION['groupAdmin']) {
                    $settingInfo = new Setting();
                    $settingInfo = fillSettingInfos($settingInfo);

                    $settingController->updateSettings($settingInfo);   
                } else throw new AuthenticationException();
                break;


            // Ajout d'un nouveau relevé
            case "addNewRecord":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && isset($_SESSION['userGroup'])) {
                        $recordInfo = new Record();
                        $recordInfo = fillBasicRecordInfos($recordInfo);
                        
                        $recordInfo->setUserUUID($_SESSION['userUUID']);
                        $recordInfo->setUserGroup($_SESSION['userGroup']);
                        $recordInfo->setWeight(inputValidation($_POST['weight']));
                        $recordController->addNewRecord($recordInfo);
                    } else throw new AuthenticationException();
                } else throw new AuthenticationException(); 
                break;

            // Modification d'un relevé non validé
            case "updateRecord":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1"){
                        if(isset($_POST['recordId']) && is_numeric($_POST['recordId'])) {
                            $recordInfo = new Record();
                            $recordInfo = fillBasicRecordInfos($recordInfo);
                            $recordInfo->setRecordId(intval(inputValidation($_POST['recordId'])));
                            $recordInfo->setWeight(inputValidation($_POST['weight']));

                            $recordController->updateRecord($recordInfo);
                        } else throw new UpdateProblemException();
                    } else throw new AuthenticationException();
                } else throw new AuthenticationException(); 
                break;
                
            // Modification du statut du relevé
            case "updateRecordStatus":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1"){
                        if(!empty($_POST['checkList'])){
                            $recordController->updateRecordStatus($_POST['checkList']);
                        } else throw new InvalidParameterException('Veuillez sélectionner un ou plusieurs relevé(s) à valider.');
                    } else throw new AuthenticationException();
                } else throw new AuthenticationException(); 
                break;

            // Supprimer un relevé
            case "deleteRecord":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1"){
                        if($_SESSION['userGroup'] === $_SESSION['groupAdmin'] || $_SESSION['userGroup'] === $_SESSION['groupManager']){
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
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1") {
                    if (isset($_POST['formFile'])){
                        $recordController->displayPartial($_POST['formFile']);
                    }
                } else throw new AuthenticationException();
                break;

            // Renvoyer le formulaire de confirmation de suppression
            case "getDeleteConfirmationForm":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1") {
                    $recordController->displayPartial('deleteForm');
                } else throw new AuthenticationException();
                break;


            // Récupérer les données d'un relevé
            case "getRecord":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1"){
                    if(isset($_POST['recordId'])){
                        $recordInfo = new Record();
                        $recordInfo->setRecordId(intval(inputValidation($_POST['recordId'])));
                        $recordController->getRecord($recordInfo);
                    } 
                    else throw new NoDataFoundException();
                } else throw new AuthenticationException();
                break;

            case "getRecords":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1"){
                    if(isset($_POST['scope']) && isset($_POST['status'])) {
                        $recordInfo = new Record();
                        $recordInfo->setUserUUID($_SESSION['userUUID']);
                        $recordInfo->setUserGroup($_SESSION['userGroup']);
                        $recordInfo->setScope(inputValidation($_POST['scope']));
                        $recordInfo->setStatus(inputValidation($_POST['status']));
                        
                        $recordController->getRecords($recordInfo);
                    } else throw new NoDataFoundException();
                } else throw new AuthenticationException();
                break;


            // Exporter les données en CSV
            case "exportRecords":
                if(!empty($_POST['csrfToken']) && hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                    if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && ($_SESSION['userGroup'] === $_SESSION['groupAdmin'] || $_SESSION['userGroup'] === $_SESSION['groupManager'])) { 
                        // if(isset($_GET['scope']) && $_GET['scope'] == 'export') {
                            if(isset($_POST['status']) && isset($_POST['periodStart']) && isset($_POST['periodEnd']) && isset($_POST['user'])) {
                                $exportInfo = new Export();
                                $exportInfo->setScope(inputValidation($_GET['scope']));
                                $exportInfo->setStatus(inputValidation($_POST['status']));
                                $exportInfo->setUserUUID(inputValidation($_POST['user']));
                                $exportInfo->setUserGroup(inputValidation($_SESSION['userGroup']));
                                $exportInfo->setPeriodStart(inputValidation($_POST['periodStart']));
                                $exportInfo->setPeriodEnd(inputValidation($_POST['periodEnd']));

                                if ($_SESSION['userGroup'] === $_SESSION['groupManager']) {
                                    $exportInfo->setManagerId(inputValidation($_SESSION['userUUID']));
                                } else {
                                    if(isset($_POST['manager'])) {
                                        $exportInfo->setManagerId(inputValidation($_POST['manager']));
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
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1"){
                    if($_SESSION['userGroup'] === $_SESSION['groupAdmin']) {
                        $recordController->getUsers();
                    }
                } else throw new AuthenticationException();
                break;
            
            // Récupérer la liste des chantiers
            case "getWorksites":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1"){
                    if(inputValidation($_POST['userUUID'] !== null)) {
                        $recordInfo = new Record();
                        $recordInfo->setUserUUID(inputValidation($_POST['userUUID']));

                        $recordController->getWorksites($recordInfo);
                    }
                } else throw new AuthenticationException();
                break;


            // Récupérer la liste des catégories de postes de travail
            case "getWorkCategories":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1"){
                    $recordController->getWorkCategories();
                } else throw new AuthenticationException();
                break;

            // Récupérer la liste des sous-catégories de postes de travail
            case "getWorkSubCategories":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1"){
                    $recordController->getWorkSubCategories();
                } else throw new AuthenticationException();
                break;

            // Récupérer les événements du planning
            case "getEventsFromPlanning":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && isset($_POST['userUUID']) && inputValidation($_POST['userUUID'] !== null)){
                    $eventController->getEventsFromPlanning(inputValidation($_POST['userUUID']));
                } else throw new AuthenticationException();
                break;

            // Récupérer le total des heures de la journée
            case "getUserDailyTotal":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && isset($_POST['userUUID']) && inputValidation($_POST['userUUID'] !== null)){
                    $recordInfo = new Record();
                    $recordInfo->setUserUUID(inputValidation($_POST['userUUID']));

                    $recordController->getUserDailyTotal($recordInfo);
                }
                break;

            // Récupérer le total des heures hebdomadaires
            case "getUserWeeklyTotal":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && isset($_POST['userUUID']) && inputValidation($_POST['userUUID'] !== null)){
                    $recordController->getUserWeeklyTotal(inputValidation($_POST['userUUID']), inputValidation($_POST['weekNumber']));
                }
                break;
            
                // Récupérer tous les cumuls de la semaine en cours
            case "getUserDailyTotals":
                if(isset($_SESSION['userUUID']) && $_SESSION['isActive'] === "1" && isset($_POST['userUUID']) && inputValidation($_POST['userUUID'] !== null)){
                    $recordController->getUserDailyTotals(inputValidation($_POST['userUUID']), inputValidation($_POST['weekNumber']));
                }
                break;
        }
    } catch (PDOException $e){
        header('HTTP/1.1 401 Unauthorized', true, 401);
        http_response_code(401);
        $errorCode = $e->getCode();
        $loginController->displayView('login', $errorCode);
    } catch (AuthenticationException $e){
        header('HTTP/1.1 401 Unauthorized', true, 401);
        http_response_code(401);
        $errorCode = $e->getCode();
        $errorMessage = $e->getMessage();
        $loginController->displayView('login',$errorCode, $errorMessage);
	} catch (InvalidParameterException $e){
        header('HTTP/1.1 401 Unauthorized', true, 401);
        http_response_code(401);
		$errorCode = $e->getCode();
        $errorMessage = $e->getMessage();
		$recordController->displayView('pendingRecordsLog', $errorCode, $errorMessage);
    } catch (Exception $e){
        $errorMessage = $e->getMessage();
        echo "Exception : " . $errorMessage;
    } catch(Error $e){
        echo "Erreur : " . $e->getMessage();
        echo "<br/> Code erreur : " . $e->getCode() . " File : " . $e->getFile() . " Line : " . $e->getLine();
    }
} else {
    $loginController->displayView('login');
}