<?php

    class AuthenticationException extends Exception {
        
        public function __construct($message = "Utilisateur non authentifié.<br>Veuillez vous connecter.") {
            parent::__construct($message, "001");
        }
    }