<?php
class Message {
 
    // database connection and table name
    private $conn;
    private $table_name = "messages";
 
    // object properties
    public $bulk_id;
    public $from;
    public $to;
    public $message_id;
    public $text;
    public $flash;
    public $transliteration;
    public $language_code;
    public $intermediate_report;
    public $notifyUrl;
    public $notify_content_type;
    public $callback_data;
    public $validity_period;
    public $send_at;
    public $delivery_time_window_from_hour;
    public $delivery_time_window_from_minute;
    public $delivery_time_window_to_hour;
    public $delivery_time_window_to_minute;
    public $delivery_time_window_days;
    public $tracking_track;
    public $tracking_type;
    public $tracking_base_url;
    public $process_key;
    public $org_id;
    public $schedule_id;
    public $instatus;
    public $outstatus;
    public $delivery_status;
    public $service;
    public $intime;
    public $outtime;
    public $processtime;
    public $correlator;
    public $title;
    public $sms_count;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    public function create(){
        try{
 
            // insert query
            $query = "INSERT INTO " . $this->table_name ." SET "
            ."from=:from,"
            ."to=:to,"
            ."message_id=:message_id,"
            ."text=:text,"
            ."organization_id=:org_id,"
            ."schedule_id=:schedule_id,"
            ."instatus=:instatus,"
            ."outstatus=:outstatus,"
            ."delivery_status=:delivery_status,"
            ."service=:service,"
            ."intime=:intime,"
            ."outtime=:outtime,"
            ."processtime=:processtime,"
            ."correlator=:correlator,"
            ."title=:title,"
            ."sms_count=:sms_count";
 
            // prepare query for execution
            $stmt = $this->conn->prepare($query);
 
            // sanitize
            $from=htmlspecialchars(strip_tags($this->from));
            $to=htmlspecialchars(strip_tags($this->to));
            $message_id=htmlspecialchars(strip_tags($this->message_id));
            $text=htmlspecialchars(strip_tags($this->text));
            $org_id=htmlspecialchars(strip_tags($this->org_id));
            $schedule_id=htmlspecialchars(strip_tags($this->schedule_id));
            $instatus=htmlspecialchars(strip_tags($this->instatus));
            $outstatus=htmlspecialchars(strip_tags($this->outstatus));
            $delivery_status=htmlspecialchars(strip_tags($this->delivery_status));
            $service=htmlspecialchars(strip_tags($this->service));
            $intime=htmlspecialchars(strip_tags($this->intime));
            $outtime=htmlspecialchars(strip_tags($this->outtime));
            $processtime=htmlspecialchars(strip_tags($this->processtime));
            $correlator=htmlspecialchars(strip_tags($this->correlator));
            $title=htmlspecialchars(strip_tags($this->title));
            $sms_count=htmlspecialchars(strip_tags($this->sms_count));
 
            // bind the parameters
            $stmt->bindParam(':from', $from);
            $stmt->bindParam(':to', $to);
            $stmt->bindParam(':message_id', $message_id);
            $stmt->bindParam(':text', $text);
            $stmt->bindParam(':org_id', $org_id);
            $stmt->bindParam(':schedule_id', $schedule_id);
            $stmt->bindParam(':instatus', $instatus);
            $stmt->bindParam(':outstatus', $outstatus);
            $stmt->bindParam(':delivery_status', $delivery_status);
            $stmt->bindParam(':service', $service);
            $stmt->bindParam(':intime', $intime);
            $stmt->bindParam(':outtime', $outtime);
            $stmt->bindParam(':processtime', $processtime);
            $stmt->bindParam(':correlator', $correlator);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':sms_count', $sms_count);
            
            // Execute the query
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
 
        }
 
