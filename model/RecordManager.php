<?php
require_once('database.php');

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
            //$query->debugDumpParams();
            $isSendingSuccessfull = true;
        } catch(Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        return $isSendingSuccessfull;
    }

    public function getAllRecords($id_user){
        header("Content-Type: text/json");

        $pdo = $this->dbConnect();

        $query = $pdo->prepare('SELECT id_chantier, date_hrs_debut, date_hrs_fin, TIMEDIFF(date_hrs_fin, date_hrs_debut) AS duree, commentaire, statut_validation, date_hrs_creation, date_hrs_modif 
        FROM t_saisie_heure 
        WHERE id_login = :id_user');
        $query->execute(array('id_user' => $id_user));
        $records = $query->fetchAll(PDO::FETCH_ASSOC);
        
        //$query->debugDumpParams();
        echo json_encode($records);
    }
}