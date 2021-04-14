<?php 

class Record {
    private $_recordId;
    private $_userId;
    private $_userGroup;
    private $_managerId;
    private $_dateTimeStart;
    private $_dateTimeEnd;
    private $_breakLengthMinutes;
    private $_tripLengthHours;
    private $_tripLengthMinutes;
    private $_comment;
    private $_periodStart;
    private $_periodEnd;
    private $_scope;
    private $_typeOfRecords;
    private $_worksite;

    public function __construct(){
        // $this->_recordId = 0;
        // $this->_userId = 0;
        // $this->_userGroup = 0;
        // $this->_managerId = 0;
        // $this->_dateTimeStart ="";
        // $this->_dateTimeEnd ="";
        // $this->_workLengthHour = 0;
        // $this->_workLengthMinute = 0;
        // $this->_tripLengthHour = 0;
        // $this->_tripLengthMinute = 0;
        // $this->_comment ="";
        // $this->_periodStart ="";
        // $this->_periodEnd ="";
        // $this->_scope ="";
        // $this->_typeOfRecords="";
        // $this->_worksite = 0;
    }

    // Setters

    public function setRecordId(int $recordId){
        $this->_recordId = $recordId;
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

    public function setManagerId(int $managerId){
        $this->_managerId = $managerId;
        return $this;
    }

    public function setDateTimeStart(String $dateTimeStart){
        $this->_dateTimeStart = $dateTimeStart;
        return $this;
    }

    public function setDateTimeEnd(String $dateTimeEnd){
        $this->_dateTimeEnd = $dateTimeEnd;
        return $this;
    }

    public function setBreakLengthMinutes(int $breakLengthMinutes){
        $this->_breakLengthMinutes = $breakLengthMinutes;
        return $this;
    }

    public function setTripLengthHours(int $tripLengthHours){
        $this->_tripLengthHours = $tripLengthHours;
        return $this;
    }

    public function setTripLengthMinutes(int $tripLengthMinutes){
        $this->_tripLengthMinutes = $tripLengthMinutes;
        return $this;
    }

    public function setComment(String $comment){
        $this->_comment = $comment;
        return $this;
    }

    public function setPeriodStart(String $periodStart){
        $this->_periodStart = $periodStart;
        return $this;
    }
    public function setPeriodEnd(String $periodEnd){
        $this->_periodEnd = $periodEnd;
        return $this;
    }

    public function setScope(String $scope){
        $this->_scope = $scope;
        return $this;
    }

    public function setTypeOfRecords(String $typeOfRecords){
        $this->_typeOfRecords = $typeOfRecords;
        return $this;
    }

    public function setWorksite(int $_worksite){
        $this->_worksite = $_worksite;
        return $this;
    }

    // Getters

    public function getRecordId(){
        return $this->_recordId;
    }

    public function getUserId(){
        return $this->_userId;
    }

    public function getUserGroup(){
        return $this->_userGroup;
    }

    public function getManagerId(){
        return $this->_managerId;
    }

    public function getDateTimeStart(){
        return $this->_dateTimeStart;
    }

    public function getDateTimeEnd(){
        return $this->_dateTimeEnd;
    }

    public function getBreakLengthMinutes(){
        return $this->_breakLengthMinutes;
    }

    public function getTripLengthHours(){
        return $this->_tripLengthHours;
    }

    public function getTripLengthMinutes(){
        return $this->_tripLengthMinutes;
    }

    public function getComment(){
        return $this->_comment;
    }

    public function getPeriodStart(){
        return $this->_periodStart;
    }

    public function getPeriodEnd(){
        return $this->_periodEnd;
    }

    public function getScope(){
        return $this->_scope;
    }

    public function getTypeOfRecords(){
        return $this->_typeOfRecords;
    }

    public function getWorksite(){
        return $this->_worksite;
    }
}