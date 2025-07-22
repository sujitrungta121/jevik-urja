<?php
class User_model extends CI_Model
{
    public function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function user_list($args=array()){
        $this->db->select("u.name,u.email,u.mobile,u.referral_code,date_format(u.created_on,'%d/%m/%Y %r') as created_on,u.user_id,u.status,a.pin_code,a.address");
        $this->db->from("user u");
        $this->db->join("address a","a.user_id=u.user_id and a.status=1","left");
        $this->db->where("u.user_type_id",$args["user_type_id"]);
        $this->db->where("u.status !=",0);
        $this->db->group_by("u.user_id");
        return $this->db->get()->result_array();
    }

    function status_update($status,$user_id){
        if($status!=0){
            $data=$this->db->update("user",array("status"=>$status),array("user_id"=>$user_id),1);
        }else{
            $data=$this->db->delete("user",array("user_id"=>$user_id));
        }
        
        //echo $this->db->last_query(); exit();
        return $data;
    }

    function save($name,$email,$mobile_no,$password,$type,$date_time,$id=""){
        $this->db->trans_begin();
        if($id==""){
            $this->db->insert("user",array("name"=>$name,"email"=>$email,"mobile"=>$mobile_no,"password"=>$password,"created_on"=>$date_time,"user_type_id"=>$type,"status"=>1));
        }else{
            $update_fields=array("name"=>$name,"email"=>$email,"mobile"=>$mobile_no);
            if($password!=""){
                $update_fields["password"]=$password;
            }
            $this->db->update("user",$update_fields);
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
