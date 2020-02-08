<?php 

class Topik extends CI_Controller {
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
		$this->load->model("Topik_model","topik");
	}

	public function index()
	{
		redirect( base_url() . "beranda" );
	}

	public function paralel($param = null)
	{
		if ( $param == "show" ) {
			$data['pagetitle'] = "show_analisis_soal_paralel";
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$this->load->view("templates/head",$data);
			$this->load->view("butirsoal/paralel_show",$data);
		} else {
			$data['pagetitle'] = "Analisis Topik";
			$this->load->view("templates/head",$data);
			$this->load->view("templates/header");
			$this->load->view("templates/navbar");
			$this->load->view("topik/paralel");
			$this->load->view("templates/footer");
		}
	}
}