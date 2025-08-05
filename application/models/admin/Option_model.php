<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Option_model extends CI_Model { 

  public function __construct(){
    // Call the Model constructor
    parent::__construct();
    $this->load->database();
  }
 
function get_options(){
				$this->db->select('option.*, option_description.*');
 				$this->db->where('option_description.language_id', 2);
 				$this->db->join('option_description', 'option_description.option_id = option.id');
				$this->db->order_by('option.rank', 'ASC');
				$query = $this->db->get("option");

				//echo $this->db->last_query(); exit();
				return $query->result();
 
}
function get_option_result($option_type){
				$this->db->select('option.*, option_description.*, option.id AS opt_value_id');
 				$this->db->where('option.option_type', $option_type);
 				$this->db->where('option_description.language_id', $this->session->userdata('lang'));
				$this->db->join('option_description', 'option.id = option_description.option_id');
 				$query = $this->db->get("option");
				return $query->result();
 
} 
 
 
function get_option_list(){
 				$query = $this->db->get("option");
				return $query->result();
 
} 
 public function add_new_option($data){
 		$this->db->trans_begin();
		foreach ($data['option'] as $language_id => $value) {
			$this->db->query("INSERT INTO option_description SET option_id = '" . (int)$data['option_id'] . "', language_id = '" . (int)$language_id . "', option_name = '" . $value['option_name'] . "'");
		}

		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
 }

 function check_value_exist($data){
 	$found=0;
 	foreach ($data['value'] as $language_id => $value) {
		$this->db->select("count(option_id) as found");
		$this->db->from("option_value");
		$this->db->where("option_id",$value['option_id']);
		$this->db->where("language_id",$language_id);
		$this->db->where("value_name",$value['value_name']);
		$found+=$this->db->get()->row()->found;
	}
	return $found;
 }

 function check_bulk_value_exist($data){
 	$count=0;
 	for ($i = 0; $i < count($data['option_value_row_id']); $i++) {
			/*$main_update = $this->db->query("UPDATE option_value SET value_name = '".$data['value_name'][$i]."' WHERE option_value_row_id = '".$data['option_value_row_id'][$i]."'");*/
			$unit_det=$this->get_unit_details($data["unit"][$i]);
			$base_unit_value=$data["unit_value"][$i]*$unit_det->base;
			$value_name=$data["unit_value"][$i]." ".$unit_det->name;

			$this->db->select("count(*) as found");
			$this->db->from("option_value");
			$this->db->where("option_value_row_id !=",$data['option_value_row_id'][$i]);
			$this->db->where("value_name",$value_name);
			$count+=$this->db->get()->row()->found;

	 } 
	 return $count;
 }

 function get_unit_details($id){
 	return $this->db->get_where("unit",array("id"=>$id))->row();
 }

 function unit_list(){
 	return $this->db->get_where("unit",array("status"=>1))->result();
 }
 
 public function add_new_value($data){
 		$this->db->trans_begin();
		$last_id  = $this->db->select('option_value_row_id')->order_by('option_value_row_id','desc')->limit(1)->get('option_value')->row('option_value_row_id');
		$last_id = $last_id + 1;
	
		foreach ($data['value'] as $language_id => $value) {
			$unit_det=$this->get_unit_details($value['unit']);
			$base_unit_value=$value['value_name']*$unit_det->base;
			$value_name=$value['value_name']." ".$unit_det->name;
			



			/*$main_update = $this->db->query("INSERT INTO option_value SET option_value_id = '".(int)$last_id."', option_id = '" . (int)$value['option_id'] . "', language_id = '" . (int)$language_id . "', value_name = '" . $value['value_name'] . "'");*/
			$this->db->insert("option_value",array("option_value_id"=>$last_id,"option_id"=>$value['option_id'],"language_id"=>$language_id,"value_name"=>$value_name,"unit"=>$value['unit'],"base_unit_value"=>$base_unit_value,"order"=>$value['order']));
		}

		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
  }
 
	
 public function value_update($data){

 	$this->db->trans_begin();
   
	for ($i = 0; $i < count($data['option_value_row_id']); $i++) {
			/*$main_update = $this->db->query("UPDATE option_value SET value_name = '".$data['value_name'][$i]."' WHERE option_value_row_id = '".$data['option_value_row_id'][$i]."'");*/

			$unit_det=$this->get_unit_details($data["unit"][$i]);
			$base_unit_value=$data["unit_value"][$i]*$unit_det->base;
			$value_name=$data["unit_value"][$i]." ".$unit_det->name;

			$this->db->update("option_value",array("value_name"=>$value_name,"order"=>$data["order"][$i],"unit_value"=>$data["unit_value"][$i],"unit"=>$data["unit"][$i],"base_unit_value"=>$base_unit_value),array("option_value_row_id"=>$data['option_value_row_id'][$i]),1);
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
	$main_update = $this->db->query("DELETE FROM product_option_value WHERE id = '" . (int)$data . "'");


					
	if($main_update){
		return true;
	}
		
 }

 function delete($option_value_id){
 	return $this->db->delete("option_value",array("option_value_id"=>$option_value_id),1);
 }
 
 public function delete_option($data){
	$main_update = $this->db->query("DELETE FROM option_description WHERE id = '" . (int)$data . "'");
					
	if($main_update){
		return true;
	}
	
 }
	function get_values($option_id, $language_id){
		$this->db->select("ov.*,u.name as unit_name");
		$this->db->from("option_value ov");
		$this->db->join("unit u","u.id=ov.unit","left");
		$this->db->where('ov.option_id', $option_id);
		$this->db->where('ov.language_id', $language_id);
		return $this->db->get()->result();
				


	}


	
	function get_values_list($option_id, $product_id){
 				$this->db->where('option_id', $option_id);
 				$this->db->where('language_id', $this->session->userdata('lang'));
				$query = $this->db->get("option_value");
				return $query->result();


	}

	function get_global_option_values($language_id=2){
		$this->db->select("*");
		$this->db->from("option_value");
		$this->db->where("language_id",$language_id);
		return $this->db->get()->result_array();
	}
  
}


    

 
 