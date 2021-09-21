<?php

/**
 * Classe qui gère les informations nécessaires à l'export de données de l'application.
 */
class Export {

    // Attributs
    private $_userUUID;
    private $_managerId;
    private $_periodStart;
    private $_periodEnd;
    private $_status;
    private $_scope;
    private $_userGroup;

    // Mutateurs (setters)
    public function setUserUUID(string $userUUID){
        $this->_userUUID = $userUUID;
        return $this;
    }

    public function setManagerId(string $managerId){
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
    
    public function setScope(string $scope){
        $this->_scope = $scope;
        return $this;
    }

    /**
     * Set the value of _userGroup
     *
     * @return  self
     */ 
    public function setUserGroup( int $userGroup)
    {
        $this->_userGroup = $userGroup;

        return $this;
    }

    // Accesseurs (getters)
    public function getUserUUID(){
        return $this->_userUUID;
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
    
    public function getScope(){
        return $this->_scope;
    }

    /**
     * Get the value of _userGroup
     */ 
    public function getUserGroup()
    {
        return $this->_userGroup;
    }
}