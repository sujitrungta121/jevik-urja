<?php
class Dashboard_model extends CI_Model {
 
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }
 function orders_count($args=array()){
     $this->db->select("count(order_id) as found");
     $this->db->from("order");
     if(isset($args["status"]) && $args["status"]!=""){
        $this->db->where("status",$args["status"]);
     }
     $this->db->where("type",1);
    return  $this->db->get()->row()->found;
 }
	function orders()
		{
		$query = $this->db->order_by('order_id')->get('order');
        return $query->result();
 
    }
 
}