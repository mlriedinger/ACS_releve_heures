<?php

require_once 'DatabaseConnection.php';

/**
 * EventManager
 */
class EventManager extends DatabaseConnection {

    public function __construct() {
        parent::__construct();
    }
      
    /**
     * getEventsFromPlanning
     *
     * @param  mixed $userId
     * @return void
     */
    public function getEventsFromPlanning(int $userId) {
        $pdo = $this->dbConnect();

        $sql ="SELECT t_equipe.id_chantier, 
            t_document.REF AS 'Ref',
            t_document.REF_interne AS 'Ref_interne',
            DATE_FORMAT(t_planning_chantier.DatePlanningDebut, '%d/%m/%Y') AS 'DatePlanningDebut',
            DATE_FORMAT(t_planning_chantier.DatePlanningFin, '%d/%m/%Y') AS 'DatePlanningFin',
            t_doc_etat.nom AS 'Type'
        FROM t_equipe
        LEFT JOIN t_document
            ON t_equipe.id_chantier = t_document.ID
        LEFT JOIN t_planning_chantier
            ON t_document.ID = t_planning_chantier.ID_chantier
        LEFT JOIN t_doc_etat
            ON t_document.IDDoc_etat = t_doc_etat.ID
        WHERE t_equipe.id_login = :userId
            AND t_equipe.supprimer = 0
            AND t_document.IDType_doc = 3
            AND t_document.Chantier_termine = 0
        ORDER BY id_chantier
        ";

        $query = $pdo->prepare($sql);
        $query->execute(array('userId' => $userId));

        $events = $query->fetchAll(PDO::FETCH_ASSOC);

        header("Content-Type: text/json");
        echo json_encode($events);

    }

}