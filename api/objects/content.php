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
    public $recipeUrl;
    public $imageurl;
    public $indholditem = array();
    public $madname;
    public $rettype;

    public function _construct($db) {
        $this->conn = $db;
    }

    // read products
    function read($mad){

        // select all query
        $query = "SELECT mad.name, ret.name, ret.antal, ret.Prep_Time, ret.time, ret.image_url, ret.recipe_url, rettype.name FROM ret
        INNER JOIN madret ON madret.ret_id = ret.id
        INNER JOIN mad ON madret.mad_id = mad.id
        INNER JOIN rettype ON ret.rettype_id = rettype.id
        WHERE mad.name = $mad ; SELECT imperial_volume.ret_id, indhold.name, imperial_volume.volume, volume_type.name FROM imperial_volume
        INNER JOIN indhold ON imperial_volume.indhold_id = indhold.id
        INNER JOIN volume_type ON imperial_volume.volume_type_id = volume_type.id
        INNER JOIN madret ON imperial_volume.ret_id = madret.ret_id
        INNER JOIN mad ON madret.mad_id = mad.id
        WHERE mad.name = $mad ;";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create recipe
    function create(){
  
    // query to insert record
    $query = "INSERT INTO ret SET name=:name, antal=:antal, time=:time, prep_time=:prep_time, image_Url=:image_url, recipe_url=:recipe_url;
              INSERT INTO rettype SET name=:name;
              INSERT INTO madret SET ret_id=:ret_id, mad_id=:mad_id;
              INSERT INTO mad SET name=:name;
              ";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
   
  
    // bind values
    $stmt->bindParam(":name", $this->name);
    
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
    }
}


?>