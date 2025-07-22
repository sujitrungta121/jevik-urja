<?php
class Reports_model extends CI_Model
{
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }


    public function pages_detail($id)
    {
        $query = $this->db->query('SELECT page.*, page_description.*
		FROM page INNER JOIN page_description
		ON page.id=page_description.page_id
		WHERE page_description.language_id = '.$this->session->userdata('lang').' AND page.id = '.$id.'');
        return $query->result();
    }

    function referral_income($args=array()){
        $this->db->select("w.in_amt,date_format(w.created_on,'%d/%m/%Y %r') as created_on,w.valid_from,w.valid_till,ord.order_id,ord.grand_total,u2.name,u2.email,u2.mobile,u2.own_referral_code");
        $this->db->from("wallet w");
        $this->db->join("wallet_log wl","wl.wallet_id=w.id","inner");
        $this->db->join("order ord","ord.order_id=wl.details_id","inner");
        $this->db->join("user u","ord.customer_id=u.user_id","inner");
        $this->db->join("user u2","u.referral_code=u2.own_referral_code","inner");
        $this->db->where("wl.type",3);
        $this->db->where("w.status",1);
        if(isset($args["from_date"]) && $args["from_date"]!=""){
            $this->db->where("date(w.created_on) >=",$args["from_date"]);
        }
        if(isset($args["to_date"]) && $args["to_date"]!=""){
            $this->db->where("date(w.created_on) <=",$args["to_date"]);
        }
        $data=$this->db->get()->result_array();
        //echo $this->db->last_query(); exit();
        return $data;
    }

    function stock_report(){
        $this->db->select("pd.name,p.id,p.base_unit_value_stock,p.show_unit_in,p.stock_mode,ov.base_unit_value,ov.value_name,pov.id as pov_id");
        $this->db->from("product p");
        $this->db->join("product_description pd","pd.product_id=p.id","inner");
        $this->db->join("product_option_value pov","pov.value_id=p.show_unit_in and p.id=pov.product_id","inner");
        $this->db->join("option_value ov","ov.option_value_id=pov.value_id","inner");
        $this->db->where("p.status",1);
        $data=$this->db->get()->result_array();
        $shared_stock_items=array();
         $array=array();
        foreach($data as $det){
            $shared_stock_items[]=$det["id"];
             $array[]=array("product_name"=>$det["name"],"stock"=>$det["base_unit_value_stock"]/$det["base_unit_value"],"option_name"=>$det["value_name"],"stock_mode"=>"Shared Stock","mode"=>2,"product_id"=>$det["id"],"pov_id"=>$det["pov_id"]);
        }


        $this->db->select("pd.name,p.id,pov.stock,pov.id as pov_id,ov.value_name");
        $this->db->from("product p");
        $this->db->join("product_description pd","pd.product_id=p.id","inner");
         $this->db->join("product_option_value pov","pov.product_id=pd.product_id","inner");
        $this->db->join("option_value ov","ov.option_value_id=pov.value_id","inner");
        $this->db->where("p.status",1);

        $data2=$this->db->get()->result_array();
       
        foreach($data2 as $det){
            if(!in_array($det["id"],$shared_stock_items)){
                $array[]=array("product_name"=>$det["name"],"stock"=>$det["stock"],"option_name"=>$det["value_name"],"stock_mode"=>"Single Stock","mode"=>1,"product_id"=>$det["id"],"pov_id"=>$det["pov_id"]);
            }
            
        }
        /*echo "<pre>";
        print_r($array); exit();*/
        return $array;
    }


    function stock_update_bulk($product_id,$pov_id,$mode,$stock){
        $this->db->trans_begin();
        for($i=0;$i<sizeof($pov_id);$i++){
            if($mode[$i]==1){
                $this->db->update("product_option_value",array("stock"=>$stock[$i]),array("id"=>$pov_id[$i]),1);
            }elseif($mode[$i]==2){
                $this->db->select("ov.base_unit_value");
                $this->db->from("product_option_value pov");
                $this->db->join("option_value ov","ov.option_value_id=pov.value_id","inner");
                $this->db->where("pov.id",$pov_id[$i]);
                $this->db->limit(1);
                $base_unit_value=$this->db->get()->row()->base_unit_value;
                echo "<br/>base_unit value : ".$base_unit_value;
                $updated_stock=$base_unit_value*$stock[$i];

                echo "<br/>updated stock : ".$updated_stock;

                $this->db->update("product",array("base_unit_value_stock"=>$updated_stock),array("id"=>$product_id[$i]),1);

                echo "<br>".$this->db->last_query();// exit();

            }

        }

        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }

    function sales_report($args=array()){
        $this->db->select("date_format(ord.delivered_on,'%d/%m/%Y %r') as delivered_on,ord.order_id,ord.total,ord.grand_total,ord.delivery_charges,a.*,pm.name as payment_mode_name");
        $this->db->from("order ord");
        $this->db->join("address a","ord.address_id=a.id","inner");
        $this->db->join("payment_mode pm","pm.id=ord.payment_type","left");

        if(isset($args["from_date"]) && $args["from_date"]!=""){
            $this->db->where("date(ord.delivered_on) >=",$args["from_date"]);
        }
        if(isset($args["to_date"]) && $args["to_date"]!=""){
            $this->db->where("date(ord.delivered_on) <=",$args["to_date"]);
        }

        $this->db->where("ord.status",3);
        $data=$this->db->get()->result_array();
        //echo $this->db->last_query(); exit();
        return $data;
    }

    function product_wise_sales_report($args=array()){
        $this->db->select("sum(count) as count,sum(od.total_price) as total_price,pd.name as product_name,ov.value_name as option_value_name,od.unit");
        $this->db->from("order_detail od");
        $this->db->join("order ord","ord.order_id=od.order_id","inner");
        $this->db->join("product_option_value pov","pov.id=od.options","inner");
        $this->db->join("option_value ov","ov.option_value_id=pov.value_id","inner");
        $this->db->join("product_description pd","pd.product_id=od.product_id","left");


        if(isset($args["from_date"]) && $args["from_date"]!=""){
            $this->db->where("date(ord.delivered_on) >=",$args["from_date"]);
        }
        if(isset($args["to_date"]) && $args["to_date"]!=""){
            $this->db->where("date(ord.delivered_on) <=",$args["to_date"]);
        }

        $this->db->where("ord.status",3);
        $this->db->group_by("od.product_id");
        $this->db->group_by("od.options");
        $this->db->order_by("pd.name");
        $data=$this->db->get()->result_array();
        //echo $this->db->last_query(); exit();
        return $data;
    }

    function product_variant_wise_sales_report($args=array()){
        $this->db->select("sum(od.total_price) as total_price,pd.name as product_name,ov.value_name as variant_name");
        $this->db->from("order_detail od");
        $this->db->join("order ord","ord.order_id=od.order_id","inner");
         $this->db->join("product_option_value pov","pov.product_id=od.product_id","inner");
         $this->db->join("option_value ov","ov.option_value_row_id=pov.value_id","inner");
        $this->db->join("product_description pd","pd.product_id=od.product_id","left");

        if(isset($args["from_date"]) && $args["from_date"]!=""){
            $this->db->where("date(ord.delivered_on) >=",$args["from_date"]);
        }
        if(isset($args["to_date"]) && $args["to_date"]!=""){
            $this->db->where("date(ord.delivered_on) <=",$args["to_date"]);
        }

        $this->db->where("ord.status",3);
        $this->db->group_by("od.product_id,od.options");
        $this->db->order_by("pd.name");
        $data=$this->db->get()->result_array();
        //echo $this->db->last_query(); exit();
        return $data;
    }

    function pin_code_search_report($args=array()){
        $this->db->select("p.*,u.name,u.email,u.mobile");
        $this->db->from("pin_code_search_log p");
        $this->db->join("user u","u.user_id=p.user_id","left");
        if(isset($args["from_date"]) && $args["from_date"]!=""){
            $this->db->where("date(p.created_on) >=",$args["from_date"]);
        }
        if(isset($args["to_date"]) && $args["to_date"]!=""){
            $this->db->where("date(p.created_on) <=",$args["to_date"]);
        }
         $data=$this->db->get()->result_array();
         return $data;
    }
}
?>
