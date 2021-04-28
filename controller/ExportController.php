<?php

require 'autoloader.php';

class ExportController {

    /**
     * Rend la vue d'export de données
     */
    public function displayExportForm(){
        require 'view/exportRecordsForm.php';
    }

    /**
     * Permet d'exporter les relevés souhaités au format CSV
     *
     * @param  Export $exportInfo
     */
    public function exportRecords(Export $exportInfo){
        $exportManager = new ExportManager();
        $exportManager->exportRecords($exportInfo);
    }
}