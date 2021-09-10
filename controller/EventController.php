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
     * @param  int $userUUID
     */
    public function getEventsFromPlanning(int $userUUID){
        $events = $this->_eventManager->getEventsFromPlanning($userUUID);

        header("Content-Type: text/json");
        echo json_encode($events);
    }
}