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
    if(!empty($_POST['worksiteUUID']) ){
        $recordInfo->setWorksiteUUID(inputValidation($_POST['worksiteUUID']));
    }

    if(isset($_POST['comment'])) {
        $recordInfo->setComment(inputValidation($_POST['comment']));
    }

    // Si le paramètre "Mode de saisie des relevés" est "date et heure de début/fin"
    if($_SESSION['dateTimeMgmt'] == '1' && !empty($_POST['datetimeStart']) && !empty($_POST['datetimeEnd'])) {
        fillWorkByDateTimeInfos($recordInfo);
    }
    // Sinon, si le paramètre "Mode de saisie des relevés" est "durée" 
    else if(($_SESSION['lengthMgmt'] == '1' || $_SESSION['lengthByCategoryMgmt'] == 1)  && !empty($_POST['recordDate']) && (!empty($_POST['workLengthHours']) || !empty($_POST['workLengthMinutes']))) {
        fillWorkByLengthInfos($recordInfo);
    }

    // Si le paramètre "gestion du temps de pause" est activé
    if($_SESSION['breakMgmt'] == '1' && (!empty($_POST['breakLengthHours']) || !empty($_POST['breakLengthMinutes']))) {
        fillBreakInfos($recordInfo);
    }

    // Si le paramètre "gestion du temps de trajet" est activé
    if($_SESSION['tripMgmt'] == '1' && (!empty($_POST['tripLengthHours']) || !empty($_POST['tripLengthMinutes']))) {
        fillTripInfos($recordInfo);
    }

    return $recordInfo;
}

/**
 * Permet d'ajouter un temps de travail sous forme de dates et heures de début/fin.  
 *
 * @param  Record $recordInfo : un objet de type Record
 * @return Record $recordInfo : l'objet Record rempli
 */
function fillWorkByDateTimeInfos(Record $recordInfo) {
    $recordInfo->setDateTimeStart(inputValidation($_POST['datetimeStart']));
    $recordInfo->setDateTimeEnd(inputValidation($_POST['datetimeEnd']));

    return $recordInfo;
}

/**
 * Permet d'ajouter un temps de travail sous forme de durée.
 *
 * @param  Record $recordInfo : un objet de type Record
 * @return Record $recordInfo : l'objet Record rempli
 */
function fillWorkByLengthInfos($recordInfo) {
    $recordInfo->setDate(inputValidation($_POST['recordDate']));
    $workHours = intval(inputValidation($_POST['workLengthHours']));
    $workMinutes = intval(inputValidation($_POST['workLengthMinutes']));
    $totalWorkLength = convertLengthIntoMinutes($workHours, $workMinutes);

    if($totalWorkLength > 0) {
        if($_SESSION['lengthByCategoryMgmt'] == 1){
            fillWorkstationsArray($recordInfo);
        }
        $recordInfo->setWorkLength($totalWorkLength);
    } else {
        throw new InvalidParameterException();
    } 
}

/**
 * Permet d'ajouter un poste de travail.
 *
 * @param  Record $recordInfo : un objet de type Record
 * @return Record $recordInfo : l'objet Record rempli
 */
function fillWorkstationsArray($recordInfo) {
    foreach(array_keys($_POST['workstationLengthHours']) as $workstationId){
        $hours = intval($_POST['workstationLengthHours'][$workstationId]);
        $minutes = intval($_POST['workstationLengthMinutes'][$workstationId]);
        $length = convertLengthIntoMinutes($hours, $minutes);

        $workstation = new Workstation($workstationId, $length);
        
        $recordInfo->addWorkstation($workstation);
    }
}

/**
 * Permet d'ajouter un temps de pause sous forme de durée.
 *
 * @param  Record $recordInfo : un objet de type Record
 * @return Record $recordInfo : l'objet Record rempli
 */
function fillBreakInfos(Record $recordInfo) {
    $breakHours = intval(inputValidation($_POST['breakLengthHours']));
    $breakMinutes = intval(inputValidation($_POST['breakLengthMinutes']));
    $breakLength = convertLengthIntoMinutes($breakHours, $breakMinutes);
    $recordInfo->setBreakLength($breakLength);

    return $recordInfo;
}

/**
 * Permet d'ajouter un temps de trajet sous forme de durée.
 *
 * @param  Record $recordInfo : un objet de type Record
 * @return Record $recordInfo : l'objet Record rempli
 */
function fillTripInfos(Record $recordInfo) {
    $tripHours = intval(inputValidation($_POST['tripLengthHours']));
    $tripMinutes = intval(inputValidation($_POST['tripLengthMinutes']));
    $tripLength = convertLengthIntoMinutes($tripHours, $tripMinutes);
    $recordInfo->setTripLength($tripLength);

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
    $settingInfo->setLengthByCategoryMgmt(intval(inputValidation($_POST['lengthByCategoryMgmtSwitch'])));

    return $settingInfo;
}