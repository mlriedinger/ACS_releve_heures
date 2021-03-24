<?php

/* On appelle la classe qui gère la connexion à la BDD */
require_once('DatabaseConnection.php');


/* Classe qui gère l'envoi et la récupération de données de la BDD 
    * [INFO] Classe-fille de DatabaseConnection pour pouvoir hériter de la méthode dbConnect()
    * Méthodes de classe :
        * sendNewRecord() 
        * updateRecord()
        * updateRecordStatus() 
        * deleteRecord()
        * getRecord()
        * addQueryScopeAndOrderByClause()
        * getAllRecordsFromUser() 
        * getRecordsFromTeam()
        * getAllRecords()
        * writeCsvFile()
        * getDataForOptionSelect() 
*/

class RecordManager extends DatabaseConnection
{
    /* Méthode qui permet d'enregistrer un nouveau relevé. Elle renvoie 'true' en cas de succès et 'false' en cas d'erreur.
        Params:
        * $userId : id utilisateur
        * $dateTimeStart : date et heure de début
        * $dateTimeEnd: date et heure de fin
        * $comment : commentaire
        * $groupId : groupe utilisateur
    */

    public function sendNewRecord($recordInfo){
        $isSendingSuccessfull = false;

        $userId = $recordInfo->getUserId();
        $userGroup = $recordInfo->getUserGroup();
        $dateTimeStart = $recordInfo->getDateTimeStart();
        $dateTimeEnd = $recordInfo->getDateTimeEnd();
        $comment = $recordInfo->getComment();

        $userGroup == 1 ? $validation_status = 1 : $validation_status = 0;
        
        $pdo = $this->dbConnect();
      
        $query = $pdo->prepare('INSERT INTO t_saisie_heure(
            id, 
            id_login, 
            date_hrs_debut, 
            date_hrs_fin, 
            statut_validation, 
            commentaire) 
        VALUES (
            :id,
            :userId, 
            :dateTimeStart, 
            :dateTimeEnd, 
            :validation_status,
            :comment)');
        $attempt = $query->execute(array(
            'id' => 0,
            'userId' => $userId, 
            'dateTimeStart' => $dateTimeStart,
            'dateTimeEnd' => $dateTimeEnd,
            'validation_status' => $validation_status,
            'comment' => $comment
        ));
    
        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        if($attempt) $isSendingSuccessfull = true;

        return $isSendingSuccessfull;
    }


    /* Méthode qui permet de mettre à jour un relevé lorsqu'il n'a pas encore été validé par un N+1. Elle renvoie 'true' en cas de succès et 'false' en cas d'erreur.
        Params:
        * $recordId : id du relevé à mettre à jour
        * $dateTimeStart : date et heure de début
        * $dateTimeEnd: date et heure de fin
        * $comment : commentaire
    */

