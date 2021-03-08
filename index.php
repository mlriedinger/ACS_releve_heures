<?php

/* On appelle les contrôleurs pour avoir accès à leurs méthodes */

require('controller/loginController.php');
require('controller/recordController.php');


/* Routeur de l'application qui appelle le contrôleur correspondant à l'URL demandée */
 
if(isset($_GET['action'])){

    // Décommenter la ligne suivante pour voir la requête qui est reçue
    // var_dump($_REQUEST);

    switch($_GET['action']){
        // Page de connexion
        case "login":
            verifyLogin();
            break;
        // Déconnexion
        case "logout":
            logout();
            break;

        // Page d'accueil
        case "showHomePage":
            displayHomePage();
            break;
        // Page "Nouveau Relevé"
        case "showNewRecordForm":
            displayNewRecordForm();
            break;
        // Page de validation
        case "showRecordsToCheck":
            displayValidationForm();
            break;
        // Page historique personnel
        case "showPersonnalRecordsLog":
            displayPersonnalRecordsLog();
            break;
        // Page historique équipe
        case "showTeamRecordsLog":
            displayTeamRecordsLog();
            break;
        // Page historique global
        case "showAllRecordsLog":
            displayAllRecordsLog();
            break;

        // Ajout d'un nouveau relevé
        case "addNewRecord":
            registerNewRecord();
            break;
        // Modification d'un relevé non validé
        case "updateRecord":
            updateRecord();
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
        case "getPersonnalRecordsLog":
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

} else {
    verifyLogin();
}

