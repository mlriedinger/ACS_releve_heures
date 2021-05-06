<?php

/**
 * Classe qui gère les informations nécessaires à l'export de données de l'application.
 */
class Export {

    // Attributs
    private $_userId;
    private $_managerId;
    private $_periodStart;
    private $_periodEnd;
    private $_status;
    private $_typeOfRecords;

    // Mutateurs (setters)
    public function setUserId(int $userId){
        $this->_userId = $userId;
        return $this;
    }

    public function setManagerId(int $managerId){
        $this->_managerId = $managerId;
        return $this;
    }

    public function setPeriodStart(string $periodStart){
        $this->_periodStart = $periodStart;
        return $this;
    }

    public function setPeriodEnd(string $periodEnd){
        $this->_periodEnd = $periodEnd;
        return $this;
    }

    public function setStatus(string $status){
        $this->_status = $status;
        return $this;
    }
    
    public function setTypeOfRecords(string $typeOfRecords){
        $this->_typeOfRecords = $typeOfRecords;
        return $this;
    }

    // Accesseurs (getters)
    public function getUserId(){
        return $this->_userId;
    }

    public function getManagerId(){
        return $this->_managerId;
    }

    public function getPeriodStart(){
        return $this->_periodStart;
    }

    public function getPeriodEnd(){
        return $this->_periodEnd;
    }

    public function getStatus(){
        return $this->_status;
    }
    
    public function getTypeOfRecords(){
        return $this->_typeOfRecords;
    }
}