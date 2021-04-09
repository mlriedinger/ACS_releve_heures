<?php

    class InvalidParameterException extends Exception {
        
        public function __construct($message = "Veuillez remplir tous les champs.") {
            parent::__construct($message, "002");
        }
    }