<?php
// include core configuration
include_once '../config/core.php';
 
// include database connection
include_once '../config/database.php';
 
// object
include_once '../objects/accounts.php';
 
// class instance
$database = new Database();
$db = $database->getConnection();
$account = new Account($db);
 
// read one
$account->organization_id=$_POST['organization_id'];
$results=$account->readOneByOrganizationId();
 
// output in json format
echo $results;
?>