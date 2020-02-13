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
		$data['pagetitle'] = "show_tabulasi";
		$data['kategori_soal'] = $this->kategori->get_all_kategori();
		$data['all_kelas'] = $this->kelas->get_all_kelas();
		$this->load->view("templates/head",$data);
		$this->load->view("tabulasi/show");
	}
}