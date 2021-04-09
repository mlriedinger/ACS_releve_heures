<?php

    class UpdateProblemException extends Exception {
        
        public function __construct($message = "La mise à jour n'a pas pu être effectuée. Veuillez ré-essayer.") {
            parent::__construct($message, "003");
        }
    }