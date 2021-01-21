<?php 

class Panduan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Panduan_model","panduan");
	}

	public function index()
	{
		$data['pagetitle'] = "Panduan Aplikasi";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("panduan/panduan");
		$this->load->view("templates/footer");
	}

	public function edit()
	{
		if ($this->Clsglobal->user_info($this->session->user_id)["privilege"] == "siswa") {
			redirect(base_url("panduan"));
		} else {
			if ( isset($_POST['panduan_app']) ) {
				$data = [
					"panduan_app" => nl2br($this->input->post("panduan_app"))
				];
				$this->panduan->edit($data);
				redirect(base_url("panduan"));
			} else {
				$data['pagetitle'] = "Panduan Aplikasi";
				$this->load->view("templates/head",$data);
				$this->load->view("templates/header");
				$this->load->view("templates/navbar");
				$this->load->view("panduan/edit");
				$this->load->view("templates/footer");
			}
		}
	}
}