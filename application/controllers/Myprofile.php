<?php 

class Myprofile extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}

		$this->load->model("Myprofile_model","myprofile");
	}

	public function index()
	{
		$data['pagetitle'] = "Pengaturan Akun";
		$data['userinfo'] = $this->Clsglobal->user_info($this->session->user_id);
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("profile/profile");
		$this->load->view("templates/footer");
	}

	public function change_name()
	{
		$data = [
			"id_user" => $this->input->post("id_user",true),
			"nama" => strtoupper($this->input->post("nama",true))
		];

		echo $this->myprofile->change_name($data);
	}

	public function change_username()
	{
		$data = [
			"id_user" => $this->input->post("id_user",true),
			"username" => $this->input->post("username",true)
		];

		echo $this->myprofile->change_username($data);
	}

	public function change_password()
	{
		$data = [
			"id_user" => $this->input->post("id_user",true),
			"passlama" => $this->input->post("oldpassword",true),
			"passbaru" => password_hash($this->input->post("newpassword",true), PASSWORD_DEFAULT)
		];

		echo $this->myprofile->change_password($data);
	}

	public function change_avatar()
	{
		$id_user = $this->input->post("id_user",true);
		$extension_check = $this->Clsglobal->check_file_extension("foto",["png"]);
		if ( $extension_check == 0 ) {
			$upload = $this->Clsglobal->upload_files("foto","img/user-ava",["png","jpg","jpeg"]);
			echo $this->myprofile->change_avatar($id_user,$upload[0]);
		} elseif ( $extension_check == 5 ) {
			echo 5;
		} else {
			echo 1;
		}
	}
}