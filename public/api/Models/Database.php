<?php


namespace Models;
use PDO;

class Database
{

    
    private $dbh;
    public $pdo; 
    private $stmt;
    private $error; 
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $db_user = DB_USER;
    private $db_password = DB_PASSWORD;

    function __construct() {
        
       $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8;port=3306";

       try{
            $this->dbh = new PDO($dsn, $this->db_user, $this->db_password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch(PDOException $e){
            
            $this->error = $e->getMessage();
            echo $this->error;
            error_log($e->getMessage());
        }
  
    }


     // Prepare statement with query
    public function query($sql){

        $this->stmt = $this->dbh->prepare($sql);

    }

    public function bind($param, $value, $type = null){

        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    // Executes statements
    public function execute() {
        
        try {
            $this->stmt->execute();
            return true;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            $this->error = $e->getMessage();
            throw $e;
        }
        
    }

    // Get result set as an array of objects
    public function resultSet(): array {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);       
    }

    // Get single result as object
    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);   
    }

}

