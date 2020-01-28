<?php 

class Jawab extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}
		
		if ( $this->Clsglobal->check_verification() == 4 ) {
			redirect( base_url() . "auth/verification" );
		}

		if ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "admin" ) {
			redirect( base_url() . "beranda" );
		}
		$this->load->model("Kategori_soal_model","kategori_soal");
		$this->load->model("Soal_model","soal");
	}

	public function index()
	{
		$data['pagetitle'] = "Cek Masalah";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("jawab/jawab");
		$this->load->view("templates/footer");
	}
}