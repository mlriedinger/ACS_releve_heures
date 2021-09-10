<?php 

require_once 'RecordManager.php';

/**
 * Classe qui permet de gérer l'export de données.
 * Hérite de RecordManager pour pouvoir utiliser la méthode addQueryScopeAndOrderByClause().
 */
class ExportManager extends RecordManager {
    
    /**
     * Permet de récupérer le contenu à exporter, c'est-à-dire la liste des relevés, selon les options choisies dans le formulaire d'export.
     * Retourne un tableau de relevés.
     *
     * @param  Export $exportInfo
     * @return array $rows
     */
    public function getRecordsToExport(Export $exportInfo){
        $scope = $exportInfo->getScope();
        $status = $exportInfo->getStatus();
        //$userGroup = $exportInfo->getUserGroup();

        $pdo = $this->dbConnect();
        
        $sql = $this->sqlRequestBasis();
        $sql = $this->sqlAddExportOptions($exportInfo, $sql);
        $sql = $this->addQueryScopeAndOrderByClause($sql, $status, $scope);

        $query = $pdo->prepare($sql);
        $queryParams = $this->fillQueryParamsArray($exportInfo);
		
        if(sizeof($queryParams) != 0){    
            $query->execute($queryParams);
        }
        else $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

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
            Membre.Nom AS 'nom_salarie',
            Membre.Prenom AS 'prenom_salarie',";
        
        $sql = $this->sqlAddSettingsOptions($sql);
            
        $sql .= "Releve.commentaire,
            CAST(Releve.statut_validation AS FLOAT) AS 'statut_validation',
            Releve.date_hrs_creation AS 'date_heure_creation',
            Releve.date_hrs_modif AS 'date_heure_modification',
            Releve.supprimer AS 'releve_supprime',
            CONCAT(Chantier.REF, ' - ', Chantier.REF_interne) AS 'chantier'
        FROM t_saisie_heure AS Releve
		   
		INNER JOIN t_document AS Chantier
			ON Releve.id_document = Chantier.ID_CHAR
		   
		INNER JOIN t_login AS Membre
		   ON Releve.id_login = Membre.ID_CHAR";

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
            
        $sql .= "
            CAST(Releve.tps_travail AS DOUBLE) AS 'tps_travail_minutes',
            CAST(ROUND((Releve.tps_travail / 60), 2) AS DOUBLE) AS 'tps_travail_heures',
        ";

        if($_SESSION['breakMgmt'] == 1){
            $sql .= " Releve.tps_pause AS 'tps_pause_minutes',";
        }
        if($_SESSION['tripMgmt'] == 1){
            $sql .= "
                CAST(Releve.tps_trajet AS DOUBLE) AS 'tps_trajet_minutes',
                CAST(ROUND((Releve.tps_trajet / 60), 2) AS DOUBLE) AS 'tps_trajet_heures',
            ";
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
        $userUUID = $exportInfo->getUserUUID();
        $userGroup = $exportInfo->getUserGroup();
		
        if($periodStart != "" && $periodEnd != "") {
			if($_SESSION['dateTimeMgmt'] == 1) {
				$sql .= " AND Releve.date_hrs_debut >= :periodStart AND Releve.date_hrs_fin <= :periodEnd";
			} else if ($_SESSION['lengthMgmt'] == 1){
				$sql .= " AND Releve.date_releve >= :periodStart AND Releve.date_releve <= :periodEnd";
			}
		}

        if($userUUID != "") $sql .= " AND Membre.ID = :userUUID";

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
        $userUUID = $exportInfo->getUserUUID();

        $queryParams = array();

        if($userUUID != "") {
            $queryParams['userUUID'] = $userUUID;
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
}