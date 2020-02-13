<?php 

class Tabulasi extends CI_Controller {
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
	}

	public function index() 
	{
		$data['pagetitle'] = "Tabulasi Hasil";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("tabulasi/tabulasi");
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

	public function print_laporan()
	{
		$data['pagetitle'] = "print_tabulasi";
		$data['kategori_soal'] = $this->kategori->get_all_kategori();
		$data['all_kelas'] = $this->kelas->get_all_kelas();
		$data['namafile'] = "Laporan Tabulasi";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/print");
		$this->load->view("tabulasi/show");
	}
}