<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

		function __construct()
		{
			parent::__construct();
			$this->load->library("session");
			$this->load->helper("url");
			
			if(! $this->session->userdata('validated')){
            redirect('admin/account/login');
        }
		
		}
 
 
	public function index(){
	    
 
		$this->load->model('admin/dashboard_model');
		$data["total_order"] = $this->dashboard_model->orders_count();
		$data["total_confirmed_order"] = $this->dashboard_model->orders_count(array("status"=>1));
		$data["total_processing_order"] = $this->dashboard_model->orders_count(array("status"=>2));
		$data["total_delivered_order"] = $this->dashboard_model->orders_count(array("status"=>3));
		$data["total_cancelled_order"] = $this->dashboard_model->orders_count(array("status"=>4));
		$data["orders"] = $this->dashboard_model->orders();


           $this->load->view('admin/dashboard', $data);
 
			

	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */