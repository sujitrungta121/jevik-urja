<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pin_code_model extends CI_Model { 

  public function __construct(){
    // Call the Model constructor
    parent::__construct();
    $this->load->database();
  }
 
  function listData(){
  	return $this->db->get_where("pin_code",array("status"=>1))->result_array();
  }


  function save($pin_code,$existing_code=""){
    if($existing_code==""){
      return $this->db->insert("pin_code",array("code"=>$pin_code,"status"=>1));
    }else{
      return $this->db->update("pin_code",array("code"=>$pin_code),array("code"=>$existing_code),1);
    }
  	
  }


  function delete($pin_code){
  	return $this->db->delete("pin_code",array("code"=>$pin_code));
  }
  
}


    

 
 