<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller
{
    /*public function login(){
        //Menu...
        $this->data['categories'] = $this->categories_model->get_cats();
        $this->load->view('login', $this->data);
    }*/

    function registration($purpose="form-submit"){
    	$this->load->library("session");
    	$referral_code=$this->input->post("referral_code");
		$name=$this->input->post("name");
		$email=$this->input->post("email");
		$mobile_no=$this->input->post("mobile");
		$password=$this->input->post("password");
		$password=password_hash($password, PASSWORD_DEFAULT);
		$fcm="";
		$time=date("Y-m-d H:i:s");
		$this->load->model("Api_model","am");
		if($this->am->mobile_exist($mobile_no)>0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Mobile No. Allready Exist"));
			exit();
		}
		if($this->am->email_exist($email)>0){
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Email Id Allready Exist"));
			exit();
		}
		if($referral_code!=""){
			if($this->am->check_own_referral_code_exist($referral_code)==0){
				echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Invalid Referer Code.. Please Try To Place Valid Referer Code Or Leave It Empty"));
			exit();
			}
		}

		if($purpose=="validation-check"){
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"All The Data Are Validated"));
			exit();
		}


		$id=$this->am->save_registration_data($name,$email,$mobile_no,$password,$referral_code,$time,$fcm);
		if($id){
			$device_info=$this->am->specific_user_device_data($id);
			$this->session->set_userdata("name",$name);
			$this->session->set_userdata("user_id",$device_info->user_id);
			$this->session->set_userdata("password",$this->input->post("password"));
			echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Registration Data Saved Successfully"));
		}else{
			echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Unable To Save Registration_data"));
		}
    }


    function login(){
    	$this->load->library("session");
		$this->load->model("Api_model","am");
		$user_name=$this->input->post("user_name");
		$password=$this->input->post("password");

		$fcm="";
		if($this->am->login_data_exist($user_name)==1){
			//echo "xdafsdf";
			$user_data=$this->am->user_data($user_name);
			//print_r($user_data); exit();
			//echo $user_data["password"];
			$authenticated=password_verify($password, $user_data["password"]);
			if($authenticated){
				//echo "hello";
				if($this->am->device_data_exist($user_data["user_id"],$fcm)>0){
					$token=$this->am->refresh_device_token($user_data["user_id"],$fcm);
				}else{
					$id=$this->am->create_user_device_data($user_data["user_id"],$fcm);
					$device_info=$this->am->specific_user_device_data($id);
					$token=$device_info->access_token;
				}
				$user_data=$this->am->user_details_by_token($token);
				$this->session->set_userdata("name",$user_data["name"]);
				$this->session->set_userdata("user_id",$user_data["user_id"]);
				$this->session->set_userdata("password",$this->input->post("password"));


				echo json_encode(array("status"=>"success","status_code"=>1,"msg"=>"Login Done Successfully","details"=>array("user_id"=>$user_data["user_id"],"token"=>$token)));
				exit();
			}
		}
		echo json_encode(array("status"=>"failed","status_code"=>0,"msg"=>"Invalid Login Data... Unable To Login"));
	}

	function logout(){
	  $this->load->helper("url");
      $this->load->library("Session");
      $this->session->sess_destroy();
      redirect("Home");
    }

}
