<?php

/* On appelle les contrôleurs pour avoir accès à leurs méthodes */

require('controller/loginController.php');
require('controller/recordController.php');


/* Routeur de l'application qui appelle le contrôleur correspondant à l'URL demandée */
 
if(isset($_GET['action'])){
    // if($_GET['action'] == 'login') displayHomePage();
    // if($_GET['action'] == 'getNewRecordForm') displayNewRecordForm();
    // if($_GET['action'] == 'addNewRecord') registerNewRecord();
    // if($_GET['action'] == 'showPersonnalRecordsLog') displayPersonnalRecordsLog();
    // if($_GET['action'] == 'getRecordsLog') getRecords();

    switch($_GET['action']){
        case "login":
            displayHomePage();
            break;
        case "getNewRecordForm":
            displayNewRecordForm();
            break;
        case "addNewRecord":
            registerNewRecord();
            break;
        case "showPersonnalRecordsLog":
            displayPersonnalRecordsLog();
            break;
        case "getPersonnalRecordsLog":
            getUserRecords();
            break;
    }

} else {
    displayHomePage();
}

