<?php

/* On appelle la classe qui gère la connexion à la BDD */
require_once 'DatabaseConnection.php';


/* Classe qui gère l'envoi et la récupération de données de la BDD 
    * [INFO] Classe-fille de DatabaseConnection pour pouvoir hériter de la méthode dbConnect()
*/

class RecordManager extends DatabaseConnection
{
    /* Méthode qui permet d'enregistrer un nouveau relevé. Elle renvoie 'true' en cas de succès et 'false' en cas d'erreur.
        Params:
        * $recordInfo : objet Record contenant l'id user, l'id du groupe utilisateur, la date et heure de début, la date et heure de fin et le commentaire
    */

    public function sendNewRecord(Record $recordInfo){
        // On récupère les informations contenues dans l'objet $recordInfo
        $userId = $recordInfo->getUserId();
        $userGroup = $recordInfo->getUserGroup();
        $worksite = $recordInfo->getWorksite();
        $dateTimeStart = $recordInfo->getDateTimeStart();
        $dateTimeEnd = $recordInfo->getDateTimeEnd();
        $comment = $recordInfo->getComment();

        // Validation automatique des relevés saisis par un utilisateur de type admin
        $userGroup == 1 ? $validation_status = 1 : $validation_status = 0;
        
        $isSendingSuccessfull = false;
        $pdo = $this->dbConnect();
      
        $query = $pdo->prepare('INSERT INTO t_saisie_heure(
            ID, 
            id_chantier,
            id_login,
            date_hrs_debut, 
            date_hrs_fin, 
            statut_validation, 
            commentaire)
            VALUES (
            :id,
            :id_chantier, 
            :id_login,
            :dateTimeStart, 
            :dateTimeEnd, 
            :validation_status,
            :comment)');
        $attempt = $query->execute(array(
            'id' => 0,
            'id_chantier' => $worksite,
            'id_login' => $userId,
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
        * $recordInfo : objet Record contenant l'id du relevé à mettre à jour, la date et heure de début, la date et heure de fin et le commentaire
    */

    public function updateRecord(Record $recordInfo){
        // On récupère les informations contenues dans l'objet $recordInfo
        $worksiteId = $recordInfo->getWorksite();
        $recordId = $recordInfo->getRecordId();
        $dateTimeStart = $recordInfo->getDateTimeStart();
        $dateTimeEnd = $recordInfo->getDateTimeEnd();
        $comment = $recordInfo->getComment();

        $isUpdateSuccessfull = false;
        $pdo = $this->dbConnect();

        $query = $pdo->prepare('UPDATE t_saisie_heure
        SET 
        id_chantier = :worksiteId,
        date_hrs_debut = :dateTimeStart, 
        date_hrs_fin = :dateTimeEnd, 
        commentaire = :comment
        WHERE ID = :recordId');
        $attempt = $query->execute(array(
            'worksiteId' => $worksiteId,
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

    public function updateRecordStatus(int $recordId){
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
        * $recordInfo : objet Record contenant l'id du relevé à supprimer et le commentaire (si justification de la suppression)
    */

    public function deleteRecord(Record $recordInfo){
        // On récupère les informations contenues dans l'objet $recordInfo
        $recordId = $recordInfo->getRecordId();
        $comment = $recordInfo->getComment();

        $isDeleteSuccessfull = false;
        $pdo = $this->dbConnect();

        $query = $pdo->prepare('UPDATE t_saisie_heure
        SET 
        supprimer = 1, 
        commentaire = :comment
        WHERE ID = :recordId');
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
        * $recordInfo : objet Record contenant l'id du relevé à récupérer
    */

    public function getRecord(Record $recordInfo){
        $recordId = $recordInfo->getRecordId();
        
        $pdo = $this->dbConnect();

        $query = $pdo->prepare('SELECT
            Releve.id_chantier, 
            Releve.id_login,
            Releve.date_hrs_debut,
            Releve.date_hrs_fin,
            Releve.statut_validation,
            Releve.commentaire,
            Releve.supprimer
        FROM t_saisie_heure AS Releve
        WHERE Releve.ID = :recordId');
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
        * $typeOfRecords : une chaîne de caractères désignant le type de relevés demandés (personnels, équipe, à valider ou tous)
        Retourne la chaîne $sql complétée
    */

    public function addQueryScopeAndOrderByClause(String $sql, String $scope, String $typeOfRecords){
        switch($scope) {
            case "all":
                if($typeOfRecords != "export") $sql .= " AND Releve.supprimer = 0";
                break;
            case "valid":
                $sql .= " AND Releve.statut_validation = 1 AND Releve.supprimer = 0";
                break;
            case "unchecked":
                $sql .= " AND Releve.statut_validation = 0 AND Releve.supprimer = 0";
                break;
            case "deleted":
                $sql .= " AND Releve.supprimer = 1";
                break;
            default:
                break;
        }

        // Si on souhaite exporter des données ou récupérer tous les relevés, on remplace la première occurrence de 'AND' et on la remplace par 'WHERE'
        if($typeOfRecords == "export" || $typeOfRecords == "all"){
            $pos = strpos($sql, "AND");
            if($pos !== false) {
                $replace = "WHERE";
                $sql = substr_replace($sql, $replace, $pos, strlen("AND"));
            }
        }
        
        // On ordonne les données par ordre décroissant de date de création
        $sql .= " ORDER BY Releve.date_hrs_creation DESC";

        return $sql;
    }


    /* Méthode qui permet de récupérer tous les relevés d'heures associés à un utilisateur. Elle renvoie les données en JSON pour être exploitables par JS.
        Params:
        * $recordInfo : objet Record contenant l'id de l'utilisateur, le type de relevés demandés (personnels, équipe, à valider ou tous) et la portée de la requête, c'est-à-dire tout ou une partie des relevés
    */

    public function getRecordsFromUser(Record $recordInfo){
        $pdo = $this->dbConnect();

        $userId = $recordInfo->getUserId();
        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $scope = $recordInfo->getScope();

        $sql = "SELECT 
            t_chantier.Nom AS 'chantier', 
            Releve.date_hrs_debut, 
            Releve.date_hrs_fin, 
            Releve.commentaire, 
            Releve.statut_validation, 
            Releve.date_hrs_creation, 
            Releve.date_hrs_modif,
            Releve.ID,
            Releve.supprimer,
            Releve.id_login 
        FROM t_saisie_heure AS Releve
        INNER JOIN t_chantier
            ON Releve.id_chantier = t_chantier.ID
        WHERE Releve.id_login = :userId";

        $sql = $this->addQueryScopeAndOrderByClause($sql, $scope, $typeOfRecords);

        $query = $pdo->prepare($sql);
        $query->execute(array(
            'userId' => $userId));

        $userRecords["typeOfRecords"] = $typeOfRecords;
        $userRecords["records"] = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        // Commenter la ligne suivante pour débugger la requête
        header("Content-Type: text/json");
        
        echo json_encode($userRecords);
    }


    /* Méthode qui permet de récupérer TOUS les relevés d'heures de salariés associés à un manager (sauf les relevés du manager lui-même). 
        Elle renvoie les données en JSON pour être exploitables par JS.
        Params: 
        * $recordInfo : objet Record contenant l'id du chef d'équipe, le type de relevés demandés (personnels, équipe, à valider ou tous) et la portée de la requête, c'est-à-dire tout ou une partie des relevés
    */

    public function getRecordsFromTeam(Record $recordInfo){
        $managerId = $recordInfo->getManagerId();
        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $scope = $recordInfo->getScope();

        $pdo = $this->dbConnect();

        $sql = "SELECT t_equipe.id_chantier
        FROM t_equipe
        WHERE t_equipe.id_login = :managerId AND t_equipe.chef_equipe = 1";

        $query = $pdo->prepare($sql);
        $query->execute(array('managerId' => $managerId));
        $worksites = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach($worksites as $worksite){

            $sql = "(SELECT
                t_chantier.Nom AS chantier, 
                t_login.ID AS 'id_login',
                t_login.Nom, 
                t_login.Prenom, 
                Releve.date_hrs_debut, 
                Releve.date_hrs_fin, 
                Releve.commentaire, 
                Releve.statut_validation, 
                Releve.date_hrs_creation, 
                Releve.date_hrs_modif,
                Releve.ID,
                Releve.supprimer
                FROM t_saisie_heure AS Releve
                INNER JOIN t_chantier 
                    ON Releve.id_chantier = t_chantier.ID
                INNER JOIN t_login 
                    ON Releve.id_login = t_login.ID
                WHERE Releve.id_chantier = :worksite";

                $sql = $this->addQueryScopeAndOrderByClause($sql, $scope, $typeOfRecords);

                $sql .= ")
                EXCEPT
                    (SELECT 
                        t_chantier.Nom AS chantier, 
                        t_login.ID,
                        t_login.Nom, 
                        t_login.Prenom, 
                        Releve.date_hrs_debut, 
                        Releve.date_hrs_fin, 
                        Releve.commentaire, 
                        Releve.statut_validation, 
                        Releve.date_hrs_creation, 
                        Releve.date_hrs_modif,
                        Releve.ID,
                        Releve.supprimer
                    FROM t_saisie_heure AS Releve
                    INNER JOIN t_chantier 
                        ON Releve.id_chantier = t_chantier.ID
                    INNER JOIN t_login 
                        ON Releve.id_login = t_login.ID
                    WHERE Releve.id_chantier = :worksite 
                    AND Releve.id_login = :managerId";

                $sql = $this->addQueryScopeAndOrderByClause($sql, $scope, $typeOfRecords);
                $sql .= ")";
    
            $query = $pdo->prepare($sql);
            $query->execute(array(
                'worksite' => $worksite['id_chantier'],
                'managerId' => $managerId
            ));
            $teamRecords["typeOfRecords"] = $typeOfRecords;
            $teamRecords["records"] = $query->fetchAll(PDO::FETCH_ASSOC);
    
            // $query->debugDumpParams();

            // Commenter la ligne suivante pour débugger la requête
            header("Content-Type: text/json");

            echo json_encode($teamRecords);
        }
    }  

    

    /* Méthode qui permet de récupérer les relevés de tous les utilisateurs. Elle renvoie les données en JSON pour être exploitables par JS.
         Params: 
        * $recordInfo : objet Record contenant le type de relevés demandés (personnels, équipe, à valider ou tous) et la portée de la requête, c'est-à-dire tout ou une partie des relevés
    */

    public function getAllRecords(Record $recordInfo){
        $pdo = $this->dbConnect();

        $typeOfRecords = $recordInfo->getTypeOfRecords();
        $scope = $recordInfo->getScope();

        $sql = "SELECT
            Equipe.id_chantier AS 'id_chantier',
            Chantier.Nom AS 'chantier',
            Membre.Nom AS 'nom_salarie',
            Membre.Prenom AS 'prenom_salarie',
            Manager.Nom AS 'nom_manager',
            Manager.Prenom AS 'prenom_manager',
            Releve.date_hrs_debut,
            Releve.date_hrs_fin, 
            Releve.commentaire, 
            Releve.statut_validation, 
            Releve.date_hrs_creation, 
            Releve.date_hrs_modif,
            Releve.ID,
            Releve.supprimer,
            Membre.ID
        FROM t_equipe AS Equipe

        INNER JOIN t_chantier AS Chantier
            ON Equipe.id_chantier = Chantier.ID

        INNER JOIN t_saisie_heure AS Releve
            ON Chantier.ID = Releve.id_chantier

        INNER JOIN t_login AS Manager
            ON Equipe.id_login = Manager.ID
            
        INNER JOIN t_login AS Membre
            ON Releve.id_login = Membre.ID

        WHERE Equipe.chef_equipe = 1    
        ";

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

    /* Fonction permettant de récupérer la liste des managers et des salariés pour alimenter la rubrique select du formulaire d'export
        * $type : chaîne de caractères correspondant au type d'utilisateurs à récupérer ("managers" ou "users")
    */

    public function getDataForOptionSelect($type, $userId){
        $pdo = $this->dbConnect();

        $sql ="";

        switch($type){
            case "managers":
                $sql .= "SELECT t_equipe.id_login AS 'ID', t_login.Nom, t_login.Prenom FROM t_equipe INNER JOIN t_login ON t_equipe.id_login = t_login.ID WHERE t_equipe.chef_equipe = 1";
                break;
            case "users":
                $sql .= "SELECT ID, Nom, Prenom FROM t_login";
                break;
            case "worksites":
                $sql .= "SELECT  
                    t_chantier.ID,
                    t_chantier.Nom
                    FROM t_equipe
                    INNER JOIN t_chantier
                    ON t_equipe.id_chantier = t_chantier.ID
                    WHERE t_equipe.id_login = :userId";
                break;
        }

        if($type === "managers" || $type === "users"){
            $sql .= " ORDER BY t_login.Nom ASC";
        }
        else {
            $sql .= " ORDER BY t_chantier.Nom ASC";
        }
        
        $query = $pdo->prepare($sql);

        $queryParams = array();
        if($userId != "")  $queryParams['userId'] = $userId;

        if (sizeof($queryParams) != 0){    
            $query->execute($queryParams);
        }
        else $query->execute();
        
        $data["typeOfData"] = $type;
        $data["records"] = $query->fetchAll(PDO::FETCH_ASSOC);

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        // Commenter la ligne suivante pour débugger la requête
        header("Content-Type: text/json");

        echo json_encode($data);
    }
}


    