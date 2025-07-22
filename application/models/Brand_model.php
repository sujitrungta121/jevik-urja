<?php
class Brand_model extends CI_Model
{
     public function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function brand_list(){
        $this->db->select("b.*,bd.*");
        $this->db->from("brand b");
        $this->db->join("brand_description bd","bd.brand_id=b.id and bd.language_id=2","inner");
        return $this->db->get()->result();
    }

    function brand_user_data($brand_id){
        $bd=$this->db->get_where('brand',array('id'=>$brand_id))->row();
        if(isset($bd->id)){
           // print_r($bd); exit();
           return $this->db->get_where('admin',array('id'=>$bd->admin_id))->row();
           //echo $this->db->last_query(); exit();
        }else{
            $user= new stdClass();
            $user->id='';
            $user->email= '';
            $user->password='';
            $user->name = '';
            return $user;
        }
        
    }

    function brand_description_data($brand_id){
        $this->db->select("*");
        $this->db->from("brand_description");
        $this->db->where("brand_id",$brand_id);
        $list=$this->db->get()->result_array();
        $array=array();
        foreach($list as $row){
            $array[$row["language_id"]]=$row;
        }
        return $array;
    }

    function remove_desc($brand_id){
        return $this->db->delete("brand_description",array("brand_id"=>$brand_id));
    }

    function remove($brand_id){
        $this->db->trans_begin();
        $this->db->delete("brand",array("id"=>$brand_id),1);
        $this->db->delete("brand_description",array("brand_id"=>$brand_id));
        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }


    public function save($data,$brand_id){
        $this->db->trans_begin();
        if($data["image"]!="" && $brand_id!="" ){
            $this->db->update("brand",array("image"=>$data["image"]),array("id"=>$brand_id),1);
        }
        if( $brand_id==""){
            $this->db->insert("brand",array("image"=>$data["image"]));
            $brand_id=$this->db->insert_id();
        }
        $slug_name="";
        $this->remove_desc($brand_id);
        for($i=0;$i<sizeof($data["name"]); $i++){
            if($data["language_id"][$i]=="2"){
                $slug_name=seoString($data["name"][$i]);
            }
            $this->db->insert("brand_description",array("language_id"=>$data["language_id"][$i],"description"=>$data["description"][$i],"name"=>$data["name"][$i],"brand_id"=>$brand_id));

        }
        $this->db->update("brand",array("slug_name"=>$slug_name),array("id"=>$brand_id),1);

        $brandData=$this->db->get_where('brand',array('id'=>$brand_id))->row();

        if($data['email']!='' && $brandData->admin_id==''){
            $this->db->insert('admin',array('email'=>$data['email'],'password'=>$data['password'],'name'=>$data['email'],'type'=>2));
            $admin_id=$this->db->insert_id();
            $this->db->update('brand',array('admin_id'=>$admin_id),array('id'=>$brandData->id));

        }elseif($data['email']!='' && $brandData->admin_id!=''){
             $this->db->update('admin',array('email'=>$data['email'],'password'=>$data['password'],'name'=>$data['email']),array("id"=>$brandData->admin_id));
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
