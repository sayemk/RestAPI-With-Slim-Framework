<?php
/**
 * Class to handle Registration
 * This class will have CRUD methods for database tables
 *
 * @author Md. Abu Sayem
 * @email sayem@asteriskbd.com 
 */
require_once dirname(__FILE__) . '/dbconnect.php';
class NumberOfUser
{
	private $conn;
	private $db;
	

	function __construct() {
		
		$this->db = new dbconnect();
		$this->conn = $this->db->connect();

	}

	public function getUser(&$neighbours,&$channel)
	{
		try {
			//$time=time()-10000;
			//echo "<pre>". $time;
			$stmt=$this->conn->prepare("SELECT latitude,longitude FROM activity WHERE channel=:channel AND lastactivity >:lastactivity");
			$stmt->execute(array(':channel'=>$channel,':lastactivity'=>time()-300));
			$neighbours=$stmt->fetchAll(PDO::FETCH_NUM);
			return $neighbours;
			//print_r($neighbours);

		} catch (Exception $e) {
			$this->db->errorLog($e);
        	return false;
		}
	}

	public function nOfOnline($username,$latitude,$longitude,$channel)
	{
		try {
			$noOfUser=-1;
			$neighbours=array();

			$this->getUser($neighbours,$channel);
			//calculate the distance

			//print_r($neighbours);
			foreach ($neighbours as $neighbour) {
				if($this->distance($latitude,$longitude,$neighbour[0],$neighbour[1],$channel))
					$noOfUser +=1;
				
			}
			if($noOfUser===-1)
				$noOfUser=0;

			$response['status']='success';
	        $response['data']['online']=$noOfUser;
	        return $response;

		} catch (Exception $e) {
			$this->db->errorLog($e);
			$response['status']='fail';
           	$response['data']['code']=1000;
	        return $response;
		}
		

		//print_r($neighbours);

	}

	//Distance Calculator $lat1 and $lng1 is the users latitude and longitude
	// $lat2 and lng1 is the neighbours latitude and longitude
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
}