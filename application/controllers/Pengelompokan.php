<?php 

class Pengelompokan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if ( !$this->session->user_logged ) {
			redirect( base_url() . "auth/login" );
		}

		if ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "siswa" ) {
			redirect( base_url() . "beranda" );
		}
		$this->load->model("Tabulasi_model","tabulasi");
		$this->load->model("Kategori_soal_model","kategori");
		$this->load->model("Siswa_model","siswa");
		$this->load->model("Kelas_model","kelas");
		$this->load->model("Soal_model","soal");
		$this->load->model("Profil_kelas_model","profil");
		$this->load->model("Profil_individu_model","individu");
		$this->load->model("Kategori_soal_model","kategori");
		$this->load->model("Pengelompokan_model","pengelompokan");
	}

	public function index() 
	{
		$data['pagetitle'] = "Pengelompokan Siswa per Masalah";
		$data['all_kelas'] = $this->kelas->get_all_kelas();
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("pengelompokan/pengelompokan");
		$this->load->view("templates/footer");
	}

	public function show($id_kelas)
	{
		$data['pagetitle'] = "show_pengelompokan";
		$data['all_soal'] = $this->pengelompokan->get_all_soal();
		$data['id_kelas'] = $id_kelas;
		$this->load->view("templates/head",$data);
		$this->load->view("pengelompokan/show");
	}

	public function print_laporan($id_kelas)
	{
		$all_soal = $this->pengelompokan->get_all_soal();
		$namafile = "Pengelompokkan Siswa";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$excel = new PHPExcel;
 
		$excel->getProperties()->setCreator("Dimas Firmansyah");
		$excel->getProperties()->setLastModifiedBy("Dimas Firmansyah");
		$excel->getProperties()->setTitle("Pengelompokkan Masalah");
		$excel->removeSheetByIndex(0);
		
		// HEADER
		$sheet = $excel->createSheet();
		$sheet->setTitle("Pengelompokkan Masalah");
		$sheet->setCellValue("A1", "PENGELOMPOKKAN");
		$sheet->setCellValue("A2", "SISWA PER MASALAH");
		// HEADER STYLE
		$headerStyle = [
			'font' => ['bold' => true, 'size' => '16'],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			],

		];
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($headerStyle);
		$excel->getActiveSheet()->getStyle('A2')->applyFromArray($headerStyle);
		$sheet->mergeCells("A1:E1");
		$sheet->mergeCells("A2:E2");

		// MASTER DATA
		$sheet->setCellValue("B4","Kelas");
		$sheet->setCellValue("C4",": " . $this->kelas->get_kelas($id_kelas)['kelas']);
		$sheet->setCellValue("B5","Sekolah");
		$sheet->setCellValue("C5",": " . $this->Clsglobal->site_info("nama_sekolah"));
		$sheet->setCellValue("B6","Alamat");
		$sheet->setCellValue("C6",": " . $this->Clsglobal->site_info("alamat"));

		// DATA TABLE
		$tableborderStyle = [
        	'borders' => [
        		'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
        		'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
        		'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
        		'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
        	],
        ];
		// head tabble
		$sheet->setCellValue("A8","No.");
		$sheet->setCellValue("B8","Masalah");
		$sheet->setCellValue("D8","Nomor Urut");
		$sheet->setCellValue("E8","Jumlah");
		// head style
		$sheet->mergeCells("B8:C8");
		$excel->getActiveSheet()->getStyle('A8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('B8:C8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('D8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('E8')->applyFromArray($tableborderStyle);

		// DATA
		$begin = 9;
		$i = 1;
		foreach ($all_soal as $soal) {
			$get_kelompok = $this->pengelompokan->get_kelompok($soal['no_soal'], $id_kelas);
			$sheet->setCellValue("A" . $begin,$i++);
			$sheet->setCellValue("B" . $begin,$soal['soal']);
			$jumlah = 0;
			$nomor = "";
			foreach ($get_kelompok as $kelompok) {
				$nomor .= $kelompok . ", ";
				$jumlah++;
			}
			$sheet->setCellValue("D" . $begin,$nomor);
			$sheet->setCellValue("E" . $begin,$jumlah);

			$excel->getActiveSheet()->getStyle("A" . $begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B" . $begin . ":C" . $begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("D" . $begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("E" . $begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$begin++;
		}


		// SET WIDTH OF COLUMN
	    $excel->getActiveSheet()->getColumnDimension("A")->setWidth(4);
	    $excel->getActiveSheet()->getColumnDimension("B")->setWidth(13);
	    $excel->getActiveSheet()->getColumnDimension("C")->setWidth(102);
	    $excel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
	    $excel->getActiveSheet()->getColumnDimension("E")->setWidth(7);

		$excel->getActiveSheet()->setTitle("Pengelompokkan Masalah");

		// signature
		$headsignature = $begin + 2;
		$subheadsignature = $headsignature + 1;
		$namesignature = $subheadsignature + 6;
	    $sheet->setCellValue("B" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("B" . $subheadsignature,"Kepala Sekolah");
		$sheet->setCellValue("B" . $namesignature,$this->Clsglobal->site_info("kepala_sekolah"));
		$sheet->setCellValue("D" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("D" . $subheadsignature,"Guru Pembimbing");
		$sheet->setCellValue("D" . $namesignature,$this->Clsglobal->site_info("guru_pembimbing"));



		$sheet->getPageSetup()->setFitToWidth(1);    
	    $sheet->getPageSetup()->setFitToHeight(0);

	    $excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$namafile.'.xlsx"');
		header('Cache-Control: max-age=1');
		 
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->setIncludeCharts(TRUE);
		$objWriter->save('php://output');
	}
}