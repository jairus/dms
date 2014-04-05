<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
@session_start();
class user_validation extends CI_Model {
    public function __construct(){
		parent::__construct();
		$this->load->database();
    }
	public function validate($class, $method){
		if(!$_SESSION['user']['id']){
			die("Access Denied");
			return false;
		}
		//echo $class."-".$method;
		if($_SESSION['user']['email']=="admin"){ //return true to all if admin
			return true;
		}
		else{
			//check if current user has permission to this class
			$sql = "select * from `user_permissions` where `class_name`='".db_escape($class)."' and `user_id`='".db_escape($_SESSION['user']['id'])."'";
			$q = $this->db->query($sql);
			$records = $q->result_array();		
			print_r($records);
			//die("Access Denied");
			//return false;
		}
	}
}

/* End of file user_validation.php */