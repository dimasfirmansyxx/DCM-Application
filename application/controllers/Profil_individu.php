<?php 

class Profil_individu extends CI_Controller {
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
		$this->load->model("Profil_individu_model","profil");
		$this->load->model("Kategori_soal_model","kategori");
		$this->load->model("Siswa_model","siswa");
		$this->load->model("Kelas_model","kelas");
		$this->load->model("Soal_model","soal");
	}

	public function index() 
	{
		$data['pagetitle'] = "Profil Individu";
		$data['all_kelas'] = $this->kelas->get_all_kelas();
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("profil_individu/profil_individu");
		$this->load->view("templates/footer");
	}

	public function show($kelas, $no_urut)
	{
		$data['pagetitle'] = "show_profil_individu";
		$id_siswa = $this->profil->get_siswa_by_nourut($kelas,$no_urut)['id_siswa'];
		$data['siswa'] = $this->profil->get_siswa($id_siswa);
		$data['pribadi_kategori'] = $this->profil->get_kategori(1,5);
		$data['sosial_kategori'] = $this->profil->get_kategori(6,8);
		$data['belajar_kategori'] = $this->profil->get_kategori(9,11);
		$data['karir_kategori'] = $this->profil->get_kategori(12,12);
		$data['soal_essay'] = $this->profil->get_essay();
		$data['kategori_chart'] = $this->profil->get_kategori_chart($id_siswa);
		$data['section_chart'] = $this->profil->get_section_chart($id_siswa);
		$this->load->view("templates/head",$data);
		$this->load->view("profil_individu/show",$data);
	}

	public function print_laporan($kelas, $no_urut)
	{
		$data['pagetitle'] = "print_profil_individu";
		$id_siswa = $this->profil->get_siswa_by_nourut($kelas,$no_urut)['id_siswa'];
		$data['siswa'] = $this->profil->get_siswa($id_siswa);
		$data['pribadi_kategori'] = $this->profil->get_kategori(1,5);
		$data['sosial_kategori'] = $this->profil->get_kategori(6,8);
		$data['belajar_kategori'] = $this->profil->get_kategori(9,11);
		$data['karir_kategori'] = $this->profil->get_kategori(12,12);
		$data['soal_essay'] = $this->profil->get_essay();
		$data['kategori_chart'] = $this->profil->get_kategori_chart($id_siswa);
		$data['section_chart'] = $this->profil->get_section_chart($id_siswa);
		$data['namafile'] = $data['siswa']['nama_siswa'] . " (Profil Individu) ";
	
		$this->load->view('templates/head', $data);
		$this->load->view('profil_individu/show', $data);
		$this->load->view('templates/print', $data);
	}
}