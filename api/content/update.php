<?php 

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate product object
include_once '../objects/content.php';
  
$database = new Database();
$db = $database->getConnection();
  
$recipes = new Content($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$recipes->retname = $data->retname;
$recipes->howmany = $data->antal;
$recipes->preptime = $data->preptime;
$recipes->total_time = $data->total_time;
$recipes->recipeurl = $data->recipe_url;
$recipes->imageurl = $data->imageurl;
$recipes->indhold = $data->indhold;
$recipes->madname = $data->madname;
$recipes->rettype = $data->rettype;

// update the product
if($recipe->update()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "Recipe was updated."));
}
// if unable to update the product, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update Recipe."));
}


?>