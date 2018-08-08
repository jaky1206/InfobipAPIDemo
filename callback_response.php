<?php
// include configuration file
include 'config/core.php';
// include utility file
include 'libs/functions.php';
 
// include the head template
//include_once "layout_head.php";

//$response = json_decode(file_get_contents('notify_url_response.json'));
$response = json_decode(file_get_contents('php://input'));
if (isset($response->results)) {
    foreach($response->results as $key=>$result) {
        //var_dump($result);
        $result = json_decode(curl_post($g_url_list["update_message_by_message_id"],array(
            'bulk_id' => $result->bulkId,
            'message_id' => $result->messageId,
            'outstatus' => 'SENT',
            'delivery_status' => $result->status->name,
            'outtime' => $result->sentAt,
            'processtime' => $result->doneAt
        )));
        var_dump($result);
    }
}

// page footer
//include_once "layout_foot.php";
?>
