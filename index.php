<?php

    /* On charge les classes des contrôleurs, modèles et exceptions pour avoir accès à leurs méthodes */
    require 'autoloader.php';


    /* Fonction qui permet d'assainir les inputs utilisateur */
    function inputValidation($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    function fillBasicRecordInfos($recordInfo){
        if(!empty($_POST['worksiteId'])){
            $recordInfo->setWorksite(intval(inputValidation($_POST['worksiteId'])));
        }

        if(isset($_POST['comment'])) {
            $recordInfo->setComment(inputValidation($_POST['comment']));
        }

        if($_SESSION['dateTimeMgmt'] == '1' && !empty($_POST['datetimeStart']) && !empty($_POST['datetimeEnd'])){
            $recordInfo->setDateTimeStart(inputValidation($_POST['datetimeStart']));
            $recordInfo->setDateTimeEnd(inputValidation($_POST['datetimeEnd']));
        }
        else if($_SESSION['lengthMgmt'] == '1' && !empty($_POST['recordDate']) && !empty($_POST['workLengthHours']) && !empty($_POST['workLengthMinutes'])){
            $recordInfo->setDate(inputValidation($_POST['recordDate']));
            $recordInfo->setWorkLengthHours(intval(inputValidation($_POST['workLengthHours'])));
            $recordInfo->setWorkLengthMinutes(intval(inputValidation($_POST['workLengthMinutes'])));
        }

        if($_SESSION['breakMgmt'] == '1' && !empty($_POST['breakLengthHours']) && !empty($_POST['breakLengthMinutes'])){
            $recordInfo->setBreakLengthHours(intval(inputValidation($_POST['breakLengthHours'])));
            $recordInfo->setBreakLengthMinutes(intval(inputValidation($_POST['breakLengthMinutes'])));
        }

        if($_SESSION['tripMgmt'] == '1' && !empty($_POST['tripLengthHours']) && !empty($_POST['tripLengthMinutes'])){
            $recordInfo->setTripLengthHours(intval(inputValidation($_POST['tripLengthHours'])));
            $recordInfo->setTripLengthMinutes(intval(inputValidation($_POST['tripLengthMinutes'])));
        }
        
        return $recordInfo;
    }


    // Initialisation d'un objet LoginController et RecordController pour être utilisés par le routeur
    $loginController = new LoginController();
    $recordController = new RecordController();
    $settingController = new SettingController();


    /* Routeur de l'application qui appelle le contrôleur correspondant à l'URL demandée */
    if(isset($_GET['action'])) {
    
        // Décommenter la ligne suivante pour voir la requête qui est reçue
        // var_dump($_REQUEST);

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


                // Page d'accueil
                case "showHomePage":
                    if(isset($_SESSION['userId'])) {
                        $loginController->displayHomePage();
                    } else throw new AuthenticationException();
                    break;

                // Page "Nouveau Relevé"
                case "showNewRecordForm":
                    if(isset($_SESSION['userId'])) {
                        $recordController->displayNewRecordForm();
                    } else throw new AuthenticationException();
                    break;

                // Page de relevés en attente de validation
                case "showRecordsToCheck":
                    if(isset($_SESSION['userId']) && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) {
                        $recordController->displayValidationForm();
                    } else throw new AuthenticationException();
                    break;

                // Page historique personnel
                case "showPersonalRecordsLog":
                    if(isset($_SESSION['userId'])) {
                        $recordController->displayPersonalRecordsLog();
                    } else throw new AuthenticationException();
                    break;

                // Page historique équipe
                case "showTeamRecordsLog":
                    if(isset($_SESSION['userId']) && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) {
                        $recordController->displayTeamRecordsLog();
                    } else throw new AuthenticationException();
                    break;

                // Page historique global
                case "showAllRecordsLog":
                    if(isset($_SESSION['userId']) && $_SESSION['userGroup'] == '1') {
                        $recordController->displayAllRecordsLog();
                    } else throw new AuthenticationException();
                    break;

                // Page export
                case "showExportForm":
                    if(isset($_SESSION['userId']) && $_SESSION['userGroup'] == '1') {
                        $recordController->displayExportForm();
                    } else throw new AuthenticationException();
                    break;

                case "showSettingsForm":
                    if(isset($_SESSION['userId']) && $_SESSION['userGroup'] == '1') {
                        $settingController->displaySettingsForm();
                    } else throw new AuthenticationException();
                    break;

                case "updateSettings":
                    if(isset($_SESSION['userId']) && $_SESSION['userGroup'] == '1') {
                        $settingInfo = new Setting();
                        $settingInfo->setDateTimeMgmt(intval(inputValidation($_POST['dateTimeMgmtSwitch'])));
                        $settingInfo->setLengthMgmt(intval(inputValidation($_POST['lengthMgmtSwitch'])));
                        $settingInfo->setTripMgmt(intval(inputValidation($_POST['tripMgmtSwitch'])));
                        $settingInfo->setBreakMgmt(intval(inputValidation($_POST['breakMgmtSwitch'])));

                        $settingController->updateSettings($settingInfo);   
                    } else throw new AuthenticationException();
                    break;


                // Ajout d'un nouveau relevé
                case "addNewRecord":
                    if(!empty($_POST['csrfToken'])) {
                        if(hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                            if(isset($_SESSION['userId']) && isset($_SESSION['userGroup'])){
                                if(!empty($_POST['worksiteId'])) {
                                    $recordInfo = new Record();
                                    $recordInfo = fillBasicRecordInfos($recordInfo);
                                    $recordInfo->setUserId($_SESSION['userId']);
                                    $recordInfo->setUserGroup($_SESSION['userGroup']);

                                    $recordController->addNewRecord($recordInfo);
                                } else throw new InvalidParameterException();
                            } else throw new AuthenticationException();
                        } else throw new AuthenticationException();
                    } else throw new AuthenticationException(); 
                    break;

                // Modification d'un relevé non validé
                case "updateRecord":
                    if(!empty($_POST['csrfToken'])) {
                        if(hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                            if(isset($_SESSION['userId'])){
                                if(isset($_POST['recordId']) && is_numeric($_POST['recordId'])) {
                                    $recordInfo = new Record();
                                    $recordInfo = fillBasicRecordInfos($recordInfo);
                                    $recordInfo->setRecordId(intval(inputValidation($_POST['recordId'])));

                                    $recordController->updateRecord($recordInfo);
                                } else throw new UpdateProblemException();
                            } else throw new AuthenticationException();
                        } else throw new AuthenticationException();
                    } else throw new AuthenticationException(); 
                    break;
                    
                // Modification du statut du relevé
                case "updateRecordStatus":
                    if(!empty($_POST['csrfToken'])) {
                        if(hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                            if(isset($_SESSION['userId'])){
                                if(!empty($_POST['checkList'])){
                                    $recordController->updateRecordStatus($_POST['checkList']);
                                } else throw new InvalidParameterException('Veuillez sélectionner un ou plusieurs relevé(s) à valider.');
                            } else throw new AuthenticationException();
                        } else throw new AuthenticationException();
                    } else throw new AuthenticationException(); 
                    break;

                // Supprimer un relevé
                case "deleteRecord":
                    if(!empty($_POST['csrfToken'])) {
                        if(hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                            if(isset($_SESSION['userId'])){
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
                    } else throw new AuthenticationException(); 
                    break;


                // Renvoyer le formulaire de saisie
                case "getRecordForm":
                    if(isset($_SESSION['userId'])) {
                        if (isset($_POST['recordId'])){
                            $recordInfo = new Record();
                            $recordInfo->setRecordId(intval(inputValidation($_POST['recordId'])));
                            $recordInfo->setUserId(intval(inputValidation($_POST['userId'])));

                            $recordController->getRecordForm($recordInfo);
                        }
                    } else throw new AuthenticationException();
                    break;

                // Renvoyer le formulaire de confirmation de suppression
                case "getDeleteConfirmationForm":
                    if(isset($_SESSION['userId'])) {
                        $recordController->getDeleteConfirmationForm();
                    } else throw new AuthenticationException();
                    break;


                // Récupérer les données d'un relevé
                case "getRecordData":
                    if(isset($_SESSION['userId'])){
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
                    if(isset($_SESSION['userId'])){
                        if(isset($_POST['typeOfRecords']) && isset($_POST['scope'])) {
                            $recordInfo = new Record();
                            $recordInfo->setUserId($_SESSION['userId']);
                            $recordInfo->setTypeOfRecords(inputValidation($_POST['typeOfRecords']));
                            $recordInfo->setScope(inputValidation($_POST['scope']));
                            
                            $recordController->getUserRecords($recordInfo);
                        }
                        else throw new NoDataFoundException();
                    } else throw new AuthenticationException();
                    break;

                // Récupérer les données de l'historique équipe
                case "getTeamRecordsLog":
                    if(isset($_SESSION['userId'])){
                        if(isset($_POST['typeOfRecords']) && isset($_POST['scope']) && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) {
                            $recordInfo = new Record();
                            $recordInfo->setManagerId($_SESSION['userId']);
                            $recordInfo->setTypeOfRecords(inputValidation($_POST['typeOfRecords']));
                            $recordInfo->setScope(inputValidation($_POST['scope']));
                            
                            $recordController->getTeamRecords($recordInfo);
                        }
                        else throw new NoDataFoundException();
                    } else throw new AuthenticationException();
                    break;

                // Récupérer les données de l'historique global
                case "getAllUsersRecordsLog":
                    if(isset($_SESSION['userId'])){
                        if(isset($_POST['typeOfRecords']) && isset($_POST['scope']) && $_SESSION['userGroup'] == '1') {
                            $recordInfo = new Record();
                            $recordInfo->setTypeOfRecords(inputValidation($_POST['typeOfRecords']));
                            $recordInfo->setScope(inputValidation($_POST['scope']));

                            $recordController->getAllUsersRecords($recordInfo);
                        }
                        else throw new NoDataFoundException();
                    } else throw new AuthenticationException();
                    break;


                // Exporter les données en CSV
                case "exportRecords":
                    if(!empty($_POST['csrfToken'])) {
                        if(hash_equals($_SESSION['csrfToken'], inputValidation($_POST['csrfToken']))) {
                            if(isset($_SESSION['userId']) && $_SESSION['userGroup'] == '1') {
                                if(isset($_GET['typeOfRecords']) && $_GET['typeOfRecords'] == 'export') {
                                    if(isset($_POST['scope']) && isset($_POST['periodStart']) && isset($_POST['periodEnd']) && isset($_POST['manager']) && isset($_POST['user'])) {
                                        $recordInfo = new Record();
                                        $recordInfo->setTypeOfRecords(inputValidation($_GET['typeOfRecords']));
                                        $recordInfo->setScope(inputValidation($_POST['scope']));
                                        $recordInfo->setPeriodStart(inputValidation($_POST['periodStart']));
                                        $recordInfo->setPeriodEnd(inputValidation($_POST['periodEnd']));
                                        $recordInfo->setManagerId(intval(inputValidation($_POST['manager'])));
                                        $recordInfo->setUserId(intval(inputValidation($_POST['user'])));

                                        $recordController->exportRecords($recordInfo);
                                    }
                                } 
                            } else throw new AuthenticationException();
                        } else throw new AuthenticationException();
                    } else throw new AuthenticationException(); 
                    break;


                // Récupérer les listes des managers et des salariés pour le formulaire d'export
                case "getOptionsData":
                    if(isset($_SESSION['userId'])){
                        if(isset($_POST['typeOfData']) && isset($_POST['scope'])) {
                            if(inputValidation($_POST['scope']) === "export"){
                                $recordController->getOptionsData(inputValidation($_POST['typeOfData']));
                            }
                            if(inputValidation($_POST['scope']) === "add" && inputValidation($_POST['userId'] !== null)) {
                                $recordController->getOptionsData(inputValidation($_POST['typeOfData']), intval(inputValidation($_POST['userId'])));
                            }
                        }
                    } else throw new AuthenticationException();
                    break;
            }
        } catch (PDOException $e){
            $errorCode = $e->getCode();
            $loginController->displayLoginPage($errorCode);
        } catch (AuthenticationException $e){
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            $loginController->displayLoginPage($errorCode, $errorMessage);
        } catch (Exception $e){
            $errorMessage = $e->getMessage();
            echo "Exception : " . $errorMessage;
        } catch(Error $e){
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        $loginController->displayLoginPage();
    }
