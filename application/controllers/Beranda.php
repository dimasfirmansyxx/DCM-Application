<?php 

class Beranda extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}
	}

	public function index() 
	{
		$data['pagetitle'] = "Beranda";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("beranda/beranda");
		$this->load->view("templates/footer");
	}
}