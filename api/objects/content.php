<?php 

class Content{

    // Database connection 
    private $conn;

    // object properties
    public $id;
    public $retname;
    public $howmany;
    public $preptime;
    public $time;
    public $indholditem = array("id"=> $id, "indhold" => $indhold, "volume" => $volume);
    public $madname;
    public $rettype;

    public function _construct($db) {
        $this->conn = ¤db;
    }

    // read products
    function read($mad){

        // select all query
        $query = "SELECT mad.name, ret.name, ret.antal, ret.Prep_Time, ret.Prep_Time, ret.image_url, ret.recipe_url, rettype.name FROM ret
        INNER JOIN madret ON madret.ret_id = ret.id
        INNER JOIN mad ON madret.mad_id = mad.id
        INNER JOIN rettype ON ret.rettype_id = rettype.id
        WHERE mad.name =" . $mad .  ";";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
}
}


?>