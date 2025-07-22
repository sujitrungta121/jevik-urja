<?php
class Banner_model extends CI_Model
{
    public function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function listData($args=array()){
       $this->db->select("*");
       $this->db->from("banner");
       if(isset($args["status"]) && $args["status"]!=""){
        $this->db->where($args["status"]);
       }
       return $this->db->get()->result_array();
    }

    function status_update($status,$id){
        if($status!=0){
          $data=$this->db->update("banner",array("status"=>$status),array("id"=>$id),1);
        }else{
          $data=$this->db->delete("banner",array("id"=>$id),1);
        }
        
        //echo $this->db->last_query(); exit();
        //echo $this->db->last_query(); exit();
        return $data;
    }

    function save($image){
        return $this->db->insert("banner",array("file_path"=>$image,"created_on"=>date("Y-m-d H:i:s"),"status"=>2));
    }
}
