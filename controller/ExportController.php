<?php

require_once 'AbstractController.php';
require 'autoloader.php';

/**
 * Classe qui permet de gérer l'export de données. Classe-fille d'AbstractController pour hériter des méthodes permettant de rendre une vue.
 */
class ExportController extends AbstractController {

    /**
     * Permet d'exporter des données selon les options sélectionnées dans le formulaire d'export.
     * 3 étapes : 
     * - récupérer le contenu à exporter, c'est-à-dire la liste des relevés
     * - écrire un nom de fichier pertinent en fonction des données exportées
     * - écrire le fichier CSV
     *
     * @param  Export $exportInfo
     */
    public function exportRecords(Export $exportInfo){
        $exportManager = new ExportManager();
        $rows = $exportManager->getRecordsToExport($exportInfo);
        $fileName = $exportManager->getFileName($exportInfo);
        $this->writeCsvFile($rows, $fileName);
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
        fputcsv($filePointer, $columnNames, $delimiter = ";");

        foreach ($rows as $row) {
            
            fputcsv($filePointer, $row, $delimiter = ";");
        }

        fclose($filePointer);
    }
}