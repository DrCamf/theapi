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
   
    public function getLastPlusOne($table) {
        $query = "SELECT id+1 FROM" . $table . "ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // create recipe
    public function create(){
  
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
    
        //get id+1
        $rid = getLastPlusOne("ret");

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


    // update the product
    public function update(){
 
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    ret_name = :ret_name,
                    antal = :antal,
                    total_time = :total_time,
                    prep_time=:prep_time,
                    image_url=:image_url,
                    recipe_url=:recipe_url
                WHERE
                    id = :id";
                    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->antal=htmlspecialchars(strip_tags($this->antal));
        $this->total_time=htmlspecialchars(strip_tags($this->total_time));
        $this->prep_time=htmlspecialchars(strip_tags($this->prep_time));
        $this->image_url=htmlspecialchars(strip_tags($this->image_url));
        $this->recipe_url=htmlspecialchars(strip_tags($this->recipe_url));
        $this->id=htmlspecialchars(strip_tags($this->id));
    
    
        // bind new values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":antal", $this->antal);
        $stmt->bindParam(":time", $this->total_time);
        $stmt->bindParam(":prep_time", $this->prep_time);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":recipe_url", $this->recipe_url);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    // delete the product
    public function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }


    // search products
    public function search($keywords){
  
        // select all query
        $query = "SELECT ret.id, ret.ret_name, ret.antal, ret.total_time, ret.prep_time, ret.image_url, ret.recipe_url, 
        mad.mad_name, rettype.rettype_name FROM Ret INNER JOIN madret ON madret.ret_id = ret.id INNER JOIN mad ON madret.mad_id = mad.id 
        INNER JOIN rettype ON ret.rettype_id = rettype.id WHERE mad.mad_name LIKE ? OR ret.ret_name LIKE ? OR rettype.rettype_name LIKE ?  ORDER BY ret.created DESC";
                    
               
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // read products with pagination
    public function readPaging($from_record_num, $records_per_page){
  
        // select query
        $query = "SELECT
                    ret.ret_name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                ORDER BY p.created DESC
                LIMIT ?, ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        // return values from database
        return $stmt;
    }

    // used for paging products
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }

    public function controlIfIngredience($ingred) {
        $query="SELECT indhold_name FROM indhold WHERE indhold_name = $ingred;";

        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row.count>0 ) {
            return true;
        }
        return false;
    }

    public function createIngredience($rid, $table, $voltypid ) {
        $indid = getLastPlusOne("indhold") -1;


        //Insert query
        $query="INSERT INTO indhold SET indhold_name=:indhold_name;
            INSERT INTO $table SET volume:volume, ret_id=:$rid, indhold_id=:$indid: volume_type_id=:$voltypid;";


        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->indhold_name=htmlspecialchars(strip_tags($this->indhold_name));
        $this->volume=htmlspecialchars(strip_tags($this->volume));

        if(controlIfIngredience($this->indhold_name)) {
            return false;
        } else {
            //Binding
            $stmt->bindParam(":indhold_name", $this->indhold_name);
            $stmt->bindParam(":volume", $this->volume);

            // execute query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }
        

    }

}

?>