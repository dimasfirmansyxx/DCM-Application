<?php 

class Admin extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}

		if ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "siswa" ) {
			redirect( base_url() . "beranda" );
		}

		$this->load->model("Admin_model","admin");
	}

	public function index() 
	{
		$data['pagetitle'] = "Manajemen Admin";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("admin/admin");
		$this->load->view("templates/footer");
	}

	public function get_admin()
	{
		$list = $this->admin->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
        	$no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nama;
            $row[] = $field->username;
            $row[] = "
            	<button class='btn btn-success btn-sm btnedit' data-id='$field->id_user'>Edit</button>
            	<button class='btn btn-danger btn-sm btnhapus' data-id='$field->id_user'>Hapus</button>
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

	// public function get_kelas_by_id()
	// {
	// 	$id_kelas = $this->input->post("id_kelas",true);
	// 	if ( $this->Clsglobal->check_availability("tblkelas",["id_kelas" => $id_kelas]) == 2 ) {
	// 		$output = $this->kelas->get_kelas($id_kelas);
	// 	} else {
	// 		$output = 3;
	// 	}

	// 	echo json_encode($output);
	// }

	public function insert_admin()
	{
		$data["username"] = $this->input->post("username",true);

		if ( $this->Clsglobal->check_availability("tbluser",$data) == 3 ) {
			$data["nama"] = ucwords($this->input->post("nama",true));
			$data["password"] = password_hash($this->input->post("password",true), PASSWORD_DEFAULT);
			$output = $this->admin->insert_admin($data);
		} else {
			$output = 2;
		}

		echo $output;
	}

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