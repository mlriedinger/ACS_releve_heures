<?php
session_start();
require_once('model/RecordManager.php');

function displayNewRecordForm(){
    require('view/newRecordForm.php');
}

function registerNewRecord(){
    $recordManager = new recordManager();
    $isSendingSuccessfull = $recordManager->sendNewRecord($_POST['user_id'], $_POST['datetime_start'], $_POST['datetime_end'], $_POST['comment']);
    
    if($isSendingSuccessfull) require('view/recordsLog.php');
    else require('view/newRecordform.php');
    $isSendingSuccessfull = false;
}

function displayRecordsLog(){
    require('view/recordsLog.php');
}

function getRecords(){
    $recordManager = new recordManager();
    $recordManager->getAllRecords($_SESSION['id']);
}