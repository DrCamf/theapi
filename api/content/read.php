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

// query products
$stmt = $recipes->read("Salat");
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
  
    // products array
    $products_arr=array();
    $products_arr["records"]=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
  
        $product_item=array(
            "id" => $id,
            "retname" => $retname,
            "antal" => $howmany,
            "prep_time" => $preptime,
            "time" => $totaltime,
            "madname" => $madname,
            "rettype" => $rettype,
            "recipeurl" => $recipeurl,
            "imageurl" => $imageurl,
            "indhold" => $indhold_item=array()
        );
  
        array_push($products_arr["records"], $product_item);
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


?>