<?php
/**
 * Class to handle Login
 * This class will have CRUD methods for database tables
 *
 * @author Md. Abu Sayem
 * @email sayem@asteriskbd.com 
 */
require_once dirname(__FILE__) . '/dbconnect.php';

class Dialplan
{
	private $conn;
	private $db;
	
	function __construct() {
		
		$this->db = new dbconnect();
		$this->conn = $this->db->connect();

	}

	public function getChannel($username)
	{
		//echo "$username";
		$stmt=$this->conn->prepare("SELECT latitude,longitude,channel FROM activity WHERE username=:username");
		$stmt->execute(array(':username'=>$username));
		$caller=$stmt->fetch(PDO::FETCH_NUM);
		//print_r($caller);

		//exit('Test');
		return $caller;
	}

	public function getAllUser($username,$channel)
	{
		$stmt=$this->conn->prepare("SELECT latitude,longitude,username FROM activity WHERE channel=:channel AND lastactivity >:lastactivity AND username NOT LIKE :username");
		$stmt->execute(array(':channel'=>$channel,':lastactivity'=>time()-300,':username'=>$username));
		$neighbours=$stmt->fetchAll(PDO::FETCH_ASSOC);
		//print_r($neighbours);
		// print_r($username);
		// print_r($channel);
		// exit('Test');
		return $neighbours;
	}

	public function distance($lat1, $lng1, $lat2, $lng2,$channel)
    {
	    $pi80 = M_PI / 180;
	    $lat1 *= $pi80;
	    $lng1 *= $pi80;
	    $lat2 *= $pi80;
	    $lng2 *= $pi80;
	     
	    $r = 6372.797; // mean radius of Earth in km
	    $dlat = $lat2 - $lat1;
	    $dlng = $lng2 - $lng1;
	    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
	    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	    $km= $r * $c;
	   	//echo $km.' ';
	    if ($km>$channel) {
	    	return false;
	    } else {
	    	return true;
	    }
    }

    public function getSipFriend($username='')		
    {
    	
    	if ($username=='') {
    		return 0;
    	} else {
    		$sipFriend='';
    		$caller=$this->getChannel($username);
    		//print_r($caller);
    		$allUsers=$this->getAllUser($username,$caller[2]);
    		//print_r($allUsers);

    		foreach ($allUsers as $user) {
    			if($this->distance($caller[0],$caller[1],$user['latitude'],$user['longitude'],$caller[2]))
    			{
    				$sipFriend.='SIP/'.$user['username'].'&';

    			}
    			//echo $user['username'];

    		}
    		return substr($sipFriend, 0,-1);


    	}
    	
    }

}