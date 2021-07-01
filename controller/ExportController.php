<?php

require_once 'AbstractController.php';
require 'autoloader.php';

/**
 * Classe qui permet de gérer l'export de données. Classe-fille d'AbstractController pour hériter des méthodes permettant de rendre une vue.
 */
class ExportController extends AbstractController {

    private $_loginManager;

    public function __construct() {
        $this->_loginManager = new LoginManager();
    }

    /**
     * Permet d'exporter les relevés souhaités au format CSV.
     *
     * @param  Export $exportInfo
     */
    public function exportRecords(Export $exportInfo){
        $this->_loginManager->exportRecords($exportInfo);
    }
}