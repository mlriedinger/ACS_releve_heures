<?php 

require_once 'RecordManager.php';

/**
 * Classe qui permet de gérer l'export de données.
 * Hérite de RecordManager pour pouvoir utiliser la méthode addQueryScopeAndOrderByClause().
 */
class ExportManager extends RecordManager {

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
        $rows = $this->getRecordsToExport($exportInfo);
        $fileName = $this->getFileName($exportInfo);
        $this->writeCsvFile($rows, $fileName);
    }
    
    /**
     * Permet de récupérer le contenu à exporter, c'est-à-dire la liste des relevés, selon les options choisies dans le formulaire d'export.
     * Retourne un tableau de relevés.
     *
     * @param  Export $exportInfo
     * @return array $rows
     */
    public function getRecordsToExport(Export $exportInfo){
        $typeOfRecords = $exportInfo->getTypeOfRecords();
        $status = $exportInfo->getStatus();
        $userGroup = $exportInfo->getUserGroup();

        $pdo = $this->dbConnect();

        // Construction de la requête SQL
        if ($userGroup > 1) {
            $sql = $this->sqlRequestBasisForManager();
        } else {
            $sql = $this->sqlRequestBasis();
        }
        $sql = $this->sqlAddExportOptions($exportInfo, $sql);
        $sql = $this->addQueryScopeAndOrderByClause($sql, $status, $typeOfRecords);

        $query = $pdo->prepare($sql);
        $queryParams = $this->fillQueryParamsArray($exportInfo);
		
        if(sizeof($queryParams) != 0){    
            $query->execute($queryParams);
        }
        else $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        //$query->debugDumpParams();

        // On force la boucle à pointer sur l'emplacement mémoire et à ne pas créer de copie temporaire avec "&"
        foreach($rows as &$row) {
            $row["tps_travail_heures"] = str_replace('.', ',', $row["tps_travail_heures"]);
            $row["tps_trajet_heures"] = str_replace('.', ',', $row["tps_trajet_heures"]);
        }    

        return $rows;
    }
    
    /**
     * Permet de construire la base de la requête SQL pour récupérer les relevés.
     *
     * @return string $sql
     */
    public function sqlRequestBasis(){
        $sql = "SELECT 
            Releve.ID AS 'num_releve',
            CONCAT(Membre.Nom, ' ', Membre.Prenom) AS 'nom_prenom_salarie',";
        
        $sql = $this->sqlAddSettingsOptions($sql);
            
        $sql .= "Releve.commentaire,
            Releve.statut_validation AS 'statut_validation',
            Releve.date_hrs_creation AS 'date_heure_creation',
            Releve.date_hrs_modif AS 'date_heure_modification',
            Releve.supprimer AS 'releve_supprime',
            Affaire.Nom AS 'projet',
            CONCAT(Manager.Nom, ' ', Manager.Prenom) AS 'nom_prenom_manager'
        FROM t_saisie_heure AS Releve
		   
		INNER JOIN t_affaires AS Affaire
			ON Releve.id_affaire = Affaire.ID
		   
		INNER JOIN t_login AS Membre
		   ON Releve.id_login = Membre.ID
		   
		INNER JOIN t_login AS Manager
			ON Releve.id_manager = Manager.ID";

        return $sql;
    }

    /**
     * Permet de construire la base de la requête SQL pour récupérer les relevés.
     *
     * @return string $sql
     */
    public function sqlRequestBasisForManager(){
        $sql = "SELECT 
            Releve.ID AS 'num_releve',
            CONCAT(Membre.Nom, ' ', Membre.Prenom) AS 'nom_prenom_salarie',";
        
        $sql = $this->sqlAddSettingsOptions($sql);
            
        $sql .= "Releve.commentaire,
            Releve.statut_validation AS 'statut_validation',
            Releve.date_hrs_creation AS 'date_heure_creation',
            Releve.date_hrs_modif AS 'date_heure_modification',
            Releve.supprimer AS 'releve_supprime',
            Affaire.Nom AS 'projet',
            CONCAT(Manager.Nom, ' ', Manager.Prenom) AS 'nom_prenom_manager'
        FROM t_saisie_heure AS Releve
		   
		INNER JOIN t_affaires AS Affaire
			ON Releve.id_affaire = Affaire.ID
		   
		INNER JOIN t_login AS Membre
		   ON Releve.id_login = Membre.ID
		   
		INNER JOIN t_login AS Manager
			ON Releve.id_manager = Manager.ID
        
        WHERE Releve.id_manager = :managerId";

        return $sql;
    }
    
    /**
     * Permet de compléter la requête SQL en fonction des paramètres de saisie de l'application.
     *
     * @param  string $sql
     * @return string $sql
     */
    public function sqlAddSettingsOptions($sql) {
        if($_SESSION['dateTimeMgmt'] == 1) {
            $sql .= "Releve.date_hrs_debut AS 'date_heure_debut',
            Releve.date_hrs_fin AS 'date_heure_fin',";
        }
        else if ($_SESSION['lengthMgmt'] == 1){
            $sql .= "Releve.date_releve,";
        }
            
        $sql .= "Releve.tps_travail AS 'tps_travail_minutes',
                ROUND((Releve.tps_travail / 60), 2) AS 'tps_travail_heures',";

        if($_SESSION['breakMgmt'] == 1){
            $sql .= " Releve.tps_pause AS 'tps_pause_minutes',";
        }
        if($_SESSION['tripMgmt'] == 1){
            $sql .= "Releve.tps_trajet AS 'tps_trajet_minutes',
                    ROUND((Releve.tps_trajet / 60), 2) AS 'tps_trajet_heures',";
        }

        return $sql;
    }

    /**
     * Permet d'ajouter des options à la requêtes SQL.
     * Par exemple, récupérer uniquement des relevés entre 2 dates, et/ou d'un salarié (ou d'un manager) en particulier.
     *
     * @param  Export $exportInfo
     * @param  string $sql
     * @return string $sql
     */
    public function sqlAddExportOptions(Export $exportInfo, string $sql){
        $periodStart = $exportInfo->getPeriodStart();
        $periodEnd = $exportInfo->getPeriodEnd();
        $managerId = $exportInfo->getManagerId();
        $userId = $exportInfo->getUserId();
        $userGroup = $exportInfo->getUserGroup();
		
        if($periodStart != "" && $periodEnd != "") {
			if($_SESSION['dateTimeMgmt'] == 1) {
				$sql .= " AND Releve.date_hrs_debut >= :periodStart AND Releve.date_hrs_fin <= :periodEnd";
			} else if ($_SESSION['lengthMgmt'] == 1){
				$sql .= " AND Releve.date_releve >= :periodStart AND Releve.date_releve <= :periodEnd";
			}
		}
        if($userGroup == '1') {
            if($managerId != "") $sql .= " AND Manager.ID = :managerId";
        }
        if($userId != "") $sql .= " AND Membre.ID = :userId";

        return $sql;
    }

    /**
     * Permet de construire le tableau de paramètres qui seront passés lors de l'exécution de la requête SQL.
     *
     * @param  Export $exportInfo
     * @return array $queryParams
     */
    public function fillQueryParamsArray(Export $exportInfo){
        $periodStart = $exportInfo->getPeriodStart();
        $periodEnd = $exportInfo->getPeriodEnd();
        $managerId = $exportInfo->getManagerId();
        $userId = $exportInfo->getUserId();

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
     * Permet de construire le nom du fichier d'export en fonction des champs sélectionnés dans le formulaire d'export.
     *
     * @param  Export $exportInfo
     * @return string $fileName
     */
    public function getFileName(Export $exportInfo){
        $status = $exportInfo->getStatus();
        $periodStart = $exportInfo->getPeriodStart();
        $periodEnd = $exportInfo->getPeriodEnd();
        $managerId = $exportInfo->getManagerId();
        $userId = $exportInfo->getUserId();

        $fileNameDetails ="_";
        $fileNameDetails .= $status . "_records";

        if($managerId != "") $fileNameDetails .= "_manager_" . $managerId;
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
        fputcsv($filePointer, $columnNames, $delimiter = ";");

        foreach ($rows as $row) {
            
            fputcsv($filePointer, $row, $delimiter = ";");
        }

        fclose($filePointer);
    }
}