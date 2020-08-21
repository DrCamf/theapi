<?php 
include_once '../objects/ware.php';
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
    public $retid;
    public $indhold;
    public $volume;
    public $voltype;
    public $madname;
    public $rettype;

    public function __construct($db) {
        $this->conn = $db;
    }

    // read products
    public function read($recipeserch, $searchtype){
        // select all query
        if ($searchtype == "mad") {
            $query = "SELECT ret.id, ret.ret_name, ret.antal, ret.total_time, ret.prep_time, ret.image_url, ret.recipe_url, 
            mad.mad_name, rettype.rettype_name FROM Ret INNER JOIN madret ON madret.ret_id = ret.id INNER JOIN mad ON madret.mad_id = mad.id 
            INNER JOIN rettype ON ret.rettype_id = rettype.id WHERE mad.mad_name = '$recipeserch';";
        } else {
            $query = "SELECT ret.id, ret.ret_name, ret.antal, ret.total_time, ret.prep_time, ret.image_url, ret.recipe_url, mad.mad_name, 
            rettype.rettype_name FROM Ret INNER JOIN madret ON madret.ret_id = ret.id INNER JOIN mad ON madret.mad_id = mad.id 
            INNER JOIN rettype ON ret.rettype_id = rettype.id WHERE rettype.rettype_name = '$recipeserch' ORDER BY id DESC;
           ";
        
        }
   
        // prepare query statement
        $stmt = $this->conn->prepare($query);
             
        // execute query
        $stmt->execute();
    
        return $stmt;
    }


    public function readMore($recipeserch, $searchtype){
      
         // select all query
         if ($searchtype == "mad") {
             $query = "SELECT imperial_volume.ret_id, indhold.indhold_name, imperial_volume.volume, volume_type.vol_type_name FROM indhold 
             INNER JOIN imperial_volume ON imperial_volume.indhold_id = indhold.id 
             INNER JOIN volume_type ON imperial_volume.volume_type_id = volume_type.id 
             INNER JOIN madret ON imperial_volume.ret_id = madret.ret_id 
             INNER JOIN mad ON madret.mad_id = mad.id 
             WHERE imperial_volume.ret_id = '$recipeserch'";
         } else {
             $query = "SELECT imperial_volume.ret_id, indhold.indhold_name, imperial_volume.volume, volume_type.vol_type_name FROM indhold 
             INNER JOIN imperial_volume ON imperial_volume.indhold_id = indhold.id 
             INNER JOIN volume_type ON imperial_volume.volume_type_id = volume_type.id 
             INNER JOIN madret ON imperial_volume.ret_id = madret.ret_id 
             INNER JOIN mad ON madret.mad_id = mad.id 
             WHERE imperial_volume.ret_id = '$recipeserch'";
         }
      
         // prepare query statement
         $stmt = $this->conn->prepare($query);
           
         // execute query
         $stmt->execute();
     
         return $stmt;
     }
   
    function getLastPlusOne($table) {
        $query = "SELECT id+1 FROM" . $table . "ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // create recipe
    function create(){
  
        // query to insert record
        $query = "INSERT INTO ret SET ret_name=:ret_name, antal=:antal, total_time=:total_time, prep_time=:prep_time, image_url=:image_url, recipe_url=:recipe_url;
                INSERT INTO rettype SET rettype_name=:rettype_name;
                INSERT INTO madret SET ret_id=:ret_id, mad_id=:mad_id;
                INSERT INTO mad SET mad_name=:mad_name;";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->antal=htmlspecialchars(strip_tags($this->antal));
        $this->total_time=htmlspecialchars(strip_tags($this->total_time));
        $this->prep_time=htmlspecialchars(strip_tags($this->prep_time));
        $this->image_url=htmlspecialchars(strip_tags($this->image_url));
        $this->recipe_url=htmlspecialchars(strip_tags($this->recipe_url));
    
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":antal", $this->antal);
        $stmt->bindParam(":time", $this->total_time);
        $stmt->bindParam(":prep_time", $this->prep_time);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":recipe_url", $this->recipe_url);

        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
      
    }
}


?>