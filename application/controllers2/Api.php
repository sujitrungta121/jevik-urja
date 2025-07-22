<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends CI_Controller{

	public function __construct(){
        parent::__construct(); 
        $this->load->library("session");
        ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

    }

    function pageContent(){
    	$page_id=$this->input->post("page_id");
    	$this->load->model("Api_model","am");
    	if($this->am->page_exist($page_id)==0){
    		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Page not exist"));
    		exit();
    	}
    	$details=$this->am->page_data($page_id);
    	echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"data fetched successfully","details"=>$details));

    }


	function registration($purpose="form-submit"){
		//exit();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('mobile', 'Mobile No.', 'trim|required');
		//$this->form_validation->set_rules('fcm_token', 'FCM Token', 'trim|required');
		//$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		if($this->form_validation->run()===FALSE){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>validation_errors()));
			exit();
		}
		$referral_code=$this->input->post("referral_code");
		$name=$this->input->post("name");
		$email=$this->input->post("email");
		$mobile_no=$this->input->post("mobile");
		$password=$this->input->post("password");
		$password=password_hash($password, PASSWORD_DEFAULT);
		$fcm=$this->input->post("fcm_token");
		$time=date("Y-m-d H:i:s");
		//echo "i am working ";

		$this->load->model("Api_model","am");
		if($this->am->mobile_exist($mobile_no)>0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Mobile No. Allready Exist"));
			exit();
		}
		if($this->am->email_exist($email)>0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Email Id Allready Exist"));
			exit();
		}

		if($referral_code!=""){
			if($this->am->check_own_referral_code_exist($referral_code)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Invalid Referer Code.. Please Try To Place Valid Referer Code Or Leave It Empty"));
			exit();
			}
		}

		if($purpose=="validation-check"){
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"All The Data Are Validated"));
			exit();
		}

		$id=$this->am->save_registration_data($name,$email,$mobile_no,$password,$referral_code,$time,$fcm);
		if($id>0){
			$device_info=$this->am->specific_user_device_data($id);
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Registration Data Saved Successfully","details"=>array("user_id"=>$device_info->user_id,"token"=>$device_info->access_token)));
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Save Registration_data"));
		}
	}

	function productDetails(){
		$id=$this->input->post("product_id");
		// /echo "product id : ".$id; exit();
    	$this->load->model("Api_model","am");
    	$details=$this->am->product_details($id);
    	//print_r($details); exit();
    	$products=$this->am->product_list(array("product_id"=>$details["product_id"],"limit"=>1,"offset"=>0));
    	if(sizeof($products)==0){
    		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Product Not Found"));
    		exit();
    	}
    	$details=$products[0];
    	/*echo "<pre>";
    	print_r($details); exit();*/
    	$details["related_products"]=$this->am->product_list(array("category_id"=>$details["category_id"],"limit"=>20,"offset"=>0));
    	//$details["details"]=mb_convert_encoding($details["details"], "UTF-8");
    	echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Product details fetched successfully","details"=>$details),JSON_UNESCAPED_UNICODE);
	}


	function dashboard($source="mobile",$output="json"){
		$this->load->model("Api_model","am");
		if($source=="mobile"){
			$token=$this->input->get_request_header('token', TRUE);
				if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
		}else{
			$user_id=$this->session->userdata("user_id");
			if($user_id>0){
				$user_details=$this->am->user_details($user_id);
			}else{
				$user_details=array("user_id"=>0,"name"=>"","email"=>"","mobile"=>"");
			}
		}
		
		//$token="0ef78478e94f986112d8af26ab18ce35";
		
		//echo "found : ".$this->am->token_exist($token);
		
		$cart_count=$this->am->cart_item_count($user_details["user_id"]);
		$wallet_balance=$this->am->user_wallet_balance($user_details["user_id"]);
		$category_list=$this->am->category_list(array("limit"=>9,"parent_id"=>0));

		$array=array();
		$count=0;
		foreach($category_list as $list){
			$array[$count]=(array)$list;
            $category_ids=$this->am->get_category_chain($list->category_id);
            $category_ids[]=$list->category_id;
			$array[$count]["products"]=$this->am->product_list(array("category_id"=>$category_ids,"limit"=>12,"offset"=>0));
			$count++;
		}
		$banner_list=$this->am->banner_list();
		$brand_list=$this->am->brand_list();

		$data=array("details"=>$array,"banners"=>$banner_list,"brands"=>$brand_list,"user_details"=>
				array(
					"user_id"=>$user_details["user_id"],
					"name"=>$user_details["name"],
					"email"=>$user_details["email"],
					"mobile"=>$user_details["mobile"],
					"cart_count"=>$cart_count,
					"wallet_balance"=>$wallet_balance
				));

		if($output=="json"){
			echo json_encode($data);
		}else{
			echo "<pre>";
			print_r($data);
		}
	}

	function categoryList($parent_id=0){
		$this->load->model("Api_model","am");
		$category_list=$this->am->category_list(array("limit"=>9,"parent_id"=>$parent_id));
		echo json_encode(array("status"=>"success","status_code"=>1,"listData"=>$category_list));
	}

	function brandList(){
		$this->load->model("Api_model","am");
		$brand_list=$this->am->brand_list();
		echo json_encode(array("status"=>"success","status_code"=>1,"listData"=>$brand_list));
	}

	function mobileNoExist(){
		$this->load->model("Api_model","am");
		$mobile_no=$this->input->get_post("mobile_no");
		$exist=$this->am->mobile_exist($mobile_no);
		if($exist>0){
			echo json_encode(array("status_code"=>1));
		}else{
			echo json_encode(array("status_code"=>2));
		}

	}

	function sendOTP(){
		$mobile_no=$this->input->get_post("mobile_no");
		$otp_code=rand(100000,999999);
		$this->load->library("Sms");
		$this->sms->send_sms($mobile_no,"GOMART OTP : ".$otp_code);
		$this->load->model("Api_model","am");
		$save=$this->am->saveOTP($otp_code,$mobile_no);
		if($save){
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"OTP sent successfully"));
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Sent OTP"));
		}
	}

	function verifyOTP(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('mobile_no', 'Mobile Number', 'trim');
		$this->form_validation->set_rules('otp_code', 'OTP Code', 'trim');
		$mobile_no=$this->input->post("mobile_no");
		$otp_code=$this->input->post("otp_code");
		if($otp_code==""){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"OTP Field Cannot Be Empty"));
			exit();
		}
		$this->load->model("Api_model","am");
		if($this->am->OTPExist($otp_code,$mobile_no)>0){
			$this->am->updateOTPStatus($mobile_no,2);
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"OTP Matched successfully"));
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"OTP Mismatched"));
		}

	}

	/*function sms_check(){
		$this->load->library("Sms");
		$this->sms->send_sms(7003782339,"dummy sms from gomart");
	}*/

	function login(){
		$this->load->model("Api_model","am");
		$user_name=$this->input->post("user_name");
		$password=$this->input->post("password");
		$fcm=$this->input->post("fcm_token");
		if($this->am->login_data_exist($user_name)==1){
			$user_data=$this->am->user_data($user_name);
			/*echo "check password : ".$password;
			echo "<br/>password : ".$user_data["password"];
			exit();*/
			$authenticated=password_verify($password, $user_data["password"]);
			if($authenticated){
				if($this->am->device_data_exist($user_data["user_id"],$fcm)>0){
					$token=$this->am->refresh_device_token($user_data["user_id"],$fcm);
				}else{
					$id=$this->am->create_user_device_data($user_data["user_id"],$fcm);
					$device_info=$this->am->specific_user_device_data($id);
					$token=$device_info->access_token;
				}
				echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Login Done Successfully","details"=>array("user_id"=>$user_data["user_id"],"token"=>$token)));
				exit();
			}
		}
		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Login"));
	}


	function product_list(){
		$category_id=$this->input->post("category_id");
		$limit=$this->input->post("limit");
		$offset=$this->input->post("offset");
		$offset=$limit*$offset;
		$this->load->model("Api_model","am");
        $category_ids=$this->am->get_category_chain($category_id);
        $category_ids[]=$category_id;

		$totalRecords=$this->am->count_product_list(array("category_id"=>$category_ids));
		$list=$this->am->product_list(array("category_id"=>$category_ids,"offset"=>$offset,"limit"=>$limit));
		echo json_encode(array("status"=>"success","status_code"=>1,"listData"=>$list,"totalRecords"=>$totalRecords));
	}


	function addToCart($source="web"){
		$product_id=$this->input->post("product_id");
		$variant_id=$this->input->post("variant_id");
		$qty=$this->input->post("qty");
		if(!$this->input->post("operator")){
			$operator="+";
		}else{
			$operator=$this->input->post("operator");
		}
		$this->load->model("Api_model","am");
		if($source=="web"){
			$this->load->library("session");
			$login_state=1;
			if(!$this->session->userdata("user_id")){
				$login_state=0;
			}
			$user_id=$this->session->userdata("user_id");
			if($user_id==0 || is_null($user_id)){
				$login_state=0;
			}
			if($login_state==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"type"=>2,"msg"=>"No Active Login found"));
				exit();
			}
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		
		/*if($operator=="+"){
			$stock_in_hand=$this->am->checkStockAvailabilty($product_id,$variant_id);
			if($stock_in_hand<$qty){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"This Product Is Currently Out Of Stock"));
				exit();
			}
		}*/
		
		$cart_id=$this->am->addToCart($product_id,$variant_id,$qty,$user_id,$operator);
		if($cart_id){
			$count=$this->am->cartDetailsDataCount($cart_id);
			$list=$this->am->cartDetailsData($user_id);
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Product Added To Cart","item_count"=>$count,"list"=>$list));
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"type"=>1,"msg"=>"Unable To Add Product"));
		}

	}

	function removeFromCart($source="web"){
		$order_details_id=$this->input->post("oid");
		$this->load->model("Api_model","am");
		
		if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		
		$deleted=$this->am->removeFromCart($order_details_id,$user_id);
		if($deleted){
			$list=$this->am->cartDetailsData($user_id);
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Product Removed From Cart","list"=>$list));
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Remove Product From Cart"));
		}
		
	}

	function cartDetails($source="web"){
		$this->load->model("Api_model","am");
		if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		
		$list=$this->am->cartDetailsData($user_id);
		echo json_encode(array("status"=>"success","status_code"=>1,"list"=>$list));	
	}


	function productOptions($product_id){
		$this->load->model("Api_model","am");
		$list=$this->am->productOptions($product_id);
		/*echo json_encode(array("status"=>"success","list"=>$list));	
		echo "<br>";*/
		$array=array();
		foreach($list as $list){
			if($list["old_price"]==0){
				$disc=0;
			}else{
				$disc=($list["old_price"]-$list["price"])*100/$list["old_price"];
			}
			
			$array[]=array("option_value_row_id"=>$list["option_value_row_id"],"option_id"=>$list["option_id"],"value_name"=>$list["value_name"],"old_price"=>$list["old_price"],"price"=>$list["price"],"pr_value_id"=>$list["pr_value_id"],"disc"=>$disc,"base"=>$list["base"],"stock"=>$list["stock"]);
		}

		echo json_encode(array("status"=>"success","list"=>$array));	
	}

	function addressList($source="web"){
		$this->load->model("Api_model","am");
		if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		$address_list=$this->am->address_list($user_id);
		echo json_encode(array("status"=>"success","address_list"=>$address_list));
	}


	function saveAddress($source="web"){
		$this->load->model("Api_model","am");
		if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}

		$name=$this->input->post("name");
		$mobile_no=$this->input->post("mobile_no");
		$pin_code=$this->input->post("pin_code");
		$locality=$this->input->post("locality");
		$address=$this->input->post("address");
		$city=$this->input->post("city");
		$state_id=$this->input->post("state_id");
		$landmark=$this->input->post("landmark");
		//print_r($_POST); exit();
		$exist=$this->am->pin_code_availability_check($pin_code,$user_id);
		if($exist==0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not deliverable to this Pin Code area"));
				exit();
		}

		
		
		//$saved_id=0;
		$saved_id=$this->am->save_address($name,$mobile_no,$pin_code,$locality,$address,$city,$state_id,$landmark,$user_id);
		$address_list=$this->am->address_list($user_id);
		echo json_encode(array("status"=>"success","address_id"=>$saved_id,"address_list"=>$address_list));
		
		/*$this->load->model("Api_model","am");
		$this->am->*/
	}

	function stateList(){
		$this->load->model("Api_model","am");
		$state_list=$this->am->state_list(101);
		echo json_encode(array("status"=>"success","status_code"=>1,"state_list"=>$state_list));
	}

	function paymentMode(){
		$this->load->model("Api_model","am");
		$payment_mode_list=$this->am->payment_mode_list();
		echo json_encode(array("status"=>"success","status_code"=>1,"payment_mode_list"=>$payment_mode_list));
	}

	function filterProductList($offset=0){
		$array=array();
		$temp_array=array();
		$category_ids=array();
		$search_params=$_POST;
		if(isset($search_params["limit"])){
			$array["limit"]=$search_params["limit"];
		}else{
			$array["limit"]=20;
		}

		if(isset($search_params["offset"])){
			$array["offset"]=$search_params["offset"];
		}else{
			$array["offset"]=$offset;
		}

		$array["offset"]=$array["offset"]*$array["limit"];



		$products=array();
		$deep=0;
		//print_r($search_params);
		$this->load->model("Api_model","am");
		
		
		if(isset($search_params["category_id"])){
			$cat_list=array();
			$category_count=sizeof($search_params["category_id"]);
			//print_r($search_params["category_id"]);exit();
			if($category_count>1){
				unset($search_params["category_id"][0]);
			}
			foreach($search_params["category_id"] as $category_id){
				$category_ids=$this->am->get_category_chain($category_id);
				//print_r($category_ids);
				$category_ids[]=$category_id;
				$cat_list=array_merge($cat_list,$category_ids);
			}
			$array["category_id"]=$cat_list;
		}

		if(isset($search_params["brand_id"])){
			$array["brand_id"]=$search_params["brand_id"];
		}



		if(isset($search_params["price"])){
			$i=0;
			foreach($search_params["price"] as $price_range){
				$segments=explode("-",$price_range);
				$array["price_range"][$i]["from"]=$segments[0];
				$array["price_range"][$i]["to"]=$segments[1];
				/*$pr=$this->am->product_list($array);
				$products=array_merge($products,$pr);*/
				$i++;
			}
		}

		if(isset($search_params["discount"])){
			$i=0;
			foreach($search_params["discount"] as $disc_data){
				$segments=explode("-", $disc_data);
				$array["disc_range"][$i]["from"]=$segments[0];
				$array["disc_range"][$i]["to"]=$segments[1];
				$i++;
			}	
		}

		if(isset($search_params["order_by"]) && $search_params["order_by"]!=""){
			$segments=explode("-",$search_params["order_by"]);
			$array["order_by_field"]=$segments[0];
			$array["ordering"]=$segments[1];
		}

		//print_r($array); exit();
			$products=$this->am->product_list($array);

		/*$this->am->create_temp_tbl_for_product_filter($products);

		if(isset($search_params["order_by"]) && $search_params["order_by"]!=""){
			$segments=explode("-",$search_params["order_by"]);
			$temp_array["order_by_field"]=$segments[0];
			$temp_array["ordering"]=$segments[1];
		}*/

		/*$disc=array();
		if(isset($search_params["discount"])){
			foreach($search_params["discount"] as $disc_data){
				$segments=explode("-", $disc_data);
				$start=$segments[0];
				$end=$segments[1];
				for($i=$start;$i<$end;$i++){
					$disc[]=$i;
				}
			}	
			$temp_array["disc"]=$disc;

		}

		$products=$this->am->get_temp_product_filter_data($temp_array);*/

		
		echo json_encode(array("status"=>"success","status_code"=>1,"products"=>$products));
	}

	function deliveryTimmingSlotList(){
		 $this->load->model("Api_model","am");
		 $timmings=$this->am->delivery_timming_slot_list();
		 echo json_encode(array("status"=>"success","status_code"=>1,"timmings"=>$timmings));
	}

	function save_online_payment_details($source="web"){
		$payment_mode=$this->input->post("payment_mode");
        $payment_mode=explode(",",$payment_mode);
        $payment_amt=$this->input->post("payment_amt");
        $payment_amt=explode(",",$payment_amt);
        $grand_total=$this->input->post("grand_total");
        if(!in_array(1,$payment_mode) && !in_array(3,$payment_mode)){
            echo json_encode(array("status"=>"failed","type"=>1,"msg"=>"CASH ON DELIVERY Cannot be used here"));
            exit();
        }

        if(sizeof($payment_mode)==1 && $payment_mode[0]=="1"){
            echo json_encode(array("status"=>"failed","type"=>1,"msg"=>"Only Wallet Cannot be used here"));
            exit();
        }
        
		$this->load->model("Api_model","am");
        if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"type"=>1,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		$data["cartDetails"]=$cartDetails=$this->am->cartDetails($user_id);
        $this->am->refreshCart($cartDetails->order_id);
        $data["cartDetails"]=$cartDetails=$this->am->cartDetails($user_id);

		if(array_sum($payment_amt)!= $cartDetails->grand_total){
            echo json_encode(array("status"=>"failed","status_code"=>0,"type"=>2,"msg"=>"Applied Payment amount missmatched with the order amount"));
				exit();
        }

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
        $this->load->library("Razorpay");
        $api=$this->razorpay->load();
        $razorpayOrder = $api->order->create($orderData);
        $online_order_id= $razorpayOrder['id'];

		//$online_order_id=1234;
		if($online_order_id==""){
			echo json_encode(array("status"=>"failed","type"=>1,"msg"=>"Payment Gateway's Order Id must Be passed"));
			exit();
		}
		$updated=$this->am->update_order_trans_id($online_order_id,$cartDetails->order_id);
		$this->am->init_order_payment($cartDetails->order_id,$payment_amt,$payment_mode);
		$cartDetails=(array)$this->am->cartDetails($user_id);
		if($updated){
			echo json_encode(array("status"=>"success","details"=>$cartDetails,"msg"=>"online Order id has been attached with cart id"));
		}else{
			echo json_encode(array("status"=>"failed","type"=>1,"msg"=>"Unable To attach online order id to cart id"));
		}

	}


	private function verify_payment($user_id){
		$this->load->helper("url");
        //exit("dfasdfasdf");
        $this->load->library("session");
        $razorpay_payment_id=$this->input->post("razorpay_payment_id");
        $razorpay_signature=$this->input->post("razorpay_signature");
        $razorpay_order_id=$this->input->post("razorpay_order_id");
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

        	$this->load->library("Razorpay");
            $this->load->model("Api_model","am");
             $cartDetails=$this->am->cartDetails($user_id);
            $payDetails=$this->am->order_payment_details($cartDetails->order_id,3);
            $trans_amt=$payDetails->amt;
            $api=$this->razorpay->load();
            $payment = $api->payment->fetch($razorpay_payment_id);

             if($payment->amount!=$trans_amt*100){
                echo json_encode(array("status"=>"failed","msg"=>"Payment Amount And Order Amount Mismatched"));
                exit();
                //echo $payment->amount;
                 //print_r($payment->notes);
             }

           // echo "payment done successfully";
            //$this->complete_order();
            //redirect("Home/complete_order");
        }else{
            //echo "payment failed";
            echo json_encode(array("status"=>"failed","msg"=>$response->msg));
            exit();
        }
    }




	function completeOrder($source="web"){
        /*echo "<pre>";
        print_r($_POST);*/ //exit();
        $this->load->model("Api_model","am");
        if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		//echo "user id : ".$user_id;
        $payment_mode=$this->input->post("payment_mode");
        $address_id=$this->input->post("address_id");
        $delivery_timming=$this->input->post("delivery_timming");
        if(!is_array($payment_mode)){
        	$payment_mode=array($payment_mode);
        }
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

        if($payment_mode_in_order==3 || $payment_mode_in_order==5){
        	$this->verify_payment($user_id);
        }
        $cartDetails=$this->am->cartDetails($user_id);
        //print_r($cartDetails); exit(); 
        $this->am->refreshCart($cartDetails->order_id);
        $order_id=$this->am->complete_order($payment_mode,$payment_mode_in_order,$address_id,$delivery_timming,$user_id);
        if($order_id){
        	$order_details=$this->am->orderDetailsById($order_id);
            $user_details=$this->am->user_details($user_id);
            $this->load->library("Sms");
           /* $text="Order Placed Successfully. Order Id : ".$order_id.". Your Order Will be Delivered On ".$order_details["expected_delivery_date"];*/
            /*$text="Your order in GOMART (Order Id:".$order_id.") has been successfully placed, payment method : ".$order_details['payment_mode_name']." and amount: ".$order_details['grand_total']." .It will be delivered on ".$order_details['expected_delivery_date']." between ".$order_details['delivery_timming_name'].".!!! Thank you for choosing Gomart Have a Nice Day !!!";
            $this->sms->send_sms($order_details["mobile_no"],$text);
            $text="New Order of Rs.".$order_details["grand_total"]." Received. Payment Method ".$order_details["payment_mode_name"].". Scheduled Delivery Date ".$order_details["expected_delivery_date"];
            $this->sms->send_sms(SITE_OWNERS_DEFAULT_MOBILE_NO,$text);*/

           echo json_encode(array("status"=>"success","status_code"=>1,"order_id"=>$order_id,"msg"=>"Order Completed Successfully"));
        }else{
           echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Complete The Order"));
        }
    }


    function myOrders($source="web"){
    	$this->load->model("Api_model","am");
        if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		$list=$this->am->getOrders($user_id);
		echo json_encode(array("status"=>"success","status_code"=>1,"order_list"=>$list));

    }

    function trackOrder($source="web"){
    	$this->load->model("Api_model","am");
        if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
    	$order_id=$this->input->post("order_id");
    	//$order_id=56;
        $data["order_details"]=$this->am->orderDetailsById($order_id);
        $status=$this->am->order_status_log($order_id);
        $status_log=array();
        foreach($status as $st){
        	$status_log[]=$st["status"];
        }
        $data["status_log"]=$status_log;
        echo json_encode(array("status"=>"success","status_code"=>1,"details"=>$data));
    }


    function updatePassword($source="web"){
    	/*echo "<pre>";
    	print_r($_POST);*/
    	$current_password=$this->input->post("current_password");
    	$new_password=$this->input->post("new_password");
    	$confirm_new_password=$this->input->post("confirm_new_password");
    	if(strlen($new_password)<5){
    		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Your New Password Is Too Short"));
    		exit();
    	}
    	if($new_password!=$confirm_new_password){
    		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"New Password And Confirm password Mismatched"));
    		exit();
    	}
    	$this->load->model("Api_model","am");
        if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}

		

		$user_details=$this->am->user_details($user_id);
		//print_r($user_details);
		 $pw=$user_details["password"];

		

		if($this->am->login_data_exist($user_details["email"])==1 || $this->am->login_data_exist($user_details["mobile"])==1){
			$authenticated=password_verify($current_password, $pw);
			if($authenticated){
				$new_password=password_hash($new_password, PASSWORD_DEFAULT);
				$update=$this->am->update_password($new_password,$user_id);
				if($update){
					echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Password Changed successfully"));
					exit();
				}
			}else{
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Password Mismatched"));
				exit();
			}
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Invalid Data Provided"));
		}
    	
    }

    function myProfileData($source="web"){
    	$this->load->model("Api_model","am");
        if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		$user_details=$this->am->user_details($user_id);


		$wallet_balance=$this->am->user_wallet_balance($user_id);
		$details["wallet_balance"]=$wallet_balance;
		$details["md"]=array("name"=>$user_details["name"],"email"=>$user_details["email"],"mobile"=>$user_details["mobile"],"own_referral_code"=>$user_details["own_referral_code"]);
		echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Data Fetched Successfully","details"=>$details));
    }

    function updateProfileData($source="web"){
    	$this->load->model("Api_model","am");
        if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}

		$name=$this->input->post("name");
		if(strlen($name)<=5){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Name Field Cannot Be Less Than 6 Character"));
			exit();
		}
		$updated=$this->am->updateProfileData(array("name"=>$name),$user_id);
		if($updated){
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Profile Data Update Successfully"));
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Update Profile Data"));
		}

		

    }

    function news_updates(){
        $this->load->model("Api_model","am");
        $details=$this->am->page_data(15)["description"];
        echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Data Fetched Successfully","details"=>$details));
    }

    function forgotPassword(){
    	$this->load->library("Sms");
    	$number=$this->input->post("mobile");
    	$this->load->model("Api_model","am");
    	
    	$exist=$this->am->mobile_exist($number);
    	if($exist==0){
    		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Mobile Number Not Exist"));
    		exit();
    	}
    	$user_details=$this->am->get_user_details_by_mobile_no($number);
    	$otp=rand(100000,999999);
    	$this->sms->send_sms($number,"To reset password use this OTP ".$otp);
    	$updated=$this->am->update_forgot_password_otp($otp,$user_details["user_id"]);
    	if($updated){
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"OTP has been sent to ".$number));
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Send Otp"));
		}
    }

    function resetPassword(){
    	$otp=$this->input->post("otp");
    	$mobile=$this->input->post("mobile");
    	$new_password=$this->input->post("new_password");
    	$confirm_password=$this->input->post("confirm_password");
    	if(strlen($new_password)<3){
    		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Password is too short"));
    		exit();
    	}
    	if($new_password!=$confirm_password){
    		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Password and Confirm Password Mismatched"));
    		exit();
    	}
    	if(strlen($otp)<6){
    		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Invalid Otp Code"));
    		exit();
    	}
    	$password=password_hash($new_password, PASSWORD_DEFAULT);
    	$this->load->model("Api_model","am");
    	$verified=$this->am->verify_forgot_password_otp($otp,$mobile);
    	if($verified==0){
    		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"OTP or mobile number mismatched"));
    		exit();
    	}else{
    		$user_details=$this->am->get_user_details_by_mobile_no($mobile);
    		$updated=$this->am->update_password_against_forgot_password($password,$user_details["user_id"]);
    		if($updated){
					echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Password Changed Successfully"));
			}else{
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Update Password"));
			}

    	}
    }

    function dp_order_list($source="web"){
    	$this->load->model("Api_model","am");
    	if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		if($this->input->post("from_date")){
			$from_date=$this->input->post("from_date");
		}else{
			$from_date=date("Y-m-d");
		}
		if($this->input->post("to_date")){
			$to_date=$this->input->post("to_date");
		}else{
			$to_date=date("Y-m-d");
		}
		$status=$this->input->post("status");
		
		$args=array("delivery_person_id"=>$user_id,"status"=>$status,"from_date"=>$from_date,"to_date"=>$to_date);
		$list=$this->am->delivery_persons_order_list($args);
		echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Data fetched Successfully","list"=>$list));
    }

    function orderItemDetails($source="web"){
    	$this->load->model("Api_model","am");
    	if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}

		$order_id=$this->input->post("order_id");
		
		///authorization checking need to be done later////////////////////////////////////
		$authorised=$this->am->has_authorised_to_fetch_order($order_id,$user_id);
		//////////////////////////////////
		if($authorised==0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
		}

		$list=$this->am->orderDetailsData($order_id);
		echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Data fetched Successfully","list"=>$list));
    }

    function updateOrderStatus($source="web"){
    	$this->load->model("Api_model","am");
    	if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		$order_id=$this->input->post("order_id");
		$status=$this->input->post("status");
		$authorised=$this->am->has_authorised_to_update_order_status($order_id,$user_id);
		if($authorised==0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
		}
		if($status==3){
			$vals["delivered_on"]=date("Y-m-d H:i:s");
			$config['upload_path']          = './uploads/images/signature/';
	        $config['allowed_types']        = 'gif|jpg|png|jpeg';
	        $config['max_size']             = 10000;
	        $this->load->library('upload', $config);
	         if ( ! $this->upload->do_upload("signature_file")){
                //echo "Error : ". $this->upload->display_errors(); exit();
                echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized","error"=>$this->upload->display_errors()));
				exit();
             }else{
                $data=$this->upload->data();
                $vals["signature_file"]=$data["file_name"];
            }
		}
		$vals["status"]=$status;

		$updated=$this->am->update_order_status($vals,$order_id,$user_id);
		if($updated){
			$this->sendBillByEmail($order_id);
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Status Updated Successfully"));
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Update"));
				exit();
		}
		
    }

    function bill($order_id){
    	$this->load->helper("url");
   		$this->load->model("Api_model","am");
   		$data["item_list"]=$this->am->orderDetailsData($order_id);
   		$data["order_details"]=$this->am->orderDetailsById($order_id);
   		$data["site"]=$this->am->site_settings_data();
   		/*echo "<pre>";
   		print_r($data); exit();*/
   		$this->load->view("frontend/bill",$data);

   }

    public function sendBillByEmail($order_id){
    	$this->load->model("Api_model","am");
   		$data["item_list"]=$this->am->orderDetailsData($order_id);
   		$data["order_details"]=$this->am->orderDetailsById($order_id);
   		$data["site"]=$this->am->site_settings_data();
   		/*echo "<pre>";
   		print_r($data); exit();*/
   	
    	$this->load->library('phpmailer_lib');

        // PHPMailer object
        $mail = $this->phpmailer_lib->load();

        $mail->IsSMTP();
		$mail->Host = "103.21.59.208";  /*SMTP server*/

		$mail->SMTPAuth = true;
		//$mail->SMTPSecure = "ssl";
		$mail->Port = 587;
		$mail->Username = "no-reply@gomart.in";  /*Username*/
		$mail->Password = "Gkw*Z*@1vIH%";    /**Password**/

		$mail->From = "no-reply@gomart.in";    /*From address required*/
		$mail->FromName = "GOMART";
		$mail->AddAddress("debasish.1911@gmail.com");
		//$mail->AddReplyTo("mail@mail.com");

		$mail->IsHTML(true);

		$mail->Subject = "Your Order Has Been Delivered Successfully. ORDER ID : ".$order_id;
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
		$mail->Body = $this->load->view("frontend/bill",$data,true);
		$mail->Send();
		/*if(!$mail->Send())
		{
		echo "Message could not be sent. <p>";
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit;
		}*/


    }

    function file_uploader($name){
		$config['upload_path']          = './uploads/images/signature/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 10000;
       /* $config['max_width']            = 1024;
        $config['max_height']           = 768;
*/
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload($name))
        {
                //$error = array('error' => $this->upload->display_errors());
                echo "Error : ". $this->upload->display_errors(); exit();
                //$this->load->view('upload_form', $error);
        }
        else
        {
                $data=$this->upload->data();
                //print_r($data); exit();
                return $data["file_name"];
        }
	}


	function search(){
		$search_val=$this->input->post("search_val");
		$offset=0;
		$limit=6;
		$this->load->model("Api_model","am");
		$list=$this->am->product_list(array("search_val"=>$search_val,"limit"=>$limit,"offset"=>$offset));
		echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Data fetched Successfully","list"=>$list));
	}

	function cancelOrder($source="web"){
		$this->load->model("Api_model","am");
    	if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		$order_id=$this->input->post("order_id");
		$reason=$this->input->post("reason");

		$authorised=$this->am->has_authorised_to_fetch_order($order_id,$user_id);
		if($authorised==0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
		}

		$vals=array("status"=>4,"cancel_reason"=>$reason);
		$updated=$this->am->update_order_status($vals,$order_id,$user_id);
		if($updated){
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Order Cancelled Successfully"));
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Cancel Order"));
				exit();
		}
	}

	function WalletBalance($source="web"){
		$this->load->model("Api_model","am");
    	if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}
		$balance=$this->am->user_wallet_balance($user_id);
		echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Wallet balance fetched successfully","balance"=>$balance));

	}


	function shippingCharges(){
		$amt=$this->input->get_post("amt");
		$this->load->model("Api_model","am");
    	$shipping_charges=$this->am->shipping_charges($amt);
		echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Wallet balance fetched successfully","shipping_charges"=>$shipping_charges));

	}

	function add_wallet_money(){
		$this->load->model("Api_model","am");
		$token=$this->input->get_request_header('token', TRUE);
		if($this->am->token_exist($token)==0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
			exit();
		}
		$user_details=$this->am->user_details_by_token($token);
		$user_id=$user_details["user_id"];


        $amt=$this->input->post("amt");
        $receipt="WMA".rand(100000,999999).time();

        //print_r($_POST); exit();
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
        //$this->session->set_userdata("razorpay_order_id",$razorpayOrderId);
        $displayAmount = $amount = $orderData['amount'];

        echo json_encode(array("status"=>"success","status_code"=>1,"details"=>array("receipt"=>$receipt,"trans_order_id"=>$razorpayOrderId,"amt"=>$amt)));

    }


    function wallet_payment_complete(){
		$this->load->helper("url");

		$this->load->model("Api_model","am");
		$token=$this->input->get_request_header('token', TRUE);
		if($this->am->token_exist($token)==0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
			exit();
		}
		$user_details=$this->am->user_details_by_token($token);
		$user_id=$user_details["user_id"];
        //exit("dfasdfasdf");
        $razorpay_payment_id=$this->input->post("razorpay_payment_id");
        $razorpay_signature=$this->input->post("razorpay_signature");
        $razorpay_order_id=$this->input->post("razorpay_order_id");
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

        	$this->load->library("Razorpay");
            $this->load->model("Api_model","am");
            $transLog=$this->am->trans_log($razorpay_order_id);
            $trans_amt=$transLog["in_amt"];
            $api=$this->razorpay->load();
            $payment = $api->payment->fetch($razorpay_payment_id);

             if($payment->amount!=$trans_amt*100){
                echo json_encode(array("status"=>"failed","msg"=>"Payment Amount And Order Amount Mismatched"));
                exit();
                //echo $payment->amount;
                 //print_r($payment->notes);
             }else{
             	$saved=$this->am->save_wallet_balance_add($transLog["wallet_id"]);
             	if($saved){
             		echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Wallet Balance of Rs".$trans_amt." Added Successfully"));
             	}else{
             		echo json_encode(array("status"=>"failed","msg"=>"Unable To Save The Record. Please Contact Gomart Regarding This "));
             	}
             }

           // echo "payment done successfully";
            //$this->complete_order();
            //redirect("Home/complete_order");
        }else{
            //echo "payment failed";
            echo json_encode(array("status"=>"failed","msg"=>$response->msg));
            exit();
        }
    }


    function loginStatus($source="web"){
    	if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
			if($user_id!=""){
				echo json_encode(array("status_code"=>1));
				exit();
			}else{
				echo json_encode(array("status_code"=>0));
				exit();
			}
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}


    }


    function editOrder($source="web"){
    	$order_id=$this->input->post("order_id");
    	$this->load->model("Api_model","am");
    	if($source=="web"){
			$this->load->library("session");
			$user_id=$this->session->userdata("user_id");
		}else{
			$token=$this->input->get_request_header('token', TRUE);
			if($this->am->token_exist($token)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
			}
			$user_details=$this->am->user_details_by_token($token);
			$user_id=$user_details["user_id"];
		}

		$order_details=$this->am->orderDetailsById($order_id);
		if($order_details["status"]>1){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Order Is Already ".$order_details["status_name"]." Unable To Edit This Order"));
				exit();
		}

		///authorization checking need to be done later////////////////////////////////////
		$authorised=$this->am->has_authorised_to_fetch_order($order_id,$user_id);
		//////////////////////////////////
		if($authorised==0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Not Authorized"));
				exit();
		}


		$updated=$this->am->convert_order_to_cart($order_id,$user_id);
		if($updated){
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Order Items Are Added To Cart... Kindly Modify It Accourding To Your Need"));
				exit();
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Proceed"));
				exit();
		}






    }


    


}