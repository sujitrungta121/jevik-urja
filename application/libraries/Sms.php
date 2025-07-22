<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Sms {

	function send_sms($to,$content,$route=4,$sender_id="GOMART") {
		return true;
		$content=urlencode($content);
		$smsurl="http://zipping.vispl.in/vapi/pushsms?user=GOMARTOTP&authkey=010GRXb101xYNkk14CZ9&sender=$sender_id&mobile=$to&text=$content&rpt=0";
        //echo "sms url : ".$smsurl;
        $response = file_get_contents($smsurl);
        return $response;
	}

  //private $api_key="8779AdHNX0i625f16a1daP11";
  /*function send_sms($to,$content,$route=4,$sender_id="GOMART") {
  	$error="";
    //Your authentication key
	$authKey =$this->api_key;
//$authKey="";
//Multiple mobiles numbers separated by comma
$mobileNumber = $to;

//Sender ID,While using route4 sender id should be 6 characters long.
$senderId = $sender_id;

//Your message to send, Add URL encoding here.
$message = urlencode($content);

//Prepare you post parameters
$postData = array(
    'authkey' => $authKey,
    'mobiles' => $mobileNumber,
    'message' => $message,
    'sender' => $senderId,
    'route' => $route
);

//API URL
$url="https://control.msg91.com/sendhttp.php";

// init the resource
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData
    //,CURLOPT_FOLLOWLOCATION => true
));


//Ignore SSL certificate verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


//get response
$output = curl_exec($ch);

//Print error if any
if(curl_errno($ch))
{
    echo 'error:' . curl_error($ch);
	$error="yes";
}

curl_close($ch);
 if($error=="yes"){
 	return false;
 }
 return $output;
  
  
  
  }*/
  
  function balance_check_virtual($virtual_balance,$actual_balance){
  		$auth_key =$this->api_key;
		//Prepare you post parameters
		$postData = array(
			'authkey' => $auth_key,
			'type' => 4
		);
		$url="https://control.msg91.com/api/balance.php";

		// init the resource
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData
			//,CURLOPT_FOLLOWLOCATION => true
		));
		
		
		//Ignore SSL certificate verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		
		//get response
		$output = curl_exec($ch);
		$balance=($virtual_balance-$actual_balance)+$output;
		return $balance;
	
  }
	
}
?>
