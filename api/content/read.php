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

// read products will be here

?>