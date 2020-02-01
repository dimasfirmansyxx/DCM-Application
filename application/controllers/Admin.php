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
}