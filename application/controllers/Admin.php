<?php 

class Admin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}

		if ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "siswa" ) {
			redirect( base_url() . "beranda" );
		}

		$this->load->model("Admin_model","admin");
	}

	public function index() 
	{
		$data['pagetitle'] = "Manajemen Admin";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("admin/admin");
		$this->load->view("templates/footer");
	}
}