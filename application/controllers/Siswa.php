<?php 

class Siswa extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Siswa_model","siswa");
		$this->load->model("Kelas_model","kelas");
	}

	public function index() 
	{
		$data['pagetitle'] = "Siswa";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("siswa/siswa");
		$this->load->view("templates/footer");
	}

	public function get_siswa()
	{
		$list = $this->siswa->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
        	$no++;
            $row = array();
            $row[] = $field->id_siswa;
            $row[] = $field->nama_siswa;
            $row[] = $field->id_kelas;
            $row[] = $field->jenis_kelamin;
            $row[] = $field->alamat;
            $row[] = "
            	<button class='btn btn-success btn-sm btnedit' data-id='$field->id_kelas'>Edit</button>
            	<button class='btn btn-danger btn-sm btnhapus' data-id='$field->id_kelas'>Hapus</button>
            	";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->siswa->count_all(),
            "recordsFiltered" => $this->siswa->count_filtered(),
            "data" => $data,
        );
        echo json_encode($list);
	}

	public function get_siswa_by_id()
	{
		$id_siswa = $this->input->post("id_siswa",true);
		if ( $this->Clsglobal->check_availability("tblsiswa",["id_siswa" => $id_siswa]) == 2 ) {
			$output = $this->siswa->get_siswa($id_siswa);
		} else {
			$output = 3;
		}

		echo json_encode($output);
	}

	// public function insert_kelas()
	// {
	// 	$data = [
	// 		"kelas" => strtoupper($this->input->post("kelas",true))
	// 	];

	// 	if ( $this->Clsglobal->check_availability("tblkelas",$data) == 3 ) {
	// 		$output = $this->kelas->insert_kelas($data);
	// 	} else {
	// 		$output = 2;
	// 	}

	// 	echo $output;
	// }

	// public function delete_kelas()
	// {
	// 	$id_kelas = $this->input->post("id_kelas",true);
	// 	$delete = $this->kelas->delete_kelas($id_kelas);

	// 	echo $delete;
	// }

	// public function update_kelas()
	// {
	// 	$data = [
	// 		"id_kelas" => $this->input->post("id_kelas",true),
	// 		"kelas" => strtoupper($this->input->post("kelas",true))
	// 	];

	// 	$update = $this->kelas->update_kelas($data);

	// 	echo $update;
	// }
}