<?php 

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/content.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$recipes = new Content($db);

// set ID property of record to read
$recipes->id = isset($_GET['id']) ? $_GET['id'] : die();


// query products
$stmt = $recipes->read("salat", "mad");
$stmtmore = $recipes->readMore("salat", "mad");

$num = $stmt->rowCount();

// check if more than 0 record found
if($recipes->name!=null){
  
    // products array
    $products_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
       
       // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
               
        $product_item=array(
            $id = $row["id"],
            $retname = $row["ret_name"],
            $howmany = $row["antal"],
            $preptime = $row["prep_time"],
            $totaltime = $row["total_time"],
            $recipeurl = $row["recipe_url"],
            $imageurl = $row["image_url"],
            $madname = $row["mad_name"],
            $rettype = $row["rettype_name"],
            $indholditem=array(secondArray($stmtmore,  $id)) 
      
        );
    
        array_push($products_arr, $product_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($products_arr);
} else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No recipes found.")
    );           
}

function secondArray($st, $i) {
    
    $ind = array();

    $t = 0;
    while($row = $st->fetch(PDO::FETCH_ASSOC)) {

        extract($row);
            if($i == $row["ret_id"]){
                $ind[$t] = [$retid = $row["ret_id"],
                $indhold = $row["indhold_name"],
                $volume = $row["volume"],
                $voltype = $row["vol_type_name"]];
            }
  
    $t++;
    }
    return $ind;
}
?>