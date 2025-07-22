<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Controller {

 
		function __construct()
		{
			parent::__construct();
			$this->load->helper("url");
			$this->load->library("session");
        	$this->load->helper("site");
        	$this->load->helper("url");
        	$this->load->helper("form");
			if(! $this->session->userdata('validated')){
            redirect('admin/account/login');
             
        }
		
		}
 
 
	public function index(){
 
	}
	
	public function lists(){
		$this->load->helper("form");
		$this->load->model('admin/order_model');
		$this->load->model("User_model","um");
		$this->load->library("pagination");
 
		$config = array();
        $config["base_url"] = base_url() . "admin/order/lists";
        $config["total_rows"] = $this->order_model->order_count();
        $config["per_page"] = 10000;
        $config["uri_segment"] = 4;

        $data["from_date"]=date("Y-m-d", strtotime("-3 days"));
		$data["to_date"]=date("Y-m-d");
		if($this->input->get_post("from_date")){
			$data["from_date"]=$this->input->get_post("from_date");
		}
		if($this->input->get_post("to_date")){
			$data["to_date"]=$this->input->get_post("to_date");
		}

		$data["status_id"]="";

		if($this->input->get_post("status_id")){
			$data["status_id"]=$this->input->get_post("status_id");
		}
 
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["results"] =$this->order_model->get_orders(array("from_date"=>$data["from_date"],"to_date"=>$data["to_date"],"status"=>$data["status_id"]));
        $data["links"] = $this->pagination->create_links();
        $data["order_status_list"]=$this->order_model->order_status_list();
        //exit();
        $data["user_list"]=$this->um->user_list(array("user_type_id"=>8));
		$this->load->view('admin/order/list', $data);
   }

   function status_update(){
	   	$this->load->model('admin/order_model','om');
	   	
	   	$order_id=$this->input->post("order_id");
	   	$status_id=$this->input->post("status_id");
	   	$reason=$this->input->post("reason");
	   	$updated=$this->om->order_status_update($status_id,$reason,$order_id);

	   	if($updated){
	   		if($status_id==3){
	   			$this->load->library("sms");
	   			$this->load->model("Api_model","am");
	   			$order_details=$this->am->orderDetailsById($order_id);
	   			$text="You order has been successfully delivered. Your order id is ".$order_id.".
!!! Thank you for choosing Gomart Have a Nice Day !!!";
	   			$this->sms->send_sms($order_details["mobile_no"],$text);
	   			$this->sendBillByEmail($order_id,$text);
	   		}

	   		if($status_id==2){
	   			$this->load->library("sms");
	   			$this->load->model("Api_model","am");
	   			$order_details=$this->am->orderDetailsById($order_id);
	   			$text="We are processing your order Id - ".$order_id.".You will receive your order on your selected timeslot. For further queries call us on - +919007005138";
	   			$this->sms->send_sms($order_details["mobile_no"],$text);
	   			$this->sendBillByEmail($order_id,$text);
	   		}



			$this->session->set_flashdata('action_message', 'Order Status Updated Successfully!');
			$this->session->set_flashdata('action_message_type', 'success');
			redirect($_SERVER['HTTP_REFERER']);
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Status Update !. An error has occured.');
			$this->session->set_flashdata('action_message_type', 'danger');
			redirect($_SERVER['HTTP_REFERER']);
		}
   }

   private function sendBillByEmail($order_id,$text){
    	$this->load->model("Api_model","am");
   		$data["item_list"]=$this->am->orderDetailsData($order_id);
   		$data["order_details"]=$order_details=$this->am->orderDetailsById($order_id);
   		$data["site"]=$this->am->site_settings_data();
   		/*echo "<pre>";
   		print_r($data); exit();*/
   		$subject="Your Order Status Updated";
   		/*$content=$this->load->view("frontend/bill",$data,true);*/
   		$content="<h1>".$text."</h1><a style='display: block;width: 115px;height: 25px;background: #4E9CAF;padding: 10px;text-align: center;border-radius: 5px;color: white;font-weight: bold;' href='".base_url('Api/bill/'.$order_id)."'>View Bill</a>";
   		//echo $content;

   		//$content="Your order id : ".$order_id;

		   		//extract data from the post
		//set POST variables
		$url = base_url("phpmailer/mail.php");
		$fields = array(
			'subject' => urlencode($subject),
			'content' => urlencode($content),
			'receiver' => urlencode($order_details["email"]),
		);

		$fields_string="";
		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

		//execute post
		$result = curl_exec($ch);

		//"curl response is:" . $result;

		//close connection
		curl_close($ch);
    	


    }


   private function sendBillByEmail2($order_id,$text){
    	$this->load->model("Api_model","am");
    	$this->load->library("Email");
   		$data["item_list"]=$this->am->orderDetailsData($order_id);
   		$data["order_details"]=$order_details=$this->am->orderDetailsById($order_id);
   		$data["site"]=$this->am->site_settings_data();
   		/*echo "<pre>";
   		print_r($data); exit();*/
   		$subject="Your Order Status Updated";
   		/*$content=$this->load->view("frontend/bill",$data,true);*/
   		$content="<h1>".$text."</h1><a style='display: block;width: 115px;height: 25px;background: #4E9CAF;padding: 10px;text-align: center;border-radius: 5px;color: white;font-weight: bold;' href='".base_url('Api/bill/'.$order_id)."'>View Bill</a>";
   		//echo $content;

   		//$content="Your order id : ".$order_id;

		   		//extract data from the post
		//set POST variables


		$this->email->set_mailtype("html");
		$this->email->set_header('Content-Type', 'text/html');
		$this->email->from('no-reply@gomart.in', 'GoMart');
		$this->email->to($order_details["email"]);
		/*$this->email->cc('another@another-example.com');
		$this->email->bcc('them@their-example.com');*/

		$this->email->subject($subject);
		$this->email->message($content);

		$this->email->send();
    }

   function bill($order_id){
   		$this->load->model("Api_model","am");
   		$data["item_list"]=$this->am->orderDetailsData($order_id);
   		$data["order_details"]=$this->am->orderDetailsById($order_id);

   		$data["site"]=$this->am->site_settings_data();
   		/*echo "<pre>";
   		print_r($data['order_details']); exit();*/
   		$this->load->view("frontend/bill",$data);
   }

   function remove_item(){
   	$oid=$this->input->post("oid");
   	$this->load->model("Api_model","am");

   	if($this->am->remove_order_item($oid)){
		$this->session->set_flashdata('action_message', ' Item Removed Successfully');
		$this->session->set_flashdata('action_message_type', 'success');
		redirect($_SERVER['HTTP_REFERER']);
	}else{
		$this->session->set_flashdata('action_message', 'Unable To remove Item');
		$this->session->set_flashdata('action_message_type', 'danger');
		redirect($_SERVER['HTTP_REFERER']);
	}

   }
   
   
   public function detail($order_id){
   		$this->load->model('admin/order_model','om');
		if($_POST){
			$update_comment = false;
			//$update_comment = $update = $this->order_model->update_comment($_POST);
			if($update_comment){
				$this->session->set_flashdata('action_message', 'Order comment updated!');
				$this->session->set_flashdata('action_message_type', 'success');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('action_message', 'Order comment not updated!. An error has occured.');
				$this->session->set_flashdata('action_message_type', 'danger');
				redirect($_SERVER['HTTP_REFERER']);
			}
	}
 			 
 		$data["order"] = $this->om->detail($order_id);
		$data["products"] = $this->om->products($order_id);
		
 		/*echo "<pre>";
 		print_r($data); exit();*/
   		$this->load->view('admin/order/detail', $data);

   }
   
	function get_product_name(){
		$this->load->model('admin/module_model');	
		if(isset($_GET['term'])){
			$q = strtolower($_GET['term']);
			$this->module_model->get_products($q);
		}
	}
   
   public function product_add(){
		$this->load->model('admin/order_model');

       	if($_POST){
			$add_product = false;
			//$add_product = $this->order_model->productadd($_POST);
			if($add_product){
				$this->session->set_flashdata('action_message', 'New product added in Order!');
				$this->session->set_flashdata('action_message_type', 'success');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('action_message', 'New product not added in Order!. An error has occured.');
				$this->session->set_flashdata('action_message_type', 'danger');
				redirect($_SERVER['HTTP_REFERER']);
			}
	}
  
   }
   
   public function product_delete(){
      		$this->load->model('admin/order_model');
			
 			$delete_product = false;
			//$delete_product = $this->order_model->productdelete($this->uri->segment(4));
			if($delete_product){
				$this->session->set_flashdata('action_message', 'Product deleted in Order!');
				$this->session->set_flashdata('action_message_type', 'success');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('action_message', 'Product not deleted in Order!. An error has occured.');
				$this->session->set_flashdata('action_message_type', 'danger');
				redirect($_SERVER['HTTP_REFERER']);
			}
   }

   function assign_delivery_person(){
   	$delivery_person_id=$this->input->post("delivery_person_id");
   	$order_id=$this->input->post("order_id");
   	$this->load->model('admin/order_model','om');
   	$saved=$this->om->assign_delivery_person($delivery_person_id,$order_id);
   	if($saved){
   		$this->session->set_flashdata('action_message', 'Delivery Person Assigned Successfully');
			$this->session->set_flashdata('action_message_type', 'success');
			redirect($_SERVER['HTTP_REFERER']);
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Assign Delivery Person');
			$this->session->set_flashdata('action_message_type', 'danger');
			redirect($_SERVER['HTTP_REFERER']);
		}

   }

   function notify_order_details_updates(){
   		$order_id=$this->input->post("order_id");
   		$type=$this->input->post("type");
   		$this->load->model("Api_model","am");
	   	$order_details=$this->am->orderDetailsById($order_id);


	   	if($type==2){
	   		$this->load->library("sms");
   			$text="Your Current Order Amount Rs ".$order_details["grand_total"]." By Order Id ".$order_id;
   			$this->sms->send_sms($order_details["mobile_no"],$text);
   			//$this->sms->send_sms(7003782339,$text);
   			$this->session->set_flashdata('action_message', 'Sms Sent Successfully');
   			$this->session->set_flashdata('action_message_type', 'success');
			redirect($_SERVER['HTTP_REFERER']);

	   	}

	   	if($type==1){
	   		$this->load->library("sms");
   			$text="Your Current Order Amount Rs ".$order_details["grand_total"]." By Order Id ".$order_id;
   			$content="<h1>".$text."</h1><a style='display: block;width: 115px;height: 25px;background: #4E9CAF;padding: 10px;text-align: center;border-radius: 5px;color: white;font-weight: bold;' href='".base_url('Api/bill/'.$order_id)."'>View Bill</a>";

   			$url = base_url("phpmailer/mail.php");
			$fields = array(
				'subject' => urlencode("Bill Generated For The Order Id ".$order_id),
				'content' => urlencode($content),
				'receiver' => urlencode($order_details["email"]),
			);

			$fields_string="";
			//url-ify the data for the POST
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');

			//open connection
			$ch = curl_init();

			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, count($fields));
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

			//execute post
			$result = curl_exec($ch);

			//"curl response is:" . $result;

			//close connection
			curl_close($ch);

			$this->session->set_flashdata('action_message', 'Email Sent Successfully');
   			$this->session->set_flashdata('action_message_type', 'success');
			redirect($_SERVER['HTTP_REFERER']);
	   	}



   }



   
   
}

 