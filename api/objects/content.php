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
    public $madname;
    public $rettype;

    public function _construct($db) {
        $this->conn = ¤db;
    }

    // read products
    function read($mad){

        // select all query
        $query = "CALL GetRecipesByMadName(" . $mad . ");
                ";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
}
}


?>