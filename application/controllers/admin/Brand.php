<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brand extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library("session");
		$this->load->helper("url");
		$this->load->helper("site");
		if(! $this->session->userdata('validated')){
        	redirect('admin/account/login');
    	}
	}

	function lists(){
		$this->load->helper("form");
		$data=array();
		$this->load->model("Brand_model","bm");
		$data["list"]=$this->bm->brand_list();
		$this->load->view("admin/brand/list",$data);

	}

	function form($brand_id=""){


		//exit("sadfasdfas");
		$this->load->helper("form");
		$this->load->model('Brand_model','bm');
		$this->load->model('admin/language_model');	
		$data["brand_id"]=$brand_id;
		if($brand_id!=""){
			$data["desc_data"]=$this->bm->brand_description_data($brand_id);
			$data["brand_user_data"]= $this->bm->brand_user_data($brand_id);
			//print_r($data['brand_user_data']); exit();
		}else{
			$data["desc_data"]=array();
		}
	   	if($_POST){
	   		/*echo "<pre>";
	   		print_r($_POST);
	   		EXIT();*/
	   		if(empty($_FILES['image']['name']) && $brand_id!="" ){
				$_POST["image"]="";
			}else{
				$main_image=$this->file_uploader("image");
				$_POST["image"]=base_url("uploads/images/".$main_image);
			}	
				$add = $this->bm->save($_POST,$brand_id);
				if($add){
					$this->session->set_flashdata('action_message', 'Brand Data Saved Successfully!');
					$this->session->set_flashdata('action_message_type', 'success');
					redirect($_SERVER['HTTP_REFERER']);
				}else{
					$this->session->set_flashdata('action_message', 'Unable To Save Brand Data !. An error has occured.');
					$this->session->set_flashdata('action_message_type', 'danger');
					redirect($_SERVER['HTTP_REFERER']);
				}
		}

		
		$data["languages"] = $this->language_model->languages();
		//exit();
		$this->load->view('admin/brand/add', $data);
	}


	function remove(){
		$brand_id=$this->input->post("brand_id");
		$this->load->model('Brand_model','bm');
		$removed=$this->bm->remove($brand_id);
		if($removed){
			$this->session->set_flashdata('action_message', 'Brand Data Deleted Successfully!');
			$this->session->set_flashdata('action_message_type', 'success');
			redirect($_SERVER['HTTP_REFERER']);
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Delete Brand Data !. An error has occured.');
			$this->session->set_flashdata('action_message_type', 'danger');
			redirect($_SERVER['HTTP_REFERER']);
		}

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
 
}