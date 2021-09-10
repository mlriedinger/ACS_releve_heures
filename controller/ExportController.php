<?php

require_once 'AbstractController.php';
require 'autoloader.php';

/**
 * Classe qui permet de gérer l'export de données. Classe-fille d'AbstractController pour hériter des méthodes permettant de rendre une vue.
 */
class ExportController extends AbstractController {

    private $_exportManager;

    public function __construct() {
        $this->_exportManager = new ExportManager();
    }

    /**
     * Permet d'exporter les relevés souhaités au format CSV.
     *
     * @param  Export $exportInfo
     */
    public function exportRecords(Export $exportInfo){
        $rows = $this->_exportManager->getRecordsToExport($exportInfo);
        $fileName = $this->getFileName($exportInfo);
        $this->writeCsvFile($rows, $fileName);
    }

    /**
     * Permet de construire le nom du fichier d'export en fonction des champs sélectionnés dans le formulaire d'export.
     *
     * @param  Export $exportInfo
     * @return string $fileName
     */
    public function getFileName(Export $exportInfo){
        $status = $exportInfo->getStatus();
        $periodStart = $exportInfo->getPeriodStart();
        $periodEnd = $exportInfo->getPeriodEnd();
        $userId = $exportInfo->getUserId();

        $fileNameDetails ="_";
        $fileNameDetails .= $status . "_records";

        if($userId != "") $fileNameDetails .= "_user_" . $userId;
        if($periodStart != "") $fileNameDetails .= "_from_" . $periodStart;
        if($periodEnd != "") $fileNameDetails .= "_to_" . $periodEnd;

        $fileName = date('Ymd') . '_export_releves_heures' . $fileNameDetails . '.csv';

        return $fileName;
    }

    /**
     * Permet d'écrire un fichier CSV.
     *
     * @param  array $rows
     * @param  string $fileName
     */
    public function writeCsvFile(array $rows, string $fileName){
        $columnNames = array();

        if(!empty($rows)){
            $firstRow = $rows[0];

            foreach($firstRow as $colName => $value){
                $columnNames[] = $colName;
            }
        }

        header("Content-type: text/csv ; charset=UTF-8");
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        // On crée un pointeur de fichier dans le flux output pour envoyer le fichier directement au navigateur
        $filePointer = fopen('php://output', 'w');
        fputcsv($filePointer, $columnNames);

        foreach ($rows as $row) {
            fputcsv($filePointer, $row);
        }

        fclose($filePointer);
    }
}