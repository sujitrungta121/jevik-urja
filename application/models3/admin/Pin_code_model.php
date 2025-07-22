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
  
}


    

 
 