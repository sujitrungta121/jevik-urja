<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Login extends CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->database();
    }
    
    public function validate(){
        // grab user input
        $username = $this->security->xss_clean($this->input->post('email'));
        $password = $this->security->xss_clean($this->input->post('password'));
        
        // Prep the query
        $this->db->where('email', $username);
        $this->db->where('password', $password);
        
        // Run the query
        $query = $this->db->get('admin');

        //echo $this->db->last_query(); exit();
        // Let's check if there are any results
        if($query->num_rows() == 1)
        {
            // If there is a user, then create session data
            $row = $query->row();
            $data = array(
                    'id' => $row->id,
                    'email' => $row->email,
                    'name' => $row->name,
                    'type' => $row->type,
                    'validated' => true
                    );
            $this->session->set_userdata($data);
			$_SESSION['validated']= true;

            return true;
        }
        // If the previous process did not validate
        // then return false.
        return false;
    }

    function get_login_user_details($email){
        $this->db->select("id,email,type,name");
        $this->db->from("admin");
        $this->db->where("email",$email);
        $ud=$this->db->get()->row();
        if($ud->type==2){
            $gt=$this->db->get_where("brand",array("admin_id"=>$ud->id));
            if($gt->num_rows()>0){
                $bd=$gt->row();
                $ud->brand_id=$bd->id;
            }
        }
        return $ud;

    }
}
?>