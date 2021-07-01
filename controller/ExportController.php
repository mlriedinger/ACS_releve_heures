<?php

require_once 'AbstractController.php';
require 'autoloader.php';

/**
 * Classe qui permet de gérer l'export de données. Classe-fille d'AbstractController pour hériter des méthodes permettant de rendre une vue.
 */
class ExportController extends AbstractController {

    /**
     * Permet d'exporter les relevés souhaités au format CSV.
     *
     * @param  Export $exportInfo
     */
    public function exportRecords(Export $exportInfo){
        $exportManager = new ExportManager();
        $exportManager->exportRecords($exportInfo);
    }
}