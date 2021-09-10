<?php

require_once 'AbstractController.php';
require 'autoloader.php';

/**
 * Classe qui permet de gérer les événements du planning.
 */
class EventController extends AbstractController {

    private $_eventManager;

    public function __construct() {
        $this->_eventManager = new EventManager();
    }

    /**
     * Permet de récupérer les événements du planning liés à un salarié.
     *
     * @param  int $userId
     */
    public function getEventsFromPlanning(int $userId){
        $events = $this->_eventManager->getEventsFromPlanning($userId);

        header("Content-Type: text/json");
        echo json_encode($events);
    }
}