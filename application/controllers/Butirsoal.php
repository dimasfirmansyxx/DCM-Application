<?php 

class Butirsoal extends CI_Controller {
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
		$this->load->model("Butirsoal_model","butirsoal");
	}

	public function index()
	{
		redirect( base_url() . "beranda" );
	}

	public function paralel($param = null, $sortir = null)
	{
		if ( $param == "show" ) {
			$data['pagetitle'] = "show_analisis_soal_paralel";
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['sortir'] = $sortir;
			$this->load->view("templates/head",$data);
			$this->load->view("butirsoal/paralel_show",$data);
		} elseif ( $param == "print_laporan" ) {
			$data['pagetitle'] = "print_analisis_soal_paralel";
			$data['namafile'] = "Laporan Analisis Butir Soal Paralel";
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['sortir'] = $sortir;
			$this->load->view("templates/head",$data);
			$this->load->view("templates/print");
			$this->load->view("butirsoal/paralel_show",$data);
		} else {
			$data['pagetitle'] = "Analisis Butir Soal";
			$this->load->view("templates/head",$data);
			$this->load->view("templates/header");
			$this->load->view("templates/navbar");
			$this->load->view("butirsoal/paralel");
			$this->load->view("templates/footer");
		}
	}

	public function print_paralel($sortir = null)
	{
		$namafile = "Laporan Analisis Butir Soal Paralel";
		$get_kategori = $this->kategori->get_all_kategori();

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$kelas = $this->kelas->get_all_kelas();
		$excel = new PHPExcel;
 
		$excel->getProperties()->setCreator("Dimas Firmansyah");
		$excel->getProperties()->setLastModifiedBy("Dimas Firmansyah");
		$excel->getProperties()->setTitle("Analisis Butir Soal");
		$excel->removeSheetByIndex(0);
		
		// HEADER
		$sheet = $excel->createSheet();
		$sheet->setTitle("Analisis Butir Soal");
		$sheet->setCellValue("A1", "HASIL ANALISIS PER BUTIR SOAL");
		$sheet->setCellValue("A2", "DCM KELAS PARALEL");
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
		$sheet->mergeCells("A1:F1");
		$sheet->mergeCells("A2:F2");

		// MASTER DATA
		$sheet->setCellValue("B4","Sekolah");
		$sheet->setCellValue("C4",": " . $this->Clsglobal->site_info("nama_sekolah"));
		$sheet->setCellValue("B5","Alamat");
		$sheet->setCellValue("C5",": " . $this->Clsglobal->site_info("alamat"));

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
		$sheet->setCellValue("A7","NO.");
		$sheet->setCellValue("B7","TOPIK");
		$sheet->setCellValue("D7","Nm");
		$sheet->setCellValue("E7","(Nm : N) x 100%");
		$sheet->setCellValue("F7","Derajat Masalah");
		// head style
		$sheet->mergeCells("B7:C7");
		$excel->getActiveSheet()->getStyle('A7')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('B7:C7')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('D7')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('E7')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('F7')->applyFromArray($tableborderStyle);

		// DATA
		$begin = 8;
		foreach ($get_kategori as $kategori) {
			if ( !($kategori['id_kategori'] == 13) ) {

				// TOPIK
				$sheet->setCellValue("A" . $begin, $this->Clsglobal->romawi($kategori['id_kategori']) . ". " .   $kategori['nama_kategori']);
				$sheet->mergeCells("A" . $begin . ":F" . $begin);
				$excel->getActiveSheet()->getStyle("A" . $begin . ":F" . $begin)->applyFromArray($tableborderStyle);
				$begin++;

				// SOAL
				$get_soal = $this->butirsoal->get_soal($kategori['id_kategori']); 
				$jumlah = 0;
				foreach ($get_soal as $soal) {
					$get_jawaban = $this->butirsoal->get_jawaban($soal['no_soal']);
					$jmlsiswa = $this->butirsoal->jmlsiswa();
					$persentase = $get_jawaban / $jmlsiswa * 100;

					if ( $persentase >= 0 && $persentase < 1 ) {
						$derajat = "A";
					} elseif ( $persentase >= 1 && $persentase < 11 ) {
						$derajat = "B";
					} elseif ( $persentase >= 11 && $persentase < 26 ) {
						$derajat = "C";
					} elseif ( $persentase >= 26 && $persentase < 51 ) {
						$derajat = "D";
					} else {
						$derajat = "E";
					}

					if ( !($sortir == null) ) {
						if ( $sortir == $derajat ) {
							$jumlah += $get_jawaban;
						}
					} else {	
						$jumlah += $get_jawaban;
					}

					if ( !($sortir == null) ) {
						if ( $sortir == $derajat ) {
							$sheet->setCellValue("A" . $begin, $soal['no_soal']);
							$sheet->setCellValue("B" . $begin, $soal['soal']);
							$sheet->setCellValue("D" . $begin, $get_jawaban);
							$sheet->setCellValue("E" . $begin, $persentase . "%");
							$sheet->setCellValue("F" . $begin, $derajat);
				
							$sheet->mergeCells("B" . $begin . ":C" . $begin);
							$excel->getActiveSheet()->getStyle("A" . $begin)->applyFromArray($tableborderStyle);
							$excel->getActiveSheet()->getStyle("B" . $begin . ":C" . $begin)->applyFromArray($tableborderStyle);
							$excel->getActiveSheet()->getStyle("D" . $begin)->applyFromArray($tableborderStyle);
							$excel->getActiveSheet()->getStyle("E" . $begin)->applyFromArray($tableborderStyle);
							$excel->getActiveSheet()->getStyle("F" . $begin)->applyFromArray($tableborderStyle);
							$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						}
					} else {
						$sheet->setCellValue("A" . $begin, $soal['no_soal']);
						$sheet->setCellValue("B" . $begin, $soal['soal']);
						$sheet->setCellValue("D" . $begin, $get_jawaban);
						$sheet->setCellValue("E" . $begin, $persentase . "%");
						$sheet->setCellValue("F" . $begin, $derajat);
			
						$sheet->mergeCells("B" . $begin . ":C" . $begin);
						$excel->getActiveSheet()->getStyle("A" . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle("B" . $begin . ":C" . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle("D" . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle("E" . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle("F" . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}

					$begin++;
				}

				$sheet->setCellValue("A" . $begin, "JUMLAH");
				$sheet->setCellValue("D" . $begin, $jumlah);
				$sheet->mergeCells("A" . $begin . ":C" . $begin);
				$sheet->mergeCells("D" . $begin . ":F" . $begin);
				$excel->getActiveSheet()->getStyle("A" . $begin . ":C" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("D" . $begin . ":F" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("A" . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$excel->getActiveSheet()->getStyle("D" . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$begin++;

			}
		}


		// SET WIDTH OF COLUMN
	    $excel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
	    $excel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
	    $excel->getActiveSheet()->getColumnDimension("C")->setWidth(58);
	    $excel->getActiveSheet()->getColumnDimension("D")->setWidth(4);
	    $excel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
	    $excel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);

		$excel->getActiveSheet()->setTitle("Analisis Butir Soal");

		// signature
		$headsignature = $begin + 2;
		$subheadsignature = $headsignature + 1;
		$namesignature = $subheadsignature + 6;
	    $sheet->setCellValue("B" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("B" . $subheadsignature,"Kepala Sekolah");
		$sheet->setCellValue("B" . $namesignature,$this->Clsglobal->site_info("kepala_sekolah"));
		$sheet->setCellValue("E" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("E" . $subheadsignature,"Guru Pembimbing");
		$sheet->setCellValue("E" . $namesignature,$this->Clsglobal->site_info("guru_pembimbing"));



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

	public function kelas($param = null, $id_kelas = null, $sortir = null)
	{
		if ( $param == "show" ) {
			$data['pagetitle'] = "show_analisis_soal_perkelas";
			$data['id_kelas'] = $id_kelas;
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['sortir'] = $sortir;
			$this->load->view("templates/head",$data);
			$this->load->view("butirsoal/kelas_show",$data);
		} elseif ( $param == "print_laporan" ) {
			$data['pagetitle'] = "show_analisis_soal_perkelas";
			$data['id_kelas'] = $id_kelas;
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['sortir'] = $sortir;
			$data['namafile'] = "Laporan Analisis Butir Soal Perkelas";
			$this->load->view("templates/head",$data);
			$this->load->view("templates/print");
			$this->load->view("butirsoal/kelas_show",$data);
		} else {
			$data['pagetitle'] = "Analisis Butir Soal";
			$data['all_kelas'] = $this->kelas->get_all_kelas();
			$this->load->view("templates/head",$data);
			$this->load->view("templates/header");
			$this->load->view("templates/navbar");
			$this->load->view("butirsoal/kelas");
			$this->load->view("templates/footer");
		}
	}

	public function print_kelas($id_kelas = null, $sortir = null)
	{
		$get_kategori = $this->kategori->get_all_kategori();
		$sortir = $sortir;
		$namafile = "Laporan Analisis Butir Soal Perkelas";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$kelas = $this->kelas->get_all_kelas();
		$excel = new PHPExcel;
 
		$excel->getProperties()->setCreator("Dimas Firmansyah");
		$excel->getProperties()->setLastModifiedBy("Dimas Firmansyah");
		$excel->getProperties()->setTitle("Analisis Butir Soal");
		$excel->removeSheetByIndex(0);
		
		// HEADER
		$sheet = $excel->createSheet();
		$sheet->setTitle("Analisis Butir Soal");
		$sheet->setCellValue("A1", "HASIL ANALISIS PER BUTIR SOAL");
		$sheet->setCellValue("A2", "DCM PER KELAS");
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
		$sheet->mergeCells("A1:F1");
		$sheet->mergeCells("A2:F2");

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
		$sheet->setCellValue("A8","NO.");
		$sheet->setCellValue("B8","TOPIK");
		$sheet->setCellValue("D8","Nm");
		$sheet->setCellValue("E8","(Nm : N) x 100%");
		$sheet->setCellValue("F8","Derajat Masalah");
		// head style
		$sheet->mergeCells("B8:C8");
		$excel->getActiveSheet()->getStyle('A8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('B8:C8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('D8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('E8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('F8')->applyFromArray($tableborderStyle);

		// DATA
		$begin = 9;
		foreach ($get_kategori as $kategori) {
			if ( !($kategori['id_kategori'] == 13) ) {

				// TOPIK
				$sheet->setCellValue("A" . $begin, $this->Clsglobal->romawi($kategori['id_kategori']) . ". " .   $kategori['nama_kategori']);
				$sheet->mergeCells("A" . $begin . ":F" . $begin);
				$excel->getActiveSheet()->getStyle("A" . $begin . ":F" . $begin)->applyFromArray($tableborderStyle);
				$begin++;

				// SOAL
				$get_soal = $this->butirsoal->get_soal($kategori['id_kategori']); 
				$jumlah = 0;
				foreach ($get_soal as $soal) {
					$get_jawaban = $this->butirsoal->get_jawaban($soal['no_soal'],$id_kelas);
					$jmlsiswa = $this->butirsoal->jmlsiswa($id_kelas);
					if ( $jmlsiswa == 0 ) {
						$persentase = 0;
					} else {
						$persentase = $get_jawaban / $jmlsiswa * 100;
					}

					if ( $persentase >= 0 && $persentase < 1 ) {
						$derajat = "A";
					} elseif ( $persentase >= 1 && $persentase < 11 ) {
						$derajat = "B";
					} elseif ( $persentase >= 11 && $persentase < 26 ) {
						$derajat = "C";
					} elseif ( $persentase >= 26 && $persentase < 51 ) {
						$derajat = "D";
					} else {
						$derajat = "E";
					}

					if ( !($sortir == null) ) {
						if ( $sortir == $derajat ) {
							$jumlah += $get_jawaban;
						}
					} else {	
						$jumlah += $get_jawaban;
					}

					if ( !($sortir == null) ) {
						if ( $sortir == $derajat ) {
							$sheet->setCellValue("A" . $begin, $soal['no_soal']);
							$sheet->setCellValue("B" . $begin, $soal['soal']);
							$sheet->setCellValue("D" . $begin, $get_jawaban);
							$sheet->setCellValue("E" . $begin, $persentase . "%");
							$sheet->setCellValue("F" . $begin, $derajat);
				
							$sheet->mergeCells("B" . $begin . ":C" . $begin);
							$excel->getActiveSheet()->getStyle("A" . $begin)->applyFromArray($tableborderStyle);
							$excel->getActiveSheet()->getStyle("B" . $begin . ":C" . $begin)->applyFromArray($tableborderStyle);
							$excel->getActiveSheet()->getStyle("D" . $begin)->applyFromArray($tableborderStyle);
							$excel->getActiveSheet()->getStyle("E" . $begin)->applyFromArray($tableborderStyle);
							$excel->getActiveSheet()->getStyle("F" . $begin)->applyFromArray($tableborderStyle);
							$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						}
					} else {
						$sheet->setCellValue("A" . $begin, $soal['no_soal']);
						$sheet->setCellValue("B" . $begin, $soal['soal']);
						$sheet->setCellValue("D" . $begin, $get_jawaban);
						$sheet->setCellValue("E" . $begin, $persentase . "%");
						$sheet->setCellValue("F" . $begin, $derajat);
			
						$sheet->mergeCells("B" . $begin . ":C" . $begin);
						$excel->getActiveSheet()->getStyle("A" . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle("B" . $begin . ":C" . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle("D" . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle("E" . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle("F" . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}

					$begin++;
				}

				$sheet->setCellValue("A" . $begin, "JUMLAH");
				$sheet->setCellValue("D" . $begin, $jumlah);
				$sheet->mergeCells("A" . $begin . ":C" . $begin);
				$sheet->mergeCells("D" . $begin . ":F" . $begin);
				$excel->getActiveSheet()->getStyle("A" . $begin . ":C" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("D" . $begin . ":F" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("A" . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$excel->getActiveSheet()->getStyle("D" . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$begin++;

			}
		}


		// SET WIDTH OF COLUMN
	    $excel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
	    $excel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
	    $excel->getActiveSheet()->getColumnDimension("C")->setWidth(58);
	    $excel->getActiveSheet()->getColumnDimension("D")->setWidth(4);
	    $excel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
	    $excel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);

		$excel->getActiveSheet()->setTitle("Analisis Butir Soal");

		// signature
		$headsignature = $begin + 2;
		$subheadsignature = $headsignature + 1;
		$namesignature = $subheadsignature + 6;
	    $sheet->setCellValue("B" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("B" . $subheadsignature,"Kepala Sekolah");
		$sheet->setCellValue("B" . $namesignature,$this->Clsglobal->site_info("kepala_sekolah"));
		$sheet->setCellValue("E" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("E" . $subheadsignature,"Guru Pembimbing");
		$sheet->setCellValue("E" . $namesignature,$this->Clsglobal->site_info("guru_pembimbing"));



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