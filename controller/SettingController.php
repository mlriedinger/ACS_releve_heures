<?php

require 'autoloader.php';

/**
 * Classe qui permet de gérer les paramètres de l'application.
 */
class SettingController {
    
    /**
     * Rend la vue paramètres.
     */
    public function displaySettingsForm() {
        require 'view/settingsForm.php';
    }
    
    /**
     * Permet de récupérer les paramètres enregistrés en base de données.
     */
    public function getSettings() {
        $settingManager = new SettingManager();
        $settings = $settingManager->getSettings();

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
        $settingManager = new SettingManager();
        $isUpdateSuccessfull = $settingManager->updateSettings($settingInfo);

        if($isUpdateSuccessfull) {
            $this->getSettings();
            $_SESSION['success'] = true;
            $this->displaySettingsForm();
        }
    }
    
    /**
     * Permet de remplir les variables de session avec les paramètres lors de la connexion à l'application.
     *
     * @param  Array $settings
     */
    public function fillSessionData(Array $settings) {
        session_start();
        $_SESSION['imgFilePath'] = $settings['chemin_dossier_images'];
        $_SESSION['logo'] = $settings['image_logo'];
        $_SESSION['dateTimeMgmt'] = $settings['releve_heures_date_debut_fin'];
        $_SESSION['lengthMgmt'] = $settings['releve_heures_duree'];
        $_SESSION['tripMgmt'] = $settings['releve_heures_trajet'];
        $_SESSION['breakMgmt'] = $settings['releve_heures_pause'];
    }
}