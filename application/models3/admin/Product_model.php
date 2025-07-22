<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model {

public function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
}
  
 
function get_products(){
        /*$query = $this->db->query('SELECT product.*, product_description.* 
			FROM product
			INNER JOIN product_description ON product.id=product_description.product_id 
			WHERE product_description.language_id =2
			ORDER BY product.id');
        return $query->result();*/

        $this->db->select("p.*,pd.*,IFNULL(pov.price,'') as price,IFNULL(pov.old_price,'') as old_price,cd.category_name,bd.name as brand_name");
        $this->db->from("product p");
        $this->db->join("product_description pd","p.id=pd.product_id","left");
        $this->db->join("brand_description bd","bd.brand_id=p.brand_id","left");
        $this->db->join("category_description cd","cd.category_id=p.category_id","inner");
        $this->db->join("product_option_value pov","pov.product_id=p.id and pov.base=1","left");
        $this->db->join("option_value ov","ov.option_value_row_id=pov.value_id","left");
        $this->db->where("pd.language_id",2);
        $this->db->order_by("p.id");
        $this->db->group_by("p.id");
        return $this->db->get()->result();
 
}

 public function Add($data){
 	$this->db->trans_begin();
 		$main_update = $this->db->query("INSERT INTO product SET category_id = '" . (int)$data['category_id'] . "',  image = '" . $data['image'] . "', stock = '" . $data['stock'] . "', rank = '" . $data['rank'] . "',brand_id = '" . $data['brand_id'] . "'");
		
		/*if($main_update){
			return true;
		}*/
	
		$product_id = $this->db->insert_id();
		$slug_name="";

		foreach ($data['product_description'] as $language_id => $value) {
			if($language_id==2){
				$slug_name=seoString($value['name']);
			}
			$this->db->query("INSERT INTO product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . strip_tags($value['name']) . "', meta_tags = '" . strip_tags($value['meta_tags']) . "', meta_keys = '" . strip_tags($value['meta_keys']) . "', details = '" . $this->db->escape_str($value['details']) . "'");
		}

		$this->db->update("product",array("slug_name"=>$slug_name),array("id"=>$product_id),1);

		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return $product_id;
		}
 }
 
 public function delete($data){
		$main_update = $this->db->query("DELETE FROM product WHERE id = '" . (int)$data . "'");
		$main_update = $this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$data . "'");
		
		if($main_update){
			return true;
		}
	
 }
   
 public function product_count() {
        return $this->db->count_all("category");
    }
 
  
 public function product($id){
			$this->db->where('id', $id);
 	        $query = $this->db->get("product");
			echo json_encode($query->result());
			return $query->result();
	}
 
	 public function product_description($id) {
		
		$product_description_data = array();
		$query = $this->db->query("SELECT * FROM product_description WHERE product_id = '" . (int)$id . "'");
		foreach ($query->result() as $result) {
			$product_description_data[$result->language_id] = array(
				'name'    		=> $result->name,
				'details'     	=> $result->details,
				'meta_tags'		=> $result->meta_tags,
				'meta_keys'		=> $result->meta_keys,
			);
		}
		return $product_description_data;
	}
	
 public function update($product_id, $data){ // of Opencart

 	$this->db->trans_begin();
		/*
		$main_update = $this->db->query("UPDATE product SET category_id = '" . (int)$data['category_id'] . "', brand_id = '".$data['brand_id']."',  url = '".$data['url']."', image = '" . $data['image'] . "', stock = '" . $data['stock'] . "', price = '" . $data['price'] . "', rank = '" . (int)$data['rank'] . "' WHERE id = '" . (int)$product_id . "'");*/
		$pr_tb_update_fields=array(
			"category_id"=>$data['category_id'],
			"brand_id"=>$data['brand_id'],
			"url"=>$data["url"],
			"stock"=>$data["stock"],
			"price"=>$data["price"],
			"rank"=>$data["rank"]);
		if($data["image"]!=""){
			$pr_tb_update_fields["image"]=$data["image"];
		}

		$this->db->update("product",$pr_tb_update_fields,array("id"=>$product_id),1);
 		

		$slug_name="";
		$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");
			foreach ($data['product_description'] as $language_id => $value) {
			if($language_id==2){
				$slug_name=seoString($value['name']);
			}
			$this->db->query("INSERT INTO `product_description` SET `product_id` = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', `name` = '" . $value['name'] . "', meta_tags = '" . $value['meta_tags'] . "', meta_keys = '" . $value['meta_keys'] . "', details = '" . $this->db->escape_str($value['details']) . "'");
		}

		$this->db->update("product",array("slug_name"=>$slug_name),array("id"=>$product_id),1);

		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
 
	}
	
	function get_options($id){
				$this->db->where('product_id', $id);
				$this->db->where('option_description.language_id', $this->session->userdata('lang'));
				$this->db->join('option', 'option.id = product_option.option_id');
				$this->db->join('option_description', 'option_description.option_id = product_option.option_id');
				$query = $this->db->get("product_option");
				return $query->result();
	}

	function get_values($option_id, $product_id){
				$this->db->select('option_value.*, product_option_value.*, product_option_value.id AS pr_value_id');
				$this->db->where('option_id', $option_id);
				$this->db->where('product_id', $product_id);
				$this->db->join('product_option_value', 'product_option_value.value_id = option_value.id');
				$this->db->where('language_id', $this->session->userdata('lang'));
				$query = $this->db->get("option_value");
				return $query->result();
	}

	function status_update($product_id,$active){
		$this->db->trans_begin();
		/*print_r($product_id);
		print_r($active);*/
		/*for($i=0;$i<sizeof($product_id);$i++){
			if(isset($active[$i]) && $active[$i]=$product_id[$i]){
				$this->db->update("product",array("status"=>1),array("id"=>$product_id[$i]),1);
			}else{
				$this->db->update("product",array("status"=>0),array("id"=>$product_id[$i]),1);
				echo $this->db->last_query(); exit();
			}
		}*/
		foreach($product_id as $key=>$val){
			if(isset($active[$key]) && $active[$key]==$val){
				$this->db->update("product",array("status"=>1),array("id"=>$val),1);
			}else{
				$this->db->update("product",array("status"=>0),array("id"=>$val),1);
				//echo $this->db->last_query(); exit();
			}
		}



		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
}


    

 
 