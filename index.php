<?php
/* On appelle le contrôleur pour avoir accès à ses méthodes */
require('controller/loginController.php');
require('controller/recordController.php');

if(isset($_GET['action'])){
    if($_GET['action'] == 'login') displayHomePage();
    if($_GET['action'] == 'getNewRecordForm') displayNewRecordForm();
    if($_GET['action'] == 'addNewRecord') registerNewRecord();
    if($_GET['action'] == 'showRecordsLog') displayRecordsLog();
    if($_GET['action'] == 'getRecordsLog') getRecords();
} else {
    displayHomePage();
}

