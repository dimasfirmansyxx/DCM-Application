<?php 

class Profil_kelas extends CI_Controller {
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
		$this->load->model("Profil_kelas_model","profil");
		$this->load->model("Profil_individu_model","individu");
		$this->load->model("Kategori_soal_model","kategori");
		$this->load->model("Siswa_model","siswa");
		$this->load->model("Kelas_model","kelas");
		$this->load->model("Soal_model","soal");
	}

	public function index() 
	{
		$data['pagetitle'] = "Profil Kelas";
		$data['all_kelas'] = $this->kelas->get_all_kelas();
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("profil_kelas/profil_kelas");
		$this->load->view("templates/footer");
	}

	public function show($id_kelas)
	{
		$data['pagetitle'] = "show_profil_kelas";
		$data['get_kelas'] = $this->kelas->get_kelas($id_kelas);
		$data['get_siswa'] = $this->profil->get_siswa_by_kelas($id_kelas);
		$data['get_kategori'] = $this->kategori->get_all_kategori();
		$data['abjad'] = ["A","B","C","D","E","F","G","H","I","J","K","L","M"];
		$this->load->view("templates/head",$data);
		$this->load->view("profil_kelas/show",$data);
	}

	public function print_laporan($id_kelas)
	{
		$data['pagetitle'] = "show_profil_kelas";
		$data['get_kelas'] = $this->kelas->get_kelas($id_kelas);
		$data['get_siswa'] = $this->profil->get_siswa_by_kelas($id_kelas);
		$data['get_kategori'] = $this->kategori->get_all_kategori();
		$data['abjad'] = ["A","B","C","D","E","F","G","H","I","J","K","L","M"];
		$data['namafile'] = "Laporan Profil Kelas " . $data['get_kelas']['kelas'];
		$this->load->view("templates/head",$data);
		$this->load->view("templates/print");
		$this->load->view("profil_kelas/show",$data);
	}
}