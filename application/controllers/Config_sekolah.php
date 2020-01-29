<?php 

class Config_sekolah extends CI_Controller {
	public function index()
	{
		$data['pagetitle'] = "Pengaturan Informasi Sekolah";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("infosekolah/infosekolah");
		$this->load->view("templates/footer");
	}

}