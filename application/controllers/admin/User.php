<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {


		function __construct()
		{
			parent::__construct();
			$this->load->library("session");
			$this->load->helper("url");
			$this->load->helper("form");
			if(! $this->session->userdata('validated')){
            redirect('admin/account/login');
        }
		
		}
 
 
	public function index(){
 		$this->load->model("User_model","um");
 		$data["list"]=$this->um->user_list(array("user_type_id"=>9));
 		$data["type"]=9;

 		$this->load->view("admin/user_list",$data);
	}

	function status_update(){
		//print_r($_POST);exit();
		$user_id=$this->input->post("user_id");
		$status=$this->input->post("status");
		$this->load->model("User_model","um");
		$updated=$this->um->status_update($status,$user_id);
		if($updated){
			$this->session->set_flashdata('action_message', 'User Data Updated Successfully');
			$this->session->set_flashdata('action_message_type', 'success');
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Updated User Data');
			$this->session->set_flashdata('action_message_type', 'danger');
		}
		redirect($_SERVER['HTTP_REFERER']);
	}

	function user_list($type){
		$this->load->model("User_model","um");
		$data["type"]=$type;
		if($type==8){
			$data["page_title"]="Delivery Boy List";
		}else{
			$data["page_title"]="User List";
		}
		$data["list"]=$this->um->user_list(array("user_type_id"=>$type));
		$this->load->view("admin/user_list",$data);

	}

	function save(){
		$name=$this->input->post("name");
		$email=$this->input->post("email");
		$mobile_no=$this->input->post("mobile_no");
		$password=$this->input->post("password");
		$type=$this->input->post("type");
		$confirm_password=$this->input->post("confirm_password");
		if($password!=""){
			$password=password_hash($password, PASSWORD_DEFAULT);
		}

		$date_time=date("Y-m-d H:i:s");
		$this->load->model("User_model","um");
		$this->load->model("Api_model","am");
		if($this->am->mobile_exist($mobile_no)>0){
			$this->session->set_flashdata('action_message', 'Unable To Add User. Mobile Number Already Exist');
			$this->session->set_flashdata('action_message_type', 'danger');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}

		if($this->am->email_exist($email)>0){
			$this->session->set_flashdata('action_message', 'Unable To Add User. Email Id Already Exist');
			$this->session->set_flashdata('action_message_type', 'danger');
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		}


		$saved=$this->um->save($name,$email,$mobile_no,$password,$type,$date_time);
		if($saved){
			$this->session->set_flashdata('action_message', 'Data saved Successfully');
			$this->session->set_flashdata('action_message_type', 'success');
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Save Data');
			$this->session->set_flashdata('action_message_type', 'danger');
		}
		redirect($_SERVER['HTTP_REFERER']);


	}

	function update_password(){
		$user_id=$this->input->post("user_id");
		$password=$this->input->post("password");
		$password =password_hash($password, PASSWORD_DEFAULT);
		$this->load->model("User_model","um");
		$updated=$this->um->update_password($password,$user_id);
		if($updated){
			$this->session->set_flashdata('action_message', 'Password Updated Successfully');
			$this->session->set_flashdata('action_message_type', 'success');
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Update Password');
			$this->session->set_flashdata('action_message_type', 'danger');
		}
		redirect($_SERVER['HTTP_REFERER']);

	}


	 public function email_test()
    {
        // Load Email library
        $this->load->library('email');

        // SMTP configuration
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://mail5015.site4now.net',  // Use ssl:// prefix
            'smtp_port' => 465,
            'smtp_user' => 'sales@jevikurja.com',
            'smtp_pass' => 'NorT4521*',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n",  // Important for SMTP
            'crlf'      => "\r\n"
        );

        // Initialize configuration
        $this->email->initialize($config);

        // Compose email
        $this->email->from('sales@jevikurja.com', 'Jevikurja Test');
        $this->email->to('debasish@mailinator.com');  // Replace with your own email
        $this->email->subject('Test Email from CodeIgniter 3');
        $this->email->message('<p>This is a <strong>test email</strong> sent via SMTP using CodeIgniter 3.</p>');

        // Send email and display result
        if ($this->email->send()) {
            echo 'Email sent successfully.';
        } else {
            echo 'Email sending failed.<br>';
            echo $this->email->print_debugger();
        }
    }
	
	
  
}
