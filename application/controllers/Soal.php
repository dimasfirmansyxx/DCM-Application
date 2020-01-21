<?php 

class Soal extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Kategori_soal_model","kategori_soal");
		$this->load->model("Soal_model","soal");
	}

	public function index()
	{
		$data['pagetitle'] = "Manajemen Soal";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("soal/soal");
		$this->load->view("templates/footer");
	}

	public function get_soal()
	{
		$list = $this->soal->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
        	$get_kategori = $this->kategori_soal->get_kategori($field->id_kategori);
            $row = array();
            $row[] = $field->no_soal;
            $row[] = $get_kategori['nama_kategori'];
            $row[] = $field->soal;
            $row[] = ucwords($field->jenis);
            $row[] = "
            	<button class='btn btn-success btn-sm btnedit' data-id='$field->no_soal'>Edit</button>
            	<button class='btn btn-danger btn-sm btnhapus' data-id='$field->no_soal'>Hapus</button>
            	";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->soal->count_all(),
            "recordsFiltered" => $this->soal->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
	}

	public function kategori()
	{
		$data['pagetitle'] = "Kategori Soal";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("soal/kategori");
		$this->load->view("templates/footer");
	}

	public function get_kategori()
	{
		$list = $this->kategori_soal->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $row = array();
            $row[] = $field->id_kategori;
            $row[] = $field->nama_kategori;
            $row[] = "
            	<button class='btn btn-success btn-sm btnedit' data-id='$field->id_kategori'>Edit</button>
            	<button class='btn btn-danger btn-sm btnhapus' data-id='$field->id_kategori'>Hapus</button>
            	";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->kategori_soal->count_all(),
            "recordsFiltered" => $this->kategori_soal->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
	}

	public function get_kategori_by_id()
	{
		$id_kategori = $this->input->post("id_kategori",true);
		if ( $this->Clsglobal->check_availability("tblkategorisoal",["id_kategori" => $id_kategori]) == 2 ) {
			$output = $this->kategori_soal->get_kategori($id_kategori);
		} else {
			$output = 3;
		}

		echo json_encode($output);
	}

	public function insert_kategori()
	{
		$data = [
			"id_kategori" => $this->Clsglobal->get_new_id("tblkategorisoal","id_kategori"),
			"nama_kategori" => ucwords($this->input->post("nama_kategori",true))
		];

		if ( $this->Clsglobal->check_availability("tblkategorisoal",$data) == 3 ) {
			$output = $this->kategori_soal->insert_kategori($data);
		} else {
			$output = 2;
		}

		echo $output;
	}

	public function delete_kategori()
	{
		$id_kategori = $this->input->post("id_kategori",true);
		$delete = $this->kategori_soal->delete_kategori($id_kategori);

		echo $delete;
	}

	public function update_kategori()
	{
		$data = [
			"id_kategori" => $this->input->post("id_kategori",true),
			"nama_kategori" => ucwords($this->input->post("nama_kategori",true))
		];

		$update = $this->kategori_soal->update_kategori($data);

		echo $update;
	}
}