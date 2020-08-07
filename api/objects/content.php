<?php 

class Content{

    // Database connection 
    private $conn;

    // object properties
    public $id;
    public $retname;
    public $howmany;
    public $indhold;
    public $volume;

    public function _construct($db) {
        $this->conn = ¤db;
    }
}


?>