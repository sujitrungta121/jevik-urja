<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library("session");
		$this->load->helper("url");
		$this->load->helper("site");
		if(! $this->session->userdata('validated')){
        	redirect('admin/account/login');
    	}
	}

	function index(){
		$this->lists();
	}

	function lists(){
		$this->load->helper("form");
		$data=array();
		$this->load->model("Page_model","pm");
		$data["list"]=$this->pm->listData();
		$this->load->view("admin/page/list",$data);

	}

	function form($page_id=""){


		//exit("sadfasdfas");
		$this->load->helper("form");
		$this->load->model('page_model','bm');
		$this->load->model('admin/language_model');	
		$data["page_id"]=$page_id;
		if($page_id!=""){
			$data["desc_data"]=$this->bm->page_description_data($page_id);
		}else{
			$data["desc_data"]=array();
		}
	   	if($_POST){
	   		/*echo "<pre>";
	   		print_r($_POST);
	   		EXIT();*/
	   		if(empty($_FILES['image']['name']) && $page_id!="" ){
				$_POST["image"]="";
			}else{
				if(!empty($_FILES['image']['name'])){
					$main_image=$this->file_uploader("image");
					$_POST["image"]=base_url("uploads/images/".$main_image);
				}else{
					$_POST["image"]="";
				}
				
			}	
				$add = $this->bm->save($_POST,$page_id);
				if($add){
					$this->session->set_flashdata('action_message', 'Data Saved Successfully!');
					$this->session->set_flashdata('action_message_type', 'success');
					redirect($_SERVER['HTTP_REFERER']);
				}else{
					$this->session->set_flashdata('action_message', 'Unable To Save Data !. An error has occured.');
					$this->session->set_flashdata('action_message_type', 'danger');
					redirect($_SERVER['HTTP_REFERER']);
				}
		}

		//exit();
		$data["languages"] = $this->language_model->languages();
		$this->load->view('admin/page/add', $data);
	}


	function remove(){
		$page_id=$this->input->post("page_id");
		$this->load->model('Page_model','pm');
		$removed=$this->pm->remove($page_id);
		if($removed){
			$this->session->set_flashdata('action_message', 'Data Deleted Successfully!');
			$this->session->set_flashdata('action_message_type', 'success');
			redirect($_SERVER['HTTP_REFERER']);
		}else{
			$this->session->set_flashdata('action_message', 'Unable To Delete Data !. An error has occured.');
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