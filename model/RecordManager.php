<?php

/* On appelle la classe qui gère la connexion à la BDD */
require_once('DatabaseConnection.php');


/* Classe qui gère l'envoi et la récupération de données de la BDD 
    * [INFO] Classe-fille de DatabaseConnection pour pouvoir hériter de la méthode dbConnect()
    * Méthodes de classe :
        * sendNewRecord() 
        * updateRecordStatus() 
        * getAllRecordsFromUser() 
        * getRecordsToCheck()
        * getRecordsFromTeam()
        * getAllRecords() 
*/

class RecordManager extends DatabaseConnection
{
    /* Méthode qui permet d'enregistrer un nouveau relevé. Elle renvoie 'true' en cas de succès et 'false' en cas d'erreur.
        Params:
        * $id_user : id utilisateur
        * $start_time : date et heure de début
        * $end_time: date et heure de fin
        * $comment : commentaire

    */

    public function sendNewRecord($id_user, $start_time, $end_time, $comment){
        $isSendingSuccessfull = false;

        try{
            $pdo = $this->dbConnect();
     
            $query = $pdo->prepare('INSERT INTO t_saisie_heure(id, id_login, date_hrs_debut, date_hrs_fin, commentaire) VALUES (
                :id,
                :id_user, 
                :start_time, 
                :end_time, 
                :comment)');
            $query->execute(array(
                'id' => 0,
                'id_user' => $id_user, 
                'start_time' => $start_time,
                'end_time' => $end_time,
                'comment' => $comment
            ));

            // Décommenter la ligne suivante pour débugger la requête
            // $query->debugDumpParams();

            $isSendingSuccessfull = true;
        } catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        return $isSendingSuccessfull;
    }


    /* Méthode qui permet de mettre à jour le statut d'un relevé lorsqu'il est validé par un N+1. Elle renvoie 'true' en cas de succès et 'false' en cas d'erreur.
        Params:
        * $id_record : id du relevé à mettre à jour
    */

    public function updateRecordStatus($id_record){
        $isUpdateSuccessfull = false;
        
        try{
            $pdo = $this->dbConnect();

            $query = $pdo->prepare('UPDATE t_saisie_heure
            SET statut_validation = 1
            WHERE ID = :id_record');
            $query->execute(array('id_record' => $id_record));

            // Décommenter la ligne suivante pour débugger la requête
                // $query->debugDumpParams();

            $isUpdateSuccessfull = true;

        } catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        return $isUpdateSuccessfull;
        
    }


    /* Méthode qui permet de récupérer tous les relevés d'heures associés à un utilisateur. Elle renvoie les données en JSON pour être exploitables par JS.
        Params:
        * $id_user : id utilisateur
        * $type_of_records : type de relevés demandés (paramètre envoyé par la requête AJAX)
    */

    public function getRecordsFromUser($id_user, $type_of_records){
        header("Content-Type: text/json");

        $pdo = $this->dbConnect();

        $query = $pdo->prepare('SELECT id_chantier, 
        date_hrs_debut, 
        date_hrs_fin, 
        commentaire, 
        statut_validation, 
        date_hrs_creation, 
        date_hrs_modif 
        FROM t_saisie_heure 
        WHERE id_login = :id_user
        ORDER BY date_hrs_debut');
        $query->execute(array('id_user' => $id_user));
        $userRecords["typeOfRecords"] = $type_of_records;
        $userRecords["records"] = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        echo json_encode($userRecords);
    }


    /* Méthode qui permet de récupérer UNIQUEMENT les relevés d'heures dont le statut est en attente. Elle renvoie les données en JSON pour être exploitables par JS.
        Params:
        * $id_manager : id du chef d'équipe
        * $type_of_records : type de relevés demandés (paramètre envoyé par la requête AJAX)
    */

    public function getRecordsToCheck($id_manager, $type_of_records){
        header("Content-Type: text/json");

        $pdo = $this->dbConnect();

        $query = $pdo->prepare('SELECT t_saisie_heure.id_chantier, 
        t_login.Nom, 
        t_login.Prenom, 
        t_saisie_heure.date_hrs_debut, 
        t_saisie_heure.date_hrs_fin, 
        t_saisie_heure.commentaire,
        t_saisie_heure.date_hrs_creation, 
        t_saisie_heure.date_hrs_modif,
        t_saisie_heure.ID 
        FROM t_equipe
        INNER JOIN t_login
        ON t_equipe.id_membre = t_login.ID
        INNER JOIN t_saisie_heure
        ON t_login.ID = t_saisie_heure.id_login
        WHERE t_equipe.id_manager = :id_manager
        AND t_saisie_heure.statut_validation = 0
        ORDER BY date_hrs_debut');
        $query->execute(array('id_manager' => $id_manager));
        $recordsToCheck["typeOfRecords"] = $type_of_records;
        $recordsToCheck["records"] = $query->fetchAll(PDO::FETCH_ASSOC);

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        echo json_encode($recordsToCheck);
    }


    /* Méthode qui permet de récupérer TOUS les relevés d'heures de salariés associés à un manager. Elle renvoie les données en JSON pour être exploitables par JS.
        Params: 
        * $id_manager : id du chef d'équipe
        * $type_of_records : type de relevés demandés (paramètre envoyé par la requête AJAX)
    */

    public function getRecordsFromTeam($id_manager, $type_of_records){
        header("Content-Type: text/json");

        $pdo = $this->dbConnect();

        $query = $pdo->prepare('SELECT t_saisie_heure.id_chantier, 
        t_login.Nom, 
        t_login.Prenom, 
        t_saisie_heure.date_hrs_debut, 
        t_saisie_heure.date_hrs_fin, 
        t_saisie_heure.commentaire, 
        t_saisie_heure.statut_validation, 
        t_saisie_heure.date_hrs_creation, 
        t_saisie_heure.date_hrs_modif 
        FROM t_equipe
        INNER JOIN t_login
        ON t_equipe.id_membre = t_login.ID
        INNER JOIN t_saisie_heure
        ON t_login.ID = t_saisie_heure.id_login
        WHERE t_equipe.id_manager = :id_manager
        ORDER BY date_hrs_debut');
        $query->execute(array('id_manager' => $id_manager));
        $teamRecords["typeOfRecords"] = $type_of_records;
        $teamRecords["records"] = $query->fetchAll(PDO::FETCH_ASSOC);

        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        echo json_encode($teamRecords);
    }


    /* Méthode qui permet de récupérer les relevés de tous les utilisateurs. Elle renvoie les données en JSON pour être exploitables par JS.
         Params: 
        * $type_of_records : type de relevés demandés (paramètre envoyé par la requête AJAX)
    */

    public function getAllRecords($type_of_records){
        header("Content-Type: text/json");

        $pdo = $this->dbConnect();

        $query = $pdo->prepare('SELECT 
        t_saisie_heure.id_chantier, 
        t_login.Nom, 
        t_login.Prenom, 
        t_saisie_heure.date_hrs_debut, 
        t_saisie_heure.date_hrs_fin, 
        t_saisie_heure.commentaire, 
        t_saisie_heure.statut_validation, 
        t_saisie_heure.date_hrs_creation, 
        t_saisie_heure.date_hrs_modif 
        FROM t_saisie_heure
        INNER JOIN t_login
        ON t_saisie_heure.id_login = t_login.ID
        ORDER BY date_hrs_debut');
        $query->execute();
        $records["typeOfRecords"] = $type_of_records;
        $records["records"] = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        echo json_encode($records);
    }
}