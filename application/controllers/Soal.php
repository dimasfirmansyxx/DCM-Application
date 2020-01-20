<?php 

class Soal extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Kategori_soal_model","kategori_soal");
	}

	public function kategori()
	{
		$this->load->view("templates/head");
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
            $no++;
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
}