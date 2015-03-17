<?php
/**
 * Class to handle Activity with channel and latitude and longitude
 * This class will have CRUD methods for database tables
 *
 * @author Md. Abu Sayem
 * @email sayem@asteriskbd.com 
 */
require_once dirname(__FILE__) . '/dbconnect.php';
class Activity
{

	private $conn;
	private $db;
	private $checkUser=0;
	

	function __construct() {
		
		$this->db = new dbconnect();
		$this->conn = $this->db->connect();

	}

	public function updateLocation(&$username,&$latitude,&$longitude,&$channel)
	{
		
		$this->checkUser = ($this->checkUser($username)) ? 1 : 0 ;

		if(!$this->checkUser){
			$response['status']='fail';
           	$response['data']['code']=1002;
			return $response;
		}

		try {
			$stmt=$this->conn->prepare("UPDATE activity SET latitude=:latitude,longitude=:longitude, channel=:channel, lastactivity=:lastactivity WHERE username=:username");
			$stmt->execute(array(':latitude'=>$latitude, ':longitude'=>$longitude,
								':channel'=>$channel, ':username'=>$username,':lastactivity'=>time()));
			$stmt->closeCursor();

			unset($stmt);

			
			$response['status']='success';
	            

			return $response;
			
		} catch (Exception $e) {
			$this->db->errorLog($e);
			$response['status']='fail';
           	$response['data']['code']=1000;
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
}