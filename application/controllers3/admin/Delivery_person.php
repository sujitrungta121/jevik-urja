<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_person extends CI_Controller {


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
 		$this->load->model("Pin_code_model","pcm");
 		$data["list"]=$this->pcm->listData();
 		$this->load->view("admin/pin_code/list",$data);
	}

	function save(){
		$pin_code=$this->input->post("pin_code");
		$this->load->model("Pin_code_model","pcm");
		$save=$this->pcm->save($pin_code);
		if($save){
			$this->session->set_flashdata('action_message', 'Pin Code Added Successfully');
			$this->session->set_flashdata('action_message_type', 'success');
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Add Pin Code');
			$this->session->set_flashdata('action_message_type', 'danger');
		}
		redirect("admin/Pin_code");

	}


	function delete(){
		$pin_code=$this->input->post("pin_code");
		$this->load->model("Pin_code_model","pcm");
		$del=$this->pcm->delete($pin_code);
		if($del){
			$this->session->set_flashdata('action_message', 'Pin Code Deleted Successfully');
			$this->session->set_flashdata('action_message_type', 'success');
		}else{
			$this->session->set_flashdata('action_message', 'Unable To delete Pin Code');
			$this->session->set_flashdata('action_message_type', 'danger');
		}
		redirect("admin/Pin_code");
	}
	
	
  
}
