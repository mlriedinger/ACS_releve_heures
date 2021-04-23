<?php

require 'autoloader.php';

class SettingController {

    public function displaySettingsForm() {
        require('view/settingsForm.php');
    }

    public function getSettings() {
        $settingManager = new SettingManager();
        $settings = $settingManager->getSettings();

        if (!empty($settings)) {
            $this->fillSessionData($settings);
        }
    }

    public function updateSettings($settingInfo) {
        $settingManager = new SettingManager();
        $isUpdateSuccessfull = $settingManager->updateSettings($settingInfo);

        if($isUpdateSuccessfull) {
            $this->getSettings();
            $_SESSION['success'] = true;
            $this->displaySettingsForm();
        }
    }

    public function fillSessionData($settings) {
        session_start();
        $_SESSION['logo'] = $settings['image_logo'];
        $_SESSION['dateTimeMgmt'] = $settings['releve_heures_date_debut_fin'];
        $_SESSION['lengthMgmt'] = $settings['releve_heures_duree'];
        $_SESSION['tripMgmt'] = $settings['releve_heures_trajet'];
        $_SESSION['breakMgmt'] = $settings['releve_heures_pause'];
    }
}