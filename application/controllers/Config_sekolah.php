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
			"guru_pembimbing" => ucwords($this->input->post("guru_pembimbing",true)),
			"tahun_ajar" => ucwords($this->input->post("tahun_ajar",true)),
			"welcome_message" => nl2br($this->input->post("welcome_message")),
			"login_message" => nl2br($this->input->post("login_message"))
		];

		echo $this->config_sekolah->change_info($data);
	}

	public function change_logo()
	{
		$extension_check = $this->Clsglobal->check_file_extension("logo",["png"]);
		if ( $extension_check == 0 ) {
			unlink( "./assets/img/core/logo.png" );

			$upload = $this->Clsglobal->upload_files("logo","img/core",["png"],"logo.png");
			if ( $upload[0] == "logo.png" ) {
				echo 0;
			} else if ( $upload == 5 ) {
				echo 5;
			} else {
				echo 1;
			}
		} elseif ( $extension_check == 5 ) {
			echo 5;
		} else {
			echo 1;
		}
	}
}