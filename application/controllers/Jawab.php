<?php 

class Jawab extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}
		
		if ( $this->Clsglobal->check_verification() == 4 ) {
			redirect( base_url() . "auth/verification" );
		}

		if ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "admin" ) {
			redirect( base_url() . "beranda" );
		}
		$this->load->model("Kategori_soal_model","kategori_soal");
		$this->load->model("Soal_model","soal");
		$this->load->model("Jawab_model","jawab");
	}

	public function index()
	{
		$data['pagetitle'] = "Cek Masalah";
		$data['jmlkategori'] = $this->Clsglobal->num_rows("tblkategorisoal");
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("jawab/jawab");
		$this->load->view("templates/footer");
	}

	public function lembar($id_kategori)
	{
		$data['pagetitle'] = "Lembar Soal";
		$data['kategori'] = $this->kategori->get_kategori($id_kategori);
		$data['soal'] = $this->jawab->get_soal($id_kategori);
		$data['jmlkategori'] = $this->Clsglobal->num_rows("tblkategorisoal");
		$this->load->view("templates/head",$data);
		$this->load->view("jawab/lembar");
	}

	public function push_answer()
	{
		$output = [];
		foreach ($_POST as $key => $value) {
			if ( is_numeric($key) ) {
				$output[$key] = $value;
			} else {
				if ( !($value == "") ) {
					if ( $key == "belumtercantum" ) {
						if ( isset($_SESSION['jawaban_belumtercantum']) ) {
							$_SESSION['jawaban_belumtercantum'] = $_SESSION['jawaban_belumtercantum'] . ", " . $value;
						} else {
							$_SESSION['jawaban_belumtercantum'] = $value ;
						}
					} else {
						if ( isset($_SESSION['jawaban_menyusahkan']) ) {
							$_SESSION['jawaban_menyusahkan'] = $_SESSION['jawaban_menyusahkan'] . ", " . $value;
						} else {
							$_SESSION['jawaban_menyusahkan'] = $value;
						}
					}
				}
			}
		}
		echo json_encode($output);
	}

	public function selesai()
	{
		$data = [
			"id_user" => $this->input->post("id_user"),
			"jawaban" => $this->input->post("jawaban",true)
		];

		echo $this->jawab->save_jawaban($data);
	}
}