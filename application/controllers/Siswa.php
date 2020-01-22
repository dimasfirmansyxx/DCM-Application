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
        	$kelas = $this->kelas->get_kelas($field->id_kelas);
            $row = array();
            $row[] = $field->id_siswa;
            $row[] = $field->nama_siswa;
            $row[] = $kelas['kelas'];
            $row[] = ucwords($field->jenis_kelamin);
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
			"id_kelas" => $this->input->post("id_kelas",true),
			"jenis_kelamin" => $this->input->post("jenis_kelamin",true)
		];

		if ( $this->Clsglobal->check_availability("tblsiswa",$data) == 3 ) {
			$data["id_siswa"] = $this->Clsglobal->get_new_id("tblsiswa","id_siswa");
			$output = $this->soal->insert_siswa($data);
		} else {
			$output = 2;
		}

		echo $output;
	}

	// public function delete_soal()
	// {
	// 	$no_soal = $this->input->post("no_soal",true);
	// 	$delete = $this->soal->delete_soal($no_soal);

	// 	echo $delete;
	// }

	// public function update_soal()
	// {
	// 	$data = [
	// 		"no_soal" => $this->input->post("no_soal"),
	// 		"soal" => ucwords($this->input->post("soal",true)),
	// 		"id_kategori" => $this->input->post("kategori",true),
	// 		"jenis" => $this->input->post("jenis",true)
	// 	];

	// 	$update = $this->soal->update_soal($data);

	// 	echo $update;
	// }

	// public function download_format_excel()
	// {
	// 	include APPPATH.'third_party/PHPExcel/PHPExcel.php';
	// 	$kategori_soal = $this->kategori_soal->get_all_kategori();
	// 	$excel = new PHPExcel;
 
	// 	$excel->getProperties()->setCreator("Dimas Firmansyah");
	// 	$excel->getProperties()->setLastModifiedBy("Dimas Firmansyah");
	// 	$excel->getProperties()->setTitle("Format Pengisian Soal - DCM App");
	// 	$excel->removeSheetByIndex(0);
		 
		 
	// 	$sheet = $excel->createSheet();
	// 	$sheet->setTitle('SOAL');
	// 	$sheet->setCellValue("A1", "KATEGORI");
	// 	$sheet->setCellValue("D1", "PENGISIAN SOAL");
	// 	$sheet->setCellValue("A2", "id_kategori");
	// 	$sheet->setCellValue("B2", "nama_kategori");
	// 	$sheet->setCellValue("D2", "no_soal");
	// 	$sheet->setCellValue("E2", "id_kategori");
	// 	$sheet->setCellValue("F2", "soal");
	// 	$sheet->setCellValue("G2", "jenis");

	// 	$iteration = 3;
	// 	foreach ($kategori_soal as $kategori) {
	// 		$sheet->setCellValue("A" . $iteration, $kategori['id_kategori']);
	// 		$sheet->setCellValue("B" . $iteration, $kategori['nama_kategori']);
	// 		$iteration++;
	// 	}

	// 	$excel->getActiveSheet()->setTitle('Format Pengisian Soal - DCM App');
	// 	$excel->setActiveSheetIndex(0)->mergeCells("A1:B1");
	// 	$excel->setActiveSheetIndex(0)->mergeCells("D1:G1");

	// 	foreach (range('A', $excel->getActiveSheet()->getHighestDataColumn()) as $col) {
	//         $excel->getActiveSheet()
	//                 ->getColumnDimension($col)
	//                 ->setAutoSize(true);
	//     } 

	// 	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// 	header('Content-Disposition: attachment;filename="Format Pengisian Soal - DCM App.xlsx"');
	// 	header('Cache-Control: max-age=0');
		 
	// 	$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	// 	$objWriter->save('php://output');
	// }

	// public function import_soal_from_excel()
	// {
	// 	$filename = $this->Clsglobal->upload_files("excelfiles","excel_files",["xlsx"])[0];
	// 	if ( $filename == 5 ) {
	// 		echo 5;
	// 	} else {
	// 		echo $this->soal->import_soal($filename);
	// 	}
	// }
}