<?php 

require_once 'RecordManager.php';

/**
 * Classe qui permet de gérer l'export de données.
 * Hérite de RecordManager pour pouvoir utiliser la méthode addQueryScopeAndOrderByClause()
 */
class ExportManager extends RecordManager {

    /**
     * Permet d'exporter les données au format CSV
     *
     * @param  Record $recordInfo
     */
    public function exportRecords(Record $recordInfo){
        $rows = $this->getRecordsToExport($recordInfo);
        $fileName = $this->getFileName($recordInfo);
        $this->writeCsvFile($rows, $fileName);
    }

    
    /**
     * Permet de construire la base de la requête SQL de récupération des relevés à exporter
     *
     * @return String $sql
     */
    public function sqlRequestBasis(){
        $sql = "SELECT 
            Releve.ID AS 'num_releve',
            Membre.Nom AS 'nom_salarie',
            Membre.Prenom AS 'prenom_salarie',";
        
        if($_SESSION['dateTimeMgmt'] == 1) {
            $sql .= "Releve.date_hrs_debut AS 'date_heure_debut',
            Releve.date_hrs_fin AS 'date_heure_fin',";
        }
        else if ($_SESSION['lengthMgmt'] == 1){
            $sql .= "Releve.date_releve,";
        }
            
        $sql .= "Releve.tps_travail,";

        if($_SESSION['breakMgmt'] == 1){
            $sql .= " Releve.tps_pause,";
        }
        if($_SESSION['tripMgmt'] == 1){
            $sql .= "Releve.tps_trajet,";
        }
            
        $sql .= "Releve.commentaire,
            Releve.statut_validation AS 'statut_validation',
            Releve.date_hrs_creation AS 'date_heure_creation',
            Releve.date_hrs_modif AS 'date_heure_modification',
            Releve.supprimer AS 'releve_supprime',
            Chantier.Nom AS 'chantier',
            Manager.Nom AS 'nom_manager',
            Manager.Prenom AS 'prenom_manager'
        FROM t_equipe AS Equipe
        INNER JOIN t_chantier AS Chantier
            ON Equipe.id_chantier = Chantier.ID
        INNER JOIN t_saisie_heure AS Releve
            ON Chantier.ID = Releve.id_chantier
        INNER JOIN t_login AS Manager
            ON Equipe.id_login = Manager.ID
        INNER JOIN t_login AS Membre
            ON Releve.id_login = Membre.ID
        WHERE Equipe.chef_equipe = 1";

        return $sql;
    }

    
    /**
     * Permet d'ajouter des options à la requête SQL de récupération des relevés à exporter
     *
     * @param  Record $recordInfo
     * @param  String $sql
     * @return String $sql
     */
    public function sqlRequestOptions(Record $recordInfo, String $sql){
        $periodStart = $recordInfo->getPeriodStart();
        $periodEnd = $recordInfo->getPeriodEnd();
        $managerId = $recordInfo->getManagerId();
        $userId = $recordInfo->getUserId();
        
        if($periodStart != "" && $periodEnd != "") $sql .= " AND Releve.date_hrs_debut >= :periodStart AND Releve.date_hrs_fin <= :periodEnd";
        if($managerId != "") $sql .= " AND Manager.ID = :managerId";
        if($userId != "") $sql .= " AND Membre.ID = :userId";

        return $sql;
    }

    
    /**
     * Permet de remplir un tableau de valeurs pour l'exécution de la requête préparée
     *
     * @param  Record $recordInfo
     * @return Array $queryParams
     */
    public function fillQueryParamsArray(Record $recordInfo){
        $periodStart = $recordInfo->getPeriodStart();
        $periodEnd = $recordInfo->getPeriodEnd();
        $managerId = $recordInfo->getManagerId();
        $userId = $recordInfo->getUserId();

        $queryParams = array();

        if($managerId != "") {
            $queryParams['managerId'] = $managerId;
        }
        if($userId != "") {
            $queryParams['userId'] = $userId;
        }
        if($periodStart != "") {
            $periodStart .= " 00:00:01";
            $queryParams['periodStart'] = $periodStart;
        }
        if($periodEnd != "") {
            $periodEnd .= " 23:59:59";
            $queryParams['periodEnd'] = $periodEnd;
        }

        return $queryParams;
    }

    
    /**
     * Permet de récupérer la liste des relevés à exporter
     *
     * @param  Record $recordInfo
     * @return Array $rows
     */
    public function getRecordsToExport(Record $recordInfo){
        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $scope = $recordInfo->getScope();

        $pdo = $this->dbConnect();

        // Construction de la requête SQL
        $sql = $this->sqlRequestBasis();
        $sql = $this->sqlRequestOptions($recordInfo, $sql);
        $sql = $this->addQueryScopeAndOrderByClause($sql, $scope, $typeOfRecords);

        $query = $pdo->prepare($sql);
        $queryParams = $this->fillQueryParamsArray($recordInfo);
        
        if (sizeof($queryParams) != 0){    
            $query->execute($queryParams);
        }
        else $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        return $rows;
    }

    
    /**
     * Permet de construire le nom du fichier d'export
     *
     * @param  Record $recordInfo
     * @return String $fileName
     */
    public function getFileName(Record $recordInfo){
        $scope = $recordInfo->getScope();
        $periodStart = $recordInfo->getPeriodStart();
        $periodEnd = $recordInfo->getPeriodEnd();
        $managerId = $recordInfo->getManagerId();
        $userId = $recordInfo->getUserId();

        $fileNameDetails ="_";
        $fileNameDetails .= $scope . "_records";

        if($managerId != "") $fileNameDetails .= "_manager_" . $managerId;
        if($userId != "") $fileNameDetails .= "_user_" . $userId;
        if($periodStart != "") $fileNameDetails .= "_from_" . $periodStart;
        if($periodEnd != "") $fileNameDetails .= "_to_" . $periodEnd;

        $fileName = date('Ymd') . '_export_releves_heures' . $fileNameDetails . '.csv';

        return $fileName;
    }

    
    /**
     * Permet d'écrire un fichier CSV
     *
     * @param  Array $rows
     * @param  String $fileName
     */
    public function writeCsvFile(Array $rows, String $fileName){
        $columnNames = array();
        if(!empty($rows)){
            // On boucle sur la première ligne pour récupérer les en-têtes des colonnes
            $firstRow = $rows[0];
            foreach($firstRow as $colName => $val){
                $columnNames[] = $colName;
            }
        }

        // Commenter les lignes suivantes pour débugger la requête
        header("Content-type: text/csv ; charset=UTF-8");
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        // On crée un pointeur de fichier dans le flux output pour envoyer le fichier directement au navigateur
        $filePointer = fopen('php://output', 'w');

        // On insère les en-têtes de colonnes au format CSV
        fputcsv($filePointer, $columnNames);

        // On boucle sur les lignes récupérées de la requête pour les insérer dans le fichier au format CSV
        foreach ($rows as $row) {
            fputcsv($filePointer, $row);
        }

        // On ferme le pointeur de fichier
        fclose($filePointer);
    }
}