<?php

class Setting {

    // Attributes
    private $_dateTimeMgmt;
    private $_lengthMgmt;
    private $_tripMgmt;
    private $_breakMgmt;

    // Setters
    public function setDateTimeMgmt(int $dateTimeMgmtSwitch) {
        $this->_dateTimeMgmt = $dateTimeMgmtSwitch;
        return $this;
    }

    public function setLengthMgmt(int $lengthMgmtSwitch) {
        $this->_lengthMgmt = $lengthMgmtSwitch;
        return $this;
    }

    public function setTripMgmt(int $tripMgmtSwitch) {
        $this->_tripMgmt = $tripMgmtSwitch;
        return $this;
    }

    public function setBreakMgmt(int $breakMgmtSwitch) {
        $this->_breakMgmt = $breakMgmtSwitch;
        return $this;
    }

    // Getters
    public function getDateTimeMgmt() {
        return $this->_dateTimeMgmt;
    }

    public function getLengthMgmt() {
        return $this->_lengthMgmt;
    }

    public function getTripMgmt() {
        return $this->_tripMgmt;
    }

    public function getBreakMgmt() {
        return $this->_breakMgmt;
    }
}