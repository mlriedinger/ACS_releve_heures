<?php

/* On appelle les contrôleurs pour avoir accès à leurs méthodes */

require('controller/loginController.php');
require('controller/recordController.php');


/* Routeur de l'application qui appelle le contrôleur correspondant à l'URL demandée */

if(isset($_GET['action'])){

    // Décommenter la ligne suivante pour voir la requête qui est reçue
    // var_dump($_REQUEST);

    try {
        switch($_GET['action']){
            // Page de connexion
            case "login":
                if(isset($_POST['login']) && isset($_POST['password'])) verifyLogin($_POST['login'], $_POST['password']);
                else throw new Exception('Veuillez remplir tous les champs.');
                break;
            // Déconnexion
            case "logout":
                logout();
                break;

            // Page d'accueil
            case "showHomePage":
                if(isset($_SESSION['id'])) displayHomePage();
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;
            // Page "Nouveau Relevé"
            case "showNewRecordForm":
                if(isset($_SESSION['id'])) displayNewRecordForm();
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;
            // Page de validation
            case "showRecordsToCheck":
                if(isset($_SESSION['id']) && ($_SESSION['id_group'] == '1' || $_SESSION['id_group'] == '2')) displayValidationForm();
                else throw new Exception('Accès refusé. Veuillez contacter l\'administrateur.');
                break;
            // Page historique personnel
            case "showPersonalRecordsLog":
                if(isset($_SESSION['id'])) displayPersonalRecordsLog();
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;
            // Page historique équipe
            case "showTeamRecordsLog":
                if(isset($_SESSION['id']) && ($_SESSION['id_group'] == '1' || $_SESSION['id_group'] == '2')) displayTeamRecordsLog();
                else throw new Exception('Accès refusé. Veuillez contacter l\'administrateur.');
                break;
            // Page historique global
            case "showAllRecordsLog":
                if(isset($_SESSION['id']) && $_SESSION['id_group'] == '1') displayAllRecordsLog();
                else throw new Exception('Accès refusé. Veuillez contacter l\'administrateur.');
                break;

            // Ajout d'un nouveau relevé
            case "addNewRecord":
                if(isset($_SESSION['id']) && isset($_SESSION['id_group'])){
                    if(!empty($_POST['datetime_start']) && !empty($_POST['datetime_end'])) addNewRecord($_SESSION['id'], $_POST['datetime_start'], $_POST['datetime_end'], $_POST['comment'], $_SESSION['id_group']);
                    else throw new Exception('Les champs dates doivent être obligatoirement remplis.');
                } 
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;
            // Modification d'un relevé non validé
            case "updateRecord":
                if(isset($_SESSION['id'])){
                    if(isset($_POST['record_id']) && !empty($_POST['datetime_start']) && !empty($_POST['datetime_end'])) updateRecord($_POST['record_id'], $_POST['datetime_start'], $_POST['datetime_end'], $_POST['comment']);
                    else throw new Exception('Un problème est survenu. La modification n\'a pas pu être effectuée.');
                } 
                else throw new Exception('Utilisateur non authentifié. Veuillez vous connecter.');
                break;
            // Modification du statut du relevé
            case "updateRecordStatus":
                updateRecordStatus();
                break;
            // Supprimer un relevé
            case "deleteRecord":
                deleteRecord();
                break;

            // Renvoyer le formulaire de saisie
            case "getRecordForm":
                getRecordForm();
                break;
            // Renvoyer le formulaire de confirmation de suppression
            case "getDeleteConfirmationForm":
                getDeleteConfirmationForm();
                break;

            // Récupérer les données d'un relevé
            case "getRecordData":
                getRecordData($_POST['recordID']);
                break;
            // Récupérer les données de l'historique personnel
            case "getPersonalRecordsLog":
                getUserRecords($_POST['typeOfRecords']);
                break;
            // Récupérer les relevés en attente de validation
            case "getRecordsToCheck":
                getTeamRecordsToCheck($_POST['typeOfRecords']);
                break;
            // Récupérer les données de l'historique équipe
            case "getTeamRecordsLog":
                getTeamRecords($_POST['typeOfRecords']);
                break;
            // Récupérer les données de l'historique global
            case "getAllUsersRecordsLog":
                getAllUsersRecords($_POST['typeOfRecords']);
                break;
        }
    }catch (Exception $e){
        $errorMessage = $e->getMessage();
        echo $errorMessage;
    }
} else {
    displayLoginPage();
}
