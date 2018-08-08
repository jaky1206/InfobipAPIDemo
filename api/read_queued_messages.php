<?php
// include core configuration
include_once '../config/core.php';
 
// include database connection
include_once '../config/database.php';
 
// object
include_once '../objects/messages.php';
 
// class instance
$database = new Database();
$db = $database->getConnection();

$messages = new Message($db);
 
// read
$results=$messages->readQueued();
 
// output in json format
echo $results;
?>