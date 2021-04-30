<?php

    /**
     * Classe qui permet de gérer un problème lié à un problème générique.
     */
    class NoDataFoundException extends Exception {
        
        public function __construct($message = "Un problème est survenu. Impossible de charger les données.") {
            parent::__construct($message, "004");
        }
    }