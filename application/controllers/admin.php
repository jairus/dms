<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class admin extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	public function index(){
		if($_SESSION['user']){
			redirect(site_url("admin/createcms"), "refresh");
			//$this->load->view('layout/main');
		}
		else{
			$this->load->view('layout/main');
		}
	}
	public function createcms(){
		if($_POST){
			$table = trim($_POST['table']);
			$folder = trim($_POST['folder']);
			if(!$folder){
				$folder = $table;
			}
			$contents = file_get_contents(dirname(__FILE__)."/admin/controller.txt");
			$contents = str_replace("[[table]]", $table, $contents);
			$contents = str_replace("[[folder]]", $folder, $contents);
			$str = "";
			$fields_temp = explode("\n", trim($_POST['edit_fields']));
			$more = 0;
			foreach($fields_temp as $value){
				$values = explode("|", trim($value));
				$field = trim($values[0]);
				$label = trim($values[1]);
				if($more==0){
					$str .= '$sql .= "   `'.$field.'` = \'".mysql_real_escape_string($_POST[\''.trim($values[0]).'\'])."\'"'.";\n";
					$more=1;
				}
				else{
					$str .= '$sql .= " , `'.$field.'` = \'".mysql_real_escape_string($_POST[\''.trim($values[0]).'\'])."\'"'.";\n";
				}
			}
			$contents = str_replace("[[update_fields]]", $str, $contents);
			file_put_contents(dirname(__FILE__)."/".$folder.".php", $contents);
			
			//create add and main
			mkdir(dirname(__FILE__)."/../views/".$folder, 0777);
			$contents = file_get_contents(dirname(__FILE__)."/admin/add.txt");
			$str = "";
			$fields_temp = explode("\n", trim($_POST['edit_fields']));
			foreach($fields_temp as $value){
				$values = explode("|", trim($value));
				$field = trim($values[0]);
				$label = trim($values[1]);
				$str .= '<tr class="even required"><td>* '.$label.':</td><td><input type="text" name="'.$field.'" size="40"></td></tr>'."\n";
			}
			$contents = str_replace("[[edit_fields]]", $str, $contents);
			file_put_contents(dirname(__FILE__)."/../views/".$folder."/add.php", $contents);
			
			
			$contents = file_get_contents(dirname(__FILE__)."/admin/main.txt");
			$str = "";
			$fields_temp = explode("\n", trim($_POST['filter_fields']));
			foreach($fields_temp as $value){
				$values = explode("|", trim($value));
				$field = trim($values[0]);
				$label = trim($values[1]);
				$str .= '<option value="'.$field.'">'.$label.'</option>	'."\n";
			}
			$contents = str_replace("[[filter_fields]]", $str, $contents);
			
			$str1 = "";
			$str2 = "";
			$fields_temp = explode("\n", trim($_POST['display_fields']));
			foreach($fields_temp as $value){
				$values = explode("|", trim($value));
				$field = trim($values[0]);
				$label = trim($values[1]);
				$str1 .= '<th>'.$label.'</th>'."\n";
				$str2 .= '<td><?php echo $records[$i][\''.$field.'\'];?></td>'."\n";
			}
			$contents = str_replace("[[display_heads]]", $str1, $contents);
			$contents = str_replace("[[display_values]]", $str2, $contents);
			file_put_contents(dirname(__FILE__)."/../views/".$folder."/main.php", $contents);
			
			
			redirect(site_url("admin/createcms/?message=Done!"), 'refresh');
		}
		$data['createcms'] = 1;
		$this->load->view('layout/main', $data);
	}
	public function logout(){
		unset($_SESSION['user']);
		redirect(site_url("admin"), 'refresh');
	}
	public function login(){
		$sql = "select * from `users` where `email`= ".$this->db->escape($_POST['login_email'])." and `password`= '".md5($_POST['password'])."'";
		$q = $this->db->query($sql);
		$r = $q->result_array();	
		if($r[0]){
			unset($_SESSION['user']);
			$_SESSION['user'] = $r[0];
			redirect(site_url("admin"), 'refresh');
		}
		else{
			redirect(site_url("admin/?error=Invalid Login&login_email=".$_POST['login_email']), 'refresh');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */