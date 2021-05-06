<?php

/**
 * Permet d'assainir les données reçues de l'utilisateur.
 *
 * @param  mixed $data : donnée issue d'un input de formulaire
 * @return mixed $data : donnée assainie
 */
function inputValidation($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}


/**
 * Permet de convertir une durée exprimée en heures/minutes uniquement en minutes.
 *
 * @param  int $hours : un nombre d'heures
 * @param  int $minutes : un nombre de minutes
 * @return int $lengthInMinutes : la durée exprimée en minutes
 */
function convertLengthIntoMinutes(int $hours, int $minutes) {
    $lengthInMinutes = $hours * 60 + $minutes;
    return $lengthInMinutes;
}


/**
 * Permet d'encapsuler les données nécessaires à l'ajout ou à la modification d'un relevé d'heure.
 *
 * @param  Record $recordInfo : un objet de type Record vide
 * @return Record $recordInfo : l'objet Record rempli
 */
function fillBasicRecordInfos(Record $recordInfo) {
    if(!empty($_POST['worksiteId']) ){
        $recordInfo->setWorksite(intval(inputValidation($_POST['worksiteId'])));
    }

    if(isset($_POST['comment'])) {
        $recordInfo->setComment(inputValidation($_POST['comment']));
    }

    // Si le paramètre "Mode de saisie des relevés" est "date et heure de début/fin"
    if($_SESSION['dateTimeMgmt'] == '1' && !empty($_POST['datetimeStart']) && !empty($_POST['datetimeEnd'])) {
        $recordInfo->setDateTimeStart(inputValidation($_POST['datetimeStart']));
        $recordInfo->setDateTimeEnd(inputValidation($_POST['datetimeEnd']));
    }
    // Sinon, si le paramètre "Mode de saisie des relevés" est "durée"
    else if($_SESSION['lengthMgmt'] == '1' && !empty($_POST['recordDate']) && (!empty($_POST['workLengthHours']) || !empty($_POST['workLengthMinutes']))) {
        $recordInfo->setDate(inputValidation($_POST['recordDate']));
        $workHours = intval(inputValidation($_POST['workLengthHours']));
        $workMinutes = intval(inputValidation($_POST['workLengthMinutes']));
        $workLength = convertLengthIntoMinutes($workHours, $workMinutes);

        if($workLength > 0) {
            $recordInfo->setWorkLength($workLength);
        }
        else {
            throw new InvalidParameterException();
        } 
    }

    // Si le paramètre "gestion du temps de pause" est activé
    if($_SESSION['breakMgmt'] == '1' && (!empty($_POST['breakLengthHours']) || !empty($_POST['breakLengthMinutes']))) {
        $breakHours = intval(inputValidation($_POST['breakLengthHours']));
        $breakMinutes = intval(inputValidation($_POST['breakLengthMinutes']));
        $breakLength = convertLengthIntoMinutes($breakHours, $breakMinutes);
        $recordInfo->setBreakLength($breakLength);
    }

    // Si le paramètre "gestion du temps de trajet" est activé
    if($_SESSION['tripMgmt'] == '1' && (!empty($_POST['tripLengthHours']) || !empty($_POST['tripLengthMinutes']))) {
        $tripHours = intval(inputValidation($_POST['tripLengthHours']));
        $tripMinutes = intval(inputValidation($_POST['tripLengthMinutes']));
        $tripLength = convertLengthIntoMinutes($tripHours, $tripMinutes);
        $recordInfo->setTripLength($tripLength);
    }
    
    return $recordInfo;
}

/**
 * Permet d'encapsuler les données nécessaires à la modification des paramètres de l'application.
 *
 * @param  Setting $settingInfo : un objet Setting vide
 * @return Setting $settingInfo : l'objet Setting rempli
 */
function fillSettingInfos(Setting $settingInfo) {
    $settingInfo->setDateTimeMgmt(intval(inputValidation($_POST['dateTimeMgmtSwitch'])));
    $settingInfo->setLengthMgmt(intval(inputValidation($_POST['lengthMgmtSwitch'])));
    $settingInfo->setTripMgmt(intval(inputValidation($_POST['tripMgmtSwitch'])));
    $settingInfo->setBreakMgmt(intval(inputValidation($_POST['breakMgmtSwitch'])));

    return $settingInfo;
}