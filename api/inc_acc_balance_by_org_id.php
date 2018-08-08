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

$account->organization_id = $_POST['organization_id'];

// update
echo $account->increaseAccountBalanceByOrgId() ? "true" : "false";
?>