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
            CONCAT(REF, ' - ', REF_interne) AS 'Nom',
            DatePlanningDebut,
            DatePlanningFin
        FROM t_equipe
        LEFT JOIN t_document
            ON t_equipe.id_chantier = t_document.ID
        LEFT JOIN t_planning_chantier
            ON t_document.ID = t_planning_chantier.ID_chantier
        WHERE t_equipe.id_login = :userId";

        $query = $pdo->prepare($sql);
        $query->execute(array('userId' => $userId));

        $events = $query->fetchAll(PDO::FETCH_ASSOC);

        header("Content-Type: text/json");
        echo json_encode($events);

    }

}