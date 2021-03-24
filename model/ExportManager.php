<?php 

require_once 'RecordManager.php';

class ExportManager extends RecordManager {

    /* Fonction permettant d'exporter des données
        Params: 
        * $recordInfo : objet Record contenant 
            - le type de relevés demandés (personnels, équipe, à valider ou tous)
            - la portée de la requête, c'est-à-dire tout ou une partie des relevés
            - la date de début de période (facultatif)
            - la date de fin de période (facultatif)
            - l'id du manager (facultatif)
            - l'id du salarié (facultatif)
    */


    public function exportRecords(Record $recordInfo){
        $rows = $this->getRecordsToExport($recordInfo);
        $fileName = $this->getFileName($recordInfo);
        $this->writeCsvFile($rows, $fileName);
    }


    /* Fonction permettant de récupérer la liste des relevés à exporter
        Params: 
            * $recordInfo : objet Record contenant 
                - le type de relevés demandés (personnels, équipe, à valider ou tous)
                - la portée de la requête, c'est-à-dire tout ou une partie des relevés
                - la date de début de période (facultatif)
                - la date de fin de période (facultatif)
                - l'id du manager (facultatif)
                - l'id du salarié (facultatif)
    */

    public function getRecordsToExport(Record $recordInfo){
        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $scope = $recordInfo->getScope();
        $periodStart = $recordInfo->getPeriodStart();
        $periodEnd = $recordInfo->getPeriodEnd();
        $managerId = $recordInfo->getManagerId();
        $userId = $recordInfo->getUserId();

        $pdo = $this->dbConnect();

        $sql = "SELECT t_saisie_heure.ID AS 'numero de releve',
        t_saisie_heure.id_of AS 'chantier',
        t_login.Nom AS 'nom salarie', 
        t_login.Prenom AS 'prenom salarie', 
        t_saisie_heure.date_hrs_debut AS 'date et heure de debut', 
        t_saisie_heure.date_hrs_fin AS 'date et heure de fin', 
        t_saisie_heure.commentaire, 
        t_saisie_heure.statut_validation AS 'statut de validation', 
        t_saisie_heure.date_hrs_creation AS 'date et heure de creation', 
        t_saisie_heure.date_hrs_modif AS 'date et heure de modification',
        t_saisie_heure.supprimer AS 'releve supprime'
        FROM t_saisie_heure
        INNER JOIN t_login
        ON t_saisie_heure.id_login = t_login.ID";

        if($managerId != "" || $userId != "") $sql .=" INNER JOIN t_equipe ON t_login.ID = t_equipe.id_membre";
        
        if($periodStart != "" && $periodEnd != "") $sql .= " AND t_saisie_heure.date_hrs_debut >= :periodStart AND t_saisie_heure.date_hrs_fin <= :periodEnd";
        if($managerId != "") $sql .= " AND t_equipe.id_manager = :managerId";
        if($userId != "") $sql .= " AND t_saisie_heure.id_login = :userId";

        $sql = $this->addQueryScopeAndOrderByClause($sql, $scope, $typeOfRecords);

        $query = $pdo->prepare($sql);

        if($managerId != "" || ($periodStart != "" && $periodEnd != "") || $userId != ""){
            $queryParams = array();
            if($managerId != "") $queryParams['managerId'] = $managerId;
            if($userId != "")  $queryParams['userId'] = $userId;
            if($periodStart != "") $queryParams['periodStart'] = $periodStart;
            if($periodEnd != "") $queryParams['periodEnd'] = $periodEnd;
        
            $query->execute($queryParams);
        }
        else $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        return $rows;
    }


    /* Fonction permettant de construire le nom du fichier d'export
        Params: 
        * $recordInfo : objet Record contenant 
            - la portée de la requête, c'est-à-dire tout ou une partie des relevés
            - la date de début de période (facultatif)
            - la date de fin de période (facultatif)
            - l'id du manager (facultatif)
            - l'id du salarié (facultatif)
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


    /* Fonction permettant d'écrire un fichier CSV
        Params: 
        * $rows : contient le résultat de la requête getRecordsToExport()
        * $fileName : contient le résultat de la fonction getFileName()
    */

    public function writeCsvFile($rows, $fileName){
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
        $fp = fopen('php://output', 'w');

        // On insère les en-têtes de colonnes au format CSV
        fputcsv($fp, $columnNames);

        // On boucle sur les lignes récupérées de la requête pour les insérer dans le fichier au format CSV
        foreach ($rows as $row) {
            fputcsv($fp, $row);
        }

        // On ferme le pointeur de fichier
        fclose($fp);
    }
}