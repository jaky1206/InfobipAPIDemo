<?php
// include configuration file
include 'config/core.php';
// include utility file
include 'libs/functions.php';
 
// include the head template
//include_once "layout_head.php";

$infobip_balance = json_decode(curl_check_infobip_balance());
//var_dump($infobip_balance);
//$infobip_balance->balance = 0.00;

$queued_messages = json_decode(curl_post($g_url_list["read_queued_messages"],''));

//var_dump($queued_messages);
$message_string = "";
$bulk_id = guidv4();
$message_count = count($queued_messages);

if ($message_count > 0) {
    foreach($queued_messages as $key=>$queued_message) {
        ($queued_message->intermediate_report == 1) ? $queued_message->intermediate_report = 'true' : $queued_message->intermediate_report = 'false'; 
        ($queued_message->flash == 1) ? $queued_message->flash = 'true' : $queued_message->flash = 'false';
        $queued_message->text = str_replace('"', '\'', $queued_message->text);
        if ($key != ($message_count -  1)) {
            $message_string .= "\r\n\t\t{\r\n\t\t\t\"from\":\"\",\r\n\t\t\t\"destinations\":[\r\n\t\t\t\t{\r\n\t\t\t\t\t\"to\":\"$queued_message->to\",\r\n\t\t\t\t\t\"messageId\":\"$queued_message->message_id\"\r\n\t\t\t\t}\r\n\t\t\t],\r\n\t\t\t\"text\":\"$queued_message->text\",\r\n\t\t\t\"flash\":$queued_message->flash,\r\n\t\t\t\"intermediateReport\":$queued_message->intermediate_report,\r\n\t\t\t\"notifyUrl\":\"$queued_message->notifyUrl\",\r\n\t\t\t\"notifyContentType\":\"$queued_message->notify_content_type\",\r\n\t\t\t\"callbackData\":\"$queued_message->callback_data\",\r\n\t\t\t\"validityPeriod\":$queued_message->validity_period\r\n\t\t},";
        }
        else {
            $message_string .= "\r\n\t\t{\r\n\t\t\t\"from\":\"\",\r\n\t\t\t\"destinations\":[\r\n\t\t\t\t{\r\n\t\t\t\t\t\"to\":\"$queued_message->to\",\r\n\t\t\t\t\t\"messageId\":\"$queued_message->message_id\"\r\n\t\t\t\t}\r\n\t\t\t],\r\n\t\t\t\"text\":\"$queued_message->text\",\r\n\t\t\t\"flash\":$queued_message->flash,\r\n\t\t\t\"intermediateReport\":$queued_message->intermediate_report,\r\n\t\t\t\"notifyUrl\":\"$queued_message->notifyUrl\",\r\n\t\t\t\"notifyContentType\":\"$queued_message->notify_content_type\",\r\n\t\t\t\"callbackData\":\"$queued_message->callback_data\",\r\n\t\t\t\"validityPeriod\":$queued_message->validity_period\r\n\t\t}";
        }
        $result = json_decode(curl_post($g_url_list["update_message_by_message_id"],array(
            'bulk_id' => $bulk_id,
            'message_id' => $queued_message->message_id,
            'outstatus' => 'PROCESSING',
            'delivery_status' => 'PROCESSING',
            'outtime' => date('Y-m-d H:i:s'),
            'processtime' => date('Y-m-d H:i:s')
        )));
        //var_dump($result);
    }
    $message_string = "{\r\n\t\"bulkId\":\"$bulk_id\",\r\n\t\"messages\":[".$message_string."\r\n\t]\r\n}";

    if($infobip_balance->balance > 0) {
        $response = curl_push_to_infobip_api($message_string);
        if (strtoupper($g_environment) == "DEVELOPMENT") {
            $logFile = "log_file.txt";
            $fh = fopen($logFile, 'a') or die("can't open file");
            fwrite($fh, "\n");
            fwrite($fh, "/***********************************************************************************/");
            fwrite($fh, "\n");
            fwrite($fh, date('Y-m-d H:i:s'));
            fwrite($fh, "\n");
            fwrite($fh, $response);
            fclose($fh);
        }
        $response = json_decode($response);
        var_dump($response);

        if (isset($response->requestError)) {
            $result = json_decode(curl_post($g_url_list["update_message_by_bulk_id"],array(
                'bulk_id' => $bulk_id,
                'outstatus' => 'FAILED',
                'delivery_status' => $response->requestError->serviceException->messageId,
                'processtime' => date('Y-m-d H:i:s')
            )));
            //var_dump($result);
            foreach($queued_messages as $key=>$queued_message) {
                $result = json_decode(curl_post($g_url_list["inc_acc_balance_by_org_id"],array(
                    'organization_id' => $queued_message->organization_id
                )));
                //var_dump($result);
            }
        }
        
        //$response = json_decode(file_get_contents('immediate_response.json'));
        //var_dump($response);
        if (isset($response->bulkId)) {
            foreach($response->messages as $key=>$message) {
                $result = json_decode(curl_post($g_url_list["update_message_by_message_id"],array(
                    'bulk_id' => $response->bulkId,
                    'message_id' => $message->messageId,
                    'outstatus' => 'SENT',
                    'delivery_status' => $message->status->name,
                    'outtime' => date('Y-m-d H:i:s'),
                    'processtime' => date('Y-m-d H:i:s')
                )));
                //var_dump($result);
            }
        }
    }
    else {
        $result = json_decode(curl_post($g_url_list["update_message_by_bulk_id"],array(
            'bulk_id' => $bulk_id,
            'outstatus' => 'FAILED',
            'delivery_status' => 'SYSTEM_ERROR_01',
            'processtime' => date('Y-m-d H:i:s')
        )));
        //var_dump($result);
        foreach($queued_messages as $key=>$queued_message) {
            $result = json_decode(curl_post($g_url_list["inc_acc_balance_by_org_id"],array(
                'organization_id' => $queued_message->organization_id
            )));
            //var_dump($result);
        }
    }
    
}
/*
$result = json_decode(curl_post($g_url_list["read_one_account_by_org_id"],array(
    'organization_id' => '1'
)));
var_dump($result);*/

// page footer
//include_once "layout_foot.php";
?>