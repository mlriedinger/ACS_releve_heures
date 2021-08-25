<?php

require_once 'DatabaseConnection.php';

/**
 * Classe qui permet de gérer l'ajout, la modification et la récupération de relevés.
 * Hérite de DatabseConnection pour pouvoir utiliser la méthode dbConnect(). 
 */
class RecordManager extends DatabaseConnection {

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Permet de convertir une durée exprimée en heures/minutes en une durée exprimée uniquement en minutes.
     * Par exemple, 1h30 = 90 minutes.
     *
     * @param  int $hours
     * @param  int $minutes
     * @return int $lengthInMinutes
     */
    public function convertLengthIntoMinutes(int $hours, int $minutes){
        $lengthInMinutes = $hours * 60 + $minutes;
        return $lengthInMinutes;
    }
        
    /**
     * Permet d'enregistrer un nouveau relevé.
     * Renvoie l'id du dernier enregistrement en cas de succès.
     *
     * @param  Record $recordInfo
     * @return bool $pdo->lastInsertId()
     */
    public function addNewRecord(Record $recordInfo){
        $userId = $recordInfo->getUserId();
        $userGroup = $recordInfo->getUserGroup();
        $breakLength = $recordInfo->getBreakLength();
        $comment = $recordInfo->getComment();
        $dateTimeStart = $recordInfo->getDateTimeStart();
        $dateTimeEnd = $recordInfo->getDateTimeEnd();
        $recordDate = $recordInfo->getDate();
        $tripLength = $recordInfo->getTripLength();
        $workLength = $recordInfo->getWorkLength();
        $worksite = $recordInfo->getWorksite();
        
        // Validation automatique des relevés saisis par un utilisateur de type "admin"
        $userGroup == 1 ? $validation_status = 1 : $validation_status = 0;
        
        $pdo = $this->dbConnect();
		
		$sql = 'INSERT INTO t_saisie_heure(
            ID, 
            id_chantier,
            id_login,
            date_hrs_debut, 
            date_hrs_fin, 
            date_releve,
            tps_travail,
            tps_pause,
            tps_trajet,
            statut_validation, 
            commentaire)
            VALUES (
            :id,
            :id_chantier, 
            :id_login,
            :dateTimeStart, 
            :dateTimeEnd,
            CASE 
                WHEN date_hrs_debut <> "0000-00-00 00:00:00" THEN DATE(:dateTimeStart)
                ELSE :recordDate
            END,
            CASE 
                WHEN date_hrs_debut <> "0000-00-00 00:00:00" AND date_hrs_fin <> "0000-00-00 00:00:00" THEN TIMESTAMPDIFF(MINUTE, :dateTimeStart, :dateTimeEnd)
                ELSE :workLength
            END,
            :pauseLength,
            :tripLength, 
            :validation_status,
            :comment)';
		
        $query = $pdo->prepare($sql);
        $attempt = $query->execute(array(
            'id' => 0,
            'id_chantier' => $worksite,
            'id_login' => $userId,
            'dateTimeStart' => $dateTimeStart,
            'dateTimeEnd' => $dateTimeEnd,
            'recordDate' => $recordDate,
            'workLength' => $workLength,
            'pauseLength' => $breakLength,
            'tripLength' => $tripLength,
            'validation_status' => $validation_status,
            'comment' => $comment
        ));

        //$query->debugDumpParams();

