<?php 

class Soal extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Kategori_soal_model","kategori_soal");
		$this->load->model("Soal_model","soal");
		$this->load->library("PHPExcel");
	}

	public function index()
	{
		$data['pagetitle'] = "Manajemen Soal";
		$data['kategori_soal'] = $this->kategori_soal->get_all_kategori();
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

	public function insert_soal()
	{
		$data = [
			"soal" => ucwords($this->input->post("soal",true)),
			"id_kategori" => $this->input->post("kategori",true),
			"jenis" => $this->input->post("jenis",true)
		];

		if ( $this->Clsglobal->check_availability("tblsoal",$data) == 3 ) {
			$data["no_soal"] = $this->Clsglobal->get_new_id("tblsoal","no_soal");
			$output = $this->soal->insert_soal($data);
		} else {
			$output = 2;
		}

		echo $output;
	}

	public function delete_soal()
	{
		$no_soal = $this->input->post("no_soal",true);
		$delete = $this->soal->delete_soal($no_soal);

		echo $delete;
	}

	public function get_soal_by_id()
	{
		$no_soal = $this->input->post("no_soal",true);
		if ( $this->Clsglobal->check_availability("tblsoal",["no_soal" => $no_soal]) == 2 ) {
			$output = $this->soal->get_soal($no_soal);
		} else {
			$output = 3;
		}

		echo json_encode($output);
	}

	public function update_soal()
	{
		$data = [
			"no_soal" => $this->input->post("no_soal"),
			"soal" => ucwords($this->input->post("soal",true)),
			"id_kategori" => $this->input->post("kategori",true),
			"jenis" => $this->input->post("jenis",true)
		];

		$update = $this->soal->update_soal($data);

		echo $update;
	}

	public function download_format_excel()
	{
		$data['filename'] = "Format Pengisian Soal - DCM App";
		$data['kategori_soal'] = $this->kategori_soal->get_all_kategori();
		$this->load->view("soal/format_excel",$data);
	}

	public function import_soal_from_excel()
	{
		$filename = $this->Clsglobal->upload_files("excelfiles","excel_files",["xls"])[0];
		if ( $filename == 5 ) {
			echo 5;
		} else {
			echo json_encode($this->soal->import_soal($filename));
		}
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
			"nama_kategori" => ucwords($this->input->post("nama_kategori",true))
		];

		if ( $this->Clsglobal->check_availability("tblkategorisoal",$data) == 3 ) {
			$data["id_kategori"] = $this->Clsglobal->get_new_id("tblkategorisoal","id_kategori");
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