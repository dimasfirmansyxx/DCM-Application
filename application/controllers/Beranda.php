<?php 

class Beranda extends CI_Controller {
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