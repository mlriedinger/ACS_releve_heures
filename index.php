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
            displayRecordsLog();
            break;
        // Page historique global
        case "showAllRecordsLog":
            displayRecordsLog();
            break;

        // Ajout d'un nouveau relevé
        case "addNewRecord":
            registerNewRecord();
            break;
        
        // Modification du statut du relevé
        case "updateRecordStatus":
            updateRecordStatus();
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

