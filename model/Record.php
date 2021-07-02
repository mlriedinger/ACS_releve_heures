<?php 

/**
 * Classe qui gère les informations nécessaires à l'ajout ou la modification d'un relevé.
 */
class Record {

    // Attributs
    private $_breakLengthHours;
    private $_comment;
    private $_date;
    private $_dateTimeEnd;
    private $_dateTimeStart;
    private $_recordId;
    private $_status;
    private $_tripLength;
    private $_typeOfRecords;
    private $_workLength;
    private $_worksite;
    private $_userId;
    private $_userGroup;
    private $_workstations;

    // Constructeur
    public function __construct() {
        $_breakLength = $this->setBreakLength(0);
        $_date = $this->setDate("0000-00-00");
        $_dateTimeEnd = $this->setDateTimeEnd("0000-00-00 00:00:00");
        $_dateTimeStart = $this->setDateTimeStart("0000-00-00 00:00:00");
        $_tripLength = $this->setTripLength(0);
        $_workLength = $this->setWorkLength(0);
        $_workstations = [];
    }
    
    // Mutateurs (setters)
    public function setBreakLength(int $breakLength){
        $this->_breakLength = $breakLength;
        return $this;
    }

    public function setComment(string $comment){
        $this->_comment = $comment;
        return $this;
    }

    public function setDate(String $date){
        $this->_date = $date;
        return $this;
    }

    public function setDateTimeEnd(String $dateTimeEnd){
        $this->_dateTimeEnd = $dateTimeEnd;
        return $this;
    }

    public function setDateTimeStart(String $dateTimeStart){
        $this->_dateTimeStart = $dateTimeStart;
        return $this;
    }

    public function setRecordId(int $recordId){
        $this->_recordId = $recordId;
        return $this;
    }

    public function setStatus(String $status){
        $this->_status = $status;
        return $this;
    }

    public function setTripLength(int $tripLength){
        $this->_tripLength = $tripLength;
        return $this;
    }

    public function setTypeOfRecords(String $typeOfRecords){
        $this->_typeOfRecords = $typeOfRecords;
        return $this;
    }

    public function setWorkLength(int $workLength){
        $this->_workLength = $workLength;
        return $this;
    }

    public function setWorksite(int $worksite){
        $this->_worksite = $worksite;
        return $this;
    }

    public function setUserId(int $userId){
        $this->_userId = $userId;
        return $this;
    }

    public function setUserGroup(int $userGroup){
        $this->_userGroup = $userGroup;
        return $this;
    }

    public function setWorkstations(array $workstations) {
        $this->_workstations = $workstations;
        return $this;
    }

    public function addWorkstation(Workstation $workstation) {
        $this->_workstations[$workstation->getWorkstationId()] = $workstation;
        return $this;
    }

    public function modifyWorkstation(Workstation $workstation) {
        $this->_workstations[$workstation->getWorkstationId()] = $workstation;
        return $this;
    }

    public function removeWorkstation($workstation) {
        if(gettype($workstation) == 'int'){
            unset($this->_workstations[$workstation]);
        }
        else if(get_class($workstation) == 'Workstation') {
            unset($this->_workstations[$workstation->getWorkstationId()]);
        }
        return $this;
    }

    // Accesseurs (getters)
    public function getBreakLength(){
        return $this->_breakLength;
    }

    public function getComment(){
        return $this->_comment;
    }

    public function getDate(){
        return $this->_date;
    }

    public function getDateTimeEnd(){
        return $this->_dateTimeEnd;
    }

    public function getDateTimeStart(){
        return $this->_dateTimeStart;
    }

    public function getRecordId(){
        return $this->_recordId;
    }

    public function getStatus(){
        return $this->_status;
    }

    public function getTripLength(){
        return $this->_tripLength;
    }

    public function getTypeOfRecords(){
        return $this->_typeOfRecords;
    }

    public function getWorkLength(){
        return $this->_workLength;
    }

    public function getWorksite(){
        return $this->_worksite;
    }

    public function getUserId(){
        return $this->_userId;
    }

    public function getUserGroup(){
        return $this->_userGroup;
    }
    
    public function getWorkstations() {
        return $this->_workstations;
    }
}