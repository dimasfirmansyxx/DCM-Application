<?php 

class Butirsoal extends CI_Controller {
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
		$this->load->model("Butirsoal_model","butirsoal");
	}

	public function index()
	{
		redirect( base_url() . "beranda" );
	}

	public function paralel($param = null, $sortir = null)
	{
		if ( $param == "show" ) {
			$data['pagetitle'] = "show_analisis_soal_paralel";
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['sortir'] = $sortir;
			$this->load->view("templates/head",$data);
			$this->load->view("butirsoal/paralel_show",$data);
		} elseif ( $param == "print_laporan" ) {
			$data['pagetitle'] = "print_analisis_soal_paralel";
			$data['namafile'] = "Laporan Analisis Butir Soal Paralel";
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['sortir'] = $sortir;
			$this->load->view("templates/head",$data);
			$this->load->view("templates/print");
			$this->load->view("butirsoal/paralel_show",$data);
		} else {
			$data['pagetitle'] = "Analisis Butir Soal";
			$this->load->view("templates/head",$data);
			$this->load->view("templates/header");
			$this->load->view("templates/navbar");
			$this->load->view("butirsoal/paralel");
			$this->load->view("templates/footer");
		}
	}

	public function kelas($param = null, $id_kelas = null, $sortir = null)
	{
		if ( $param == "show" ) {
			$data['pagetitle'] = "show_analisis_soal_perkelas";
			$data['id_kelas'] = $id_kelas;
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['sortir'] = $sortir;
			$this->load->view("templates/head",$data);
			$this->load->view("butirsoal/kelas_show",$data);
		} elseif ( $param == "print_laporan" ) {
			$data['pagetitle'] = "show_analisis_soal_perkelas";
			$data['id_kelas'] = $id_kelas;
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['sortir'] = $sortir;
			$data['namafile'] = "Laporan Analisis Butir Soal Perkelas";
			$this->load->view("templates/head",$data);
			$this->load->view("templates/print");
			$this->load->view("butirsoal/kelas_show",$data);
		} else {
			$data['pagetitle'] = "Analisis Butir Soal";
			$data['all_kelas'] = $this->kelas->get_all_kelas();
			$this->load->view("templates/head",$data);
			$this->load->view("templates/header");
			$this->load->view("templates/navbar");
			$this->load->view("butirsoal/kelas");
			$this->load->view("templates/footer");
		}
	}
}