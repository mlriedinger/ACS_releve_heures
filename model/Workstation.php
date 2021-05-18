<?php 

/**
 * Classe qui gère les informations nécessaires à----.
 */
class Workstation {

    private $_workstationId;
    private $_length;
    private $_recordId;

    public function __construct($workstationId, $length) {
        $_workstationId = $this->setWorkstationId($workstationId);
        $_length = $this->setLength($length);
        $_recordId = $this->setRecordId(0);
    }

    /**
     * Get the value of _workstationId
     */ 
    public function getWorkstationId()
    {
        return $this->_workstationId;
    }

    /**
     * Get the value of _length
     */ 
    public function getLength()
    {
        return $this->_length;
    }

    /**
     * Get the value of _recordId
     */ 
    public function getRecordId()
    {
        return $this->_recordId;
    }

    /**
     * Set the value of _workstationId
     *
     * @return  self
     */ 
    public function setWorkstationId(int $_workstationId)
    {
        $this->_workstationId = $_workstationId;

        return $this;
    }

    /**
     * Set the value of _length
     *
     * @return  self
     */ 
    public function setLength(int $_length)
    {
        $this->_length = $_length;

        return $this;
    }

    /**
     * Set the value of _recordId
     *
     * @return  self
     */ 
    public function setRecordId(int $_recordId)
    {
        $this->_recordId = $_recordId;

        return $this;
    }
}