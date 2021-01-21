<?php 

class Panduan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Panduan_model","panduan");
	}

	public function index()
	{
		$data['pagetitle'] = "Panduan Aplikasi";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("panduan/panduan");
		$this->load->view("templates/footer");
	}
}