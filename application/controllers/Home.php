<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
        $this->authlogin();
	}

	public function index()
	{
		$data["menu"] = "home";	
		$this->load->view("home", $data);
	}

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */