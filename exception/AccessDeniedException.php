<?php
    
    /**
     * Classe d'exception qui permet de gérer un problème de droits d'accès à l'application.
     */
    class AccessDeniedException extends Exception {
        
        public function __construct($message = "Vous ne disposez pas de droits d'accès suffisants. Veuillez contacter votre administrateur.") {
            parent::__construct($message, "005");
        }
    }