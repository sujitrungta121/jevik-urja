<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends CI_Controller{
	public function __construct(){
        parent::__construct(); 
        $this->load->helper("url");
        $this->load->helper("form");
        //$this->load->library("session");
        $this->load->helper("site");
        ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

    }

    function pay_test(){
        session_start();
        $this->load->library("Razorpay");
        $orderData = [
            'receipt'         => 3456,
            'amount'          => 2000 * 100, // 2000 rupees in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];
        $api=$this->razorpay->load();
        $razorpayOrder = $api->order->create($orderData);
        $keyId=$this->razorpay->get_key();
        //echo "key : ".$keyId; exit();
        $displayCurrency=$this->razorpay->get_default_currency();
        $razorpayOrderId = $razorpayOrder['id'];
        $_SESSION['razorpay_order_id'] = $razorpayOrderId;
        //$this->session->set_userdata("razorpay_order_id",$razorpayOrderId);
        $displayAmount = $amount = $orderData['amount'];
        //$checkout = 'automatic';
        $data['displayCurrency']  = $displayCurrency;
        $data['displayAmount']    = $displayAmount;
        $data["key"]=$keyId;
        $data["amount"]=$displayAmount;
        $data["order_id"]=$razorpayOrderId;
        //$data["checkout_method"]=$checkout;
        $this->load->view("test/payment_page",array("data"=>$data));

    }


    function submitsms($host,$user,$authkey,$sender,$text,$mobile,$rpt){
        $smsurl="http://zipping.vispl.in/vapi/pushsms?user=GOMARTOTP&authkey=010GRXb101xYNkk14CZ9&sender=GOMART&mobile=7003782339&text=$text&rpt=0";
        //echo "sms url : ".$smsurl;
        $response = file_get_contents($smsurl);
        return $response;
    }

    function smsCheck(){
        $user="GOMARTOTP";
        $authkey="010ni1GP07l7fudOW7DU";
        $mobile="7003782339";
        $text=urlencode("This is Test Message from gomart");
        $sender="GOMART";
        $rpt=0;
        $host="zipping.vispl.in";
        $response = $this->submitsms($host,$user,$authkey,$sender,$text,$mobile,$rpt);    
        echo $response;
    }



}