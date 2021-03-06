<?php 

class Siswa extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}

		if ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "siswa" ) {
			redirect( base_url() . "beranda" );
		}
		$this->load->model("Siswa_model","siswa");
		$this->load->model("Kelas_model","kelas");
	}

	public function index() 
	{
		$data['pagetitle'] = "Siswa";
		$data['kelas'] = $this->kelas->get_all_kelas();
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
        	$kelas = $this->kelas->get_kelas($field->id_kelas);
            $row = array();
            $no++;
            $row[] = $no;
            $row[] = $field->no_urut;
            $row[] = $field->nama_siswa;
            $row[] = $kelas['kelas'];
            $row[] = ucwords($field->jenis_kelamin);
            $row[] = "
            	<button class='btn btn-success btn-sm btnedit' data-id='$field->id_siswa'>Edit</button>
            	<button class='btn btn-primary btn-sm btnubah' data-id='$field->id_siswa'>Ubah Info Login</button>
            	<button class='btn btn-warning btn-sm btnreset' data-id='$field->id_siswa'>Reset Hasil</button>
            	<button class='btn btn-danger btn-sm btnhapus' data-id='$field->id_siswa'>Hapus</button>
            	";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->siswa->count_all(),
            "recordsFiltered" => $this->siswa->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
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

	public function insert_siswa()
	{
		$data = [
			"nama_siswa" => strtoupper($this->input->post("nama_siswa",true)),
			"id_kelas" => $this->input->post("kelas",true),
			"jenis_kelamin" => $this->input->post("jenis_kelamin",true)
		];

		if ( $this->Clsglobal->check_availability("tblsiswa",$data) == 3 ) {
			$data["id_siswa"] = $this->Clsglobal->get_new_id("tblsiswa","id_siswa");
			$data["no_urut"] = $this->input->post("no_urut",true);
			$data["tempat_lahir"] = "";
			$data["tgl_lahir"] = "01/01/2020";
			$data["verification"] = "not";

			$cekabsen = [
				"no_urut" => $data['no_urut'],
				"id_kelas" => $data['id_kelas']
			];

			if ( $this->Clsglobal->check_availability("tblsiswa",$cekabsen) == 3 ) {
				$output = $this->siswa->insert_siswa($data);
			} else {
				$output = 202;
			}
		} else {
			$output = 201;
		}

		echo $output;
	}

	public function delete_siswa()
	{
		$id_siswa = $this->input->post("id_siswa",true);
		$delete = $this->siswa->delete_siswa($id_siswa);

		echo $delete;
	}

	public function reset_siswa()
	{
		$id_siswa = $this->input->post("id_siswa",true);
		$reset = $this->siswa->reset_siswa($id_siswa);

		echo $reset;
	}

	public function update_siswa()
	{
		$data = [
			"id_siswa" => $this->input->post("id_siswa"),
			"nama_siswa" => strtoupper($this->input->post("nama_siswa",true)),
			"jenis_kelamin" => strtolower($this->input->post("jenis_kelamin",true))
		];

		$update = $this->siswa->update_siswa($data);

		echo $update;
	}

	public function update_login()
	{
		$data = [
			"id_siswa" => $this->input->post("id_siswa"),
			"username" => $this->input->post("username",true),
			"password" => password_hash($this->input->post("password",true), PASSWORD_DEFAULT)
		];

		$update = $this->siswa->update_login($data);

		echo $update;
	}

	public function download_format_excel()
	{
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$kelas = $this->kelas->get_all_kelas();
		$excel = new PHPExcel;
 
		$excel->getProperties()->setCreator("Dimas Firmansyah");
		$excel->getProperties()->setLastModifiedBy("Dimas Firmansyah");
		$excel->getProperties()->setTitle("Format Pengisian Siswa - DCM App");
		$excel->removeSheetByIndex(0);
		 
		 
		$sheet = $excel->createSheet();
		$sheet->setTitle('SOAL');
		$sheet->setCellValue("A1", "KELAS");
		$sheet->setCellValue("D1", "PENGISIAN SISWA");
		$sheet->setCellValue("A2", "Kelas");
		$sheet->setCellValue("D2", "Nomor Absen");
		$sheet->setCellValue("E2", "Nama Siswa");
		$sheet->setCellValue("F2", "Kelas");
		$sheet->setCellValue("G2", "Jenis Kelamin (pria, wanita)");

		$iteration = 3;
		foreach ($kelas as $row) {
			$sheet->setCellValue("A" . $iteration, $row['kelas']);
			$iteration++;
		}

		$excel->getActiveSheet()->setTitle('Siswa - DCM App');
		$excel->setActiveSheetIndex(0)->mergeCells("A1:B1");
		$excel->setActiveSheetIndex(0)->mergeCells("D1:G1");

		foreach (range('A', $excel->getActiveSheet()->getHighestDataColumn()) as $col) {
	        $excel->getActiveSheet()
	                ->getColumnDimension($col)
	                ->setAutoSize(true);
	    } 

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Format Pengisian Siswa - DCM App.xlsx"');
		header('Cache-Control: max-age=0');
		 
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save('php://output');
	}

	public function import_siswa_from_excel()
	{
		$filename = $this->Clsglobal->upload_single("excelfiles","excel_files",["xlsx"]);
		if ( $filename == 5 ) {
			echo 5;
		} else {
			echo $this->siswa->import_siswa($filename);
		}
	}
}