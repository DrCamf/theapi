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

// make sure data is not empty
if(
    !empty($data->ret_name) &&
    !empty($data->antal) &&
    !empty($data->prep_time) &&
    !empty($data->total_time) &&
    !empty($data->recipe_url) &&
    !empty($data->image_url) &&
    !empty($data->rettype_name) &&
    !empty($data->mad_name) &&
    !empty($data->indhold) 
    
){
  
     // set product property values
     $recipes->retname = $data->retname;
     $recipes->howmany = $data->antal;
     $recipes->preptime = $data->preptime;
     $recipes->time = $data->time;
     $recipes->recipeurl = $data->recipe_url;
     $recipes->imageurl = $data->imageurl;
     $recipes->indhold = $data->indhold;
     $recipes->madname = $data->madname;
     $recipes->rettype = $data->rettype;
     
     
   
     // create the product
     if($recipes->create()){
   
         // set response code - 201 created
         http_response_code(201);
   
         // tell the user
         echo json_encode(array("message" => "Recipe was created."));
     }
   
     // if unable to create the product, tell the user
     else{
   
         // set response code - 503 service unavailable
         http_response_code(503);
   
         // tell the user
         echo json_encode(array("message" => "Unable to create recipe."));
     }
 }
   
 // tell the user data is incomplete
 else{
   
     // set response code - 400 bad request
     http_response_code(400);
   
     // tell the user
     echo json_encode(array("message" => "Unable to create recipe. Data is incomplete."));
 }

?>