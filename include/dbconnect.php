<?php
 
/**
 * Handling database connection
 *
 * @author Ravi Tamada
 */

class dbconnect {
 
    public $conn;
 
    function connect() {
        try {
            $this->conn = new PDO("mysql:host=localhost;dbname=voipapi", 'root', '');
            return $this->conn;
        } catch (Exception $e) {
            $this->errorLog($e);
            $response['error']=true;
            $response['db_connection']=$e->getMessage();
           // echo json_encode($response);  
        }
       
    }
        
        
    
    public function errorLog($error)
    {
       //Set Timezone
            date_default_timezone_set("Asia/Dhaka");
            //Generate error message
            $message=date("j F, Y, H:m:s a",time()).'  '.$error->getMessage().PHP_EOL.PHP_EOL;    
            //Write to file
            file_put_contents('include/error.log',$message , FILE_APPEND);
    }
 
}
 
?>