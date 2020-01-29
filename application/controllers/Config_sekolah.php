<?php 

class Config_sekolah extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}

		if ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "siswa" ) {
			redirect( base_url() . "beranda" );
		}
		
		$this->load->model("Config_sekolah_model","config_sekolah");
	}

	public function index()
	{
		$data['pagetitle'] = "Pengaturan Informasi Sekolah";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("infosekolah/infosekolah");
		$this->load->view("templates/footer");
	}

	public function change_info()
	{
		$data = [
			"nama_sekolah" => strtoupper($this->input->post("nama_sekolah",true)),
			"alamat" => $this->input->post("alamat",true),
			"kepala_sekolah" => ucwords($this->input->post("kepala_sekolah",true)),
			"guru_pembimbing" => ucwords($this->input->post("guru_pembimbing",true))
		];

		echo $this->config_sekolah->change_info($data);
	}
}