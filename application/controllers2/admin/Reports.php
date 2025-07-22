<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library("session");
		$this->load->helper("url");
		$this->load->helper("site");
		$this->load->helper("form");
		if(! $this->session->userdata('validated')){
        	redirect('admin/account/login');
    	}
	}

	function referral_income(){
		$data["from_date"]=date("Y-m-d");
		$data["to_date"]=date("Y-m-d");
		if($this->input->post("from_date")){
			$data["from_date"]=$this->input->post("from_date");
		}
		if($this->input->post("to_date")){
			$data["to_date"]=$this->input->post("to_date");
		}
		$this->load->model("Reports_model","rm");
		$data["list"]=$this->rm->referral_income(array("from_date"=>$data["from_date"],"to_date"=>$data["to_date"]));
		$data["page_title"]="Referral Income Report";

		$this->load->view("admin/reports/referral_income",$data);
	}

	function stock_report(){
		$this->load->model("Reports_model","rm");
		$data["list"]=$this->rm->stock_report();
		$data["page_title"]="Current Stock Report";
		$this->load->view("admin/reports/stock_report",$data);
	}

	function stock_update(){
		/*echo "<pre>";
		print_r($_POST);
		exit();*/
		$stock=$this->input->post("stock");
		$mode=$this->input->post("mode");
		$pov_id=$this->input->post("pov_id");
		$product_id=$this->input->post("product_id");
		$this->load->model("Reports_model","rm");
		$updated=$this->rm->stock_update_bulk($product_id,$pov_id,$mode,$stock);
		if($updated){
			$this->session->set_flashdata('action_message', 'Stock Updated Successfully');
			$this->session->set_flashdata('action_message_type', 'success');	
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Update Stock');
			$this->session->set_flashdata('action_message_type', 'danger');	
		}
		redirect($_SERVER['HTTP_REFERER']);

	}

	function sales_report(){
		$data["from_date"]=date("Y-m-d");
		$data["to_date"]=date("Y-m-d");
		if($this->input->post("from_date")){
			$data["from_date"]=$this->input->post("from_date");
		}
		if($this->input->post("to_date")){
			$data["to_date"]=$this->input->post("to_date");
		}
		$this->load->model("Reports_model","rm");
		$data["list"]=$this->rm->sales_report(array("from_date"=>$data["from_date"],"to_date"=>$data["to_date"]));
		$data["page_title"]="Sales Report";

		$this->load->view("admin/reports/sales_report",$data);
	}

	function product_wise_sales_report(){
		$data["from_date"]=date("Y-m-d");
		$data["to_date"]=date("Y-m-d");
		if($this->input->post("from_date")){
			$data["from_date"]=$this->input->post("from_date");
		}
		if($this->input->post("to_date")){
			$data["to_date"]=$this->input->post("to_date");
		}
		$this->load->model("Reports_model","rm");
		$data["list"]=$this->rm->product_wise_sales_report(array("from_date"=>$data["from_date"],"to_date"=>$data["to_date"]));
		$data["page_title"]="Product Wise Sales Report";

		$this->load->view("admin/reports/product_wise_sales_report",$data);
	}

	function product_variant_wise_sales_report(){
		$data["from_date"]=date("Y-m-d");
		$data["to_date"]=date("Y-m-d");
		if($this->input->post("from_date")){
			$data["from_date"]=$this->input->post("from_date");
		}
		if($this->input->post("to_date")){
			$data["to_date"]=$this->input->post("to_date");
		}
		$this->load->model("Reports_model","rm");
		$data["list"]=$this->rm->product_variant_wise_sales_report(array("from_date"=>$data["from_date"],"to_date"=>$data["to_date"]));
		$data["page_title"]="Product Variant Wise Sales Report";

		$this->load->view("admin/reports/product_variant_wise_sales_report",$data);
	}

	function pin_code_search_report(){
		$data["from_date"]=date("Y-m-d");
		$data["to_date"]=date("Y-m-d");
		if($this->input->post("from_date")){
			$data["from_date"]=$this->input->post("from_date");
		}
		if($this->input->post("to_date")){
			$data["to_date"]=$this->input->post("to_date");
		}
		$this->load->model("Reports_model","rm");
		$data["list"]=$this->rm->pin_code_search_report(array("from_date"=>$data["from_date"],"to_date"=>$data["to_date"]));
		$data["page_title"]="Pin Code Search Report";

		$this->load->view("admin/reports/pin_code_search_report",$data);
	}



}
?>