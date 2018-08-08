<?php
class Account {
 
    // database connection and table name
    private $conn;
    private $table_name = "accounts";
 
    // object properties
    public $id;
    public $orgname;
    public $description;
    public $amount;
    public $ismaster;
    public $type;
    public $organization_id;
 
    public function __construct($db){
        $this->conn = $db;
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
    public function readOneByOrganizationId(){
        $query = "SELECT "
        ."tbl.id,"
        ."tbl.orgname,"
        ."tbl.description,"
        ."tbl.amount,"
        ."tbl.ismaster,"
        ."tbl.type,"
        ."tbl.organization_id"
        ." FROM " . $this->table_name . " AS tbl WHERE  tbl.organization_id = :organization_id and tbl.type = 'SMS'";

        //prepare query for excecution
        $stmt = $this->conn->prepare($query);
    
        $organization_id=htmlspecialchars(strip_tags($this->organization_id));
        $stmt->bindParam(':organization_id', $organization_id);
        $stmt->execute();
        
        $count = $stmt->rowCount();

        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(($count != 0) ? $results : $count);
    }
    public function increaseAccountBalanceByOrgId(){
        try{
 
            // query
            $query = "UPDATE " . $this->table_name ." SET "
            ."amount = amount + 1"
            ." WHERE organization_id=:organization_id AND type='SMS'";
 
            // prepare query for execution
            $stmt = $this->conn->prepare($query);
 
            // sanitize
            $organization_id=htmlspecialchars(strip_tags($this->organization_id));
 
            // bind the parameters
            $stmt->bindParam(':organization_id', $organization_id);
            
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