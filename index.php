<?php

/* On appelle les contrôleurs pour avoir accès à leurs méthodes */

require('controller/loginController.php');
require('controller/recordController.php');
require('model/Record.php');


/* Routeur de l'application qui appelle le contrôleur correspondant à l'URL demandée */

if(isset($_GET['action'])) {

    // Décommenter la ligne suivante pour voir la requête qui est reçue
    // var_dump($_REQUEST);

    try {
        switch(htmlspecialchars($_GET['action'])) {

            // Connexion
            case "login":
                if(isset($_POST['login']) && isset($_POST['password'])) verifyLogin(htmlspecialchars($_POST['login']), htmlspecialchars($_POST['password']));
                else throw new Exception('Veuillez remplir tous les champs.');
                break;

            // Déconnexion
            case "logout":
                logout();
                break;


            // Page d'accueil
            case "showHomePage":
                if(isset($_SESSION['userId'])) displayHomePage();
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;

            // Page "Nouveau Relevé"
            case "showNewRecordForm":
                if(isset($_SESSION['userId'])) displayNewRecordForm();
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;

            // Page de validation
            case "showRecordsToCheck":
                if(isset($_SESSION['userId']) && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) displayValidationForm();
                else throw new Exception('Accès refusé. Veuillez contacter l\'administrateur.');
                break;

            // Page historique personnel
            case "showPersonalRecordsLog":
                if(isset($_SESSION['userId'])) displayPersonalRecordsLog();
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;

            // Page historique équipe
            case "showTeamRecordsLog":
                if(isset($_SESSION['userId']) && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) displayTeamRecordsLog();
                else throw new Exception('Accès refusé. Veuillez contacter l\'administrateur.');
                break;

            // Page historique global
            case "showAllRecordsLog":
                if(isset($_SESSION['userId']) && $_SESSION['userGroup'] == '1') displayAllRecordsLog();
                else throw new Exception('Accès refusé. Veuillez contacter l\'administrateur.');
                break;

            // Page export
            case "showExportForm":
                if(isset($_SESSION['userId']) && $_SESSION['userGroup'] == '1') displayExportForm();
                else throw new Exception('Accès refusé. Veuillez contacter l\'administrateur.');
                break;


            // Ajout d'un nouveau relevé
            case "addNewRecord":
                if(isset($_SESSION['userId']) && isset($_SESSION['userGroup'])){
                    if(!empty($_POST['worksiteId']) && !empty($_POST['datetimeStart']) && !empty($_POST['datetimeEnd'])) {
                        $recordInfo = new Record();
                        $recordInfo->setUserId($_SESSION['userId']);
                        $recordInfo->setUserGroup($_SESSION['userGroup']);
                        $recordInfo->setWorksite(intval(htmlspecialchars($_POST['worksiteId'])));
                        $recordInfo->setDateTimeStart(htmlspecialchars($_POST['datetimeStart']));
                        $recordInfo->setDateTimeEnd(htmlspecialchars($_POST['datetimeEnd']));
                        $recordInfo->setComment(htmlspecialchars($_POST['comment']));

                        addNewRecord($recordInfo);
                    }
                    else throw new Exception('Les rubriques "Chantier", "Début" et "Fin" sont obligatoires.');
                } 
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;

            // Modification d'un relevé non validé
            case "updateRecord":
                if(isset($_SESSION['userId'])){
                    if(isset($_POST['recordId']) && is_numeric($_POST['recordId']) && !empty($_POST['datetimeStart']) && !empty($_POST['datetimeEnd'])) {
                        $recordInfo = new Record();
                        $recordInfo->setWorksite(intval(htmlspecialchars($_POST['worksiteId'])));
                        $recordInfo->setRecordId(intval(htmlspecialchars($_POST['recordId'])));
                        $recordInfo->setDateTimeStart(htmlspecialchars($_POST['datetimeStart']));
                        $recordInfo->setDateTimeEnd(htmlspecialchars($_POST['datetimeEnd']));
                        $recordInfo->setComment(htmlspecialchars($_POST['comment']));

                        updateRecord($recordInfo);
                    }
                    else throw new Exception('Un problème est survenu. La modification n\'a pas pu être effectuée.');
                } 
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;
                
            // Modification du statut du relevé
            case "updateRecordStatus":
                if(isset($_SESSION['userId'])){
                    if(!empty($_POST['checkList'])){
                        updateRecordStatus($_POST['checkList']);
                    } 
                    else throw new Exception('Veuillez sélectionner un ou plusieurs relevé(s) à valider.');
                } 
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;

            // Supprimer un relevé
            case "deleteRecord":
                if(isset($_SESSION['userId'])){
                    if($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2'){
                        if(isset($_POST['recordId']) && is_numeric($_POST['recordId']) && !empty($_POST['comment'])) {
                            $recordInfo = new Record();

                            $recordInfo->setRecordId(intval(htmlspecialchars($_POST['recordId'])));
                            $recordInfo->setComment(htmlspecialchars($_POST['comment']));

                            deleteRecord($recordInfo);
                        }
                        else throw new Exception('Un problème est survenu. La modification n\'a pas pu être effectuée. NB : Le champ "commentaire" est obligatoire.');
                    } else {
                        if(isset($_POST['recordId']) && is_numeric($_POST['recordId'])) {
                            $recordInfo = new Record();

                            $recordInfo->setRecordId(intval(htmlspecialchars($_POST['recordId'])));
                            $recordInfo->setComment(htmlspecialchars($_POST['comment']));

                            deleteRecord($recordInfo);
                        }
                        else throw new Exception('Un problème est survenu. La modification n\'a pas pu être effectuée.');
                    }
                }
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;


            // Renvoyer le formulaire de saisie
            case "getRecordForm":
                if(isset($_SESSION['userId'])) {
                    if (isset($_POST['recordId'])){
                        $recordInfo = new Record();
                        $recordInfo->setRecordId(intval(htmlspecialchars($_POST['recordId'])));
                        $recordInfo->setUserId(intval(htmlspecialchars($_POST['userId'])));

                        getRecordForm($recordInfo);
                    }
                }
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;

            // Renvoyer le formulaire de confirmation de suppression
            case "getDeleteConfirmationForm":
                if(isset($_SESSION['userId'])) getDeleteConfirmationForm();
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;


            // Récupérer les données d'un relevé
            case "getRecordData":
                if(isset($_SESSION['userId'])){
                    if(isset($_POST['recordId']) /*&& is_numeric($_POST['recordId'])*/){
                        $recordInfo = new Record();
                        $recordInfo->setRecordId(intval(htmlspecialchars($_POST['recordId'])));
                        getRecordData($recordInfo);
                    } 
                    else throw new Exception('Un problème est survenu.');
                }
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;

            // Récupérer les données de l'historique personnel
            case "getPersonalRecordsLog":
                if(isset($_SESSION['userId'])){
                    if(isset($_POST['typeOfRecords']) && isset($_POST['scope'])) {
                        $recordInfo = new Record();
                        $recordInfo->setUserId($_SESSION['userId']);
                        $recordInfo->setTypeOfRecords(htmlspecialchars($_POST['typeOfRecords']));
                        $recordInfo->setScope(htmlspecialchars($_POST['scope']));
                        
                        getUserRecords($recordInfo);
                    }
                    else throw new Exception('Un problème est survenu.');
                }
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;

            // Récupérer les données de l'historique équipe
            case "getTeamRecordsLog":
                if(isset($_SESSION['userId'])){
                    if(isset($_POST['typeOfRecords']) && isset($_POST['scope']) && ($_SESSION['userGroup'] == '1' || $_SESSION['userGroup'] == '2')) {
                        $recordInfo = new Record();
                        $recordInfo->setManagerId($_SESSION['userId']);
                        $recordInfo->setTypeOfRecords(htmlspecialchars($_POST['typeOfRecords']));
                        $recordInfo->setScope(htmlspecialchars($_POST['scope']));
                        
                        getTeamRecords($recordInfo);
                    }
                    else throw new Exception('Un problème est survenu.');
                }
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;

            // Récupérer les données de l'historique global
            case "getAllUsersRecordsLog":
                if(isset($_SESSION['userId'])){
                    if(isset($_POST['typeOfRecords']) && isset($_POST['scope']) && $_SESSION['userGroup'] == '1') {
                        $recordInfo = new Record();
                        $recordInfo->setTypeOfRecords(htmlspecialchars($_POST['typeOfRecords']));
                        $recordInfo->setScope(htmlspecialchars($_POST['scope']));
                        getAllUsersRecords($recordInfo);
                    }
                    else throw new Exception('Un problème est survenu.');
                }
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;


            // Exporter les données en CSV
            case "exportRecords":
                if(isset($_GET['typeOfRecords']) && $_GET['typeOfRecords'] == 'export'){
                    if(isset($_SESSION['userId']) && $_SESSION['userGroup'] == '1') {
                        if(isset($_POST['scope']) && isset($_POST['periodStart']) && isset($_POST['periodEnd']) && isset($_POST['manager']) && isset($_POST['user'])) {
                            $recordInfo = new Record();
                            $recordInfo->setTypeOfRecords(htmlspecialchars($_GET['typeOfRecords']));
                            $recordInfo->setScope(htmlspecialchars($_POST['scope']));
                            $recordInfo->setPeriodStart(htmlspecialchars($_POST['periodStart']));
                            $recordInfo->setPeriodEnd(htmlspecialchars($_POST['periodEnd']));
                            $recordInfo->setManagerId(intval(htmlspecialchars($_POST['manager'])));
                            $recordInfo->setUserId(intval(htmlspecialchars($_POST['user'])));

                            exportRecords($recordInfo);
                         }
                    }
                }
                break;


            // Récupérer les listes des managers et des salariés pour le formulaire d'export
            case "getOptionsData":
                if(isset($_SESSION['userId'])){
                    if(isset($_POST['typeOfData']) && isset($_POST['scope'])) {
                        if(htmlspecialchars($_POST['scope']) === "export"){
                            getOptionsData(htmlspecialchars($_POST['typeOfData']));
                        }
                        if(htmlspecialchars($_POST['scope']) === "add" && htmlspecialchars($_POST['userId'] !== null)) {
                            getOptionsData(htmlspecialchars($_POST['typeOfData']), htmlspecialchars($_POST['userId']));
                        }
                    }
                }
                break;
        }
    } catch (PDOException $e){
        $error = true;
        displayLoginPage($error);
       
    } catch (Exception $e){
        $errorMessage = $e->getMessage();
        echo "Exception : " . $errorMessage;
    } catch(Error $e){
        echo "Erreur : " . $e->getMessage();
    }
} else {
    displayLoginPage();
}
