<?php 

class Auth extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( $this->session->user_logged === true ) {
			if ( !($this->uri->segment(2) == "logout") ) {
				redirect( base_url() . "beranda" );
			}
		}

		$this->load->model("Auth_model","auth");
		$this->load->model("Kelas_model","kelas");
	}

	public function index()
	{
		redirect( base_url() . "auth/login" );
	}

	public function login()
	{
		$data['pagetitle'] = "Login";
		$this->load->view("templates/head",$data);
		$this->load->view("session/login");
	}

	public function login_check()
	{
		$data = [
			"username" => strtolower($this->input->post("username",true)),
			"password" => $this->input->post("password")
		];

		echo $this->auth->login_check($data);
	}

	public function register()
	{
		$data['pagetitle'] = "Registrasi";
		$data['kelas'] = $this->kelas->get_all_kelas();
		$this->load->view("templates/head",$data);
		$this->load->view("session/registrasi");
	}

	public function register_act()
	{
		$data = [
			"nama" => $this->input->post("nama_siswa",true),
			"username" => $this->input->post("username",true),
			"password" => password_hash($this->input->post("password"), PASSWORD_DEFAULT),
			"privilege" => "siswa",
			"id_siswa" => $this->input->post("id_siswa",true),
			"profile_photo" => "noava.png"
		];

		echo $this->auth->register($data);
	}

	public function get_siswa()
	{
		$data = [
			"no_urut" => $this->input->post("no_urut"),
			"id_kelas" => $this->input->post("kelas")
		];

		echo json_encode($this->auth->get_siswa($data));
	}

	public function logout()
	{
		$this->session->unset_userdata("user_logged");
		$this->session->unset_userdata("user_id");
		redirect( base_url() . "auth/login" );
	}
}