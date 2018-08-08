<?php
// if the form was submitted
if($_POST){
 
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
 
    // new values
    $messages->bulk_id = $_POST['bulk_id'];
    $messages->outstatus = $_POST['outstatus'];
    $messages->delivery_status = $_POST['delivery_status'];
    $messages->processtime = $_POST['processtime'];
 
    // update
    echo $messages->updateBulk() ? "true" : "false";
}
?>