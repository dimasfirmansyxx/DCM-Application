<?php 

class Kelas extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Kelas_model","kelas");
	}

	public function index() 
	{
		$data['pagetitle'] = "Kelas";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("kelas/kelas");
		$this->load->view("templates/footer");
	}

	public function get_kelas()
	{
		$list = $this->kelas->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
        	$no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->kelas;
            $row[] = "
            	<button class='btn btn-success btn-sm btnedit' data-id='$field->id_kelas'>Edit</button>
            	<button class='btn btn-danger btn-sm btnhapus' data-id='$field->id_kelas'>Hapus</button>
            	";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->kelas->count_all(),
            "recordsFiltered" => $this->kelas->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
	}

	public function get_kelas_by_id()
	{
		$id_kelas = $this->input->post("id_kelas",true);
		if ( $this->Clsglobal->check_availability("tblkelas",["id_kelas" => $id_kelas]) == 2 ) {
			$output = $this->kelas->get_kelas($id_kelas);
		} else {
			$output = 3;
		}

		echo json_encode($output);
	}

	public function insert_kelas()
	{
		$data = [
			"kelas" => strtoupper($this->input->post("kelas",true))
		];

		if ( $this->Clsglobal->check_availability("tblkelas",$data) == 3 ) {
			$output = $this->kelas->insert_kelas($data);
		} else {
			$output = 2;
		}

		echo $output;
	}

	public function delete_kelas()
	{
		$id_kelas = $this->input->post("id_kelas",true);
		$delete = $this->kelas->delete_kelas($id_kelas);

		echo $delete;
	}

	public function update_kelas()
	{
		$data = [
			"id_kelas" => $this->input->post("id_kelas",true),
			"kelas" => strtoupper($this->input->post("kelas",true))
		];

		$update = $this->kelas->update_kelas($data);

		echo $update;
	}
}