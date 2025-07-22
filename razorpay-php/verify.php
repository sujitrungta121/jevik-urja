<?php

require('config.php');

session_start();
//print_r($_POST);
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
$response=array();
$success = true;

$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false)
{
    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_POST['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

if ($success === true)
{


    /*$payment = $api->payment->fetch('pay_29QQoUBi66xm2f');
    $payment->capture(array('amount' => 5000, 'currency' => 'INR'));*/

    $response=array("status"=>"success","msg"=>"Your payment was successful. Payment ID: {$_POST['razorpay_payment_id']}");
    /*$html = "<p>Your payment was successful</p>
             <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";*/

}
else
{
   /* $html = "<p>Your payment failed</p>
             <p>{$error}</p>";*/
    $response=array("status"=>"failed","msg"=>"Your payment failed error : {$error}");
}
//echo $html;
echo json_encode($response);
