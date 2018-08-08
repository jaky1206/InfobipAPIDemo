<?php

$g_base_url = 'http://localhost/infobip_api_demo/';
$g_environment = 'DEVELOPMENT';
//$g_environment = 'PRODUCTION';

if (strtoupper($g_environment) == "DEVELOPMENT") {
    // show error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}
// default time-zone
date_default_timezone_set('Asia/Dhaka');

$g_url_list = array(
    'read_queued_messages' => $g_base_url.'api/read_queued_messages.php',
    'update_message_by_message_id' => $g_base_url.'api/update_message_by_message_id.php',
    'update_message_by_bulk_id' => $g_base_url.'api/update_message_by_bulk_id.php',
    'read_one_account_by_org_id' => $g_base_url.'api/read_one_account_by_org_id.php',
    'inc_acc_balance_by_org_id' => $g_base_url.'api/inc_acc_balance_by_org_id.php'
);

?>