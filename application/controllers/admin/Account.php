<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

 
		function __construct()
		{
			parent::__construct();
			$this->load->library("session");
			$this->load->helper("url");
			//session_start(); 	// For KCFinder -> browser.php $_SESSION['validated'] creating.
								// account_model have $_SESSION['validated'] created.

		}
	
	public function index()
	{
		// Redirect to login if no method is specified
		$this->login();
	}
	
	public function login()
	{
		$this->load->view('admin/account/login');

	}
	
	public function test()
	{
		echo "Admin Account controller is working! Login method is accessible.";
	}
 
	public function check(){
	
		
	$email = $this->input->post("email");
    $this->load->model('admin/account/login');

      $result = $this->login->validate();

        if(!$result){

					$this->load->view('admin/account/login');
        }else{
        	$ud=$this->login->get_login_user_details($email);
        	//print_r($ud); exit();
					$this->session->set_flashdata('action_message', 'Welcome TO Backend');
					$this->session->set_flashdata('action_message_type', 'success');

					if(!empty($ud->brand_id)){
						$this->session->set_userdata("user_brand_id",$ud->brand_id);
						redirect('admin/product/lists');
					}else{
						redirect('admin/dashboard');
					}

				
        }
			

	}
	
	public function logout(){
        $this->session->sess_destroy();
		session_start(); 
		session_destroy();

        redirect('admin/account/login');
    
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */