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
		//$managerId = [];
        
        // Validation automatique des relevés saisis par un utilisateur de type "admin"
        $userGroup == 1 ? $validation_status = 1 : $validation_status = 0;
        
        $pdo = $this->dbConnect();
		
		// if($userGroup == 1) {
		// 	$managerId['ID'] = $userId;
		// } else {
		// 	$sql = 'SELECT ID
		// 		FROM t_login
		// 		WHERE ID = (
		// 			SELECT id_login 
		// 			FROM t_equipe 
		// 			WHERE chef_equipe = 1 AND id_chantier = (
		// 				SELECT id_chantier 
		// 				FROM t_equipe 
		// 				WHERE id_login = :userId AND chef_equipe = 0
		// 				)
		// 			)';

		// 	$query = $pdo->prepare($sql);
		// 	$query->execute(array('userId' => $userId));
		// 	$managerId = $query->fetch(PDO::FETCH_ASSOC);
		// }
		
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
			'id_manager' => $managerId['ID'],
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
    public function addDetails(Record $recordInfo, int $lastInsertId) {
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
     * Prmet de mettre à jour le statut d'un relevé lorsqu'il est validé par un N+1.
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
            Releve.statut_validation,
            Releve.commentaire,
            Releve.supprimer,
            Releve.tps_travail,
            Releve.tps_pause,
            Releve.tps_trajet 
        FROM t_saisie_heure AS Releve
        WHERE Releve.ID = :recordId';

        $query = $pdo->prepare($sql);
        $query->execute(array('recordId' => $recordId));
        $recordData = $query->fetch(PDO::FETCH_ASSOC);

        header("Content-Type: text/json");
        echo json_encode($recordData);
    }
    
    /**
     * Permet d'ajouter des lignes dans la clause WHERE et d'ajouter une clause ORDER BY.
     * Retourne la chaîne $sql complétée.
     *
     * @param  string $sql : une chaîne de caractères contenant le début de la requête SQL
     * @param  string $status : une chaîne de caractères désignant la portée de la requêtes (tout ou une partie des relevés)
     * @param  string $typeOfRecords: une chaîne de caractères désignant le type de relevés demandés (personnels, équipe, à valider ou tous)
     * @return string $sql
     */
    public function addQueryScopeAndOrderByClause(string $sql, string $status, string $typeOfRecords){
        switch($status) {
            case "all":
                if($typeOfRecords != "export") $sql .= ' AND Releve.supprimer = 0';
                break;
            case "valid":
                $sql .= ' AND Releve.statut_validation = 1 AND Releve.supprimer = 0';
                break;
            case "unchecked":
                $sql .= ' AND Releve.statut_validation = 0 AND Releve.supprimer = 0';
                break;
            case "deleted":
                $sql .= ' AND Releve.supprimer = 1';
                break;
            default:
                break;
        }

        // Si on souhaite exporter des données ou récupérer tous les relevés, on remplace la première occurrence de 'AND' par 'WHERE'
        if($typeOfRecords == "all"){
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
    public function getRecordsFromUser(Record $recordInfo){
        $pdo = $this->dbConnect();

        $userId = $recordInfo->getUserId();
        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $status = $recordInfo->getStatus();

        $sql = 'SELECT 
            CONCAT(t_affaires.REF, " - ", t_affaires.Nom) AS "affaire", 
            Releve.date_hrs_debut, 
            Releve.date_hrs_fin, 
            Releve.commentaire, 
            Releve.statut_validation, 
            Releve.date_hrs_creation, 
            Releve.date_hrs_modif,
            Releve.date_releve,
            Releve.ID,
            Releve.supprimer,
            Releve.id_login,
            Releve.tps_travail, 
            Releve.tps_pause,
            Releve.tps_trajet,
			t_login.id_groupe
        FROM t_saisie_heure AS Releve
		
        INNER JOIN t_affaires
            ON Releve.id_chantier = t_affaires.ID
			
		INNER JOIN t_login
			ON Releve.id_login = t_login.ID
			
        WHERE Releve.id_login = :userId';

        $sql = $this->addQueryScopeAndOrderByClause($sql, $status, $typeOfRecords);

        $query = $pdo->prepare($sql);
        $query->execute(array(
            'userId' => $userId));
        
        $userRecords["currentUserId"] = $userId ;
        $userRecords["typeOfRecords"] = $typeOfRecords;
        $userRecords["records"] = $query->fetchAll(PDO::FETCH_ASSOC);

        header("Content-Type: text/json");
        echo json_encode($userRecords);
    }
    
    /**
     * Permet de récupérer TOUS les relevés d'heures d'une équipe, c'est-à-dire les salariés associés à un manager (sauf les relevés du manager lui-même).
     * Retourne les données au format JSON pour être exploitables par les requêtes AJAX.
     *
     * @param  Record $recordInfo
     * @return string $teamRecords
     */
    public function getRecordsFromTeam(Record $recordInfo){
        $managerId = $recordInfo->getUserId();
        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $teamRecords["currentUserId"] = $managerId;
        $teamRecords["typeOfRecords"] = $typeOfRecords;
        $teamRecords["records"] = [];
        $status = $recordInfo->getStatus();

        $pdo = $this->dbConnect();

        $sql = 'SELECT id_login
			FROM t_equipe
			WHERE id_chantier = (SELECT t_equipe.id_chantier
			FROM t_equipe
			WHERE t_equipe.id_login = :managerId AND t_equipe.chef_equipe = 1) AND chef_equipe <> 1';

        $query = $pdo->prepare($sql);
        $query->execute(array('managerId' => $managerId));
        $teamMembers = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($teamMembers as $teamMember){

            $sql = 'SELECT
                CONCAT(t_affaires.REF, " - ", t_affaires.Nom) AS "affaire", 
                t_login.ID AS "id_login",
                t_login.Nom AS "nom_salarie",
                t_login.Prenom AS "prenom_salarie",
                Releve.date_hrs_debut, 
                Releve.date_hrs_fin, 
                Releve.commentaire, 
                Releve.statut_validation, 
                Releve.date_hrs_creation, 
                Releve.date_hrs_modif,
                Releve.ID,
                Releve.supprimer,
                Releve.tps_pause,
                Releve.tps_trajet,
                Releve.date_releve,
                Releve.tps_travail
                FROM t_saisie_heure AS Releve
                INNER JOIN t_affaires 
                    ON Releve.id_chantier = t_affaires.ID
                INNER JOIN t_login 
                    ON Releve.id_login = t_login.ID
                WHERE Releve.id_login = :teamMember';

                $sql = $this->addQueryScopeAndOrderByClause($sql, $status, $typeOfRecords);
    
            $query = $pdo->prepare($sql);
            $query->execute(array(
                'teamMember' => $teamMember['id_login']
            ));
            $records = $query->fetchAll(PDO::FETCH_ASSOC);

            $teamRecords["records"] = array_merge($teamRecords["records"], $records);
        }
		header("Content-Type: text/json");
        echo json_encode($teamRecords);
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

        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $status = $recordInfo->getStatus();

        $sql = 'SELECT 
			Releve.id_chantier AS "id_affaire",
			CONCAT(Affaire.REF, " - ", Affaire.Nom) AS "affaire",
			Manager.Nom AS "nom_manager",
			Manager.Prenom AS "prenom_manager",
			Membre.Nom AS "nom_salarie",
			Membre.Prenom AS "prenom_salarie",
			Releve.date_hrs_debut,
			Releve.date_hrs_fin, 
			Releve.commentaire, 
			Releve.statut_validation, 
			Releve.date_hrs_creation, 
			Releve.date_hrs_modif,
			Releve.ID,
			Releve.supprimer,
			Membre.ID AS "id_login",
			Releve.tps_pause,
			Releve.tps_trajet,
			Releve.date_releve,
			Releve.tps_travail
		   
		FROM t_saisie_heure AS Releve
		   
		INNER JOIN t_affaires AS Affaire
			ON Releve.id_affaire = Affaire.ID
		   
		INNER JOIN t_login AS Membre
		   ON Releve.id_login = Membre.ID
		   
		INNER JOIN t_login AS Manager
			ON Releve.id_manager = Manager.ID';

        $sql = $this->addQueryScopeAndOrderByClause($sql, $status, $typeOfRecords);

        $query = $pdo->prepare($sql);
        $query->execute();
        $records["currentUserId"] = $userId;
        $records["typeOfRecords"] = $typeOfRecords;
        $records["records"] = $query->fetchAll(PDO::FETCH_ASSOC);
		
        header("Content-Type: text/json");
        echo json_encode($records);
    }
    
    /**
     * Permet de récupérer (au choix) la liste des managers, des salariés ou des chantiers pour alimenter un input <select> (formulaire de saisie ou d'export).
     * Retourne les données au format JSON pour être exploitables par les requêtes AJAX.
     *
     * @param  Record $recordInfo
     * @return string $data
     */
    public function getDataForOptionSelect(Record $recordInfo){
        //print_r($recordInfo);
        $type = $recordInfo->getTypeOfRecords();
        $userGroup = $recordInfo->getUserGroup();
        $userId = $recordInfo->getUserId();

        $pdo = $this->dbConnect();

        $sql ="";

        switch($type){
            case "managers":
                if ($userGroup == '1') {
                    $sql .= 'SELECT t_equipe.id_login AS "ID", t_login.Nom, t_login.Prenom 
                        FROM t_equipe 
                        INNER JOIN t_login 
                        ON t_equipe.id_login = t_login.ID 
                        WHERE t_equipe.chef_equipe = 1';
                    break;
                } else {
                    $sql .= 'SELECT t_login.ID, t_login.Nom, t_login.Prenom 
                        FROM t_login  
                        WHERE ID = :userId';
                    break;
                }
                
            case "users":
                if ($userGroup == '1') {
                    $sql .= 'SELECT ID, Nom, Prenom FROM t_login';
                    break;
                } else {
                    $sql .= 'SELECT t_equipe.id_login AS "ID", t_login.Nom, t_login.Prenom
                        FROM t_equipe
                        INNER JOIN t_login
                        ON t_equipe.id_login = t_login.ID
                        WHERE id_chantier = (SELECT t_equipe.id_chantier
                        FROM t_equipe
                        WHERE t_equipe.id_login = :userId AND t_equipe.chef_equipe = 1) AND chef_equipe <> 1 ';
                    break;
                }
            case "worksites":
                $sql .= 'SELECT 
                    id_chantier AS "ID", 
                    CONCAT(REF, " - ", REF_interne) AS "Nom" 
                        FROM t_equipe
                        INNER JOIN t_document
                        ON t_equipe.id_chantier = t_document.ID
                        WHERE t_equipe.id_login = :userId';
                break;
        }

        if($type === "managers" || $type === "users"){
            $sql .= ' ORDER BY t_login.Nom ASC';
        }
        else {
            $sql .= ' ORDER BY t_document.REF ASC';
        }
        
        $query = $pdo->prepare($sql);

        $queryParams = array(
            'userId' => $userId
        );
        $query->execute($queryParams);
        
        $data["typeOfData"] = $type;
        $data["records"] = $query->fetchAll(PDO::FETCH_ASSOC);
        
        header("Content-Type: text/json");
        echo json_encode($data);
    }


    public function getWorkCategories() {
        $pdo = $this->dbConnect();

        $sql = "SELECT ID, 
            Libelle AS 'code_poste', 
            Libelle AS 'libelle_poste', 
            Supprimer 
        FROM t_rentabilite_cat";
        
        $query = $pdo->prepare($sql);
        $query->execute();
        $workCategories = $query->fetchAll(PDO::FETCH_ASSOC);

        // $query->debugDumpParams();
        header("Content-Type: text/json");
        echo json_encode($workCategories);
    }
}