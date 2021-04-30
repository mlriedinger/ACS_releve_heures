<?php
    
    /**
     * Classe d'exception qui permet de gérer un problème d'authentification à l'application.
     */
    class AuthenticationException extends Exception {
        
        public function __construct($message = "Utilisateur non authentifié.<br>Veuillez vous connecter.") {
            parent::__construct($message, "001");
        }
    }