        // show error if any
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }
    public function readAll(){
        try {
            //select all data
            $query = "SELECT * FROM " . $this->table_name;

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $results=$stmt->fetchAll(PDO::FETCH_ASSOC);

            return json_encode($results);
        }
        // show error if any
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }
    public function readQueued(){
        try {
            //select all data
            $query = "( " 
            ."SELECT "
            ."tbl.bulk_id,"
            ."tbl.from,"
            ."tbl.to,"
            ."tbl.message_id,"
            ."tbl.text,"
            ."tbl.flash,"
            ."tbl.transliteration,"
            ."tbl.language_code,"
            ."tbl.intermediate_report,"
            ."tbl.notifyUrl,"
            ."tbl.notify_content_type,"
            ."tbl.callback_data,"
            ."tbl.validity_period,"
            ."tbl.send_at,"
            ."tbl.is_delivery_time_applicable,"
            ."tbl.delivery_time_window_from_hour,"
            ."tbl.delivery_time_window_from_minute,"
            ."tbl.delivery_time_window_to_hour,"
            ."tbl.delivery_time_window_to_minute,"
            ."tbl.delivery_time_window_days,"
            ."tbl.is_tracking_applicable,"
            ."tbl.tracking_track,"
            ."tbl.tracking_type,"
            ."tbl.tracking_base_url,"
            ."tbl.process_key,"
            ."tbl.organization_id,"
            ."tbl.schedule_id,"
            ."tbl.instatus,"
            ."tbl.outstatus,"
            ."tbl.delivery_status,"
            ."tbl.service,"
            ."tbl.intime,"
            ."tbl.outtime,"
            ."tbl.processtime,"
            ."tbl.correlator,"
            ."tbl.title,"
            ."tbl.sms_count"
            ." FROM " . $this->table_name . " as tbl WHERE "
            ."tbl.outstatus = 'QUE' AND "
            ."tbl.outtime <= CURRENT_TIMESTAMP AND "
            ."tbl.title ='OTP' LIMIT 200"
            ." )"
            ." UNION ALL "
            ."( " 
            ."SELECT "
            ."tbl.bulk_id,"
            ."tbl.from,"
            ."tbl.to,"
            ."tbl.message_id,"
            ."tbl.text,"
            ."tbl.flash,"
            ."tbl.transliteration,"
            ."tbl.language_code,"
            ."tbl.intermediate_report,"
            ."tbl.notifyUrl,"
            ."tbl.notify_content_type,"
            ."tbl.callback_data,"
            ."tbl.validity_period,"
            ."tbl.send_at,"
            ."tbl.is_delivery_time_applicable,"
            ."tbl.delivery_time_window_from_hour,"
            ."tbl.delivery_time_window_from_minute,"
            ."tbl.delivery_time_window_to_hour,"
            ."tbl.delivery_time_window_to_minute,"
            ."tbl.delivery_time_window_days,"
            ."tbl.is_tracking_applicable,"
            ."tbl.tracking_track,"
            ."tbl.tracking_type,"
            ."tbl.tracking_base_url,"
            ."tbl.process_key,"
            ."tbl.organization_id,"
            ."tbl.schedule_id,"
            ."tbl.instatus,"
            ."tbl.outstatus,"
            ."tbl.delivery_status,"
            ."tbl.service,"
            ."tbl.intime,"
            ."tbl.outtime,"
            ."tbl.processtime,"
            ."tbl.correlator,"
            ."tbl.title,"
            ."tbl.sms_count"
            ." FROM " . $this->table_name . " as tbl WHERE "
            ."tbl.outstatus = 'QUE' AND "
            ."tbl.outtime <= CURRENT_TIMESTAMP AND "
            ."tbl.title <> 'OTP' LIMIT 800"
            ." )";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $results=$stmt->fetchAll(PDO::FETCH_ASSOC);

            return json_encode($results);
        }
        // show error if any
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }
    public function update(){
        try{
 
            // query
            $query = "UPDATE " . $this->table_name ." SET "
            ."bulk_id=:bulk_id,"
            ."outstatus=:outstatus,"
            ."delivery_status=:delivery_status,"
            ."outtime=:outtime,"
            ."processtime=:processtime"
            ." WHERE message_id=:message_id";
 
            // prepare query for execution
            $stmt = $this->conn->prepare($query);
 
            // sanitize
            $bulk_id=htmlspecialchars(strip_tags($this->bulk_id));
            $message_id=htmlspecialchars(strip_tags($this->message_id));            
            $outstatus=htmlspecialchars(strip_tags($this->outstatus));
            $delivery_status=htmlspecialchars(strip_tags($this->delivery_status));
            $outtime=htmlspecialchars(strip_tags($this->outtime));
            $processtime=htmlspecialchars(strip_tags($this->processtime));
 
            // bind the parameters
            $stmt->bindParam(':bulk_id', $bulk_id);
            $stmt->bindParam(':message_id', $message_id);
            $stmt->bindParam(':outstatus', $outstatus);
            $stmt->bindParam(':delivery_status', $delivery_status);
            $stmt->bindParam(':outtime', $outtime);
            $stmt->bindParam(':processtime', $processtime);
            
            // Execute the query
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
 
        }
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }
    public function updateBulk(){
        try{
 
            // query
            $query = "UPDATE " . $this->table_name ." SET "
            ."outstatus=:outstatus,"
            ."delivery_status=:delivery_status,"
            ."processtime=:processtime"
            ." WHERE bulk_id=:bulk_id";
 
            // prepare query for execution
            $stmt = $this->conn->prepare($query);
 
            // sanitize
            $bulk_id=htmlspecialchars(strip_tags($this->bulk_id));         
            $outstatus=htmlspecialchars(strip_tags($this->outstatus));
            $delivery_status=htmlspecialchars(strip_tags($this->delivery_status));
            $processtime=htmlspecialchars(strip_tags($this->processtime));
 
            // bind the parameters
            $stmt->bindParam(':bulk_id', $bulk_id);
            $stmt->bindParam(':outstatus', $outstatus);
            $stmt->bindParam(':delivery_status', $delivery_status);
            $stmt->bindParam(':processtime', $processtime);
            
            // Execute the query
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
 
        }
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }
}