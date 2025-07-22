<?php 
/********************************************
Currency Price calculate and info
********************************************/
class currency_library {
	  
    function currency()
    {
		$this->CI = & get_instance();
		$this->CI->load->library('session');
		$this->CI->load->database();
		$query = $this->CI->db->query('SELECT * FROM currency WHERE id =3');
		//echo $this->CI->db->last_query(); exit();
 		return $query->result();

    } 
}
	