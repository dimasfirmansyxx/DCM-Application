<?php 

class Profil_kelas extends CI_Controller {
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
		$this->load->model("Profil_kelas_model","profil");
		$this->load->model("Profil_individu_model","individu");
		$this->load->model("Kategori_soal_model","kategori");
		$this->load->model("Siswa_model","siswa");
		$this->load->model("Kelas_model","kelas");
		$this->load->model("Soal_model","soal");
	}

	public function index() 
	{
		$data['pagetitle'] = "Profil Kelas";
		$data['all_kelas'] = $this->kelas->get_all_kelas();
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("profil_kelas/profil_kelas");
		$this->load->view("templates/footer");
	}

	public function show($id_kelas)
	{
		$data['pagetitle'] = "show_profil_kelas";
		$data['get_kelas'] = $this->kelas->get_kelas($id_kelas);
		$data['get_siswa'] = $this->profil->get_siswa_by_kelas($id_kelas);
		$data['get_kategori'] = $this->kategori->get_all_kategori();
		$data['abjad'] = ["A","B","C","D","E","F","G","H","I","J","K","L","M"];
		$this->load->view("templates/head",$data);
		$this->load->view("profil_kelas/show",$data);
	}

	public function print_laporan($id_kelas)
	{
		$get_kelas = $this->kelas->get_kelas($id_kelas);
		$get_siswa = $this->profil->get_siswa_by_kelas($id_kelas);
		$get_kategori = $this->kategori->get_all_kategori();
		$namafile = "Laporan Profil Kelas " . $get_kelas['kelas'];

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$kelas = $this->kelas->get_all_kelas();
		$excel = new PHPExcel;
 
		$excel->getProperties()->setCreator("Dimas Firmansyah");
		$excel->getProperties()->setLastModifiedBy("Dimas Firmansyah");
		$excel->getProperties()->setTitle("Profil Kelas");
		$excel->removeSheetByIndex(0);
		
		// HEADER
		$sheet = $excel->createSheet();
		$sheet->setTitle("Profil Kelas");
		$sheet->setCellValue("A1", "HASIL PENGOLAHAN");
		$sheet->setCellValue("A2", "DCM (DAFTAR CEK MASALAH)");
		$sheet->setCellValue("A3", "(KLASIKAL)");
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
		$excel->getActiveSheet()->getStyle('A3')->applyFromArray($headerStyle);
		$sheet->mergeCells("A1:Q1");
		$sheet->mergeCells("A2:Q2");
		$sheet->mergeCells("A3:Q3");

		// MASTER DATA
		$sheet->setCellValue("B5","Kelas");
		$sheet->setCellValue("C5",": " . $get_kelas['kelas']);
		$sheet->setCellValue("B6","Sekolah");
		$sheet->setCellValue("C6",": " . $this->Clsglobal->site_info("nama_sekolah"));
		$sheet->setCellValue("B7","Alamat");
		$sheet->setCellValue("C7",": " . $this->Clsglobal->site_info("alamat"));

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
		$sheet->setCellValue("A9","No.");
		$sheet->setCellValue("B9","Nama");
		$sheet->setCellValue("C9","Jenis Kelamin");
		$sheet->setCellValue("D9","Topik Masalah");
		$sheet->setCellValue("P9","JML");
		// head style
		$sheet->mergeCells("A9:A11");
		$sheet->mergeCells("B9:B11");
		$sheet->mergeCells("C9:C11");
		$sheet->mergeCells("D9:O9");
		$sheet->mergeCells("P9:P11");
		$excel->getActiveSheet()->getStyle('A9:A11')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('B9:B11')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('C9:C11')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('D9:O9')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('P9:P11')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('A9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('A9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle('B9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('B9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle('C9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('C9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle('D9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('P9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('P9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	    $excel->getActiveSheet()->getStyle("D10:O11")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$sheet->setCellValue("D10","Pribadi");
		$sheet->setCellValue("I10","Sosial");
		$sheet->setCellValue("L10","Belajar");
		$sheet->setCellValue("O10","Karir");
		$sheet->mergeCells("D10:H10");
		$sheet->mergeCells("I10:K10");
		$sheet->mergeCells("L10:N10");
		$excel->getActiveSheet()->getStyle('D10:H10')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('I10:K10')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('L10:N10')->applyFromArray($tableborderStyle);

		$colstopik = ["D","E","F","G","H","I","J","K","L","M","N","O"];
		$valtopik = ["A","B","C","D","E","F","G","H","I","J","K","L"];
		for($i = 0; $i < count($get_kategori) - 1; $i++) {
			// $sheet->setCellValue($colstopik[$i] . "11",$get_kategori[$i]['nama_kategori']);
			$sheet->setCellValue($colstopik[$i] . "11",$valtopik[$i]);
			$excel->getActiveSheet()->getStyle($colstopik[$i] . "11")->applyFromArray($tableborderStyle);
		}


		// data
		$begin = 12;
		$colstable = ["D","E","F","G","H","I","J","K","L","M","N","O","P"];
		foreach ($get_siswa as $siswa) {
			$sheet->setCellValue("A" . $begin, $siswa['no_urut']);
			$sheet->setCellValue("B" . $begin, $siswa['nama_siswa']);
			$sheet->setCellValue("C" . $begin, ucwords($siswa['jenis_kelamin']));
			$excel->getActiveSheet()->getStyle("A" . $begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B" . $begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("C" . $begin)->applyFromArray($tableborderStyle);

			$get_score = $this->profil->get_score($siswa['id_siswa']);
			$i = 0;
			foreach ($get_score as $score) {
				$sheet->setCellValue($colstable[$i] . $begin, $score);
				$excel->getActiveSheet()->getStyle($colstable[$i] . $begin)->applyFromArray($tableborderStyle);
			    $excel->getActiveSheet()->getStyle($colstable[$i] . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$i++;
			}
			$begin++;
		}


		// SET WIDTH OF COLUMN
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
		$excel->getActiveSheet()->getColumnDimension('P')->setWidth(4);
	    $excel->getActiveSheet()->getColumnDimension("D")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("E")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("F")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("G")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("H")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("I")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("J")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("K")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("L")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("M")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("N")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("O")->setWidth(5);
	    $excel->getActiveSheet()->getColumnDimension("Q")->setWidth(42);

	    $excel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);

		$excel->getActiveSheet()->setTitle("Profil Kelas");

		// signature
		if ( $begin > 23 ) {
			$headsignature = $begin + 2;
			$subheadsignature = $headsignature + 1;
			$namesignature = $subheadsignature + 6;
		} else {
			$headsignature = 26;
			$subheadsignature = $headsignature + 1;
			$namesignature = $subheadsignature + 6;
		}
	    $sheet->setCellValue("B" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("B" . $subheadsignature,"Kepala Sekolah");
		$sheet->setCellValue("B" . $namesignature,$this->Clsglobal->site_info("kepala_sekolah"));
		$sheet->setCellValue("M" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("M" . $subheadsignature,"Guru Pembimbing");
		$sheet->setCellValue("M" . $namesignature,$this->Clsglobal->site_info("guru_pembimbing"));

		// keterangan column
		$sheet->setCellValue("Q9","Keterangan");
		$sheet->mergeCells("Q9:Q11");
		$excel->getActiveSheet()->getStyle('Q9:Q11')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('Q9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('Q9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$beginketcol = 12;
		$ketcol_num = ["A","B","C","D","E","F","G","H","I","J","K","L"];
		for($i = 0; $i < count($get_kategori) - 1; $i++) {
			$sheet->setCellValue("Q" . $beginketcol,$ketcol_num[$i] . ". " . $get_kategori[$i]['nama_kategori']);
			$beginketcol++;
		}
		$excel->getActiveSheet()->getStyle('Q12:Q23')->applyFromArray($tableborderStyle);



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