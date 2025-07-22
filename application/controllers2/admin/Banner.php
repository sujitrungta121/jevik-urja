<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner extends CI_Controller {


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
 		$this->load->model("Banner_model","bm");
 		$data["list"]=$this->bm->listData(array());
 		$this->load->view("admin/banner_list",$data);
	}

	function save(){
		$pin_code=$this->input->post("pin_code");
		$this->load->model("Banner_model","bm");
		$image=$this->file_uploader("image");
		$image=base_url("uploads/images/".$image);

		//echo "image path : ".$image; exit();

		$save=$this->bm->save($image);
		if($save){
			$this->session->set_flashdata('action_message', 'Banner Added Successfully');
			$this->session->set_flashdata('action_message_type', 'success');
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Add Banner');
			$this->session->set_flashdata('action_message_type', 'danger');
		}
		redirect("admin/Banner");

	}


	function file_uploader($name){
		$config['upload_path']          = './uploads/images/';
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


	function status_update(){
		//print_r($_POST);exit();
		$id=$this->input->post("id");
		$status=$this->input->post("status");
		$this->load->model("Banner_model","bm");
		$updated=$this->bm->status_update($status,$id);
		if($updated){
			$this->session->set_flashdata('action_message', 'Banner Data Updated Successfully');
			$this->session->set_flashdata('action_message_type', 'success');
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Update Banner Data');
			$this->session->set_flashdata('action_message_type', 'danger');
		}
		redirect("admin/Banner");
	}
	
	
  
}
