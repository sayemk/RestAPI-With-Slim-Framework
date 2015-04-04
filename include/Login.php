<?php
/**
 * Class to handle Login
 * This class will have CRUD methods for database tables
 *
 * @author Md. Abu Sayem
 * @email sayem@asteriskbd.com 
 */
require_once dirname(__FILE__) . '/dbconnect.php';

class Login
{
	private $conn;
	private $db;
	private $checkUser=0;
	private $checkOldUser=0;

	function __construct() {
		
		$this->db = new dbconnect();
		$this->conn = $this->db->connect();

	}

	public function login(&$username,&$latitude,&$longitude,&$channel,&$userPassword)
	{
		$this->checkUser = ($this->checkUser($username)) ? 1 : 0 ;

		if(!$this->checkUser){
			$response['status']='fail';
           	$response['data']['code']=1002;
			return $response;
		}

		if(!$this->checkPassword($username,$userPassword)){
			$response['status']='fail';
           	$response['data']['code']=1004;
			return $response;
		}

		$this->checkOldUser = ($this->checkOldUser($username)) ? 1 : 0 ;

		if($this->checkOldUser)
		{
			try {
				$stmt=$this->conn->prepare("UPDATE activity SET latitude=:latitude,longitude=:longitude, channel=:channel, lastactivity=:lastactivity WHERE username=:username");
				$stmt->execute(array(':latitude'=>$latitude, ':longitude'=>$longitude,
									':channel'=>$channel, ':username'=>$username,':lastactivity'=>time()));
				$stmt->closeCursor();

				unset($stmt);

				//Get The Password
				// $stmt=$this->conn->prepare("SELECT password FROM users WHERE username=:username");
				// $stmt->bindParam(":username",$username)
				// $result=$stmt->execute();
				$response['status']='success';
	            
				
				$sql="SELECT secret FROM users WHERE username='$username'";
				foreach ($this->conn->query($sql)as $row) {
					$response['data']['secret']=$row['secret'];
				}

				//echo "string";
				// exit($response);
				return $response;
				
			} catch (Exception $e) {
				$this->db->errorLog($e);
				$response['status']='fail';
           		$response['data']['code']=1000;
			}
		}else{
			try {

				$stmt=$this->conn->prepare("INSERT INTO activity(username,latitude,longitude,channel,lastactivity) 
					VALUES(:username,:latitude,:longitude,:channel,:lastactivity)");

				$stmt->execute(array(':username'=>$username, ':latitude'=>$latitude,':longitude'=>$longitude, ':channel'=>$channel, 'lastactivity'=>time()));

				$response['status']='success';

				$sql="SELECT secret FROM users WHERE username='$username'";
				foreach ($this->conn->query($sql)as $row) {
					$response['data']['secret']=$row['secret'];
				}
				//exit($response);
				return $response;

			} catch (Exception $e) {
				$this->db->errorLog($e);
				$response['status']='fail';
           		$response['data']['code']=1000;
	            return $response;
			}
		}
	}

	public function checkPassword($username='',$password)
	{
		try {
			$stmt=$this->conn->prepare("SELECT user_password FROM users WHERE username=:username");
			$stmt->bindParam(':username',$username);

			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			//print_r($result);
			if($result['user_password']==md5($password)){
				return True;
			}else{
				return False;
			}

		} catch (Exception $e) {
			$this->db->errorLog($e);
        	return false;
		}
	}
	public function checkUser($username='')
	{
		try {
			$stmt=$this->conn->prepare("SELECT COUNT(*) FROM users WHERE username=:username");
			$stmt->bindParam(':username',$username);
			$stmt->execute();
			$number=$stmt->fetchColumn();
			$stmt->closeCursor();

			if($number>0){
				return True;
			}else{
				return False;
			}

		} catch (Exception $e) {
			$this->db->errorLog($e);
        	return false;
		}
	}

	public function checkOldUser($username='')
	{
		try {
			$stmt=$this->conn->prepare("SELECT COUNT(*) FROM activity WHERE username=:username");
			$stmt->bindParam(':username',$username);
			$stmt->execute();
			$number=$stmt->fetchColumn();
			$stmt->closeCursor();
			//exit($number);
			if($number>0){
				return True;
			}else{
				return False;
			}

		} catch (Exception $e) {
			$this->db->errorLog($e);
        	return False;
		}
	}

}