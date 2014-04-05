<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller {
	public function index(){
		redirect(site_url("admin"), "refresh");
		//$this->load->view('main/main.php');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */