<?php

require_once APPPATH.'third_party/razorpay-php/Razorpay.php';
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
//require_once APPPATH.'third_party/razorpay-php/src/Errors/SignatureVerificationError.php';

class Razorpay{
	private $keyId = 'rzp_test_hPN7NsttwMm26F';
	private $keySecret = '3StoPgsvrUYtnkp5QTslRYRr';
   /* private $keyId = 'rzp_live_TrX0GrwNVHy43n';
    private $keySecret = 'IavwEmiNJC5FpfHoojVqCgKc';*/
	private $displayCurrency = 'INR';
    public function __construct(){
        log_message('Debug', 'Razorpay Api class is loaded.');
    }

    function get_key(){
    	return $this->keyId;
    }

    function get_secret(){
        return $this->keySecret;
    }

    function get_default_currency(){
    	return $this->displayCurrency;
    }
    public function load(){
    	
        $api = new Api($this->keyId, $this->keySecret);
        return $api;
    }
}
?>