<?php
class Order_model extends CI_Model {
 
    public function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }
    
    public function order_count() {
        return $this->db->count_all("order");
    }
 
   public function get_orders($args=array()) {
     $this->db->select("ord.*,date_format(ord.date,'%d/%m/%Y %r') as date,date_format(ord.expected_delivery_date,'%d/%m/%Y') as expected_delivery_date,ord.status as order_status_id,a.*,os.name as status_name,dt.name as delivery_timming_name,du.name as delivery_person_name,dt.from_time as delivery_timming_slot_from_time");
     $this->db->from("order ord");
     $this->db->join("address a","a.id=ord.address_id","left");
     $this->db->join("order_status os","os.id=ord.status","left");
     $this->db->join("delivery_timming dt","dt.id=ord.delivery_timming_id","left");
     $this->db->join("user du","du.user_id=ord.delivery_person_id","left");
     $this->db->where("ord.type",1);
     if(isset($args["from_date"]) && $args["from_date"]!=""){
        $this->db->where("date(ord.date) >=",$args["from_date"]);
	 }
     if(isset($args["to_date"]) && $args["to_date"]!=""){
        $this->db->where("date(ord.date) <=",$args["to_date"]);
     }
     if(isset($args["status"]) && $args["status"]!=""){
        $this->db->where("ord.status",$args["status"]);
     }
     $this->db->order_by("ord.order_id","desc");
     return $this->db->get()->result_array();
   }
 
 
	 function product($id){
        $query = $this->db->query('SELECT product.*, product_description.*
		FROM product
		INNER JOIN product_description ON product.id=product_description.product_id 
		WHERE product_description.language_id = '.$this->session->userdata('lang').' AND product.id = '.$id.'');
        return $query->result();
 
    }
	public function detail($id){
			$this->db->select('order.*');
			$this->db->where('order_id', $id);
	        $query = $this->db->get("order");
			return $query->row_array();
	
	}
	public function update_comment($data){
			$update = array(
               'comment' => $data['comment'],
               'status' => $data['status']
            );
			$this->db->where('order_id', $data['order_id']);
			return $this->db->update('order', $update); 
	}
	
 	public function products($id){
 			$this->db->select("p.image,od.product_id,pd.name as product_name,pov.wb_price,pov.old_price,od.unit_price as price,od.count,od.options as variant_id,ov.value_name as variant_name,od.oid,od.unit");
        $this->db->from("order_detail od");
        $this->db->join("order ord","ord.order_id=od.order_id and ord.type=1","inner");
        $this->db->join("product p","p.id=od.product_id","inner");
        $this->db->join("product_description pd","pd.product_id=od.product_id and pd.language_id=2","inner");
        $this->db->join("product_option_value pov","pov.id=od.options","inner");
        $this->db->join("option_value ov","ov.option_value_id=pov.value_id","inner");
        $this->db->where("ord.order_id",$id);
        return $this->db->get()->result_array();
	}
	public function product_options($options_id)
		 {
		 if($options_id){
				$this->db->join('option_value', 'option_value.option_value_id = product_option_value.value_id');
 				$this->db->where('option_value.language_id', 2);
				$this->db->where_in('id', $options_id);
 				$query = $this->db->get("product_option_value");
				return $query->result();
			}
 
 
    }
	public function productadd($data){
		$main_update = $this->db->query("INSERT INTO order_detail SET order_id = '" . $data['order_id'] . "', product_id = '" . $data['product_id'] . "', count = '" . $data['count'] . "'");
					
		if($main_update){
			return true;
		}
	
	}
	public function productdelete($data){
 		$main_update = $this->db->query("DELETE FROM order_detail WHERE oid = '" . (int)$data . "'");
					
		if($main_update){
			return true;
		}
	
	}

	function order_status_list(){
		return $this->db->get_where("order_status",array("status"=>1))->result_array();
	}

	function order_status_update($status_id,$reason,$order_id){
		$this->db->trans_begin();
		if($status_id==1 || $status_id==2){
			$this->db->update("order",array("status"=>$status_id),array("order_id"=>$order_id),1);
		}elseif($status_id==3){
			$this->db->update("order",array("status"=>$status_id,"delivered_on"=>date("Y-m-d H:i:s")),array("order_id"=>$order_id),1);
		}else{
			$this->db->update("order",array("status"=>$status_id,"cancelled_on"=>date("Y-m-d H:i:s"),"cancel_reason"=>$reason),array("order_id"=>$order_id),1);

		}

		//echo $this->db->last_query(); exit();
		
		$this->db->insert("order_status_log",array("order_id"=>$order_id,"status"=>$status_id,"created_on"=>date("Y-m-d H:i:s")));
		if($this->db->trans_status()===FALSE){
			$this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}
	}


	function assign_delivery_person($delivery_person_id,$order_id){
		return $this->db->update("order",array("delivery_person_id"=>$delivery_person_id),array("order_id"=>$order_id),1);
	}


	
	
}