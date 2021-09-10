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
        $columnNames = $this->getColumnNames($rows);
        array_unshift($rows, $columnNames);

        $fileName = $this->getFileName($exportInfo);
        $this->writeXlsxFile($rows, $fileName);
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
        $userUUID = $exportInfo->getUserUUID();

        $fileNameDetails ="_";
        $fileNameDetails .= $status . "_records";

        if($userUUID != "") $fileNameDetails .= "_user_" . $userUUID;
        if($periodStart != "") $fileNameDetails .= "_from_" . $periodStart;
        if($periodEnd != "") $fileNameDetails .= "_to_" . $periodEnd;

        $fileName = date('Ymd') . '_export_releves_heures' . $fileNameDetails . '.xlsx';

        return $fileName;
    }

    public function getColumnNames($array) {
        $columnNames = array();

        if(!empty($array)){
            $firstRow = $array[0];

            foreach($firstRow as $columnName => $value){
                $columnNames[] = $columnName;
            }
        }

        return $columnNames;
    }

    /**
     * Permet d'écrire un fichier CSV.
     *
     * @param  array $rows
     * @param  string $fileName
     */
    public function writeCsvFile(array $rows, string $fileName){
        header("Content-type: text/csv ; charset=UTF-8");
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        // On crée un pointeur de fichier dans le flux output pour envoyer le fichier directement au navigateur
        $filePointer = fopen('php://output', 'w');

        foreach ($rows as $row) {
            fputcsv($filePointer, $row);
        }

        fclose($filePointer);
    }

    public function writeXlsxFile(array $rows, string $fileName) {
        $xlsxFile = SimpleXLSXGen::fromArray($rows);
        $xlsxFile->downloadAs($fileName);
    }
}