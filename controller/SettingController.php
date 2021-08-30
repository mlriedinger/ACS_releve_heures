<?php

require_once 'AbstractController.php';
require 'autoloader.php';

/**
 * Classe qui permet de gérer les paramètres de l'application.
 */
class SettingController extends AbstractController {

    private $_settingManager;

    public function __construct() {
        $this->_settingManager = new SettingManager();
    }
    
    /**
     * Permet de récupérer les paramètres enregistrés en base de données.
     */
    public function getSettings() {
        $settings = $this->_settingManager->getSettings();

        if (!empty($settings)) {
            $this->fillSessionData($settings);
        }
    }
    
    /**
     * Permet de mettre à jour les paramètres : en base de données, puis en variables de session.
     * Enregistre un booléen en variable de session pour déclencher l'affichage d'une notification à l'utilisateur en cas de succès ou d'erreur.
     *
     * @param  Setting $settingInfo
     */
    public function updateSettings(Setting $settingInfo) {
        $isUpdateSuccessfull = $this->_settingManager->updateSettings($settingInfo);

        if($isUpdateSuccessfull) {
            $this->getSettings();
            $_SESSION['success'] = true;
            $this->displayView('settingsForm');
        }
    }
    
    /**
     * Permet de remplir les variables de session avec les paramètres lors de la connexion à l'application.
     *
     * @param  array $settings
     */
    public function fillSessionData(array $settings) {
        session_start();
        $_SESSION['imgFilePath'] = $settings['chemin_dossier_images'];
        $_SESSION['logo'] = $settings['image_logo'];
        $_SESSION['dateTimeMgmt'] = $settings['releve_heures_date_debut_fin'];
        $_SESSION['lengthMgmt'] = $settings['releve_heures_duree'];
        $_SESSION['lengthByCategoryMgmt'] = $settings['releve_heures_duree_categorie'];
        $_SESSION['tripMgmt'] = $settings['releve_heures_trajet'];
        $_SESSION['breakMgmt'] = $settings['releve_heures_pause'];
        $_SESSION['specificInfoMgmt'] = $settings['releve_heures_info_specifique'];
    }
}