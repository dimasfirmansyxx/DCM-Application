<?php 

class Beranda extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}
		
		if ( $this->Clsglobal->check_verification() == 4 ) {
			redirect( base_url() . "auth/verification" );
		}
	}

	public function index() 
	{
		$data['pagetitle'] = "Beranda";
		$data['jmlsiswa'] = $this->Clsglobal->num_rows("tblsiswa");
		$data['jmlkelas'] = $this->Clsglobal->num_rows("tblkelas");
		$data['jmlsoal'] = $this->Clsglobal->num_rows("tblsoal");
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("beranda/beranda");
		$this->load->view("templates/footer");
	}
}