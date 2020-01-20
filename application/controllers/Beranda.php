<?php 

class Beranda extends CI_Controller {
	public function index() {
		$this->load->view("templates/head");
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("beranda/beranda");
		$this->load->view("templates/footer");
	}
}