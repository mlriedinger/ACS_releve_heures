<?php

/* On appelle la classe qui gère la connexion à la BDD */
require_once 'DatabaseConnection.php';

/* Classe qui gère l'envoi et la récupération de données de la BDD 
    * [INFO] Classe-fille de DatabaseConnection pour pouvoir hériter de la méthode dbConnect()
*/

class SettingManager extends DatabaseConnection 
{
    private $_dbUserForSettings;
    private $_dbPasswordForSettings;

    public function __construct() {
        parent::__construct();
        $this->_dbUserForSettings = $this->_config['dbUserForSettings'];
        $this->_dbPasswordForSettings = $this->_config['dbPasswordForSettings'];
    }

    public function getSettings() {
        $pdo = $this->dbConnect($this->_dbUserForSettings, $this->_dbPasswordForSettings);

        $query = $pdo->prepare('SELECT 
            chemin_dossier_images,
            image_logo, 
            releve_heures_date_debut_fin, 
            releve_heures_duree, 
            releve_heures_trajet, 
            releve_heures_pause 
        FROM t_parametres 
        WHERE ID = :id');
        $query->execute(array('id' => 2));
        $settings = $query->fetch(PDO::FETCH_ASSOC);

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();
 
        return $settings;
    }

    public function updateSettings(Setting $settingInfo) {
        $dateTimeMgmt = $settingInfo->getDateTimeMgmt();
        $timeLengthMgmt = $settingInfo->getLengthMgmt();
        $tripLengthMgmt = $settingInfo->getTripMgmt();
        $breakLengthMgmt = $settingInfo->getBreakMgmt();

        $pdo = $this->dbConnect();

        $query = $pdo->prepare('UPDATE t_parametres
        SET 
            releve_heures_date_debut_fin = :dateTimeMgmt,
            releve_heures_duree = :timeLengthMgmt, 
            releve_heures_trajet = :tripLengthMgmt, 
            releve_heures_pause = :breakLengthMgmt
        WHERE ID = :id');
        $updateAttempt = $query->execute(array(
            'dateTimeMgmt' => $dateTimeMgmt,
            'timeLengthMgmt' => $timeLengthMgmt,
            'tripLengthMgmt' => $tripLengthMgmt,
            'breakLengthMgmt' => $breakLengthMgmt,
            'id' => 2));

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        return $updateAttempt;
    }
}