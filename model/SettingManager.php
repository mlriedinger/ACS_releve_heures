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
            image_logo, 
            releve_heures_date_debut_fin, 
            releve_heures_duree, 
            releve_heures_trajet, 
            releve_heures_pause 
        FROM t_parametres 
        WHERE ID = :id');
        $query->execute(array('id' => 2));
        $settings = $query->fetch(PDO::FETCH_ASSOC);
 
        return $settings;
    }

    public function updateSettings() {
    }
}