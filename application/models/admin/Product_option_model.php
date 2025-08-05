<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_option_model extends CI_Model { 

public function __construct(){
    // Call the Model constructor
    parent::__construct();
    $this->load->database();
}

function product_details($product_id){
	$this->db->select("pd.*,p.base_unit_value_stock,p.show_unit_in,base_unit_value");
	$this->db->from("product p");
	$this->db->join("product_description pd","pd.product_id=p.id","inner");
	$this->db->join("option_value ov","ov.option_value_row_id=show_unit_in","left");
	$this->db->where("p.id",$product_id);
	$data=$this->db->get()->row();
	//echo $this->db->last_query(); exit();
	return $data;
	/*return $this->db->get_where("product_description",array("product_id"=>$product_id))->row();*/
}

function product_shared_stock_unit_row_id($show_unit_in,$product_id){
	$get=$this->db->get_where("product_option_value",array("value_id"=>$show_unit_in,"product_id"=>$product_id));
	if($get->num_rows()==1){
		return $get->row()->id;
	}else{
		return 0;
	}

}

  
 
function get_options($id){
				$this->db->select('product_option.*, option.*, option_description.*, product_option.id AS pr_opt_id');
				$this->db->where('product_option.product_id', $id);
				$this->db->where('option_description.language_id', 2);
				$this->db->join('option', 'option.id = product_option.option_id');
				$this->db->join('option_description', 'option_description.option_id = product_option.option_id');
				$query = $this->db->get("product_option");
				return $query->result();
 
}
function get_option_result($option_type){
				$this->db->select('option.*, option_description.*, option.id AS opt_value_id');
 				$this->db->where('option.option_type', $option_type);
 				$this->db->where('option_description.language_id',2);
				$this->db->join('option_description', 'option.id = option_description.option_id');
 				$query = $this->db->get("option");
				$data=$query->result();
				//echo $this->db->last_query();exit();
				return $data;
 
} 
 
 
function get_option_type_list(){
 				$query = $this->db->get("option");
				return $query->result();
 
} 

function product_option_exist($product_id){
	$this->db->select("count(*) as found");
	$this->db->from("product_option");
	$this->db->where("product_id",$product_id);
	return $this->db->get()->row()->found;
}

function product_option_value_exist($product_id){
	$this->db->select("count(*) as found");
	$this->db->from("product_option_value");
	$this->db->where("product_id",$product_id);
	return $this->db->get()->row()->found;
}
 public function add_new_option($data){
 	return $this->db->insert("product_option",array("product_id"=>$data['product_id'],"option_id"=>$data['option_id']));
 		/*return $this->db->query("INSERT INTO product_option SET product_id = '" . $data['product_id'] . "',  option_id = '" . $data['option_id'] . "'");*/
		
	
 }
 
  public function add_new_value($data){
  $base=0;
  if($this->product_option_value_exist($data['product_id'])==0){
    $base=1;
  }
  // Check for duplicate size
  $exists = $this->db->get_where('product_option_value', array('product_id' => $data['product_id'], 'value_id' => $data['value_id']));
  if ($exists->num_rows() > 0) {
    // Size already exists, do not insert
    return false;
  }
  return $this->db->query("INSERT INTO product_option_value SET product_id = '" . $data['product_id'] . "',  value_id = '" . $data['value_id'] . "', old_price = '" . $data['old_price'] . "', wb_price = '" . $data['wb_price'] . "', up_price = '" . $data['up_price'] . "', base = '" . $base . "',  stock = '" . $data['stock'] . "',  disc = '" . $data['disc'] . "'");
 }	
 public function value_update($data){  
  $this->db->trans_begin();
  for ($i = 0; $i < count($data['pr_value_id']); $i++) {
    if(isset($data["default"]) && $data['pr_value_id'][$i]==$data["default"]){
      $base=1;
    }else{
      $base=0;
    }
    $this->db->update("product_option_value",
      array(
        // "price"=>$data['price'][$i],
        "old_price"=>$data['old_price'][$i],
        "wb_price"=>$data['wb_price'][$i],
        "up_price"=>$data['up_price'][$i],
        "base"=>$base,
        "stock"=>$data['stock'][$i],
        "disc"=>$data['disc'][$i],
      ),array("id"=>$data['pr_value_id'][$i])
    );

    if($data["stock_mode"]==1){
      $this->db->update("product",array("base_unit_value_stock"=>0,"show_unit_in"=>0),array("id"=>$data["product_id"]),1);
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
	
 public function delete_value($data){
		return $this->db->query("DELETE FROM product_option_value WHERE id = '" . (int)$data . "'");
		//echo $this->db->last_query(); exit();
 }
 
 public function delete($data){
		$main_update = $this->db->query("DELETE FROM product_option WHERE id = '" . (int)$data . "'");
		
		if($main_update){
			return true;
		}
 
 }
	function get_values($option_id, $product_id){
		$this->db->select('option_value.*, product_option_value.*, product_option_value.id AS pr_value_id, product_option_value.wb_price, product_option_value.up_price, option_value.order');
		$this->db->where('option_id', $option_id);
		$this->db->where('product_id', $product_id);
		$this->db->join('product_option_value', 'product_option_value.value_id = option_value.option_value_id');
		$this->db->where('language_id', 2);
		$this->db->order_by('option_value.order', 'ASC'); // Sort by order field
		$query = $this->db->get("option_value");
		return $query->result();
		//echo $this->db->last_query(); exit();

	}

	function get_values_list($option_id){
		$this->db->where('option_id', $option_id);
		$this->db->where('language_id', 2);
		$this->db->order_by('value_name', 'ASC'); // Sort by size name ascending
		$query = $this->db->get("option_value");
		return $query->result();
	}

	function update_shared_stock($shared_stock,$option_value_id,$product_id){
		$this->db->trans_begin();
		$this->db->select("base_unit_value,option_value_row_id");
		$this->db->from("option_value ov");
		$this->db->join("product_option_value pov","pov.value_id=ov.option_value_row_id","inner");
		$this->db->where("pov.id",$option_value_id);
		$details=$this->db->get()->row();
		//echo $this->db->last_query(); exit();
		$base_unit_value=$details->base_unit_value;
		$option_value_row_id=$details->option_value_row_id;
		$updated_stock_entry=$shared_stock*$base_unit_value;

		$this->db->update("product",array("base_unit_value_stock"=>$updated_stock_entry,"show_unit_in"=>$option_value_row_id),array("id"=>$product_id),1);
		//echo $this->db->last_query(); exit();

		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}
  
}





