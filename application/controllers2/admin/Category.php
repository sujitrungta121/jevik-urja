<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {

 
		function __construct()
		{
			parent::__construct();
			$this->load->library("session");
			$this->load->helper("url");
			$this->load->helper("site");
			$this->load->helper("form");
			if(! $this->session->userdata('validated')){
            	redirect('admin/account/login');
        	}
		}
	public function index(){

	}
	
	public function lists(){
		$this->load->model('admin/category_model');
		$this->load->library("pagination");
 
		$config = array();
        $config["base_url"] = base_url() . "admin/category/lists";
        $config["total_rows"] = $this->category_model->category_count();
        $config["per_page"] = 100000;
        $config["uri_segment"] = 4;
 
        $this->pagination->initialize($config);
 
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();
		$data["categories"] = $this->category_model->get_categories($page, $config["per_page"]);
 
 		// /print_r($data); exit();
		$this->load->view('admin/category/list', $data);
   }
   
 
   public function detail(){
   	$this->load->model('admin/category_model');
 	$this->load->model('admin/language_model');
	
 	$data["languages"] = $this->language_model->languages();
 	$category_infos = $this->category_model->category($this->uri->segment(4));
 
	foreach($category_infos as $category_info){
			$data["id"] = $category_info->id;
			$data["parent_id"] = $category_info->parent_id;
			$data["parent_name"] = $this->category_model->get_category($category_info->id);
			$data["link"] = $category_info->link;
			$data["rank"] = $category_info->rank;
			$data["category_description"] = $this->category_model->category_description($category_info->id);
}
			$start = 0;
			$limit = 100000000;
 			$data["categories"] = $this->category_model->get_categories($start, $limit);

   		$this->load->view('admin/category/detail', $data);

   }
   public function Add(){
	   	$this->load->helper("form");
	   	$this->load->helper("site");
		$this->load->model('admin/category_model');
		$this->load->model('admin/language_model');	
	   	if($_POST){
				$add = false;
				$_POST["small_image"]="";
				$_POST["main_image"]="";
				$_POST["banner_image"]="";
				if($_FILES['small_image']['size']>0){
					$small_image=$this->file_uploader("small_image");
					$_POST["small_image"]=base_url("uploads/images/".$small_image);
					//echo "image path : ".$_POST["small_image"]; exit();
				}
				if($_FILES['main_image']['size']>0){
					$main_image=$this->file_uploader("main_image");
					$_POST["main_image"]=base_url("uploads/images/".$main_image);
					//echo "image path : ".$_POST["main_image"]; exit();
				}

				if($_FILES['banner_image']['size']>0){
					$banner_image=$this->file_uploader("banner_image");
					$_POST["banner_image"]=base_url("uploads/images/".$banner_image);
					//echo "image path : ".$_POST["banner_image"]; exit();
				}
				
				
				
				$add = $this->category_model->add($_POST);
				if($add){
					$this->session->set_flashdata('action_message', 'New Category added!');
					$this->session->set_flashdata('action_message_type', 'success');
					redirect($_SERVER['HTTP_REFERER']);
				}else{
					$this->session->set_flashdata('action_message', 'New Category not added!. An error has occured.');
					$this->session->set_flashdata('action_message_type', 'danger');
					redirect($_SERVER['HTTP_REFERER']);
				}
		}


		
	 
		
		
		
		$data["languages"] = $this->language_model->languages();
				$start = 0;
				$limit = 100000000;
	 			$data["categories"] = $this->category_model->get_categories($start, $limit);
				
		$this->load->view('admin/category/add', $data);
		 
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
                        return $data["file_name"];
                }
	}
   
   public function update($id){
   	$this->load->model('admin/category_model');
   	//print_r($_FILES);exit();
   	if($_POST){
			$update = false;
			if($_FILES['small_image']['size']>0){
				$small_image=$this->file_uploader("small_image");
				$_POST["small_image"]=base_url("uploads/images/".$small_image);
				//echo "image path : ".$_POST["small_image"]; exit();
			}
			if($_FILES['main_image']['size']>0){
				$main_image=$this->file_uploader("main_image");
				$_POST["main_image"]=base_url("uploads/images/".$main_image);
				//echo "image path : ".$_POST["main_image"]; exit();
			}

			if($_FILES['banner_image']['size']>0){
				$banner_image=$this->file_uploader("banner_image");
				$_POST["banner_image"]=base_url("uploads/images/".$banner_image);
				//echo "image path : ".$_POST["banner_image"]; exit();
			}
			$update = $this->category_model->update($id, $_POST);
			if($update){
				$this->session->set_flashdata('action_message', 'Category updated!');
				$this->session->set_flashdata('action_message_type', 'success');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('action_message', 'Category not updated!. An error has occured.');
				$this->session->set_flashdata('action_message_type', 'danger');
				redirect($_SERVER['HTTP_REFERER']);
			}
	}
   }
   
   public function delete(){
	$this->load->model('admin/category_model');
			
	if(intval($this->uri->segment(4)) > 0){
			$delete = false;
			$delete = $this->category_model->delete($this->uri->segment(4));
			if($delete){
				$this->session->set_flashdata('action_message', 'Category deleted!');
				$this->session->set_flashdata('action_message_type', 'success');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('action_message', 'Category not deleted!. An error has occured.');
				$this->session->set_flashdata('action_message_type', 'danger');
				redirect($_SERVER['HTTP_REFERER']);
			}
	} 
   }
   
   
}
 