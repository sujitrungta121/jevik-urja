<?php
class Api_model extends CI_Model{
    private $recursive_category_list=array();
    private $category_chain_data=array();
    public function __construct(){
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }


    function create_user_device_data($user_id,$fcm){
        do{
            $token=time().rand(1000000,9999999);
            $token=md5($token);
        } while ($this->device_token_exist($token)>0);

        $this->db->insert("user_device",array("user_id"=>$user_id,"access_token"=>$token,"fcm_token"=>$fcm,"created_on"=>date("Y-m-d H:i:s")));
        return $this->db->insert_id();
    }

    function own_referral_code_exist($code){
        $this->db->select("count(user_id) as found");
        $this->db->from("user");
        $this->db->where("own_referral_code",$code);
        return $this->db->get()->row()->found;
    }

    function create_referral_code($name){
        do {
          $code=rand(1000,9999);
          $code=substr(strtoupper($name),0,4).$code;
        } while ($this->own_referral_code_exist($code)>0);

        return $code;
    }


    function save_registration_data($name,$email,$mobile,$password,$referral_code,$time,$fcm,$id=""){
        $this->db->trans_begin();
        if($id==""){
            $own_referral_code=$this->create_referral_code($name);
            $this->db->insert("user",array("name"=>$name,"email"=>$email,"mobile"=>$mobile,"password"=>$password,"created_on"=>$time,"own_referral_code"=>$own_referral_code,"referral_code"=>$referral_code,"status"=>1,"user_type_id"=>9));
            $user_id=$this->db->insert_id();
            $device_id=$this->create_user_device_data($user_id,$fcm);
        }
        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return $device_id;
        }
    }

    /*function user_details_by_token($token){
        $this->db->select("ud.fcm_token,u.name,u.user_id");
        $this->db->from("user u");
        $this->db->join("user_device ud","ud.user_id=u.user_id","inner");
        $this->db->where("ud.access_token",$token);
        $this->db->where("u.status",1);
        return $this->db->get()->row_array();
    }
*/
    function device_token_exist($token){
        $this->db->select("count(id) as found");
        $this->db->from("user_device");
        $this->db->where("access_token",$token);
        return $this->db->get()->row()->found;
    }

    function specific_user_device_data($id){
        $this->db->select("*");
        $this->db->from("user_device");
        $this->db->where("id",$id);
        return $this->db->get()->row();
    }

    function mobile_exist($mob){
        $this->db->select("count(user_id) as found");
        $this->db->from("user");
        $this->db->where("mobile",$mob);
        return $this->db->get()->row()->found;
    }
    function email_exist($email){
        $this->db->select("count(user_id) as found");
        $this->db->from("user");
        $this->db->where("email",$email);
        return $this->db->get()->row()->found;
    }

    function category_list($args=array()){
        $this->db->select("cd.category_name,cd.category_id,c.slug_name,c.main_image,c.banner_image,c.small_image,c.slug_name");
        $this->db->from("category_description cd");
        $this->db->join("category c","c.id=cd.category_id","inner");
        $this->db->where("status",1);
        $this->db->where("cd.language_id",2);
        /*if($args["parent_id"]!=""){*/
            $this->db->where("c.parent_id",$args["parent_id"]);
        /*}*/
        if(isset($args["limit"]) && $args["limit"]!=""){
            $this->db->limit($args["limit"]);
        }
        return $this->db->get()->result();
    }

    function count_product_list($args=array()){
        $this->db->select("count(id) as found");
        $this->db->from("product");
        if(isset($args["category_id"]) && is_array($args["category_id"])){
            $this->db->where_in("category_id",$args["category_id"]);
        }
        return $this->db->get()->row()->found;   
    }


    function product_list($args=array()){
        $this->db->select("cd.category_name,cd.category_id,pov.price,pov.old_price,p.slug_name,p.id as product_id,p.stock,p.show_unit_in,p.base_unit_value_stock,ov.base_unit_value,pov.stock,p.image,pd.name,ov.value_name as selected_option_name,pov.id as selected_option_id,pov.disc");
        $this->db->from("product p");
        $this->db->join("product_description pd","pd.product_id=p.id","inner");
         $this->db->join("category_description cd","cd.category_id=p.category_id and cd.language_id=2","inner");
        $this->db->join("product_option_value pov","pov.product_id=p.id and pov.base=1","inner");
        $this->db->join("option_value ov","ov.option_value_id=pov.value_id","left");
        $this->db->where("pd.language_id",2);
        $this->db->where("p.status",1);
        if(isset($args["product_id"]) && $args["product_id"]!=""){
            $this->db->where("p.id",$args["product_id"]);
            $this->db->select("pd.details");
        }
        if(isset($args["category_id"]) && !is_array($args["category_id"])){
            $this->db->where("p.category_id",$args["category_id"]);
        }
        if(isset($args["category_id"]) && is_array($args["category_id"])){
            $this->db->where_in("p.category_id",$args["category_id"]);
        }

        if(isset($args["brand_id"]) && !is_array($args["brand_id"])){
            $this->db->where("p.brand_id",$args["brand_id"]);
        }
        if(isset($args["brand_id"]) && is_array($args["brand_id"])){
            $this->db->where_in("p.brand_id",$args["brand_id"]);
        }
        if(isset($args["disc"]) && is_array($args["disc"])){
            $this->db->where_in("pov.disc",$args["disc"]);
        }

        if(isset($args["price_from"]) && $args["price_from"]>0){
            $this->db->where("pov.price >=",$args["price_from"]);
        }
        if(isset($args["price_to"]) && $args["price_to"]>0){
            $this->db->where("pov.price <=",$args["price_to"]);
        }

        if(isset($args["price_range"]) && sizeof($args["price_range"])>0){
            $this->db->group_start();
            $count=0;
            foreach($args["price_range"] as $price_range){$count++;
                if($count==1){
                   $this->db->group_start(); 
               }else{
                    $this->db->or_group_start();
               }
                $this->db->where("pov.price >=",$price_range["from"]);
                $this->db->where("pov.price <=",$price_range["to"]);
                $this->db->group_end();
            }
            $this->db->group_end();
        }

        if(isset($args["disc_range"]) && sizeof($args["disc_range"])>0){
            $this->db->group_start();
            $count=0;
            foreach($args["disc_range"] as $disc_range){$count++;
                if($count==1){
                   $this->db->group_start(); 
               }else{
                    $this->db->or_group_start();
               }
                $this->db->where("pov.disc >=",$disc_range["from"]);
                $this->db->where("pov.disc <=",$disc_range["to"]);
                $this->db->group_end();
            }
            $this->db->group_end();
        }

        if(isset($args["search_val"]) && $args["search_val"]!=""){
            $this->db->like("pd.name",$args["search_val"]);
        }


        if(isset($args["exclude_product_id"]) && $args["exclude_product_id"]!=""){
            $this->db->where("p.id !=",$args["exclude_product_id"]);
        }
        if(isset($args["limit"]) && $args["limit"]!=""){
            $this->db->limit($args["limit"],$args["offset"]);
        }
        if(isset($args["order_by_field"]) && $args["order_by_field"]=="price"){
            $this->db->order_by("pov.price",$args["ordering"]);
        }
        $this->db->group_by('p.id');
        $products=$this->db->get()->result_array();
        //echo $this->db->last_query(); exit();
        $array=array();
        $count=0;
        foreach($products as $pr){
            $array[$count]=$pr;
            $array[$count]["selected_option"]=array("option_name"=>$pr["selected_option_name"],"id"=>$pr["selected_option_id"],"price"=>$pr["price"],"old_price"=>$pr["old_price"],"disc"=>$pr["disc"],"stock"=>$pr["stock"],"base_unit_value"=>$pr["base_unit_value"]);
            ///shared stock logic////////////////////
            if($pr["show_unit_in"]>0){
                $array[$count]["selected_option"]["stock"]=floor($pr["base_unit_value_stock"]/$pr["base_unit_value"]);
            }
            $array2=array();
            $options=$this->product_options($pr["product_id"]);
            $i=0;
            foreach($options as $opt){
                $array2[$i]=$opt;
                if($pr["show_unit_in"]>0){
                    if($pr["base_unit_value_stock"]>0 && $opt["base_unit_value"]>0){
                        $array2[$i]["stock"]=floor($pr["base_unit_value_stock"]/$opt["base_unit_value"]);
                    }else{
                         $array2[$i]["stock"]=0;
                    }
                    
                }
                $i++;
            }

            $array[$count]["options"]=$array2;
            $count++;
        }
        return $array;

    }

    function product_options($product_id){
        $this->db->select("ov.value_name as option_name,pov.id,pov.price,pov.old_price,pov.disc,pov.stock,ov.base_unit_value");
        $this->db->from("product_option_value pov");
        $this->db->join("option_value ov","ov.option_value_id=pov.value_id","inner");
        $this->db->where("pov.product_id",$product_id);

        return $this->db->get()->result_array();
        //echo $this->db->last_query(); exit();

    }

    function banner_list(){
        $this->db->select("file_path");
        $this->db->from("banner");
        $this->db->where("status",1);
        return $this->db->get()->result_array();
    
    }

    function brand_list($args=array()){
        $this->db->select("b.id,b.slug_name,b.image,bd.name");
        $this->db->from("brand b");
        $this->db->join("brand_description bd","bd.brand_id=b.id and bd.language_id=2","inner");
        if(isset($args["frontend_display"])){
            $this->db->where("b.frontend_display",$args["frontend_display"]);
        }
        return $this->db->get()->result_array();

    }

    function specific_brand_details($id,$by="b.id"){
        $this->db->select("b.*,bd.name");
        $this->db->from("brand b");
        $this->db->join("brand_description bd","bd.brand_id=b.id and bd.language_id=2","inner");
        $this->db->where($by,$id);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    function login_data_exist($user_name){
        $this->db->select("count(user_id) as found");
        $this->db->from("user");
        $this->db->group_start();
        $this->db->where("email",$user_name);
        $this->db->or_where("mobile",$user_name);
        $this->db->group_end();
        $this->db->where("status",1);
        //$this->db->where("user_type_id",9);
        $this->db->where_in("user_type_id",array(8,9));
        $count=$this->db->get()->row()->found;
        //echo $this->db->last_query(); exit();
        return $count;
    }

    function user_data($user_name){
        $this->db->select("*");
        $this->db->from("user");
        $this->db->where("email",$user_name);
        $this->db->or_where("mobile",$user_name);
        $this->db->where("status",1);
        return $this->db->get()->row_array();
    }

    function device_data_exist($user_id,$fcm_token){
        $this->db->select("count(id) as found");
        $this->db->from("user_device");
        $this->db->where("fcm_token",$fcm_token);
        $this->db->where("user_id",$user_id);
        return $this->db->get()->row()->found;
    }


    function refresh_device_token($user_id,$fcm){

        do{
            $token=time().rand(1000000,9999999);
            $token=md5($token);
        } while ($this->device_token_exist($token)>0);
        $this->db->update("user_device",array("access_token"=>$token),array("user_id"=>$user_id,"fcm_token"=>$fcm),1);
        return $token;
    }

    function product_details_by_slug_name($slug_name){
        $this->db->select("*");
        $this->db->from("product");
        $this->db->where("slug_name",$slug_name);
        $this->db->where("status",1);
        $data=$this->db->get()->row();
        //echo $this->db->last_query(); exit();
        return$data;
    }


    function product_details($id){
        $this->db->select("cd.category_name,cd.category_id,pd.details,pov.price,pov.old_price,p.id as product_id,p.stock,p.image,pd.name,ov.value_name as selected_option_name,pov.id as selected_option_id,p.base_unit_value_stock,p.show_unit_in,c.slug_name as category_slug_name");
        $this->db->from("product p");
        $this->db->join("product_description pd","pd.product_id=p.id","inner");
        $this->db->join("category_description cd","cd.category_id=p.category_id and cd.language_id=2","inner");
        $this->db->join("category c","cd.category_id=c.id","inner");
        $this->db->join("product_option_value pov","pov.product_id=p.id and pov.base=1","inner");
        $this->db->join("option_value ov","ov.option_value_row_id=pov.value_id","left");
        $this->db->where("pd.language_id",2);
        $this->db->where("p.status",1);
        $this->db->where("p.id",$id);
        if(isset($args["category_id"]) && $args["category_id"]!=""){
            $this->db->where("p.category_id",$args["category_id"]);
        }
        if(isset($args["limit"]) && $args["limit"]!=""){
            $this->db->limit($args["limit"],$args["offset"]);
        }
        $product=$this->db->get()->row_array();
       // echo $this->db->last_query(); exit();
            $product["options"]=$this->product_options($id);
        return $product;
    }

    function product_option_details($option_id){
        $this->db->select("pov.*,ov.value_name");
        $this->db->from("product_option_value pov");
        $this->db->join("option_value ov","ov.option_value_id=pov.value_id","inner");
        $this->db->where("pov.id",$option_id);
        $data=$this->db->get()->row_array();
        //echo $this->db->last_query(); exit();
        return $data;
    }

    function removeFromCart($order_details_id,$user_id){
        $this->db->trans_begin();
        $od=$this->db->get_where("order_detail",array("oid"=>$order_details_id))->row();
        $this->db->select("pov.id,od.count,od.product_id");
        $this->db->from("order_detail od");
        $this->db->join("product_option_value pov","pov.id=od.options","inner");
        $this->db->where("od.oid",$order_details_id);
        $this->db->limit(1);
        $det=$this->db->get()->row();
        $this->stockUpdate($det->product_id,$det->id,$det->count,"-");

        $this->db->delete("order_detail",array("oid"=>$order_details_id));
        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }



    function addToCart($product_id,$variant_id,$qty,$user_id,$operator="+"){
        $this->db->trans_begin();
        if($this->cartDataExist($user_id)==0){
            $this->db->insert("order",array("customer_id"=>$user_id,"type"=>2,"date"=>date("Y-m-d H:i:s")));
            $id=$this->db->insert_id();
            $option_details=$this->product_option_details($variant_id);
            //print_r($option_details); exit();
            $this->db->insert("order_detail",array("order_id"=>$id,"product_id"=>$product_id,"count"=>$qty,"options"=>$variant_id,"total_price"=>$option_details["price"]*$qty,"unit_price"=>$option_details["price"]));
        }else{
            $cart_details=$this->cartDetails($user_id);
            $id=$cart_details->order_id;
            if($this->cartDetailsDataExist($id,$product_id,$qty,$variant_id)>0){
                //echo "exist ";
                $option_details=$this->product_option_details($variant_id);
                $price=$option_details["price"];               
                $this->db->set("count","count".$operator.$qty,FALSE);
                $this->db->set("total_price","total_price".$operator.$price,FALSE);
                $this->db->where("order_id",$id);
                $this->db->where("product_id",$product_id);
                $this->db->where("options",$variant_id);
                $this->db->update("order_detail");
                //echo $this->db->last_query(); exit();

                $this->db->select("count")->from("order_detail");
                $this->db->where("order_id",$id);
                $this->db->where("product_id",$product_id);
                $this->db->where("options",$variant_id);
                $count=$this->db->get()->row()->count;
                if($count==0){
                    $this->db->where("order_id",$id);
                    $this->db->where("product_id",$product_id);
                    $this->db->where("options",$variant_id);
                    $this->db->limit(1);
                    $this->db->delete("order_detail");
                }



            }else{
                 //echo "not exist ";
                $option_details=$this->product_option_details($variant_id);
                $id=$cart_details->order_id;
            //print_r($option_details); exit();
                $this->db->insert("order_detail",array("order_id"=>$id,"product_id"=>$product_id,"count"=>$qty,"options"=>$variant_id,"total_price"=>$option_details["price"]*$qty,"unit_price"=>$option_details["price"]));
            }

        }
        $this->update_order_total($id);
        //$this->stockUpdate($product_id,$variant_id,$qty,$operator);
        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return $id;
        }
    }

    function update_order_total($order_id){
        $this->db->select("ifnull(sum(total_price),0) as total");
        $this->db->from("order_detail");
        $this->db->where("order_id",$order_id);
        $total=$this->db->get()->row()->total;
        if($total<500){
            $grand_total=$total+35;
            $delivery_charges=35;
        }else{
            $grand_total=$total;
            $delivery_charges=0;
        }
        $this->db->update("order",array("total"=>$total,"delivery_charges"=>$delivery_charges,"grand_total"=>$grand_total),array("order_id"=>$order_id),1);
        //echo $this->db->last_query(); 
        return $grand_total;
    }

    function cartDataExist($user_id){
        $this->db->select("count(order_id) as found");
        $this->db->from("order");
        $this->db->where("customer_id",$user_id);
        $this->db->where("type",2);
        return $this->db->get()->row()->found;
    }

    function refreshCart($id){
        $this->db->trans_begin();
        $products=$this->db->select("*")->from("order_detail")->where("order_id",$id)->get()->result();
        foreach($products as $pr){
            $option_id=$pr->options;
            $det=$this->product_option_details($option_id);
            $this->db->update("order_detail",array("unit_price"=>$det["price"],"total_price"=>$det["price"]*$pr->count),array("oid"=>$pr->oid),1);
            //echo $this->db->last_query(); 
        }
        $this->update_order_total($id);
        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }


    function cartDetails($user_id){
       $this->db->select("ord.*,u.email,u.mobile,u.name");
       $this->db->from("order ord");
       //$this->db->join("address a","a.id=ord.address_id","left");
       $this->db->join("user u","u.user_id=ord.customer_id","left");
       $this->db->where("ord.customer_id",$user_id); 
       $this->db->where("ord.type",2);
       $data=$this->db->get()->row();
       //echo $this->db->last_query(); exit();
       return $data;
    }

    function cartDetailsDataCount($order_id){
        $this->db->select("count(order_id) as found");
        $this->db->from("order_detail");
        $this->db->where("order_id",$order_id);
        return $this->db->get()->row()->found;
    }

    function cartDetailsData($user_id){
        $this->db->select("p.image,od.product_id,pd.name as product_name,pov.price,pov.old_price,od.count,od.options as variant_id,ov.value_name as variant_name,od.oid");
        $this->db->from("order_detail od");
        $this->db->join("order ord","ord.order_id=od.order_id and ord.type=2","inner");
        $this->db->join("product p","p.id=od.product_id","inner");
        $this->db->join("product_description pd","pd.product_id=od.product_id and pd.language_id=2","inner");
        $this->db->join("product_option_value pov","pov.id=od.options","inner");
        $this->db->join("option_value ov","ov.option_value_id=pov.value_id","inner");
        $this->db->where("ord.customer_id",$user_id);
        return $this->db->get()->result_array();
    }

    function cartDetailsAmtCount($user_id){
        $this->db->select("sum(total_price) as total");
        $this->db->from("order_detail od");
        $this->db->join("order ord","ord.order_id=od.order_id and ord.type=2","inner"); 
        $this->db->where("ord.customer_id",$user_id);
        return $this->db->get()->row()->total;
    }


    function cartDetailsDataExist($order_id,$product_id,$qty,$variant_id){
        $this->db->select("count(order_id) as found");
        $this->db->from("order_detail");
        $this->db->where("order_id",$order_id);
        $this->db->where("product_id",$product_id);
        $this->db->where("options",$variant_id);
        return $this->db->get()->row()->found;
    }

    function orderDetails($user_id){
       $this->db->select("*");
       $this->db->from("order");
       $this->db->where("customer_id",$user_id); 
       $this->db->where("type",2);
       return $this->db->get()->row();
    }

    function saveOTP($otp_code,$mobile_no){
        $this->db->trans_begin();
        $this->db->select("count(id) as found");
        $this->db->from("otp");
        $this->db->where("phone_no",$mobile_no);
        $found=$this->db->get()->row()->found;
        if($found==0){
            $this->db->insert("otp",array("phone_no"=>$mobile_no,"otp"=>$otp_code,"created_on"=>date("Y-m-d H:i:s"),"status"=>1));
        }else{
            $this->db->update("otp",array("otp"=>$otp_code,"created_on"=>date("Y-m-d H:i:s"),"status"=>1),array("phone_no"=>$mobile_no));
        }
        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }

    }

    function OTPExist($otp_code,$mobile_no){
        $this->db->select("count(id) as found");
        $this->db->from("otp");
        $this->db->where("otp",$otp_code);
        $this->db->where("phone_no",$mobile_no);
        $this->db->where("status",1);
        $data=$this->db->get()->row()->found;
        //echo $this->db->last_query(); exit();
        return $data;

    }

    function updateOTPStatus($mobile_no,$status){
        return $this->db->update("otp",array("status"=>2),array("phone_no"=>$mobile_no));
    }


    function productOptions($product_id){
        $this->db->select('option_value.*, product_option_value.*, product_option_value.id AS pr_value_id');
                $this->db->where('option_id', 25);
                $this->db->where('product_id', $product_id);
                $this->db->join('product_option_value', 'product_option_value.value_id = option_value.option_value_id');
                $this->db->where('language_id', 2);
                $query = $this->db->get("option_value");
                return $query->result_array();
    }

    function token_exist($token){
        $this->db->select("count(ud.id) as found");
        $this->db->from("user_device ud");
        $this->db->join("user u","u.user_id=ud.user_id and u.status=1","inner");
        $this->db->where("ud.access_token",$token);
        $this->db->where("ud.status",1);
        $data=$this->db->get()->row()->found;
        //echo $this->db->last_query(); exit();
        return $data;
    }



    function user_details_by_token($token){
        $this->db->select("ud.fcm_token,u.user_id,u.name,u.email,u.mobile");
        $this->db->from("user_device ud");
        $this->db->join("user u","u.user_id=ud.user_id and u.status=1","inner");
        $this->db->where("ud.access_token",$token);
        $this->db->where("ud.status",1);
        $data=$this->db->get()->row_array();
        //echo $this->db->last_query(); exit();
        return $data;
        
    }
    function user_details($user_id){
        $this->db->select("ud.fcm_token,u.user_id,u.name,u.email,u.mobile,u.password,u.referral_code,u.own_referral_code,u.referral_paymented");
        $this->db->from("user_device ud");
        $this->db->join("user u","u.user_id=ud.user_id and u.status=1","inner");
        $this->db->where("u.user_id",$user_id);
        $this->db->where("ud.status",1);
        return $this->db->get()->row_array();
        
    }

    function user_wallet_balance($user_id){
        $this->db->select("sum(balance_amt) as constant_balance");
        $this->db->from("wallet");
        $this->db->where("constant",1);
        $this->db->where("user_id",$user_id);
        $this->db->where("status",1);
        $constant_balance=$this->db->get()->row()->constant_balance;
        $this->db->select("sum(balance_amt) as dynamic_balance");
        $this->db->from("wallet");
        $this->db->where("constant",0);
        $this->db->where("valid_from <=",date("Y-m-d H:i:s"));
        $this->db->where("valid_till >=",date("Y-m-d H:i:s"));
        $this->db->where("user_id",$user_id);
        $this->db->where("status",1);
        $dynamic_balance=$this->db->get()->row()->dynamic_balance;
        return $constant_balance+$dynamic_balance;

    }

    function cart_item_count($user_id){
        $this->db->select("count(od.order_id) as found");
        $this->db->from("order_detail od");
        $this->db->join("order ord","ord.order_id=od.order_id","inner");
        $this->db->where("ord.type",2);
        $this->db->where("ord.customer_id",$user_id);
        return $this->db->get()->row()->found;
    }

    function category_list_recursive($parent_id=0){
        $this->db->select("cd.category_name,c.slug_name,c.id as category_id,c.parent_id");
        $this->db->from("category_description cd");
        $this->db->join("category c","c.id=cd.category_id","inner");
        $this->db->where("c.status",1);
        $this->db->where("cd.language_id",2);
        $this->db->where("parent_id",$parent_id);
        $details=$this->db->get()->result_array();
        foreach($details as $det){
            $this->recursive_category_list[]=array("name"=>$det["category_name"],"category_id"=>$det["category_id"],"slug_name"=>$det["slug_name"],"child_data"=>$this->category_list_recursive2($det["category_id"]));
        }

       return $this->recursive_category_list;

    }
    function reset_category_list_recursive(){
        $this->recursive_category_list=array();
    }

    function category_list_recursive2($parent_id=0){
        $this->db->select("cd.category_name,c.slug_name,c.id as category_id,c.parent_id");
        $this->db->from("category_description cd");
        $this->db->join("category c","c.id=cd.category_id","inner");
        $this->db->where("c.status",1);
        $this->db->where("cd.language_id",2);
        $this->db->where("parent_id",$parent_id);
        $details=$this->db->get()->result_array();
        $array=array();
        $i=0;
        foreach($details as $det){
            $array[$i]=array("name"=>$det["category_name"],"category_id"=>$det["category_id"],"slug_name"=>$det["slug_name"],"child_data"=>$this->category_list_recursive2($det["category_id"]));
            $i++;
        }

        return $array;
    }


    function get_category_chain($parent_id,$node=0){
        if($node==0){
            $this->category_chain_data=array();
        }
        $this->db->select("id");
        $this->db->from("category");
        $this->db->where("status",1);
        $this->db->where("parent_id",$parent_id);
        $details=$this->db->get()->result();
        foreach($details as $det){
            $this->category_chain_data[]=$det->id;
            $this->get_category_chain($det->id,1);
        }
        return $this->category_chain_data;  
    }

    function specific_category_data($id,$by="c.id"){
        $this->db->select("cd.category_id,cd.category_name,c.*");
        $this->db->from("category_description cd");
        $this->db->join("category c","c.id=cd.category_id","inner");
        $this->db->where($by,$id);
        $this->db->where("c.status",1);
        $data=$this->db->get()->row_array();
        //echo $this->db->last_query();exit();
        return $data;
    }

    function create_temp_tbl_for_product_filter($products){
        $this->db->query("DROP TEMPORARY TABLE IF EXISTS `temp_product_list`");
        $this->db->query("CREATE TEMPORARY TABLE `temp_product_list` (
         `product_id` int(10) NOT NULL,
         `category_name` varchar(200) NOT NULL,
         `category_id` int(10) NOT NULL,
         `slug_name` varchar(200) NOT NULL,
         `price` double(12,2) NOT NULL,
         `disc` double(5,2) NOT NULL,
         `old_price` double(12,2) NOT NULL,
         `stock` int(10) NOT NULL,
         `image` text NOT NULL,
         `name` varchar(200) NOT NULL,
         `selected_option_name` varchar(200) NOT NULL,
         `selected_option_id` int(10) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1");

        foreach($products as $pr){
            unset($pr["options"]);
            unset($pr["selected_option"]);
            $pr["disc"]=($pr["old_price"]-$pr["price"])*100/$pr["old_price"];
            $this->db->insert("temp_product_list",$pr);
        }
    }

    function get_temp_product_filter_data($args){
        $this->db->select("*");
        $this->db->from("temp_product_list");
        if(isset($args["order_by_field"]) && $args["order_by_field"]!=""){
            $this->db->order_by($args["order_by_field"],$args["ordering"]);
        }

        if(isset($args["disc"])){
            $this->db->where_in("disc",$args["disc"]);
        }

        $products=$this->db->get()->result_array();
        //echo $this->db->last_query(); exit();
        $array=array();
        $count=0;
        foreach($products as $pr){
            $array[$count]=$pr;
            $array[$count]["selected_option"]=array("option_name"=>$pr["selected_option_name"],"id"=>$pr["selected_option_id"],"price"=>$pr["price"],"old_price"=>$pr["old_price"],"slug_name"=>$pr["slug_name"]);
            $array[$count]["options"]=$this->product_options($pr["product_id"]);
            $count++;
        }
        return $array;
    }

    function state_list($country_id){
        $this->db->select("*");
        $this->db->from("states");
        $this->db->where("country_id",$country_id);
        return $this->db->get()->result_array();
    }

    function save_address($name,$mobile_no,$pin_code,$locality,$address,$city,$state_id,$landmark,$user_id){
        $this->db->trans_begin();
        $this->db->insert("address",array("name"=>$name,"mobile_no"=>$mobile_no,"pin_code"=>$pin_code,"locality"=>$locality,"address"=>$address,"city"=>$city,"state_id"=>$state_id,"landmark"=>$landmark,"user_id"=>$user_id));
        $id=$this->db->insert_id();

        if($this->db->trans_status()===false){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return $id; 
        }
    }

    function address_list($user_id){
        $this->db->select("a.*,s.name as state_name,ifnull(pc.status,0) as pin_code_available");
        $this->db->from("address a");
        $this->db->join("states s","s.id=a.state_id","inner");
        $this->db->join("pin_code pc","pc.code=a.pin_code and pc.status=1","left");
        $this->db->where("a.user_id",$user_id);
        $this->db->where("a.status",1);
        return $this->db->get()->result_array();
        //echo $this->db->last_query(); exit();
    }

    function payment_mode_list(){
        return $this->db->get_where("payment_mode",array("status"=>1,"display"=>1))->result_array();
    }

    function get_delivery_charge($amt){
        if($amt>499){
            return 0;
        }else{
            return 35;
        }
    }

    function complete_order($payment_mode,$payment_mode_in_order,$address_id,$delivery_timming,$user_id){
        $this->db->trans_begin();
        $cart_details=$this->cartDetails($user_id);
        $cart_id=$cart_details->order_id;
        $total=$this->cartDetailsAmtCount($user_id);
        $delivery_charge=$this->get_delivery_charge($total);
        $grand_total=$total+$delivery_charge;
        if (strtotime(date("H:i:s")) > strtotime('21:00:00')) {
    // whatever you have to do here
            $expected_delivery_date= date('Y-m-d', strtotime('+2 days'));
        }else{
             $expected_delivery_date= date('Y-m-d', strtotime('+1 days'));
        }


        $this->db->update("order",array("payment_type"=>$payment_mode_in_order,"type"=>1,"total"=>$total,"delivery_charges"=>$delivery_charge,"grand_total"=>$grand_total,"address_id"=>$address_id,"delivery_timming_id"=>$delivery_timming,"date"=>date("Y-m-d H:i:s"),"expected_delivery_date"=>$expected_delivery_date),array("order_id"=>$cart_id,"customer_id"=>$user_id));
        $this->db->insert("order_status_log",array("order_id"=>$cart_id,"user_id"=>$user_id,"status"=>1,"created_on"=>date("Y-m-d H:i:s"),"created_by"=>$user_id));
        if($payment_mode_in_order==1){
            $this->deduct_from_wallet($grand_total,$cart_id,$user_id);
        }
        if($payment_mode_in_order==5){
            $this->db->select("amt")->from("order_payment");
            $this->db->where("payment_mode",1);
            $this->db->where("order_id",$cart_id);
            $this->db->limit(1);
            $amt=$this->db->get()->row()->amt;
            $this->deduct_from_wallet($amt,$cart_id,$user_id);
        }
        $this->db->update("order_payment",array("status"=>1),array("order_id"=>$cart_id));

        if($payment_mode_in_order==1 || $payment_mode_in_order==3 || $payment_mode_in_order==5){
            $this->referral_income($grand_total,$cart_id,$user_id);
        }

        /////////stock update //////////////////////////////////////////
            $itemDetails=$this->orderDetailsData($cart_id);
            foreach($itemDetails as $det){
                $this->stockUpdate($det["product_id"],$det["variant_id"],$det["count"],"+");
            }
        /////////////////////////////////////


        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return $cart_id;
        }

    }

    function order_payment_details($order_id,$payment_mode){
        $data=$this->db->get_where("order_payment",array("order_id"=>$order_id,"payment_mode"=>$payment_mode),1)->row();
        //echo $this->db->last_query();
        return $data;
    }

    function init_order_payment($order_id,$payment_amt,$payment_mode){
         $this->db->trans_begin();
        $this->db->update("order_payment",array("status"=>2),array("order_id"=>$order_id));
        for($i=0;$i<sizeof($payment_mode);$i++){
             $this->db->insert("order_payment",array("order_id"=>$order_id,"payment_mode"=>$payment_mode[$i],"amt"=>$payment_amt[$i],"created_on"=>date("Y-m-d H:i:s"),"status"=>0));
        }

        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
       
    }

    function referral_income($grand_total,$order_id,$user_id){
     /////////referral income related process/////////////////////////////////////
        $user_details=$this->user_details($user_id);
        if($user_details["referral_paymented"]==0){
            if($user_details["referral_code"]!=""){
                $this->db->select("user_id")->from("user");
                $this->db->where("own_referral_code",$user_details["referral_code"]);
                $this->db->where("status",1);
                $get=$this->db->get();
                if($get->num_rows()==1){
                    $this->db->select("commission_amt,hold_days,valid_upto")->from("referral_commission");
                    $this->db->where("from_amt <=",$grand_total);
                    $this->db->where("to_amt >=",$grand_total);
                    $comm=$this->db->get();
                    if($comm->num_rows()==1){
                        $comm_det=$comm->row();
                        $comm_amt=$comm_det->commission_amt;
                        $hold_days=$comm_det->hold_days;
                        $valid_upto=intval($comm_det->valid_upto)+intval($hold_days);

                        $valid_from=Date('Y-m-d H:i:s', strtotime('+ '.$hold_days.' days'));
                        $valid_till=Date('Y-m-d H:i:s', strtotime('+ '.$valid_upto.' days'));
                        $this->db->insert("wallet",array("constant"=>0,"user_id"=>$get->row()->user_id,"in_amt"=>$comm_amt,"balance_amt"=>$comm_amt,"created_on"=>date("Y-m-d H:i:s"),"valid_from"=>$valid_from,"valid_till"=>$valid_till,"status"=>1));
                        $wallet_id=$this->db->insert_id();
                        $this->db->insert("wallet_log",array("wallet_id"=>$wallet_id,"in_amt"=>$comm_amt,"type"=>3,"details_id"=>$order_id,"created_on"=>date("Y-m-d H:i:s"),"created_by"=>$user_id));
                        $this->db->update("user",array("referral_paymented"=>1),array("user_id"=>$user_id),1);
                    }
                }
            }
        }
        
    }

    function deduct_from_wallet($amt,$order_id,$user_id){

        /////at first deduct from dynamic balance//////////////////
        $this->db->select("*")->from("wallet");
        $this->db->where("constant",0);
        $this->db->where("user_id",$user_id);
        $this->db->where("balance_amt >",0);
        $this->db->where("status",1);
        $this->db->order_by("valid_till","asc");
        $details=$this->db->get()->result();
        $bal=$amt;
        foreach($details as $det){
            if($bal>0){
                if($det->balance_amt>=$bal){
                $row_current_balance=$det->balance_amt-$bal;
                $this->db->update("wallet",array("balance_amt"=>$row_current_balance),array("id"=>$det->id));
                $this->db->insert("wallet_log",array("wallet_id"=>$det->id,"out_amt"=>$bal,"type"=>2,"details_id"=>$order_id,"created_on"=>date("Y-m-d H:i:s"),"created_by"=>$user_id));
                $bal=0;
                }else{
                    $row_current_balance=0;
                    $bal=$bal-$det->balance_amt;
                    $this->db->update("wallet",array("balance_amt"=>$row_current_balance),array("id"=>$det->id));
                $this->db->insert("wallet_log",array("wallet_id"=>$det->id,"out_amt"=>$det->balance_amt,"type"=>2,"details_id"=>$order_id,"created_on"=>date("Y-m-d H:i:s"),"created_by"=>$user_id));
                }
            }   
        }

        ///////////////then deduct from constant balance///////////////////////////

        if($bal>0){
            $this->db->select("*")->from("wallet");
            $this->db->where("constant",1);
            $this->db->where("user_id",$user_id);
            $this->db->where("balance_amt >",0);
            $this->db->where("status",1);
            $details=$this->db->get()->result();
            foreach($details as $det){
                if($bal>0){
                    if($det->balance_amt>=$bal){
                    $row_current_balance=$det->balance_amt-$bal;
                    $this->db->update("wallet",array("balance_amt"=>$row_current_balance),array("id"=>$det->id));
                    $this->db->insert("wallet_log",array("wallet_id"=>$det->id,"out_amt"=>$bal,"type"=>2,"details_id"=>$order_id,"created_on"=>date("Y-m-d H:i:s"),"created_by"=>$user_id));
                    $bal=0;
                    }else{
                        $row_current_balance=0;
                        $bal=$bal-$det->balance_amt;
                        $this->db->update("wallet",array("balance_amt"=>$row_current_balance),array("id"=>$det->id));
                    $this->db->insert("wallet_log",array("wallet_id"=>$det->id,"out_amt"=>$det->balance_amt,"type"=>2,"details_id"=>$order_id,"created_on"=>date("Y-m-d H:i:s"),"created_by"=>$user_id));
                    }
                }
                
            }
        }

    }

    function orderDetailsById($id){
        $this->db->select("date_format(ord.expected_delivery_date,'%d/%m/%Y') as expected_delivery_date,ord.order_id,date_format(ord.date,'%d/%m/%Y %r') as order_date,ord.total,ord.delivery_charges,ord.grand_total,ord.payment_type,ord.status,ord.customer_id,ord.comment,os.name as status_name,pm.name as payment_mode_name,a.*,u.email,ord.*,s.name as state_name,c.name as country_name,dt.name as delivery_timming_name,u2.name as delivery_person_name");
        $this->db->from("order ord");
        $this->db->join("order_status os","ord.status=os.id","inner");
        $this->db->join("delivery_timming dt","dt.id=ord.delivery_timming_id","left");
        $this->db->join("payment_mode pm","pm.id=ord.payment_type","inner");
        $this->db->join("address a","a.id=ord.address_id","inner");
        $this->db->join("states s","s.id=a.state_id","inner");
        $this->db->join("countries c","c.id=s.country_id","inner");
        $this->db->join("user u","u.user_id=ord.customer_id","left");
        $this->db->join("user u2","u2.user_id=ord.delivery_person_id","left");
        $this->db->where("ord.order_id",$id);
        $this->db->where("ord.type",1);
        $data=$this->db->get()->row_array();
        //echo $this->db->last_query(); exit();
        return $data;
    }

    function getOrders($user_id){
        $this->db->select("ord.order_id,date_format(ord.date,'%d/%m/%Y %r') as order_date,ord.total,ord.delivery_charges,ord.grand_total,ord.payment_type,ord.status,ord.comment,os.name as status_name,pm.name as payment_mode_name");
        $this->db->from("order ord");
        $this->db->join("order_status os","ord.status=os.id","inner");
        $this->db->join("payment_mode pm","pm.id=ord.payment_type","inner");
        $this->db->where("ord.customer_id",$user_id);
        $this->db->where("ord.type",1);
        //$this->db->where("ord.status !=4");
        $this->db->order_by("ord.order_id","desc");
        $order_data=$this->db->get()->result_array();
        //print_r($order_data);
        $i=0;
        $array=array();
        foreach($order_data as $ord){
            $array[$i]=$ord;
            $this->db->select("p.slug_name,p.image,od.product_id,
            od.count,od.unit_price,od.total_price,pd.name as product_name,od.options as variant_id,ov.value_name as variant_name,od.oid ");
            $this->db->from("order_detail od");
            $this->db->join("product p","p.id=od.product_id","inner");
            $this->db->join("product_description pd","pd.product_id=od.product_id and pd.language_id=2","inner");
            $this->db->join("product_option_value pov","pov.id=od.options","inner");
            $this->db->join("option_value ov","ov.option_value_id=pov.value_id","inner");
            $this->db->where("od.order_id",$ord["order_id"]);
            $array[$i]["products"]=$this->db->get()->result_array();
            $i++;
        }
        return $array;    
    }

    function order_status_log($order_id){
        $this->db->select("osl.created_on,os.name as status_name,osl.status");
        $this->db->from("order_status_log osl");
        $this->db->join("order_status os","os.id=osl.status","inner");
        $this->db->where("osl.order_id",$order_id);
        $this->db->order_by("osl.id","asc");
        return $this->db->get()->result_array();
    }


    function update_password($new_password,$user_id){
        return $this->db->update("user",array("password"=>$new_password),array("user_id"=>$user_id),1);
    }

    function updateProfileData($update_fields,$user_id){
        return $this->db->update("user",$update_fields,array("user_id"=>$user_id),1);
    }

    function update_order_trans_id($trans_order_id,$order_id){
       return $this->db->update("order",array("trans_order_id"=>$trans_order_id,"payment_type"=>3),array("order_id"=>$order_id),1);
       //echo $this->db->last_query(); exit();
    }

    function delivery_timming_slot_list($status=1){
        return $this->db->get_where("delivery_timming",array("status"=>$status))->result_array();
    }

    function pin_code_availability_check($pin_code,$user_id=""){
        $this->db->select("count(*) as found");
        $this->db->from("pin_code");
        $this->db->where("code",$pin_code);
        $this->db->where("status",1);
        $found=$this->db->get()->row()->found;
        if($found==0){
            $this->db->insert("pin_code_search_log",array("pin_code"=>$pin_code,"user_id"=>$user_id,"created_on"=>date("Y-m-d H:i:s")));
        }

        return $found;
    }

    function page_exist($page_id,$language_id=2){
        $this->db->select("count(page_id) as found");
        $this->db->from("page_description");
        $this->db->where("page_id",$page_id);
        $this->db->where("language_id",$language_id);
        return $this->db->get()->row()->found;
    }

    function page_data($page_id,$language_id=2){
        $this->db->select("*");
        $this->db->from("page_description");
        $this->db->where("page_id",$page_id);
        $this->db->where("language_id",$language_id);
        return $this->db->get()->row_array();
    }

    function update_forgot_password_otp($otp,$user_id){
        return $this->db->update("user",array("fp_otp"=>$otp,"fp_created_on"=>date("Y-m-d H:i:s")),array("user_id"=>$user_id),1);

    }

    function get_user_details_by_mobile_no($number){
        return $this->db->get_where("user",array("mobile"=>$number))->row_array();
    }

    function verify_forgot_password_otp($otp,$mobile){
        $this->db->select("count(user_id) as found");
        $this->db->from("user");
        $this->db->where(array("fp_otp"=>$otp,"mobile"=>$mobile));
        $this->db->where("status",1);
        return  $this->db->get()->row()->found;
    }

    function update_password_against_forgot_password($password,$user_id){
        return $this->db->update("user",array("password"=>$password,"fp_otp"=>""),array("user_id"=>$user_id),1);

    }

    function delivery_persons_order_list($args){
        $this->db->select("ord.*,a.*,os.name as status_name");
        $this->db->from("order ord");
        $this->db->join("address a","a.id=ord.address_id","left");
        $this->db->join("order_status os","os.id=ord.status","left");
        $this->db->where("ord.type",1);
        $this->db->where("ord.delivery_person_id",$args["delivery_person_id"]);
        if(isset($args["from_date"]) && $args["from_date"]!=""){
            $this->db->where("ord.expected_delivery_date >=",$args["from_date"]);
        }
        if(isset($args["to_date"]) && $args["to_date"]!=""){
            $this->db->where("ord.expected_delivery_date <=",$args["to_date"]);
        }
        if(isset($args["status"]) && $args["status"]!=""){
            $this->db->where("ord.status",$args["status"]);
        }
        $data=$this->db->get()->result_array();
        //echo $this->db->last_query(); exit();
        return $data;
    }

    function orderDetailsData($order_id){
        $this->db->select("p.image,od.product_id,pd.name as product_name,pov.price,pov.old_price,od.count,od.options as variant_id,ov.value_name as variant_name,od.oid,od.unit_price,od.total_price");
        $this->db->from("order_detail od");
        $this->db->join("order ord","ord.order_id=od.order_id and ord.type=1","inner");
        $this->db->join("product p","p.id=od.product_id","inner");
        $this->db->join("product_description pd","pd.product_id=od.product_id and pd.language_id=2","inner");
        $this->db->join("product_option_value pov","pov.id=od.options","inner");
        $this->db->join("option_value ov","ov.option_value_id=pov.value_id","inner");
        $this->db->where("ord.order_id",$order_id);
        return $this->db->get()->result_array();
    }


    function has_authorised_to_fetch_order($order_id,$user_id){
        $this->db->select("count(order_id) as found");
        $this->db->from("order");
        $this->db->group_start();
        $this->db->where("customer_id",$user_id);
        $this->db->or_where("delivery_person_id",$user_id);
        $this->db->group_end();
        $this->db->where("order_id",$order_id);
        return $this->db->get()->row()->found;
    }
    function has_authorised_to_update_order_status($order_id,$user_id){
        $this->db->select("count(order_id) as found");
        $this->db->from("order");
        $this->db->or_where("delivery_person_id",$user_id);
        $this->db->where("order_id",$order_id);
        return $this->db->get()->row()->found;
    }

    function update_order_status($vals,$order_id,$user_id){
        $this->db->trans_begin();
        if($vals["status"]==4){
            $vals["cancelled_on"]=date("Y-m-d H:i:s");
            $this->db->select("status,total,grand_total,delivery_charges,customer_id,payment_type");
            $this->db->from("order");
            $this->db->where("order_id",$order_id);
            $details=$this->db->get()->row();
            /*if($details->status==2 && $details->delivery_charges>0){
                $this->db->insert("cancel_charges",array("user_id"=>$details->customer_id,"order_id"=>$order_id,"amt"=>$details->delivery_charges,"created_on"=>date("Y-m-d H:i:s")));
            }*/
            if($details->payment_type==3 || $details->payment_type==1){
                $this->db->insert("wallet",array("constant"=>1,"user_id"=>$details->customer_id,"in_amt"=>$details->grand_total,"balance_amt"=>$details->grand_total,"created_on"=>date("Y-m-d H:i:s"),"status"=>1));
                $wallet_id=$this->db->insert_id();
                $this->db->insert("wallet_log",array("wallet_id"=>$wallet_id,"type"=>1,"details_id"=>$order_id,"in_amt"=>$details->grand_total,"created_on"=>date("Y-m-d H:i:s"),"created_by"=>$user_id));

                $this->cancel_referral_income($order_id);
            }

            /////stock update///////////////////////////////////////////////////
            $itemDetails=$this->orderDetailsData($order_id);
            foreach ($itemDetails as $det) {
                $this->stockUpdate($det["product_id"],$det["variant_id"],$det["count"],"-");
            }
            //////////////////////////////////////////////////////////////////////


        }
        $od=$this->orderDetailsById($order_id);
        if($od["payment_type"]==2 && $vals["status"]==3){
            $this->referral_income($od["grand_total"],$order_id,$user_id);
        }

        $this->db->update("order",$vals,array("order_id"=>$order_id),1);
        $this->db->insert("order_status_log",array("order_id"=>$order_id,"user_id"=>$user_id,"status"=>$vals["status"],"created_on"=>date("Y-m-d H:i:s"),"created_by"=>$user_id));
        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }

    function cancel_referral_income($order_id){
        $order_details=$this->orderDetailsById($order_id);
        $this->db->select("id,wallet_id");
        $this->db->from("wallet_log");
        $this->db->where("details_id",$order_id);
        $this->db->where("type",3);
        $get=$this->db->get();
        if($get->num_rows()==1){
            $wallet_id=$get->row()->wallet_id;
            $log_id=$get->row()->id;
            $this->db->update("wallet",array("status"=>0),array("id"=>$wallet_id),1);
            $this->db->update("wallet_log",array("status"=>0),array("id"=>$log_id),1);
            $this->db->update("user",array("referral_paymented"=>0),array("user_id"=>$order_details["customer_id"]),1);
        }

    }

    function shipping_charges($amt){
        $charges=0;
        $this->db->select("charges");
        $this->db->from("shipping_charges");
        $this->db->where("from_amt <=",$amt);
        $this->db->where("to_amt >=",$amt);
        $get=$this->db->get();
        if($get->num_rows()==0){
            return $charges;
        }else{
            return $get->row()->charges;
        }
    }

    function site_settings_data($id=1){
        return $this->db->get_where("settings",array("id"=>$id))->row_array();
    }


    function check_own_referral_code_exist($referral_code){
        $this->db->select("count(user_id) as found");
        $this->db->from("user");
        $this->db->where("status",1);
        $this->db->where("own_referral_code",$referral_code);
        return $this->db->get()->row()->found;
    }


    function checkStockAvailabilty($product_id,$variant_id){
        $pr=$this->product_details($product_id);
        if($pr["show_unit_in"]>0){
            $base_unit_value_stock=$pr["base_unit_value_stock"];
            $this->db->select("ov.base_unit_value");
            $this->db->from("option_value ov");
            $this->db->join("product_option_value pov","pov.value_id=ov.option_value_row_id","inner");
            $this->db->where("pov.id",$variant_id);
            $base_unit_value=$this->db->get()->row()->base_unit_value;
            $current_stock=floor($base_unit_value_stock/$base_unit_value);
            return $current_stock;
        }else{
            $this->db->select("stock")->from("product_option_value");
            $this->db->where("id",$variant_id);
            return $this->db->get()->row()->stock;
        }
    }

    function stockUpdate($product_id,$variant_id,$qty,$operator){
         $pr=$this->product_details($product_id);
          if($pr["show_unit_in"]>0){
            $base_unit_value_stock=$pr["base_unit_value_stock"];
            $this->db->select("ov.base_unit_value");
            $this->db->from("option_value ov");
            $this->db->join("product_option_value pov","pov.value_id=ov.option_value_row_id","inner");
            $this->db->where("pov.id",$variant_id);
            $base_unit_value=$this->db->get()->row()->base_unit_value;
            if($operator=="+"){
                ///as stock reduced
                $deduction=$base_unit_value*$qty;
                $base_unit_value_stock=$base_unit_value_stock-$deduction;
            }else{
                // as stock increased
                $addition=$base_unit_value*$qty;
                $base_unit_value_stock=$base_unit_value_stock+$addition;
            }
            $this->db->update("product",array("base_unit_value_stock"=>$base_unit_value_stock),array("id"=>$product_id),1);

        }else{
            $this->db->select("stock")->from("product_option_value");
            $this->db->where("id",$variant_id);
            $current_stock=$this->db->get()->row()->stock;
            if($operator=="+"){
                ///as stock reduced
               $updated_stock=$current_stock-$qty;
            }else{
                // as stock increased
                 $updated_stock=$current_stock+$qty;
            }
            $this->db->update("product_option_value",array("stock"=>$updated_stock),array("id"=>$variant_id),1);

            //echo $this->db->last_query(); exit();
        }

    }


    function remove_order_item($oid,$user_id=""){
        $this->db->select("od.*,ord.status,ord.payment_type,ord.grand_total,ord.customer_id");
        $this->db->from("order_detail od");
        $this->db->join("order ord","ord.order_id=od.order_id","inner");
        $this->db->where("od.oid",$oid);
        $details=$this->db->get()->row();

        if($details->status>2){
            return false;
        }

        $this->db->trans_begin();
        $this->db->limit(1);
        $this->db->delete("order_detail",array("oid"=>$oid));
        $this->stockUpdate($details->product_id,$details->options,$details->count,"-");
        $updated_grand_total=$this->update_order_total($details->order_id);
        if($details->payment_type==3 || $details->payment_type==1){
            if($details->grand_total>$updated_grand_total){
                $balance=$details->grand_total-$updated_grand_total;
                $this->db->insert("wallet",array("constant"=>1,"user_id"=>$details->customer_id,"in_amt"=>$balance,"balance_amt"=>$balance,"created_on"=>date("Y-m-d H:i:s"),"valid_from"=>date("Y-m-d H:i:s"),"status"=>1));
                $wallet_id=$this->db->insert_id();
                $this->db->insert("wallet_log",array("wallet_id"=>$wallet_id,"details_id"=>$details->order_id,"in_amt"=>$balance,"type"=>3,"created_on"=>date("Y-m-d H:i:s"),"created_by"=>$user_id));

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


    function init_wallet_balance_add($trans_order_id,$amt,$receipt,$user_id,$created_on){
        $this->db->trans_begin();
        $this->db->insert("wallet",array("receipt_no"=>$receipt,"constant"=>1,"user_id"=>$user_id,"in_amt"=>$amt,"balance_amt"=>$amt,"created_on"=>$created_on,"status"=>0));
        $wallet_id=$this->db->insert_id();
        $this->db->insert("wallet_log",array("trans_order_id"=>$trans_order_id,"wallet_id"=>$wallet_id,"in_amt"=>$amt,"type"=>4,"created_on"=>$created_on,"created_by"=>$user_id));

         if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }
    function save_wallet_balance_add($wallet_id){
        return $this->db->update("wallet",array("status"=>1,"created_on"=>date("Y-m-d H:i:s")),array("id"=>$wallet_id),1);
    }

    function trans_log($trans_order_id){
       return  $this->db->get_where("wallet_log",array("trans_order_id"=>$trans_order_id))->row_array();
    }

    function convert_order_to_cart($order_id,$user_id){
        $this->db->trans_begin();
        $exist=$this->cartDataExist($user_id);
        if($exist==1){
            $cart_details=$this->cartDetails($user_id);
            $this->db->update("order",array("type"=>9),array("order_id"=>$cart_details->order_id),1);
        }
        $this->db->update("order",array("type"=>2),array("order_id"=>$order_id),1);

        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }


    }





    /*function saveAddress(){

    }*/






}
