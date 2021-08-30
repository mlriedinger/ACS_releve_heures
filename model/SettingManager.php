<?php

require_once 'DatabaseConnection.php';

/**
 * Classe qui permet de gérer la modification et la récupération de paramètres de l'application.
 * Hérite de DatabseConnection pour pouvoir utiliser la méthode dbConnect().
 */
class SettingManager extends DatabaseConnection {

    private $_dbUserForSettings;
    private $_dbPasswordForSettings;

    public function __construct() {
        parent::__construct();
        $this->_dbUserForSettings = $this->_config['dbUserForSettings'];
        $this->_dbPasswordForSettings = $this->_config['dbPasswordForSettings'];
    }
    
    /**
     * Ouvre une connexion à la base de données avec l'utilisateur ayant uniquement un droit de lecture sur la table paramètres.
     * Récupère les paramètres existants.
     *
     * @return array $settings
     */
    public function getSettings() {
        $pdo = $this->dbConnect($this->_dbUserForSettings, $this->_dbPasswordForSettings);
        
        $query = $pdo->prepare('SELECT 
            chemin_dossier_images,
            image_logo, 
            releve_heures_date_debut_fin, 
            releve_heures_duree,
            releve_heures_duree_categorie,
            releve_heures_trajet, 
            releve_heures_pause,
            releve_heures_info_specifique
        FROM t_parametres 
        WHERE ID = :id');
        $query->execute(array('id' => 2));
        $settings = $query->fetch(PDO::FETCH_ASSOC);
 
        return $settings;
    }
    
    /**
     * Permet de mettre à jour les paramètres de l'application.
     * ATTENTION ! L'ID de la société est écrit en dur dans la requête !
     *
     * @param  Setting $settingInfo
     * @return bool $updateAttempt
     */
    public function updateSettings(Setting $settingInfo) {
        $dateTimeMgmt = $settingInfo->getDateTimeMgmt();
        $timeLengthMgmt = $settingInfo->getLengthMgmt();
        $timeLengthByCategoryMgmt = $settingInfo->getLengthByCategoryMgmt();
        $tripLengthMgmt = $settingInfo->getTripMgmt();
        $breakLengthMgmt = $settingInfo->getBreakMgmt();

        $pdo = $this->dbConnect();

        $query = $pdo->prepare('UPDATE t_parametres
        SET 
            releve_heures_date_debut_fin = :dateTimeMgmt,
            releve_heures_duree = :timeLengthMgmt,
            releve_heures_duree_categorie = :timeLengthByCategoryMgmt,
            releve_heures_trajet = :tripLengthMgmt, 
            releve_heures_pause = :breakLengthMgmt
        WHERE ID = :id');
        $updateAttempt = $query->execute(array(
            'dateTimeMgmt' => $dateTimeMgmt,
            'timeLengthMgmt' => $timeLengthMgmt,
            'timeLengthByCategoryMgmt' => $timeLengthByCategoryMgmt,
            'tripLengthMgmt' => $tripLengthMgmt,
            'breakLengthMgmt' => $breakLengthMgmt,
            'id' => 2));

        return $updateAttempt;
    }
}