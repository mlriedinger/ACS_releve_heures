<?php

/* On appelle la classe qui gère la connexion à la BDD */
require_once('DatabaseConnection.php');


/* Classe qui gère l'envoi et la récupération de données de la BDD 
    * [INFO] Classe-fille de DatabaseConnection pour pouvoir hériter de la méthode dbConnect()
    * Méthodes de la classe :
        * sendNewRecord() permet d'enregistrer un nouveau relevé. Elle renvoie 'true' en cas de succès et 'false' en cas d'erreur
        * getAllRecordsFromUser() permet de récupérer tous les relevés d'heures associés à un utilisateur. Elle renvoie les données en JSON pour être exploitables par JS
        * getAllRecords() permet de récupérer les relevés de tous les utilisateurs. Elle renvoie les données en JSON pour être exploitables par JS
*/

class RecordManager extends DatabaseConnection
{
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

    public function getAllRecordsFromUser($id_user){
        header("Content-Type: text/json");

        $pdo = $this->dbConnect();

        $query = $pdo->prepare('SELECT id_chantier, date_hrs_debut, date_hrs_fin, commentaire, statut_validation, date_hrs_creation, date_hrs_modif 
        FROM t_saisie_heure 
        WHERE id_login = :id_user');
        $query->execute(array('id_user' => $id_user));
        $userRecords = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        echo json_encode($userRecords);
    }

    public function getAllRecords(){
        header("Content-Type: text/json");

        $pdo = $this->dbConnect();

        $query = $pdo->prepare('SELECT id_login, id_chantier, date_hrs_debut, date_hrs_fin, commentaire, statut_validation, date_hrs_creation, date_hrs_modif 
        FROM t_saisie_heure');
        $query->execute();
        $records = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Décommenter la ligne suivante pour débugger la requête
        // $query->debugDumpParams();

        echo json_encode($records);
    }
}