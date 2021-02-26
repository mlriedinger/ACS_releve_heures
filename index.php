<?php
/* On appelle le contrôleur pour avoir accès à ses méthodes */
require('controller/loginController.php');
require('controller/newRecordFormController.php');

if(isset($_GET['action'])){
    if($_GET['action'] == 'login') displayHomePage();
    else if($_GET['action'] == 'getNewRecordForm') displayNewRecordForm();
} else {
    displayHomePage();
}

