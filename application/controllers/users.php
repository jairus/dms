<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class users extends CI_Controller {
	var $table;
	var $controller;
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('user_validation');
		$this->table = "users";
	}
	public function index(){
		$this->user_validation->validate($this->router->class, $this->router->method);
		$table = $this->table;
		$controller = $table;
		$start = $_GET['start'];
		$start += 0;
		$limit = 50;
				
		$sql = "select * from `".$table."` where 1 order by id desc limit $start, $limit";
		$export_sql = md5($sql);
		$_SESSION['export_sqls'][$export_sql] = $sql;
		$q = $this->db->query($sql);
		$records = $q->result_array();		
		
		//$sql = "select count(`id`) as `cnt` from `land` where `user_id` is NULL order by `folder` desc" ;
		$sql = "select count(`id`) as `cnt` from `".$table."` where 1" ;
		$q = $this->db->query($sql);
		$cnt = $q->result_array();
		$pages = ceil($cnt[0]['cnt']/$limit);
		
		$data = array();
		$data['records'] = $records;
		$data['export_sql'] = $export_sql;
		$data['pages'] = $pages;
		$data['start'] = $start;
		$data['limit'] = $limit;
		$data['cnt'] = $cnt[0]['cnt'];
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/main', $data, true);
		$this->load->view('layout/main', $data);
	}		
	public function search(){
		$this->user_validation->validate($this->router->class, $this->router->method);
		$table = $this->table;
		$controller = $table;
		$start = $_GET['start'];
		$filter = $_GET['filter'];
		$start += 0;
		$limit = 50;
		$search = strtolower(trim($_GET['search']));
		$searchx = trim($_GET['search']);
		
		$sql = "select * from `".$table."`  where 1 ";
		if($search != ''){
			$sql .= "and LOWER(`".$filter."`) like '%".mysql_real_escape_string($search)."%'";
		}
		$sql .= " order by id desc limit $start, $limit" ;

		$export_sql = md5($sql);
		$_SESSION['export_sqls'][$export_sql] = $sql;
		$q = $this->db->query($sql);
		$records = $q->result_array();
				
		$sql = "select count(id) as `cnt`  from `".$table."` where 1 ";
		if($search != ''){
			$sql .= "and LOWER(`".$filter."`) like '%".mysql_real_escape_string($search)."%'";
		}
		
		$q = $this->db->query($sql);
		$cnt = $q->result_array();
		$pages = ceil($cnt[0]['cnt']/$limit);
		
		$data = array();
		$data['records'] = $records;		
		$data['export_sql'] = $export_sql;
		$data['pages'] = $pages;
		$data['start'] = $start;
		$data['limit'] = $limit;
		$data['search'] = $searchx;
		$data['filter'] = $filter;
		$data['cnt'] = $cnt[0]['cnt'];
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/main', $data, true);
		$this->load->view('layout/main', $data);		
	}	
	function ajax_edit(){
		$this->user_validation->validate($this->router->class, $this->router->method);
		$table = $this->table;
		$controller = $table;
		$error = false;		
		
		/*start validation*/
		/*
		if ($_POST['name'] == ''){
			?>alertX("Please input name!");<?php
			$error = true;
		}
		*/
		/*end validation*/
		
		if(!$error){
			// check if there are other lands that are connected to the same land detail
			$id = $_POST['id'];			
			
			$sql = " update `".$table."` set ";
			//fields
			//$sql .= " `name` = '".mysql_real_escape_string($_POST['name'])."'" ;									
			$sql .= "   `email` = '".mysql_real_escape_string($_POST['email'])."'";
			if(trim($_POST['password'])){
				$sql .= " , `password` = '".mysql_real_escape_string(md5(trim($_POST['password'])))."'";
			}
			
			$sql .= " where `id` = '$id' limit 1";	
			$this->db->query($sql);										
			?>
			alertX("Successfully Updated Record.");
			self.location = "<?php echo site_url($controller."/edit/".$_POST['id']); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}	
	function ajax_add(){
		$this->user_validation->validate($this->router->class, $this->router->method);
		if(!$_SESSION['user']){
			return false;
		}
		$table = $this->table;
		$controller = $table;
		$error = false;		
				
		/*start validation*/
		/*
		if ($_POST['name'] == ''){
			?>alertX("Please input name!");<?php
			$error = true;
		}
		*/
		/*end validation*/
		
		if(!$error){								
			$sql = "insert into `".$table."` set ";
			/*fields*/
			//$sql .= " `name` = '".mysql_real_escape_string($_POST['name'])."'" ;							
			$sql .= "   `email` = '".mysql_real_escape_string($_POST['email'])."'";
			if(trim($_POST['password'])){
				$sql .= " , `password` = '".mysql_real_escape_string(md5(trim($_POST['password'])))."'";
			}

			$this->db->query($sql);										
			?>
			alertX("Successfully Inserted Record.");
			self.location = "<?php echo site_url($controller); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}
	
	public function edit($id){
		$this->user_validation->validate($this->router->class, $this->router->method);
		if(!$_SESSION['user']){
			return false;
		}
		$table = $this->table;
		$controller = $table;
		if(!trim($id)){
			redirect(site_url($controller));
		}
		$sql = "select * from `".$table."` where `id` = '".mysql_real_escape_string($id)."' limit 1";
		$q = $this->db->query($sql);
		$record = $q->result_array();
		$record = $record[0];
		if(!trim($record['id'])){
			redirect(site_url($controller));
		}
		$data['record'] = $record;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);		
		$this->load->view('layout/main', $data);;
	}
		
	public function add(){	
		$this->user_validation->validate($this->router->class, $this->router->method);
		$controller = $this->table;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);
		$this->load->view('layout/main', $data);;
	}
	public function ajax_delete($id=""){
		$this->user_validation->validate($this->router->class, $this->router->method);
		if(!$_SESSION['user']){
			return false;
		}
		if($_SESSION['user']['id']==trim($id)||$_SESSION['user']['email']=="admin"){ //cannot delete self and cannot delete admin
			return false;
		}
		$table = $this->table;
		if(!$id){
			$id = $_POST['id'];
		}
		$id = mysql_real_escape_string($id);
		$sql = "delete from `".$table."` where id = '".$id."' limit 1";
		$q = $this->db->query($sql);
		?>
		alertX("Successfully deleted.");
		<?php		
		exit();
	}
}
?>