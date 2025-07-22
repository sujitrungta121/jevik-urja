<?php
require APPPATH.'controllers/MainController.php';
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends MainController{


    public function __construct(){
        parent::__construct(); 
        $this->load->library("session");
        $this->load->helper("site");
        //exit();


    }

    function login_form_partials(){
         $this->load->helper("form");
        $this->load->view("frontend/login_modal_partials/login_form");
    }
    function forgot_pass($step=1){
        $this->load->helper("form");
        $this->load->library("session");
        $this->load->view("frontend/login_modal_partials/forgot_pass_step".$step);
    }

    function cat_list_recursive(){
        $this->load->model("Api_model","am");
        $data=$this->am->category_list_recursive(0);
        echo "<pre>";
        print_r($data);

    }

    function index($output="html"){
	// exit(); // Commented out to allow the page to load
        try {
    	    $this->load->helper("form");
    	    $this->load->model("Api_model","am");
            $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];
            $data["category_list_recursive"]=$this->am->category_list_recursive(0);
    	    $data["category_list"]=$category_list=$this->am->category_list(array("parent_id"=>0));
    	$array=array();
		$count=0;
		foreach($category_list as $list){
			$array[$count]=(array)$list;
            $category_ids=$this->am->get_category_chain($list->category_id);
            $category_ids[]=$list->category_id;
			$array[$count]["products"]=$this->am->product_list(array("category_id"=>$category_ids,"limit"=>12,"offset"=>0));
			$count++;
		}

		$data["category_data_list"]=$array;
        /*echo "<pre>";
        print_r($data); exit();*/

    	$data["banner_list"]=$this->am->banner_list();
    	$data["brand_list"]=$this->am->brand_list(array("frontend_display"=>1));
        if($output=="array"){
            echo "<pre>";
            print_r($data);
            exit();
        }
    	
    	$this->load->view("frontend/index",$data);
        } catch (Exception $e) {
            // If there's an error with database or models, show a simple message
            echo "Welcome to KoCart! The application is running but some features may not be available due to: " . $e->getMessage();
        }
    }


    function product($slug_name){
        $this->load->helper("form");
    	$this->load->model("Api_model","am");
        $pr=$this->am->product_details_by_slug_name($slug_name);
        $id=$pr->id;
         $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];
        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
    	$data["banner_list"]=$this->am->banner_list();
    	$data["brand_list"]=$this->am->brand_list();
    	$data["category_list"]=$this->am->category_list(array("parent_id"=>0));
    	$data["details"]=$details=$this->am->product_details($id);
       /* echo "<pre>";
        print_r($details); exit();*/
    	$data["product_list"]=$this->am->product_list(array("category_id"=>$details["category_id"],"limit"=>10,"offset"=>0,"exclude_product_id"=>$details["product_id"]));
    	/*echo "<pre>";
    	print_r($details); exit();*/
    	$this->load->view("frontend/product",$data);
    }

    function checkout(){
         $this->load->helper("form");
        $this->load->model("Api_model","am");
         $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];
        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
        $data["banner_list"]=$this->am->banner_list();
        $data["brand_list"]=$this->am->brand_list();
        $data["category_list"]=$this->am->category_list(array("parent_id"=>0));
        $data["state_list"]=$this->am->state_list(101);
        $data["delivery_timming_list"]=$this->am->delivery_timming_slot_list();
        $this->load->view("frontend/checkout",$data);
    }

    function brand_list($slug_name){
       $this->load->model("Api_model","am");
       $details=$this->am->specific_brand_details($slug_name,"b.slug_name");
       $this->product_list("brand",$details["id"]);   
    }

    function category_list($slug_name){
        $this->load->model("Api_model","am");
        $details=$this->am->specific_category_data($slug_name,"c.slug_name");
        $this->product_list("category",$details["id"]);
    }



    function product_list($type,$id){
        $data["type"]=$type;
        $data["id"]=$id;
        $this->load->model("Api_model","am");
         $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];
        if($type=="category"){
            $data["filter_category_data"]=$this->am->category_list_recursive($id);
            $data["main_category_data"]=$this->am->specific_category_data($id);
             $this->am->reset_category_list_recursive();
            
        }
        if($type=="brand"){
            $data["selected_brand_data"]=$this->am->specific_brand_details($id);
        }
        /*$search_params=$_GET;
        if(isset($search_params["category_id"])){
            $data["filter_category_data"]=$this->am->category_list_recursive($search_params["category_id"]);
            $data["main_category_data"]=$this->am->specific_category_data($search_params["category_id"]);
        }
*/
       /* echo "<pre>";
        print_r($data);exit();*/
        $this->load->helper("form");
        $this->load->helper("site");
        $array=get_Url_query_string();
        //echo $array; exit();
       
        $data["brand_list"]=$this->am->brand_list();
        $data["category_list"]=$this->am->category_list(array("parent_id"=>0));
        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
        $this->load->view("frontend/product-list",$data);
    }

    function before_order_complete(){
        //print_r($_POST); exit();
        if(!$this->input->post("address_id")){
            exit("Invalid Data Provided");
        }
        $this->load->helper("form");
        $this->load->library("session");
        $this->load->library("Razorpay");
        $address_id=$this->input->post("address_id");
        $payment_mode=$this->input->post("payment_mode");
        $payment_mode=explode(",",$payment_mode);
        $payment_amt=$this->input->post("payment_amt");
        $payment_amt=explode(",",$payment_amt);
        $delivery_timming=$this->input->post("delivery_timming");

        $grand_total=$this->input->post("grand_total");
        $this->session->set_userdata("order_address_id",$address_id);
        $this->session->set_userdata("order_payment_mode",$payment_mode);
        $this->session->set_userdata("order_payment_amt",$payment_amt);
        $this->session->set_userdata("grand_total",$grand_total);
        $this->session->set_userdata("delivery_timming",$delivery_timming);
        //print_r($payment_mode); exit();
        if(!in_array(1,$payment_mode) && !in_array(3,$payment_mode)){
            redirect("home/complete_order");
            exit();
        }

        if(sizeof($payment_mode)==1 && $payment_mode[0]=="1"){
            redirect("home/complete_order");
            exit();
        }
        
        $user_id=$this->session->userdata("user_id");
        $this->load->model("Api_model","am");
        $data["cartDetails"]=$cartDetails=$this->am->cartDetails($user_id);
        $this->am->refreshCart($cartDetails->order_id);
        $data["cartDetails"]=$cartDetails=$this->am->cartDetails($user_id);
        /*echo "payment Amount : ".array_sum($payment_amt);
        echo "<br/>order Amount : ". $cartDetails->grand_total;*/
        if(array_sum($payment_amt)!= $cartDetails->grand_total){
            $this->session->set_flashdata("msg","Unable To Proceed The Order... Please Retry Now");
            redirect("Home/checkout");
            exit("Payment Amount And order Amount Missmatched. Unable To Proceed Further");

        }
       /* echo "<pre>";
        print_r($data); exit();*/
        if(sizeof($payment_amt)==2){
            $online_pay_amt=$payment_amt[1];
        }else{
            $online_pay_amt=$payment_amt[0];
        }
        $orderData = [
            'receipt'         => $cartDetails->order_id,
            'amount'          => $online_pay_amt * 100, // 2000 rupees in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];
        $api=$this->razorpay->load();
        $razorpayOrder = $api->order->create($orderData);
        $keyId=$this->razorpay->get_key();
        //echo "key : ".$keyId; exit();
        $displayCurrency=$this->razorpay->get_default_currency();
        $razorpayOrderId = $razorpayOrder['id'];
        $this->am->update_order_trans_id($razorpayOrderId,$cartDetails->order_id);
        $this->am->init_order_payment($cartDetails->order_id,$payment_amt,$payment_mode);
        //$_SESSION['razorpay_order_id'] = $razorpayOrderId;
        $this->session->set_userdata("razorpay_order_id", $razorpayOrderId);
        $this->session->set_userdata("trans_route","order");
        //$this->session->set_userdata("razorpay_order_id",$razorpayOrderId);
        $displayAmount = $amount = $orderData['amount'];
        //$checkout = 'automatic';
        $data['displayCurrency']  = $displayCurrency;
        $data['displayAmount']    = $displayAmount;
        $data["key"]=$keyId;
        $data["amount"]=$displayAmount;
        $data["order_id"]=$razorpayOrderId;
        //$data["checkout_method"]=$checkout;
        $new_data=$data;
        $data=array();
        $data["data"]=$new_data;

        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
        $data["banner_list"]=$this->am->banner_list();
        $data["brand_list"]=$this->am->brand_list();
        $data["category_list"]=$this->am->category_list(array("parent_id"=>0));
         $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];

        $this->load->view("frontend/payment-gateway",$data);
    }

    function verify_payment(){
        //exit("dfasdfasdf");
        $this->load->library("session");
        $razorpay_payment_id=$this->input->post("razorpay_payment_id");
        $razorpay_signature=$this->input->post("razorpay_signature");
        $razorpay_order_id=$this->session->userdata("razorpay_order_id");

        $trans_route=$this->session->userdata("trans_route");
        $handle = curl_init();
 
        $url =base_url("razorpay-php/verify.php");
         
        // Array with the fields names and values.
        // The field names should match the field names in the form.
         
        $postData = array(
          'razorpay_payment_id' => $razorpay_payment_id,
          'razorpay_signature'  => $razorpay_signature,
          'razorpay_order_id'   => $razorpay_order_id
        );
         
        curl_setopt_array($handle,
          array(
             CURLOPT_URL => $url,
             // Enable the post response.
            CURLOPT_POST       => true,
            // The data to transfer with the response.
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER     => true,
          )
        );
         
        $data = curl_exec($handle);
         
        curl_close($handle);
         
        $response=json_decode($data);
        //print_r($response);
        if($response->status=="success"){
           // echo "payment done successfully";
             $this->load->library("Razorpay");
             $this->load->model("Api_model","am");

             $user_id=$this->session->userdata("user_id");
            if($trans_route=="order"){
                $cartDetails=$this->am->cartDetails($user_id);
                $payDetails=$this->am->order_payment_details($cartDetails->order_id,3);
                $trans_amt=$payDetails->amt;

            }elseif($trans_route=="wallet"){
                $transLog=$this->am->trans_log($razorpay_order_id);
                $trans_amt=$transLog["in_amt"];
            }
           // echo "Trans Amount : ".$trans_amt;
             $api=$this->razorpay->load();
             $payment = $api->payment->fetch($razorpay_payment_id);
            /* echo "<pre>";
             print_r($payment);
             exit();*/

             if($payment->amount!=$trans_amt*100){
                exit("Payment Amount mismatched");
                //echo $payment->amount;
                 //print_r($payment->notes);
             }

            if($trans_route=="order"){
                $this->complete_order();
            }elseif($trans_route=="wallet"){
                $this->am->save_wallet_balance_add($transLog["wallet_id"]);
                redirect("Home/my_profile");
            }
            
            //redirect("Home/complete_order");
        }else{
            echo "payment failed";
        }
    }

    function complete_order(){
        /*echo "<pre>";
        print_r($_POST);*/ //exit();
         $this->load->library("session");
        $address_id=$this->session->userdata("order_address_id");
        $payment_mode=$this->session->userdata("order_payment_mode");
        if(sizeof($payment_mode)>1){
            if(in_array(1,$payment_mode) && in_array(2,$payment_mode)){
                $payment_mode_in_order=4;
            }
            if(in_array(1,$payment_mode) && in_array(3,$payment_mode)){
                $payment_mode_in_order=5;
            }
        }else{
            $payment_mode_in_order=$payment_mode[0];
        }
        


        $grand_total=$this->session->userdata("grand_total");
        $delivery_timming=$this->session->userdata("delivery_timming");



        /*echo "address id : ".$address_id;
        echo "delivery timming : ".$delivery_timming;
        exit();*/
        
        /*$payment_mode=$this->input->post("payment_mode");
        $address_id=$this->input->post("address_id");*/
       
        $user_id=$this->session->userdata("user_id");
        $this->load->model("Api_model","am");
        if($payment_mode==1){
             $wallet_balance=$this->am->user_wallet_balance($user_id);
             if($wallet_balance<$grand_total){
                exit("Wallet balance Must be equals or more than payment amount");
             }
        }
        $order_id=$this->am->complete_order($payment_mode,$payment_mode_in_order,$address_id,$delivery_timming,$user_id);
        if($order_id){
            $order_details=$this->am->orderDetailsById($order_id);
            $user_details=$this->am->user_details($user_id);
            $this->load->library("Sms");
           /* $text="Order Placed Successfully. Order Id : ".$order_id.". Your Order Will be Delivered On ".$order_details["expected_delivery_date"];*/
            $text="Your order in GOMART (Order Id:".$order_id.") has been successfully placed, payment method : ".$order_details['payment_mode_name']." and amount: ".$order_details['grand_total']." .It will be delivered on ".$order_details['expected_delivery_date']." between ".$order_details['delivery_timming_name'].".!!! Thank you for choosing Gomart Have a Nice Day !!!";
            $this->sms->send_sms($order_details["mobile_no"],$text);
            $text="New Order of Rs.".$order_details["grand_total"]." Received. Payment Method ".$order_details["payment_mode_name"].". Scheduled Delivery Date ".$order_details["expected_delivery_date"];
            $this->sms->send_sms(SITE_OWNERS_DEFAULT_MOBILE_NO,$text);

           $this->session->set_flashdata("msg","<h1>Your Order Has Been Placed Successfully.</h1><h3>Your Order Id is ".$order_id."</h3>");
           $this->session->set_userdata("order_address_id","");
            $this->session->set_userdata("order_payment_mode","");
            $this->session->set_userdata("grand_total","");
        }else{
           $this->session->set_flashdata("msg","<h2>Unable To Place The Order .</h2>Please Retry");
        }
         redirect("Home/info");
    }

    function info(){
        $this->load->helper("form");
        $this->load->model("Api_model","am");
         $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];
        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
        $data["banner_list"]=$this->am->banner_list();
        $data["brand_list"]=$this->am->brand_list();
        $data["category_list"]=$this->am->category_list(array("parent_id"=>0));
        $this->load->view("frontend/info",$data);
    }

    function order_succcess($order_id){
        $this->load->helper("form");
        $this->load->model("Api_model","am");
         $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];
        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
        $data["banner_list"]=$this->am->banner_list();
        $data["brand_list"]=$this->am->brand_list();
        $data["category_list"]=$this->am->category_list(array("parent_id"=>0));
        $data["order_details"]=$this->am->orderDetailsById($order_id);
        $this->load->view("frontend/order_success",$data);
    }

    function page($slug_name){
        $this->load->helper("form");
        $this->load->model("Api_model","am");
        $this->load->model("Page_model","pm");
         $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];

        $data["details"]=$this->pm->page_data_by_slug_name($slug_name);
        


        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
        //$data["banner_list"]=$this->am->banner_list();
        $data["brand_list"]=$this->am->brand_list();
        $data["category_list"]=$this->am->category_list(array("parent_id"=>0));

        $this->load->view("frontend/page",$data);
    }

    function myOrder(){
        $this->load->helper("form");
        $this->load->model("Api_model","am");
         $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];
        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
        $data["banner_list"]=$this->am->banner_list();
        $data["brand_list"]=$this->am->brand_list();
        $data["category_list"]=$this->am->category_list(array("parent_id"=>0));
        $this->load->view("frontend/myorder",$data);
    }

    function track_order($order_id){
        $this->load->helper("form");
        $this->load->model("Api_model","am");
         $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];
        $data["order_id"]=$order_id;
        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
        $data["banner_list"]=$this->am->banner_list();
        $data["brand_list"]=$this->am->brand_list();
        $data["category_list"]=$this->am->category_list(array("parent_id"=>0));
        $this->load->view("frontend/trackorder",$data);
    }

    function my_profile(){
        $this->load->helper("form");
        $this->load->model("Api_model","am");
         $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];
        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
        $data["banner_list"]=$this->am->banner_list();
        $data["brand_list"]=$this->am->brand_list();
        $data["category_list"]=$this->am->category_list(array("parent_id"=>0));
        $this->load->view("frontend/myprofile",$data);
    }

    function add_wallet_money(){
        $amt=$this->input->post("amt");
        $receipt="WMA".rand(100000,999999).time();

        //print_r($_POST); exit();
       
        $this->load->library("session");
        
        $user_id=$this->session->userdata("user_id");
        if($user_id==""){
           /* $this->session->set_flashdata('action_message2', 'Your Login session Expired... Please <a data-toggle="modal" data-target="#loginmodal" style="color: blue;">Login</a> To Continue');
            $this->session->set_flashdata('action_message_type2', 'danger');*/
            redirect($_SERVER['HTTP_REFERER']);
            exit();
        }
        $this->load->helper("form");
        $this->load->library("Razorpay");
        $created_on=date("Y-m-d H:i:s");
        $this->load->model("Api_model","am");
       
       /* echo "<pre>";
        print_r($data); exit();*/
        $orderData = [
            'receipt'         => $receipt,
            'amount'          => $amt * 100, // 2000 rupees in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];
        $api=$this->razorpay->load();
        $razorpayOrder = $api->order->create($orderData);
        $keyId=$this->razorpay->get_key();
        //echo "key : ".$keyId; exit();
        $displayCurrency=$this->razorpay->get_default_currency();
        $razorpayOrderId = $razorpayOrder['id'];
         $this->am->init_wallet_balance_add($razorpayOrderId,$amt,$receipt,$user_id,$created_on);
        //$_SESSION['razorpay_order_id'] = $razorpayOrderId;
        $this->session->set_userdata("razorpay_order_id", $razorpayOrderId);
        //$this->session->set_userdata("razorpay_order_id",$razorpayOrderId);
        $displayAmount = $amount = $orderData['amount'];
        //$checkout = 'automatic';
        $data['displayCurrency']  = $displayCurrency;
        $data['displayAmount']    = $displayAmount;
        $data["key"]=$keyId;
        $data["amount"]=$displayAmount;
        $data["order_id"]=$razorpayOrderId;
        //$data["checkout_method"]=$checkout;
        $data["cartDetails"]=(object)$this->am->user_details($user_id);
        $data["cartDetails"]->order_id=$receipt;
        $new_data=$data;
        $data=array();
        $data["data"]=$new_data;

        $this->session->set_userdata("trans_route","wallet");



        $data["category_list_recursive"]=$this->am->category_list_recursive(0);
        $data["banner_list"]=$this->am->banner_list();
        $data["brand_list"]=$this->am->brand_list();
        $data["category_list"]=$this->am->category_list(array("parent_id"=>0));
        $data["additional"]["scrolling_text"]=$this->am->page_data(15)["description"];

        $this->load->view("frontend/payment-gateway",$data);
    }

    

    

}