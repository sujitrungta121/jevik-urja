<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Task extends CI_Controller{
	public function __construct(){
        parent::__construct(); 
        $this->load->library("session");
        $this->load->helper("site");
        ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

    }

    function update(){
    	///////brand slug update/////////////////////////////////////////////
    	$this->load->database();
    	
    	/*$this->db->select("b.*,bd.*");
        $this->db->from("brand b");
    	$this->db->from("brand_description bd","bd.brand_id=b.id","inner");
        $desc_data=$this->db->get()->result();
    	foreach($desc_data as $desc){
            $image_path=$desc->image;
            $image_path=str_replace("http://gomart.in/live/","http://gomart.in/",$image_path);
    		$this->db->update("brand",array("slug_name"=>seoString($desc->name),"image"=>$image_path),array("id"=>$desc->brand_id),1);
    	}*/

    	/////////category slug update////////////////////////////////////////////
    	/*$desc_data=$this->db->get("category_description")->result();*/
        /*$this->db->select("c.*,cd.*");
        $this->db->from("category c");
        $this->db->join("category_description cd","cd.category_id=c.id","inner");
        $desc_data=$this->db->get()->result();
    	foreach($desc_data as $desc){

            $small_image_path=$desc->small_image;
            $small_image_path=str_replace("http://gomart.in/live/","http://gomart.in/",$small_image_path);

            $main_image_path=$desc->main_image;
            $main_image_path=str_replace("http://gomart.in/live/","http://gomart.in/",$main_image_path);

            $banner_image_path=$desc->banner_image;
            $banner_image_path=str_replace("http://gomart.in/live/","http://gomart.in/",$banner_image_path);
    		$this->db->update("category",array("slug_name"=>seoString($desc->category_name),"small_image"=>$small_image_path,"main_image"=>$main_image_path,"banner_image"=>$banner_image_path),array("id"=>$desc->category_id),1);
    	}

    	/////////////product slug update//////////////////////////////////////////////
    	//$desc_data=$this->db->get("product_description")->result();
        $this->db->select("p.*,pd.*");
        $this->db->from("product p");
        $this->db->join("product_description pd","p.id=pd.product_id","inner");
        $desc_data=$this->db->get()->result();

    	foreach($desc_data as $desc){
            $image_path=$desc->image;
            $image_path=str_replace("http://gomart.in/live/","http://gomart.in/",$image_path);
    		$this->db->update("product",array("slug_name"=>seoString($desc->name),"image"=>$image_path),array("id"=>$desc->product_id),1);
    	}

        /////////////discount percent field data update /////////////////////////////////////////
        $desc_data=$this->db->get("product_option_value")->result();
        foreach($desc_data as $desc){
            $disc=(($desc->old_price-$desc->price)*100/$desc->old_price);
            if(is_numeric($disc) && $disc>0){
                $this->db->update("product_option_value",array("disc"=>$disc),array("id"=>$desc->id),1);
            }
            
        }*/

        $rows=$this->db->get("product_option_value")->result();
        foreach($rows as $row){
            $price=round($row->price);
            $disc=round($row->disc);
            $this->db->update("product_option_value",array("price"=>$price,"disc"=>$disc),array("id"=>$row->id),1);
            echo "<br/>".$this->db->last_query(); 
        }





    }

}