        return $pdo->lastInsertId();
    }
    
    /**
     * Permet d'enregistrer les détails d'un nouveau relevé.
     *
     * @param  Record $recordInfo
     * @param  int $lastInsertId
     * @return void
     */
    public function addRecordDetails(Record $recordInfo, int $lastInsertId) {
        $workstations = $recordInfo->getWorkstations();
        $updateResults = [];

        $pdo = $this->dbConnect();

        foreach($workstations as $workstation){
            $workstationId = $workstation->getWorkstationId();
            $length = $workstation->getLength();

            $query = $pdo->prepare('INSERT INTO t_saisie_heure_detail
            VALUES (
            :id,
            :id_releve, 
            :id_poste, 
            :duree)');
            $updateAttempt = $query->execute(array(
                'id' => 0,
                'id_releve' => $lastInsertId,
                'id_poste' => $workstationId,
                'duree' => $length
            ));

            if($updateAttempt) array_push($updateResults, $updateAttempt);

            //$query->debugDumpParams();
        }
        if(count($workstations) == count($updateResults)) $isUpdateSuccessfull = true;
        return $isUpdateSuccessfull;
    }
    
    /**
     * Permet de mettre à jour un relevé tant qu'il n'a pas été validé par un N+1.
     *
     * @param  Record $recordInfo
     * @return bool $isUpdateSuccessfull
     */
    public function updateRecord(Record $recordInfo){
        $breakLength = $recordInfo->getBreakLength();
        $comment = $recordInfo->getComment();
        $dateTimeStart = $recordInfo->getDateTimeStart();
        $dateTimeEnd = $recordInfo->getDateTimeEnd();
        $recordDate = $recordInfo->getDate();
        $recordId = $recordInfo->getRecordId();
        $tripLength = $recordInfo->getTripLength();
        $workLength = $recordInfo->getWorkLength();
        $worksiteId = $recordInfo->getWorksite();

        $isUpdateSuccessfull = false;
        $pdo = $this->dbConnect();
		
		$sql = 'UPDATE t_saisie_heure
			SET 
				id_chantier = :worksiteId,
				date_hrs_debut = :dateTimeStart, 
				date_hrs_fin = :dateTimeEnd,
				date_releve = :recordDate,
				tps_travail = 
					CASE 
						WHEN date_hrs_debut <> "0000-00-00 00:00:00" AND date_hrs_fin <> "0000-00-00 00:00:00" THEN TIMESTAMPDIFF(MINUTE, :dateTimeStart, :dateTimeEnd)
						ELSE :workLength
					END,
				tps_pause = :pauseLength,
				tps_trajet = :tripLength, 
				commentaire = :comment
			WHERE ID = :recordId';

        $query = $pdo->prepare($sql);
        $attempt = $query->execute(array(
            'worksiteId' => $worksiteId,
            'recordId' => $recordId,
            'dateTimeStart' => $dateTimeStart,
            'dateTimeEnd' => $dateTimeEnd,
            'recordDate' => $recordDate,
            'workLength' =>  $workLength,
            'pauseLength' => $breakLength,
            'tripLength' => $tripLength,
            'comment' => $comment
        ));

        if($attempt) $isUpdateSuccessfull = true;

        return $isUpdateSuccessfull;
    }

    
    /**
     * updateRecordDetails
     *
     * @return void
     */
    public function updateRecordDetails(Record $recordInfo) {
        $workstations = $recordInfo->getWorkstations();
        $recordId = $recordInfo->getRecordId();
        $updateResults = [];

        $pdo = $this->dbConnect();

        foreach($workstations as $workstation){
            $workstationId = $workstation->getWorkstationId();
            $length = $workstation->getLength();

            $sql ='UPDATE t_saisie_heure_detail
                SET duree = :duree
                WHERE id_releve = :id_releve AND id_poste = :id_poste';

            $query = $pdo->prepare($sql);
            $updateAttempt = $query->execute(array(
                'id_releve' => $recordId,
                'id_poste' => $workstationId,
                'duree' => $length
            ));

            // $query->debugDumpParams();
            // echo '<br>';
            // echo '<br>';

            if($updateAttempt) array_push($updateResults, $updateAttempt);
        }
        count($workstations) == count($updateResults) ? $isUpdateSuccessfull = true : $isUpdateSuccessfull = false;
        
        return $isUpdateSuccessfull;
    }

    /**
     * Permet de mettre à jour le statut d'un relevé lorsqu'il est validé par un N+1.
     *
     * @param  int $recordId
     * @return bool $isUpdateSuccessfull
     */
    public function updateRecordStatus(int $recordId){
        $isUpdateSuccessfull = false;      
        $pdo = $this->dbConnect();

        $query = $pdo->prepare('UPDATE t_saisie_heure
			SET statut_validation = 1
			WHERE ID = :recordId');
        $attempt = $query->execute(array('recordId' => $recordId));

        if($attempt) $isUpdateSuccessfull = true;

        return $isUpdateSuccessfull;
    }

    /**
     * Permet de "supprimer" un relevé (le rendre inactif).
     *
     * @param  Record $recordInfo
     * @return bool $isDeleteSuccessfull
     */
    public function deleteRecord(Record $recordInfo){
        $recordId = $recordInfo->getRecordId();
        $comment = $recordInfo->getComment();

        $isDeleteSuccessfull = false;
        $pdo = $this->dbConnect();
	
		$sql = 'UPDATE t_saisie_heure
			SET 
				supprimer = 1, 
				commentaire = :comment
			WHERE ID = :recordId';
			
        $query = $pdo->prepare($sql);
        $attempt = $query->execute(array(
            'recordId' => $recordId,
            'comment' => $comment
        ));

        if($attempt) $isDeleteSuccessfull = true;

        return $isDeleteSuccessfull;
    }

    /**
     * Permet de récupérer tous les informations d'un relevé d'heures.
     * Retourne les données au format JSON pour être exploitables par les requêtes AJAX.
     *
     * @param  Record $recordInfo
     * @return string $recordData
     */
    public function getRecord(Record $recordInfo){
        $recordId = $recordInfo->getRecordId();
        
        $pdo = $this->dbConnect();
		
		$sql = 'SELECT
            Releve.id_chantier, 
            Releve.id_login,
            Releve.date_hrs_debut,
            Releve.date_hrs_fin,
            Releve.date_releve,
            Releve.tps_travail,
            Releve.tps_pause,
            Releve.tps_trajet,
            Releve.statut_validation,
            Releve.commentaire,
            Releve.supprimer
        FROM t_saisie_heure AS Releve
        WHERE Releve.ID = :recordId';

        $query = $pdo->prepare($sql);
        $query->execute(array('recordId' => $recordId));
        $recordData["recordBasis"] = $query->fetch(PDO::FETCH_ASSOC);

        $sql = 'SELECT
            Details.id_releve,
            Details.id_poste,
            Details.duree
            
        FROM t_saisie_heure_detail AS Details
        WHERE Details.id_releve = :recordId';

        $query = $pdo->prepare($sql);
        $query->execute(array('recordId' => $recordId));
        $recordData["recordDetails"] = $query->fetchAll(PDO::FETCH_ASSOC);

        header("Content-Type: text/json");
        echo json_encode($recordData);
    }
    
    /**
     * Permet d'ajouter des lignes dans la clause WHERE et d'ajouter une clause ORDER BY.
     * Retourne la chaîne $sql complétée.
     *
     * @param  string $sql : une chaîne de caractères contenant le début de la requête SQL
     * @param  string $status : une chaîne de caractères désignant la portée de la requêtes (tout ou une partie des relevés)
     * @param  string $scope: une chaîne de caractères désignant le type de relevés demandés (personnels, équipe, à valider ou tous)
     * @return string $sql
     */
    public function addQueryScopeAndOrderByClause(string $sql, string $status, string $scope){
        switch($status) {
            case "approved":
                $sql .= ' AND Releve.statut_validation = 1 AND Releve.supprimer = 0';
                break;
            case "pending":
                $sql .= ' AND Releve.statut_validation = 0 AND Releve.supprimer = 0';
                break;
            case "deleted":
                $sql .= ' AND Releve.supprimer = 1';
                break;
            default:
                break;
        }

        // Si on souhaite exporter des données ou récupérer tous les relevés, on remplace la première occurrence de 'AND' par 'WHERE'
        if($scope == "global"){
            $occurence = strpos($sql, "AND");
            if($occurence !== false) {
                $textSubtitute = "WHERE";
                $sql = substr_replace($sql, $textSubtitute, $occurence, strlen("AND"));
            }
        }
        
        $sql .= ' ORDER BY Releve.date_hrs_creation DESC';

        return $sql;
    }

    /**
     * Permet de récupérer tous les relevés d'heures associés à un utilisateur.
     * Retourne les données au format JSON pour être exploitables par les requêtes AJAX.
     *
     * @param  Record $recordInfo
     * @return string $userRecords
     */
    public function getUserRecords(Record $recordInfo){
        $pdo = $this->dbConnect();

        $userId = $recordInfo->getUserId();
        $scope = $recordInfo->getScope();
        $status = $recordInfo->getStatus();

        $sql = 'SELECT 
            CONCAT(REF, " - ", REF_interne) AS "affaire", 
            DATE_FORMAT(Releve.date_hrs_debut, "%d/%m/%Y") AS "date_hrs_debut",
            DATE_FORMAT(Releve.date_hrs_fin, "%d/%m/%Y") AS "date_hrs_fin",
            Releve.commentaire, 
            Releve.statut_validation, 
            Releve.date_hrs_creation, 
            Releve.date_hrs_modif,
            DATE_FORMAT(Releve.date_releve, "%d/%m/%Y") AS "date_releve",
            Releve.ID,
            Releve.supprimer,
            Releve.id_login,
            Releve.tps_travail, 
            Releve.tps_pause,
            Releve.tps_trajet,
			t_login.id_groupe
        FROM t_saisie_heure AS Releve
		
        INNER JOIN t_document
            ON Releve.id_chantier = t_document.ID
			
		INNER JOIN t_login
			ON Releve.id_login = t_login.ID
			
        WHERE Releve.id_login = :userId';

        $sql = $this->addQueryScopeAndOrderByClause($sql, $status, $scope);

        $query = $pdo->prepare($sql);
        $query->execute(array(
            'userId' => $userId));
        
        $userRecords["currentUserId"] = $userId ;
        $userRecords["scope"] = $scope;
        $userRecords["records"] = $query->fetchAll(PDO::FETCH_ASSOC);

        header("Content-Type: text/json");
        // $query->debugDumpParams();
        // echo "<br/>";
        // var_dump($userRecords);
        echo json_encode($userRecords);
    }
    
    /**
     * Permet de récupérer les relevés de tous les utilisateurs.
     * Retourne les données au format JSON pour être exploitables par les requêtes AJAX.
     *
     * @param  Record $recordInfo
     * @return string $records
     */
    public function getAllRecords(Record $recordInfo){
        $userId = $recordInfo->getUserId();
        $pdo = $this->dbConnect();

        $scope = $recordInfo->getScope();
        $status = $recordInfo->getStatus();
        
        $sql = 'SELECT 
			Releve.id_chantier AS "id_affaire",
			CONCAT(REF, " - ", REF_interne) AS "affaire",
			Membre.Nom AS "nom_salarie",
			Membre.Prenom AS "prenom_salarie",
			DATE_FORMAT(Releve.date_hrs_debut, "%d/%m/%Y") AS "date_hrs_debut",
            DATE_FORMAT(Releve.date_hrs_fin, "%d/%m/%Y") AS "date_hrs_fin", 
			Releve.commentaire, 
			Releve.statut_validation, 
			Releve.date_hrs_creation, 
			Releve.date_hrs_modif,
			Releve.ID,
			Releve.supprimer,
			Membre.ID AS "id_login",
			Releve.tps_pause,
			Releve.tps_trajet,
            DATE_FORMAT(Releve.date_releve, "%d/%m/%Y") AS "date_releve",
			Releve.tps_travail,
            Membre.id_groupe
		   
		FROM t_saisie_heure AS Releve

        INNER JOIN t_document
            ON Releve.id_chantier = t_document.ID
		   
		INNER JOIN t_login AS Membre
		   ON Releve.id_login = Membre.ID';

        $sql = $this->addQueryScopeAndOrderByClause($sql, $status, $scope);

        $query = $pdo->prepare($sql);
        $query->execute();
        $records["currentUserId"] = $userId;
        $records["scope"] = $scope;
        $records["status"] = $status;
        $records["records"] = $query->fetchAll(PDO::FETCH_ASSOC);
		
        //$query->debugDumpParams();
        header("Content-Type: text/json");
        echo json_encode($records);
    }

    public function getUsers() {
        $pdo = $this->dbConnect();

        $sql = 'SELECT ID, Nom, Prenom 
            FROM t_login
            ORDER BY t_login.Nom ASC';

        $query = $pdo->prepare($sql);
        $query->execute();

        $users = $query->fetchAll(PDO::FETCH_ASSOC);

        header("Content-Type: text/json");
        echo json_encode($users);
    }

    public function getWorksites(Record $recordInfo) {
        $userId = $recordInfo->getUserId();

        $pdo = $this->dbConnect();

        $sql = 'SELECT 
            id_chantier AS "ID", 
            CONCAT(REF, " - ", REF_interne) AS "Nom" 
            FROM t_equipe
            INNER JOIN t_document
            ON t_equipe.id_chantier = t_document.ID
            WHERE t_equipe.id_login = :userId
            AND t_equipe.supprimer = 0
            ORDER BY t_document.REF ASC';

        $query = $pdo->prepare($sql);
        $query->execute(array(
            'userId' => $userId));

        $worksites = $query->fetchAll(PDO::FETCH_ASSOC);

        header("Content-Type: text/json");
        echo json_encode($worksites);
    }
    
    public function getWorkCategories() {
        $pdo = $this->dbConnect();

        $sql = "SELECT ID, 
            Code AS 'code_poste', 
            Libelle AS 'libelle_poste', 
            Supprimer 
        FROM t_saisie_heure_categorie
        WHERE Supprimer = 0";
        
        $query = $pdo->prepare($sql);
        $query->execute();
        $workCategories = $query->fetchAll(PDO::FETCH_ASSOC);

        //$query->debugDumpParams();
        header("Content-Type: text/json");
        echo json_encode($workCategories);
    }

    public function getWorkSubCategories() {
        $pdo = $this->dbConnect();

        $sql = "SELECT ID, 
            ID_categorie,
            Code AS 'code_poste', 
            Libelle AS 'libelle_poste', 
            Supprimer 
        FROM t_saisie_heure_sous_categorie
        WHERE Supprimer = 0";
        
        $query = $pdo->prepare($sql);
        $query->execute();
        $workCategories = $query->fetchAll(PDO::FETCH_ASSOC);

        //$query->debugDumpParams();
        header("Content-Type: text/json");
        echo json_encode($workCategories);
    }
}