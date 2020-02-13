<?php 

class Pengelompokan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}

		if ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "siswa" ) {
			redirect( base_url() . "beranda" );
		}
		$this->load->model("Tabulasi_model","tabulasi");
		$this->load->model("Kategori_soal_model","kategori");
		$this->load->model("Siswa_model","siswa");
		$this->load->model("Kelas_model","kelas");
		$this->load->model("Soal_model","soal");
		$this->load->model("Profil_kelas_model","profil");
		$this->load->model("Profil_individu_model","individu");
		$this->load->model("Kategori_soal_model","kategori");
		$this->load->model("Pengelompokan_model","pengelompokan");
	}

	public function index() 
	{
		$data['pagetitle'] = "Pengelompokan Siswa per Masalah";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("pengelompokan/pengelompokan");
		$this->load->view("templates/footer");
	}

	public function show()
	{
		$data['pagetitle'] = "show_pengelompokan";
		$data['all_soal'] = $this->pengelompokan->get_all_soal();
		$this->load->view("templates/head",$data);
		$this->load->view("pengelompokan/show");
	}
}