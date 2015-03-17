<?php
/**
 * Class to handle Registration
 * This class will have CRUD methods for database tables
 *
 * @author Md. Abu Sayem
 * @email sayem@asteriskbd.com 
 */
require_once dirname(__FILE__) . '/dbconnect.php';

class Register
{
	
	
	private $conn;
	private $db;
	private $uniqueUser=0;
	private $uniqueEmail=0;

	function __construct() {
		
		$this->db = new dbconnect();
		$this->conn = $this->db->connect();

	}

	public function passwordHelper($length=5)
	{
		$chars = "#$%!@abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars),0,$length);
	}

	public function register($username, $email)
	{
		if ($this->checkUserName($username)) {
			$this->uniqueUser=1;
		} else {
			//$response['username']='Username already Exist';
		}

		if ($this->checkEmail($email)) {
			$this->uniqueEmail=1;
		} else {
			//$response['email']='Email address is belong to another user';
		}
		
		if ($this->uniqueUser && $this->uniqueEmail) {
			
			try {

				//Get Random Password
				$password=$this->passwordHelper(5);
				$stmt = $this->conn->prepare("INSERT INTO users(username,email,secret,r_time) 
											VALUES(:username,:email,:secret,:r_time)");
	           
	            $result = $stmt->execute(array(':username'=>$username, ':email'=>$email,":secret"=>$password, 'r_time'=>time()));
	            $stmt->closeCursor();

	            //Call the Sip Register Method
	            $this->writeSip($username,$password);
	            $response['status']='success';
	            $response['data']['secret']=$password;
	           

	            return $response;

			} catch (Exception $e) {
				
				$this->db->errorLog($e);
				$response['status']='fail';
           		$response['data']['code']=1000;

	            return $response;
			}
		} else {
			$response['status']='fail';
			$response['data']['code']=1003;
	        return $response;
		}
		

		
	}
	/*
	*Function for check username's uniqueness
	*input=username
	*Output=Boolean
	*/
	public function checkUserName($username='')
	{
		if (is_null($username)) {
			return false;
		} else {
			try {
				$stmt=$this->conn->prepare("SELECT COUNT(*) FROM users WHERE username=:username");
				$stmt->bindParam(':username',$username);
				$stmt->execute();
				$number=$stmt->fetchColumn();
				$stmt->closeCursor();
				if($number>0){
					return false;
				}else{
					return true;
				}

			} catch (Exception $e) {
				$this->db->errorLog($e);
            	return false;
			}
		}
		
	}
	/*
	*Function for check Email's uniqueness
	*input=Email
	*Output=Boolean
	*/
	public function checkEmail($email='')
	{
		if (is_null($email)) {
			return false;
		} else {
			try {
				$stmt=$this->conn->prepare("SELECT COUNT(*) FROM users WHERE email=:email");
				$stmt->bindParam(':email',$email);
				$stmt->execute();
				$number=$stmt->fetchColumn();
				$stmt->closeCursor();
				if($number>0){
					return false;
				}else{
					return true;
				}

			} catch (Exception $e) {
				$this->db->errorLog($e);
            	return false;
			}
		}
		
	}

	public function writeSip($username,$secret)
	{
		try {
			$stringData=PHP_EOL.'[general]'.PHP_EOL.'bindport=5060'.PHP_EOL.'disallow=all'.PHP_EOL.'allow=g729'.PHP_EOL.'allow=g723'.PHP_EOL.'allow=gsm'.PHP_EOL.'jbenable=yes'.PHP_EOL.'jbmaxsize=200'.PHP_EOL.'jbforce=no'.PHP_EOL.'jbimpl=fixed'.PHP_EOL.'jbresyncthreshold=1000'.PHP_EOL.'jblog=no'.PHP_EOL.'defaultexpiry=300'.PHP_EOL.'minexpiry=60'.PHP_EOL.'maxexpiry=600'.PHP_EOL.'registertimeout=5'.PHP_EOL.'notifyhold=yes'.PHP_EOL.'notifyringing=yes'.PHP_EOL.'checkmwi=10'.PHP_EOL.'srvlookup=no'.PHP_EOL.'allowguest=no'.PHP_EOL.'registerattempts=2'.PHP_EOL.'g726nonstandard=no'.PHP_EOL.'t38pt_udptl=no'.PHP_EOL.'videosupprt=no'.PHP_EOL.'maxcallbitrate=384'.PHP_EOL.'canreinvite=no'.PHP_EOL.'rtpholdtimeout=10'.PHP_EOL.'rtpkeepalive=0'.PHP_EOL.'rtptimeout=30'.PHP_EOL.'nat=yes'.PHP_EOL.'useragent=sayem@asteriskbd.com'."\r\n\n";
	
			$stmt=$this->conn->prepare("SELECT username,secret FROM users");
			$stmt->execute();
			$sipUsers=$stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($sipUsers as $sip) {
				$stringData.=PHP_EOL."[".$sip['username']."]".PHP_EOL."host=dynamic".PHP_EOL. "port=5060".PHP_EOL."username=".$sip['username'].PHP_EOL."context=voipapi".PHP_EOL."secret=".$sip['secret'].PHP_EOL."call-limit=1".PHP_EOL."type=friend".PHP_EOL."insecure=port,invite".PHP_EOL.'qualify=yes'.PHP_EOL;

			}

			$this->writeSipFile($stringData);
		
		} catch (Exception $e) {
			$this->db->errorLog($e);
		}
	}

	

	public function writeSipFile($data)
	{
		$mySip = 'sip.conf';
   		$fo = fopen($mySip, 'w+') or die("Can't Open file");
    	fwrite($fo, $data);
	 	fclose($fo);

	 	//Call Sip Reloader

	 	$this->sipReload();
	  
	}

	public function sipReload()
	{
		$manager_host = "127.0.0.1";
		$manager_user = "voipapi";
		$manager_pass = "wwwrest";

		/* Default Port */
		$manager_port = "5038";

		/* Connection timeout */
		$manager_connection_timeout = 30;



		/* Connect to the manager */
		$fp = fsockopen($manager_host, $manager_port, $errno, $errstr, $manager_connection_timeout);
		if (!$fp) {
			return 0;   
		} else {

		    $login = "Action: login\r\n";
		    $login .= "Username: $manager_user\r\n";
		    $login .= "Secret: $manager_pass\r\n";
		    $login .= "Events: Off\r\n";
		    $login .= "\r\n";
		    fwrite($fp,$login);

		    $manager_version = fgets($fp);

		    $cmd_response = fgets($fp);

		    $response = fgets($fp);

		    $blank_line = fgets($fp);

		    if (substr($response,0,9) == "Message: ") {
		        /* We have got a response */
		        $loginresponse = trim(substr($response,9));
		        if (!$loginresponse == "Authentication Accepted") {
		            //echo "unable to reload: $loginresponse\n";
		            fclose($fp);
		            return 0;;
		            //exit(0);
		        } else {
		           
		            $checkpeer = "Action: Command\r\n";
		            $checkpeer .= "Command:sip reload\r\n";
		            $checkpeer .= "\r\n";
					
		            fwrite($fp,$checkpeer);
		            //echo "Unexpected response1: $fp";
					
		            fclose($fp);
		            return 0;
		            //exit(0);
		        }
	    } else {
	        echo "Unexpected response: $response\n";
	        fclose($fp);
	        return 0;
	        //exit(0);
			
		
		}

	 echo "Unexpected response1: $response\n";
		}
	}

}