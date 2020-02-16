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
		$this->load->model("Profil_individu_model","profil");
	}

	public function index()
	{
		redirect( base_url() . "beranda" );
	}

	public function paralel($param = null, $sortir = null)
	{
		if ( $param == "show" ) {
			$data['pagetitle'] = "show_analisis_topik_paralel";
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['pribadi_kategori'] = $this->profil->get_kategori(1,5);
			$data['sosial_kategori'] = $this->profil->get_kategori(6,8);
			$data['belajar_kategori'] = $this->profil->get_kategori(9,11);
			$data['karir_kategori'] = $this->profil->get_kategori(12,12);
			$data['sortir'] = $sortir;
			$this->load->view("templates/head",$data);
			$this->load->view("topik/paralel_show",$data);
		} elseif ( $param == "print_laporan" ) {
			$data['pagetitle'] = "show_analisis_topik_paralel";
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['pribadi_kategori'] = $this->profil->get_kategori(1,5);
			$data['sosial_kategori'] = $this->profil->get_kategori(6,8);
			$data['belajar_kategori'] = $this->profil->get_kategori(9,11);
			$data['karir_kategori'] = $this->profil->get_kategori(12,12);
			$data['sortir'] = $sortir;
			$data['namafile'] = "Laporan Analisis Topik Paralel";
			$this->load->view("templates/head",$data);
			$this->load->view("templates/print");
			$this->load->view("topik/paralel_show",$data);
		} else {
			$data['pagetitle'] = "Analisis Topik";
			$this->load->view("templates/head",$data);
			$this->load->view("templates/header");
			$this->load->view("templates/navbar");
			$this->load->view("topik/paralel");
			$this->load->view("templates/footer");
		}
	}

	public function kelas($param = null, $id_kelas = null)
	{
		if ( $param == "show" ) {
			$data['pagetitle'] = "show_analisis_topik_paralel";
			$data['id_kelas'] = $id_kelas;
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['pribadi_kategori'] = $this->profil->get_kategori(1,5);
			$data['sosial_kategori'] = $this->profil->get_kategori(6,8);
			$data['belajar_kategori'] = $this->profil->get_kategori(9,11);
			$data['karir_kategori'] = $this->profil->get_kategori(12,12);
			$this->load->view("templates/head",$data);
			$this->load->view("topik/kelas_show",$data);
		} else {
			$data['pagetitle'] = "Analisis Topik";
			$data['all_kelas'] = $this->kelas->get_all_kelas();
			$this->load->view("templates/head",$data);
			$this->load->view("templates/header");
			$this->load->view("templates/navbar");
			$this->load->view("topik/kelas");
			$this->load->view("templates/footer");
		}
	}
}