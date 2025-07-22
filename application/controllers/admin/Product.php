<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller {

 
		function __construct(){
			parent::__construct();
			$this->load->library("session");
			$this->load->helper("url");
			$this->load->helper("site");
			if(! $this->session->userdata('validated')){
            	redirect('admin/account/login');
        	}
		
		}
 
 
	public function index(){
 
	}
	
	public function lists(){
		$this->load->helper("form");
		$this->load->model('admin/product_model');
		$this->load->library("pagination");
 
		$config = array();
        	$config["base_url"] = base_url() . "admin/product/lists";
        	$config["total_rows"] = $this->product_model->product_count();
        	$config["per_page"] = 100000;
        	$config["uri_segment"] = 4;
 
        	$this->pagination->initialize($config);
 
        	$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        	$data["links"] = $this->pagination->create_links();
		$data["products"] = $this->product_model->get_products($config["per_page"], $page);
		$this->load->view('admin/product/list', $data);
   }

	function status_update(){
		$product_id=$this->input->post("product_id");
		$active=$this->input->post("active");
		$this->load->model('admin/product_model','pm');
		$updated=$this->pm->status_update($product_id,$active);
		if($updated){
			$this->session->set_flashdata('action_message', 'Product updated!');
			$this->session->set_flashdata('action_message_type', 'success');
			redirect($_SERVER['HTTP_REFERER']);
		}else{
			$this->session->set_flashdata('action_message', 'Product not updated!. An error has occured.');
			$this->session->set_flashdata('action_message_type', 'danger');
			redirect($_SERVER['HTTP_REFERER']);
		}

	}
   
 
   public function detail(){
   	$this->load->helper("form");
   	$this->load->model('admin/product_model');
	$this->load->model('admin/category_model');
 	$this->load->model('admin/language_model');
 	$this->load->model('Brand_model','bm');
	
 	$data["languages"] = $this->language_model->languages();
		$product_infos = $this->product_model->product($this->uri->segment(4));
 
	foreach($product_infos as $product_info){
			$data["id"] = $product_info->id;
			$data["category_id"] = $product_info->category_id;
			$data["category_name"] = $this->category_model->get_category($product_info->category_id);
			$data["url"] = $product_info->url;
			$data["rank"] = $product_info->rank;
			$data["wb_price"] = $product_info->wb_price;
			$data["wb_old_price"] = $product_info->wb_old_price;
			$data["up_price"] = $product_info->up_price;
			$data["up_old_price"] = $product_info->up_old_price;
			$data["stock"] = $product_info->stock;
			$data["image"] = $product_info->image;
			$data["brand_id"]=$product_info->brand_id;
			$data["product_description"] = $this->product_model->product_description($product_info->id);
			$data["brands"]=(object)$this->bm->brand_list();
		}
 
			$start = 0;
			$limit = 100000000;
 			$data["categories"] = $this->category_model->get_categories($start, $limit);

 		/*echo "<pre>";
 		print_r($data); exit();*/

   		$this->load->view('admin/product/detail', $data);

   }
   public function Add(){
	$this->load->model('admin/Category_model','cm');
	$this->load->model('Brand_model','bm');
	$this->load->model('admin/Product_model','pm');
	$this->load->model('admin/Language_model','lm');
	$this->load->model("admin/Option_model","om");
       	if($_POST){
			$add = false;
			$image=$this->file_uploader("image");
			$_POST["image"]=base_url("uploads/images/".$image);
			$product_id = $this->pm->add($_POST);
			if($product_id){
				$this->session->set_flashdata('action_message', 'New product added!');
				$this->session->set_flashdata('action_message_type', 'success');
				//$this->session->set_flashdata("redirection_uri","admin/product/lists");
				redirect("admin/product_option/detail/25/".$product_id);
				//redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('action_message', 'New product not added!. An error has occured.');
				$this->session->set_flashdata('action_message_type', 'danger');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
	
 
	
	
	$data["languages"] = $this->lm->languages();
			$start = 0;
			$limit = 100000000;
 			$data["categories"] = $this->cm->get_categories($start, $limit);
 			$data["brands"]=(object)$this->bm->brand_list();
 			$data["options"]=$this->om->get_global_option_values();
			
	$this->load->view('admin/product/add', $data);
		 
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
   
   public function update(){
   	$this->load->model('admin/product_model');

        	if($_POST){

        	if(empty($_FILES['image']['name'])){
				$_POST["image"]="";
			}else{
				$main_image=$this->file_uploader("image");
				$_POST["image"]=base_url("uploads/images/".$main_image);
			}	

			$update = false;
			$update = $this->product_model->update($this->uri->segment(4), $_POST);
			if($update){
				$this->session->set_flashdata('action_message', 'Product updated!');
				$this->session->set_flashdata('action_message_type', 'success');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('action_message', 'Product not updated!. An error has occured.');
				$this->session->set_flashdata('action_message_type', 'danger');
				redirect($_SERVER['HTTP_REFERER']);
			}
	}
		
	 
   
   }
   
   public function delete(){
	$this->load->model('admin/product_model');
 
 
 
			$delete = false;
			$delete = $this->product_model->delete($this->uri->segment(4));
			if($delete){
				$this->session->set_flashdata('action_message', 'Product deleted!');
				$this->session->set_flashdata('action_message_type', 'success');
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$this->session->set_flashdata('action_message', 'Product not deleted!. An error has occured.');
				$this->session->set_flashdata('action_message_type', 'danger');
				redirect($_SERVER['HTTP_REFERER']);
			}
 
   }
   
   
}
 