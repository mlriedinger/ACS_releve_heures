<?php

class AbstractController {
    
    /**
     * Rend la vue dont le nom est passé en paramètre.
     *
     * @param  string $viewFile : nom du ficher à rendre
     * @param  string $errorCode : code erreur (optionnel)
     * @param  string $errorMessage : message d'erreur à afficher dans l'alerte (optionnel)
     */
    public function displayView(string $viewFile, string $errorCode="", string $errorMessage="") {
		$errorCode;
        $errorMessage;
        require 'view/'.$viewFile.'.php';
    }
    
    /**
     * Rend la vue partielle dont le nom est passé en paramètre.
     *
     * @param  string $partialFile : nom du ficher à rendre
     */
    public function displayPartial(string $partialFile) {
        require 'view/partials/'.$partialFile.'.php';
    }
}