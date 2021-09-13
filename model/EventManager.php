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
     * @param  string $userUUID
     * @return void
     */
    public function getEventsFromPlanning(string $userUUID) {
        $pdo = $this->dbConnect();

        $sql ="SELECT t_equipe.id_document, 
            t_document.REF AS 'Ref',
            t_document.REF_interne AS 'Ref_interne',
            DATE_FORMAT(t_planning_chantier.DatePlanningDebut, '%d/%m/%Y') AS 'DatePlanningDebut',
            DATE_FORMAT(t_planning_chantier.DatePlanningFin, '%d/%m/%Y') AS 'DatePlanningFin',
            t_doc_etat.nom AS 'Type'
        FROM t_equipe
        LEFT JOIN t_document
            ON t_equipe.id_document = t_document.ID_CHAR
        LEFT JOIN t_planning_chantier
            ON t_document.ID_CHAR = t_planning_chantier.ID_chantier
        LEFT JOIN t_doc_etat
            ON t_document.ID_CHAR_DOC_ETAT = t_doc_etat.ID_CHAR
        WHERE t_equipe.id_login = :userUUID
            AND t_equipe.supprimer = 0
            AND t_document.ID_CHAR_DOC_TYPE = (SELECT ID_CHAR FROM t_type_doc WHERE etiquette = 'Chantier')
            AND t_document.Chantier_termine = 0
        ORDER BY id_document
        ";

        $query = $pdo->prepare($sql);
        $query->execute(array('userUUID' => $userUUID));

        return $query->fetchAll(PDO::FETCH_ASSOC);
        //return $query->debugDumpParams();
    }
}