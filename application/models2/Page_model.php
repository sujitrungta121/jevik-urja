<?php
class Page_model extends CI_Model
{
     public function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function listData(){
        $this->db->select("p.*,pd.*");
        $this->db->from("page p");
        $this->db->join("page_description pd","pd.page_id=p.id and pd.language_id=2","inner");
        return $this->db->get()->result();
    }

    function page_data_by_slug_name($slug_name,$language_id=2){
        $this->db->select("p.*,pd.*");
        $this->db->from("page p");
        $this->db->join("page_description pd","p.id=pd.page_id and pd.language_id='$language_id'","inner");
        $this->db->where("slug_name",$slug_name);
        return $this->db->get()->row();
    }

    function page_description_data($page_id){
        $this->db->select("*");
        $this->db->from("page_description");
        $this->db->where("page_id",$page_id);
        $list=$this->db->get()->result_array();
        $array=array();
        foreach($list as $row){
            $array[$row["language_id"]]=$row;
        }
        return $array;
    }

    function remove_desc($page_id){
        return $this->db->delete("page_description",array("page_id"=>$page_id));
    }

    function remove($brand_id){
        $this->db->trans_begin();
        $this->db->delete("page",array("id"=>$page_id),1);
        $this->db->delete("page_description",array("page_id"=>$page_id));
        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }


    public function save($data,$page_id){
        $this->db->trans_begin();
        if($data["image"]!="" && $page_id!="" ){
            $this->db->update("page",array("image"=>$data["image"]),array("id"=>$page_id),1);
        }
        if( $page_id==""){
            $this->db->insert("page",array("image"=>$data["image"]));
            $page_id=$this->db->insert_id();
        }
        $slug_name="";
        $this->remove_desc($page_id);
        for($i=0;$i<sizeof($data["name"]); $i++){
            if($data["language_id"][$i]=="2"){
                $slug_name=seoString($data["name"][$i]);
            }
            $this->db->insert("page_description",array("language_id"=>$data["language_id"][$i],"description"=>$data["description"][$i],"name"=>$data["name"][$i],"page_id"=>$page_id));

        }
        $this->db->update("page",array("slug_name"=>$slug_name),array("id"=>$page_id),1);

        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }
}