    public function updateRecord($recordInfo){
        $isUpdateSuccessfull = false;

        $recordId = $recordInfo->getRecordId();
        $dateTimeStart = $recordInfo->getDateTimeStart();
        $dateTimeEnd = $recordInfo->getDateTimeEnd();
        $comment = $recordInfo->getComment();

        $pdo = $this->dbConnect();

        $query = $pdo->prepare('UPDATE t_saisie_heure
        SET 
        date_hrs_debut = :dateTimeStart, 
        date_hrs_fin = :dateTimeEnd, 
        commentaire = :comment
        WHERE ID = :recordId');
        $attempt = $query->execute(array(
            'recordId' => $recordId,
            'dateTimeStart' => $dateTimeStart,
            'dateTimeEnd' => $dateTimeEnd,
            'comment' => $comment
        ));

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        if($attempt) $isUpdateSuccessfull = true;

        return $isUpdateSuccessfull;
    }


    /* Méthode qui permet de mettre à jour le statut d'un relevé lorsqu'il est validé par un N+1. Elle renvoie 'true' en cas de succès et 'false' en cas d'erreur.
        Params:
        * $recordId : id du relevé à mettre à jour
    */

    public function updateRecordStatus($recordId){
        $isUpdateSuccessfull = false;
                
        $pdo = $this->dbConnect();

        $query = $pdo->prepare('UPDATE t_saisie_heure
        SET statut_validation = 1
        WHERE ID = :recordId');
        $attempt = $query->execute(array('recordId' => $recordId));

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        if($attempt) $isUpdateSuccessfull = true;

        return $isUpdateSuccessfull;
    }


    /* Méthode qui permet de supprimer un relevé
        Params: 
        * $recordId : id du relevé à supprimer
        * $comment : commentaire à mettre à jour dans la BDD (si justification de la suppression)
    */

    public function deleteRecord($recordInfo){
        $isDeleteSuccessfull = false;

        $recordId = $recordInfo->getRecordId();
        $comment = $recordInfo->getComment();
    
        $pdo = $this->dbConnect();

        $query = $pdo->prepare('UPDATE t_saisie_heure
        SET supprimer = 1, commentaire = :comment
        WHERE ID=:recordId');
        $attempt = $query->execute(array(
            'recordId' => $recordId,
            'comment' => $comment
        ));

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        if($attempt) $isDeleteSuccessfull = true;

        return $isDeleteSuccessfull;
    }


    /* Méthode qui permet de récupérer tous les informations d'un relevé d'heures. Elle renvoie les données en JSON pour être exploitables par JS.
        Params:
        * $recordId : id relevé
    */

    public function getRecord($recordInfo){
        $pdo = $this->dbConnect();

        $recordId = $recordInfo->getRecordId();

        $query = $pdo->prepare('SELECT * FROM t_saisie_heure WHERE t_saisie_heure.ID = :recordId');
        $query->execute(array('recordId' => $recordId));
        $recordData = $query->fetch(PDO::FETCH_ASSOC);

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        // Commenter la ligne suivante pour débugger la requête
        header("Content-Type: text/json");

        echo json_encode($recordData);
    }


    /* Méthode qui permet d'ajouter des clauses dans le WHERE d'une requête et d'ajouter une clause ORDER BY
        Params : 
        * $sql : une chaîne de caractères contenant le début de la requête SQL
        * $scope : une chaîne de caractères désignant la portée de la requêtes (tout ou une partie des relevés)
        Retourne la chaîne $sql complétée
    */

    public function addQueryScopeAndOrderByClause($sql, $scope, $typeOfRecords){
        switch($scope) {
            case "all":
                if($typeOfRecords != "export") $sql .= " AND t_saisie_heure.supprimer = 0";
                break;
            case "valid":
                $sql .= " AND t_saisie_heure.statut_validation = 1 AND t_saisie_heure.supprimer = 0";
                break;
            case "unchecked":
                $sql .= " AND t_saisie_heure.statut_validation = 0 AND t_saisie_heure.supprimer = 0";
                break;
            case "deleted":
                $sql .= " AND t_saisie_heure.supprimer = 1";
                break;
            default:
                break;
        }

        if($typeOfRecords == "export" || $typeOfRecords == "all"){
            $pos = strpos($sql, "AND");
            if($pos !== false) {
                $search = "AND";
                $replace = "WHERE";
                $sql = substr_replace($sql, $replace, $pos, strlen("AND"));
            }
        }
        
        $sql .= " ORDER BY t_saisie_heure.date_hrs_creation DESC";

        return $sql;
    }


    /* Méthode qui permet de récupérer tous les relevés d'heures associés à un utilisateur. Elle renvoie les données en JSON pour être exploitables par JS.
        Params:
        * $userId : id utilisateur
        * $typeOfRecords : type de relevés demandés (paramètre envoyé par la requête AJAX)
        * $scope : portée de la requêtes, c'est-à-dire tout ou une partie des relevés (paramètre envoyé par la requête AJAX)
    */

    public function getRecordsFromUser($recordInfo){
        $pdo = $this->dbConnect();

        $userId = $recordInfo->getUserId();
        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $scope = $recordInfo->getScope();

        $sql = "SELECT id_of, 
        date_hrs_debut, 
        date_hrs_fin, 
        commentaire, 
        statut_validation, 
        date_hrs_creation, 
        date_hrs_modif,
        ID,
        supprimer 
        FROM t_saisie_heure 
        WHERE id_login = :userId";

        $sql = $this->addQueryScopeAndOrderByClause($sql, $scope, $typeOfRecords);

        $query = $pdo->prepare($sql);
        $query->execute(array('userId' => $userId));
        $userRecords["typeOfRecords"] = $typeOfRecords;
        $userRecords["records"] = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        // Commenter la ligne suivante pour débugger la requête
        header("Content-Type: text/json");
        
        echo json_encode($userRecords);
    }


    /* Méthode qui permet de récupérer TOUS les relevés d'heures de salariés associés à un manager. Elle renvoie les données en JSON pour être exploitables par JS.
        Params: 
        * $managerId : id du chef d'équipe
        * $typeOfRecords : type de relevés demandés (paramètre envoyé par la requête AJAX)
        * $scope : portée de la requêtes, c'est-à-dire tout ou une partie des relevés (paramètre envoyé par la requête AJAX)
    */

    public function getRecordsFromTeam($recordInfo){
        $pdo = $this->dbConnect();

        $managerId = $recordInfo->getManagerId();
        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $scope = $recordInfo->getScope();

        $sql = "SELECT t_saisie_heure.id_of, 
        t_login.Nom, 
        t_login.Prenom, 
        t_saisie_heure.date_hrs_debut, 
        t_saisie_heure.date_hrs_fin, 
        t_saisie_heure.commentaire, 
        t_saisie_heure.statut_validation, 
        t_saisie_heure.date_hrs_creation, 
        t_saisie_heure.date_hrs_modif,
        t_saisie_heure.ID,
        t_saisie_heure.supprimer
        FROM t_equipe
        INNER JOIN t_login
        ON t_equipe.id_membre = t_login.ID
        INNER JOIN t_saisie_heure
        ON t_login.ID = t_saisie_heure.id_login
        WHERE t_equipe.id_manager = :managerId";

        $sql = $this->addQueryScopeAndOrderByClause($sql, $scope, $typeOfRecords);
        
        $query = $pdo->prepare($sql);
        $query->execute(array('managerId' => $managerId));
        $teamRecords["typeOfRecords"] = $typeOfRecords;
        $teamRecords["records"] = $query->fetchAll(PDO::FETCH_ASSOC);

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        // Commenter la ligne suivante pour débugger la requête
        header("Content-Type: text/json");

        echo json_encode($teamRecords);
    }


    /* Méthode qui permet de récupérer les relevés de tous les utilisateurs. Elle renvoie les données en JSON pour être exploitables par JS.
         Params: 
        * $typeOfRecords : type de relevés demandés (paramètre envoyé par la requête AJAX)
        * $scope : portée de la requêtes, c'est-à-dire tout ou une partie des relevés (paramètre envoyé par la requête AJAX)
    */

    public function getAllRecords($recordInfo){
        $pdo = $this->dbConnect();

        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $scope = $recordInfo->getScope();

        $sql = "SELECT t_saisie_heure.id_of, 
        t_login.Nom, 
        t_login.Prenom, 
        t_saisie_heure.date_hrs_debut, 
        t_saisie_heure.date_hrs_fin, 
        t_saisie_heure.commentaire, 
        t_saisie_heure.statut_validation, 
        t_saisie_heure.date_hrs_creation, 
        t_saisie_heure.date_hrs_modif,
        t_saisie_heure.ID,
        t_saisie_heure.supprimer
        FROM t_saisie_heure
        INNER JOIN t_login
        ON t_saisie_heure.id_login = t_login.ID";

        $sql = $this->addQueryScopeAndOrderByClause($sql, $scope, $typeOfRecords);

        $query = $pdo->prepare($sql);
        $query->execute();
        $records["typeOfRecords"] = $typeOfRecords;
        $records["records"] = $query->fetchAll(PDO::FETCH_ASSOC);

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        // Commenter la ligne suivante pour débugger la requête
        header("Content-Type: text/json");

        echo json_encode($records);
    }


    public function exportRecords($recordInfo){
        $rows = $this->getRecordsToExport($recordInfo);
        $fileName = $this->getFileName($recordInfo);
        $this->writeCsvFile($rows, $fileName);
    }


    public function getRecordsToExport($recordInfo){
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


    public function getFileName($recordInfo){
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


    public function getDataForOptionSelect($type){
        $pdo = $this->dbConnect();

        $sql ="";

        switch($type){
            case "managers":
                $sql .= "SELECT t_equipe.id_manager AS 'ID', t_login.Nom, t_login.Prenom FROM t_equipe INNER JOIN t_login ON t_equipe.id_manager = t_login.ID GROUP BY t_equipe.id_manager";
                break;
            case "users":
                $sql .= "SELECT ID, Nom, Prenom FROM t_login";
                break;
        }

        $sql .= " ORDER BY t_login.Nom ASC";

        $query = $pdo->prepare($sql);
        $query->execute();
        $data["typeOfData"] = $type;
        $data["records"] = $query->fetchAll(PDO::FETCH_ASSOC);

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        // Commenter la ligne suivante pour débugger la requête
        header("Content-Type: text/json");

        echo json_encode($data);
    }
}


    