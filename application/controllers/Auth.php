<?php 

class Auth extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( $this->session->user_logged === true ) {
			redirect( base_url() . "beranda" );
		}

		$this->load->model("Auth_model","auth");
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
		$this->load->view("templates/head",$data);
		$this->load->view("session/registrasi");
	}
}