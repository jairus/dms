<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class queue extends CI_Controller {
	var $table;
	var $controller;
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "queue";
		$this->controller = "queue";
	}
	public function index(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		if($_SESSION['user']['email']=="admin"){
			$table = $this->table;
			$controller = $this->controller;
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
		else{
			$table = $this->table;
			$controller = $this->controller;
			if(in_array("applications", $_SESSION['user']['user_groups'])){
				$office = "applications";
			}
			$commands = $this->getCommands($office);
			$data['office'] = $office;
			$data['commands'] = $commands;
			$data['controller'] = $controller;
			$data['content'] = $this->load->view($controller.'/buttons', $data, true);
			$this->load->view('layout/main', $data);
		}
	}	
	private function getCommands($office){
		$table = $this->table;
		$controller = $this->controller;
		$sql = "select * from `".$table."` where `office`='".mysql_real_escape_string($office)."' order by `command` asc";
		$q = $this->db->query($sql);
		$records = $q->result_array();
		$stationvals = array();
		$commands = array();
		$lastnum = 0;
		foreach($records as $value){
			if(strpos(trim($value['command']), "station_")===0){
				//$value['value'] = substr("000".$value['value'], -3);
				$stationvals[$value['command']] = $value['value'];
				if($lastnum<($value['value']*1)){
					$lastnum = $value['value'];
				}
			}
			else{
				$commands[$value['command']] = $value['value'];
				$commands[$value['command']."_md5"] = md5($value['value']);
			}
		}
		$ret['lastnum'] = $lastnum;
		$ret['stationvals'] = $stationvals;
		$ret['commands'] = $commands;
		return $ret;
	}
	public function stations_ajax($office){
		$table = $this->table;
		$controller = $this->controller;
		$commands = $this->getCommands($office);
		echo json_encode($commands);
	}
	public function display($office){
		$table = $this->table;
		$controller = $this->controller;
		$commands = $this->getCommands($office);
		$data['office'] = $office;
		$data['commands'] = $commands;
		$this->load->view($controller.'/display', $data);
	}
	public function submitQueue(){
		if($_POST['increment']!=0){
			$sql = "select * from `queue` where `office`='".mysql_real_escape_string($_POST['office'])."' and `command` like '".mysql_real_escape_string("station_%")."' ";
			$q = $this->db->query($sql);
			$records = $q->result_array();
			$queue = $records[0]['value'];
			$t = count($records);
			for($i=0; $i<$t; $i++){
				if($queue < $records[$i]['value']){
					$queue = $records[$i]['value'];
				}
			}
			if($_POST['increment']<0){
				$queue -= 1;
			}
			else{
				$queue += 1;
			}
		}
		else{
			$queue = $_POST['value'];
		}
		
		//print_r($records);
		//$queue = $_POST['value'];
		
		$sql = "update `queue` set `value`='".mysql_real_escape_string($queue)."' where `command`='".mysql_real_escape_string($_POST['command'])."' and 
		`office` = '".mysql_real_escape_string($_POST['office'])."'
		";
		$q = $this->db->query($sql);
		$commands = $this->getCommands($_POST['office']);
		echo json_encode($commands);
	}
	public function search(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$start = $_GET['start'];
		$filter = $_GET['filter'];
		$start += 0;
		$limit = 50;
		$search = strtolower(trim($_GET['search']));
		$searchx = trim($_GET['search']);
		
		$sql = "select * from `".$table."`  where 1 ";
		if($search != ''){
			$sql .= "and LOWER(`".$filter."`) like '%".db_escape($search)."%'";
		}
		$sql .= " order by id desc limit $start, $limit" ;

		$export_sql = md5($sql);
		$_SESSION['export_sqls'][$export_sql] = $sql;
		$q = $this->db->query($sql);
		$records = $q->result_array();
				
		$sql = "select count(id) as `cnt`  from `".$table."` where 1 ";
		if($search != ''){
			$sql .= "and LOWER(`".$filter."`) like '%".db_escape($search)."%'";
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
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
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
			//$sql .= " `name` = '".db_escape($_POST['name'])."'" ;									
			$sql .= "   `office` = '".mysql_real_escape_string($_POST['office'])."'";
$sql .= " , `command` = '".mysql_real_escape_string($_POST['command'])."'";
$sql .= " , `value` = '".mysql_real_escape_string($_POST['value'])."'";

			
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
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
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
			//$sql .= " `name` = '".db_escape($_POST['name'])."'" ;							
			$sql .= "   `office` = '".mysql_real_escape_string($_POST['office'])."'";
$sql .= " , `command` = '".mysql_real_escape_string($_POST['command'])."'";
$sql .= " , `value` = '".mysql_real_escape_string($_POST['value'])."'";

			$this->db->query($sql);										
			?>
			alertX("Successfully Inserted Record.");
			self.location = "<?php echo site_url($controller); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}
	
	public function edit($id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		if(!trim($id)){
			redirect(site_url($controller));
		}
		$sql = "select * from `".$table."` where `id` = '".db_escape($id)."' limit 1";
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
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$controller = $this->controller;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);
		$this->load->view('layout/main', $data);;
	}
	public function ajax_delete($id=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		if(!$id){
			$id = $_POST['id'];
		}
		$id = db_escape($id);
		$sql = "delete from `".$table."` where id = '".$id."' limit 1";
		$q = $this->db->query($sql);
		?>
		alertX("Successfully deleted.");
		<?php		
		exit();
	}
}
?>