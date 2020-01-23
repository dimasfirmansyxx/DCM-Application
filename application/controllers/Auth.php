<?php 

class Auth extends CI_Controller {
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
}