<?php
    
    /**
     * Classe qui permet de gérer un problème lié à un champs de formulaire non rempli.
     */
    class InvalidParameterException extends Exception {
        
        public function __construct($message = "Veuillez remplir tous les champs.") {
            parent::__construct($message, "002");
        }
    }