<?php 

class Profil_individu extends CI_Controller {
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
		$this->load->model("Profil_individu_model","profil");
		$this->load->model("Kategori_soal_model","kategori");
		$this->load->model("Siswa_model","siswa");
		$this->load->model("Kelas_model","kelas");
		$this->load->model("Soal_model","soal");
	}

	public function index() 
	{
		$data['pagetitle'] = "Profil Individu";
		$data['all_kelas'] = $this->kelas->get_all_kelas();
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("profil_individu/profil_individu");
		$this->load->view("templates/footer");
	}

	public function show($kelas, $no_urut)
	{
		$data['pagetitle'] = "show_profil_individu";
		$id_siswa = $this->profil->get_siswa_by_nourut($kelas,$no_urut)['id_siswa'];
		$data['siswa'] = $this->profil->get_siswa($id_siswa);
		$data['pribadi_kategori'] = $this->profil->get_kategori(1,5);
		$data['sosial_kategori'] = $this->profil->get_kategori(6,8);
		$data['belajar_kategori'] = $this->profil->get_kategori(9,11);
		$data['karir_kategori'] = $this->profil->get_kategori(12,12);
		$data['soal_essay'] = $this->profil->get_essay();
		$data['kategori_chart'] = $this->profil->get_kategori_chart($id_siswa);
		$data['section_chart'] = $this->profil->get_section_chart($id_siswa);
		$data['answered'] = $this->profil->get_answered($id_siswa);
		$this->load->view("templates/head",$data);
		$this->load->view("profil_individu/show",$data);
	}

	public function set_chart_session()
	{
		$chart1 = $_POST['chart1'];
		$chart2 = $_POST['chart2'];

		$_SESSION["chart_profil"] = [$chart1, $chart2];
	}

	public function upload_chart()
	{
		$chart1 = $_SESSION["chart_profil"][0];
		$chart2 = $_SESSION["chart_profil"][1];

		$chart1img = $chart1;
		$chart1img = str_replace('data:image/png;base64,', '', $chart1img);
		$chart1img = str_replace(' ', '+', $chart1img);
		$chart1img = base64_decode($chart1img);
		$chart1img_name = uniqid();
		$chart1img_path = './assets/chart_img/'.$chart1img_name.'.jpg';
		file_put_contents($chart1img_path, $chart1img); 

		$chart2img = $chart2;
		$chart2img = str_replace('data:image/png;base64,', '', $chart2img);
		$chart2img = str_replace(' ', '+', $chart2img);
		$chart2img = base64_decode($chart2img);
		$chart2img_name = uniqid();
		$chart2img_path = './assets/chart_img/'.$chart2img_name.'.jpg';
		file_put_contents($chart2img_path, $chart2img);

		$encrypt = base64_encode($chart1img_path);
		$decrypt = base64_decode($encrypt);

		echo json_encode(["chart1" => $chart1img_name, "chart2" => $chart2img_name]);
	}

	public function do_print_laporan($kelas, $no_urut, $chart1, $chart2)
	{
		// $data['pagetitle'] = "print_profil_individu";
		$id_siswa = $this->profil->get_siswa_by_nourut($kelas,$no_urut)['id_siswa'];
		$siswa = $this->profil->get_siswa($id_siswa);
		$pribadi_kategori = $this->profil->get_kategori(1,5);
		$sosial_kategori = $this->profil->get_kategori(6,8);
		$belajar_kategori = $this->profil->get_kategori(9,11);
		$karir_kategori = $this->profil->get_kategori(12,12);
		$soal_essay = $this->profil->get_essay();
		$kategori_chart = $this->profil->get_kategori_chart($id_siswa);
		$section_chart = $this->profil->get_section_chart($id_siswa);
		$namafile = $siswa['nama_siswa'] . " (Profil Individu) ";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$kelas = $this->kelas->get_all_kelas();
		$excel = new PHPExcel;
 
		$excel->getProperties()->setCreator("Dimas Firmansyah");
		$excel->getProperties()->setLastModifiedBy("Dimas Firmansyah");
		$excel->getProperties()->setTitle("Profil Individu");
		$excel->removeSheetByIndex(0);
		
		// HEADER
		$sheet = $excel->createSheet();
		$sheet->setTitle("Profil Individu");
		$sheet->setCellValue("A1", "HASIL PENGOLAHAN");
		$sheet->setCellValue("A2", "DCM (DAFTAR CEK MASALAH)");
		$sheet->setCellValue("A3", "(INDIVIDUAL)");
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
		$sheet->mergeCells("A1:X1");
		$sheet->mergeCells("A2:X2");
		$sheet->mergeCells("A3:X3");

		// MASTER DATA
		$sheet->setCellValue("B5","Nomor Urut");
		$sheet->setCellValue("C5",": " . $siswa['no_urut']);
		$sheet->setCellValue("B6","Nama");
		$sheet->setCellValue("C6",": " . $siswa['nama_siswa']);
		$sheet->setCellValue("B7","Jenis Kelamin");
		$sheet->setCellValue("C7",": " . ucwords($siswa['jenis_kelamin']));
		$sheet->setCellValue("B8","Kelas");
		$sheet->setCellValue("C8",": " . $this->kelas->get_kelas($siswa['id_kelas'])['kelas']);

		// HEAD LABEL
		$sheet->setCellValue("A10","BIDANG DAN FREKUENSI MASALAH");
		$sheet->mergeCells("A10:X10");
		$excel->getActiveSheet()->getStyle('A10')->applyFromArray($headerStyle);

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
		$sheet->setCellValue("A11","KODE TOPIK MASALAH");
		$sheet->setCellValue("C11","JENIS MASALAH");
		$sheet->setCellValue("C12","NOMOR MASALAH");
		$sheet->setCellValue("W11","JML");
		$sheet->setCellValue("X11","%");
		// head style
		$sheet->mergeCells("A11:B12");
		$sheet->mergeCells("C11:V11");
		$sheet->mergeCells("C12:V12");
		$sheet->mergeCells("W11:W12");
		$sheet->mergeCells("X11:X12");
		$excel->getActiveSheet()->getStyle('A11:B12')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('C11:V11')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('C12:V12')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('W11:W12')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('X11:X12')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('A11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('A11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle('C11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('C12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('W11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('W11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle('X11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('X11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		// data
		$colsjawaban = ["C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V"];
			// pribadi
			$sheet->setCellValue("A13","I.");
			$sheet->setCellValue("B13","PRIBADI");
			$sheet->mergeCells("B13:V13");
			$excel->getActiveSheet()->getStyle('A13')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B13:V13')->applyFromArray($tableborderStyle);
			$jmlkeseluruhan = 0;

			$i = 1;
			$jmlpribadi = 0;
			$begin = 14;
			foreach ($pribadi_kategori as $kategori) {
				$get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			    $jumlah = 0;

				$sheet->setCellValue("A" . $begin,$i++);
				$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
				$excel->getActiveSheet()->getStyle("A" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("B" . $begin)->applyFromArray($tableborderStyle);

				$coljawaban = 0;
				foreach ($get_jawaban as $jawaban) {
					if ( $jawaban['remarks'] == "y" ) {
						$sheet->setCellValue($colsjawaban[$coljawaban] . $begin,$jawaban['no_soal']);
						$excel->getActiveSheet()->getStyle($colsjawaban[$coljawaban] . $begin)->applyFromArray($tableborderStyle);
						$jumlah++;
					} else{
						$sheet->setCellValue($colsjawaban[$coljawaban] . $begin," ");
						$excel->getActiveSheet()->getStyle($colsjawaban[$coljawaban] . $begin)->applyFromArray($tableborderStyle);
					}
					$coljawaban++;
				}

				$jmlpribadi += $jumlah;
				$jmlkeseluruhan += $jumlah;

				$sheet->setCellValue("W" . $begin,$jumlah);
				$sheet->setCellValue("X" . $begin,$jumlah / 20 * 100 . "%");
				$excel->getActiveSheet()->getStyle("W" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("X" . $begin)->applyFromArray($tableborderStyle);

				$begin++;
			}
			$sheet->setCellValue("W13",$jmlpribadi);
			$sheet->setCellValue("X13",ceil($jmlpribadi / 100 * 100) . "%");
			$excel->getActiveSheet()->getStyle("W13")->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("X13")->applyFromArray($tableborderStyle);
			// sosial
			$sheet->setCellValue("A19","II.");
			$sheet->setCellValue("B19","SOSIAL");
			$sheet->mergeCells("B19:V19");
			$excel->getActiveSheet()->getStyle('A19')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B19:V19')->applyFromArray($tableborderStyle);
			$i = 1;
			$jmlsosial = 0;
			$begin = 20;
			foreach ($sosial_kategori as $kategori) {
				$get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			    $jumlah = 0;

				$sheet->setCellValue("A" . $begin,$i++);
				$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
				$excel->getActiveSheet()->getStyle("A" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("B" . $begin)->applyFromArray($tableborderStyle);

				$coljawaban = 0;
				foreach ($get_jawaban as $jawaban) {
					if ( $jawaban['remarks'] == "y" ) {
						$sheet->setCellValue($colsjawaban[$coljawaban] . $begin,$jawaban['no_soal']);
						$excel->getActiveSheet()->getStyle($colsjawaban[$coljawaban] . $begin)->applyFromArray($tableborderStyle);
						$jumlah++;
					} else{
						$sheet->setCellValue($colsjawaban[$coljawaban] . $begin," ");
						$excel->getActiveSheet()->getStyle($colsjawaban[$coljawaban] . $begin)->applyFromArray($tableborderStyle);
					}
					$coljawaban++;
				}

				$jmlsosial += $jumlah;
				$jmlkeseluruhan += $jumlah;

				$sheet->setCellValue("W" . $begin,$jumlah);
				$sheet->setCellValue("X" . $begin,$jumlah / 20 * 100 . "%");
				$excel->getActiveSheet()->getStyle("W" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("X" . $begin)->applyFromArray($tableborderStyle);
				$begin++;

			}
			$sheet->setCellValue("W19",$jmlsosial);
			$sheet->setCellValue("X19",ceil($jmlsosial / 100 * 100) . "%");
			$excel->getActiveSheet()->getStyle("W19")->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("X19")->applyFromArray($tableborderStyle);
			// belajar
			$sheet->setCellValue("A23","III.");
			$sheet->setCellValue("B23","BELAJAR");
			$sheet->mergeCells("B23:V23");
			$excel->getActiveSheet()->getStyle('A23')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B23:V23')->applyFromArray($tableborderStyle);
			$i = 1;
			$jmlbelajar = 0;
			$begin = 24;
			foreach ($belajar_kategori as $kategori) {
				$get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			    $jumlah = 0;

				$sheet->setCellValue("A" . $begin,$i++);
				$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
				$excel->getActiveSheet()->getStyle("A" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("B" . $begin)->applyFromArray($tableborderStyle);

				$coljawaban = 0;
				foreach ($get_jawaban as $jawaban) {
					if ( $jawaban['remarks'] == "y" ) {
						$sheet->setCellValue($colsjawaban[$coljawaban] . $begin,$jawaban['no_soal']);
						$excel->getActiveSheet()->getStyle($colsjawaban[$coljawaban] . $begin)->applyFromArray($tableborderStyle);
						$jumlah++;
					} else{
						$sheet->setCellValue($colsjawaban[$coljawaban] . $begin," ");
						$excel->getActiveSheet()->getStyle($colsjawaban[$coljawaban] . $begin)->applyFromArray($tableborderStyle);
					}
					$coljawaban++;
				}

				$jmlbelajar += $jumlah;
				$jmlkeseluruhan += $jumlah;

				$sheet->setCellValue("W" . $begin,$jumlah);
				$sheet->setCellValue("X" . $begin,$jumlah / 20 * 100 . "%");
				$excel->getActiveSheet()->getStyle("W" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("X" . $begin)->applyFromArray($tableborderStyle);
				$begin++;

			}
			$sheet->setCellValue("W23",$jmlbelajar);
			$sheet->setCellValue("X23",ceil($jmlbelajar / 100 * 100) . "%");
			$excel->getActiveSheet()->getStyle("W23")->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("X23")->applyFromArray($tableborderStyle);
			// karir
			$sheet->setCellValue("A27","IV.");
			$sheet->setCellValue("B27","KARIR");
			$sheet->mergeCells("B27:V27");
			$excel->getActiveSheet()->getStyle('A27')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B27:V27')->applyFromArray($tableborderStyle);
			$i = 1;
			$jmlkarir = 0;
			$begin = 28;
			foreach ($karir_kategori as $kategori) {
				$get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			    $jumlah = 0;

				$sheet->setCellValue("A" . $begin,$i++);
				$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
				$excel->getActiveSheet()->getStyle("A" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("B" . $begin)->applyFromArray($tableborderStyle);

				$coljawaban = 0;
				foreach ($get_jawaban as $jawaban) {
					if ( $jawaban['remarks'] == "y" ) {
						$sheet->setCellValue($colsjawaban[$coljawaban] . $begin,$jawaban['no_soal']);
						$excel->getActiveSheet()->getStyle($colsjawaban[$coljawaban] . $begin)->applyFromArray($tableborderStyle);
						$jumlah++;
					} else{
						$sheet->setCellValue($colsjawaban[$coljawaban] . $begin," ");
						$excel->getActiveSheet()->getStyle($colsjawaban[$coljawaban] . $begin)->applyFromArray($tableborderStyle);
					}
					$coljawaban++;
				}

				$jmlkarir += $jumlah;
				$jmlkeseluruhan += $jumlah;

				$sheet->setCellValue("W" . $begin,$jumlah);
				$sheet->setCellValue("X" . $begin,$jumlah / 20 * 100 . "%");
				$excel->getActiveSheet()->getStyle("W" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("X" . $begin)->applyFromArray($tableborderStyle);
				$begin++;

			}
			$sheet->setCellValue("W27",$jmlkarir);
			$sheet->setCellValue("X27",ceil($jmlkarir / 100 * 100) . "%");
			$excel->getActiveSheet()->getStyle("W27")->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("X27")->applyFromArray($tableborderStyle);
			// essay
			$i = 0;
			$begin = 30;
			foreach ($soal_essay as $essay) {
				$get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],"13");
				$sheet->setCellValue("A" . $begin,$essay['no_soal']);
				$colnum = $begin +1;
				$sheet->mergeCells("A$begin:A$colnum");
				$excel->getActiveSheet()->getStyle("A$begin")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$excel->getActiveSheet()->getStyle("A$begin")->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$excel->getActiveSheet()->getStyle("A$begin:A$colnum")->applyFromArray($tableborderStyle);


				$sheet->mergeCells("B$begin:X$begin");
				$excel->getActiveSheet()->getStyle("B$begin:X$begin")->applyFromArray($tableborderStyle);
				$sheet->setCellValue("B" . $begin++,$essay['soal']);

				$sheet->mergeCells("B$begin:X$begin");
				$excel->getActiveSheet()->getStyle("B$begin:X$begin")->applyFromArray($tableborderStyle);
				$sheet->setCellValue("B" .$begin,ltrim($get_jawaban[$i++]['remarks']));

				$begin++;
			}


		// SET WIDTH OF COLUMN
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(39);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('J')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('K')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('L')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('M')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('N')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('O')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('P')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('Q')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('R')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('S')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('T')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('U')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('V')->setWidth(3);
		$excel->getActiveSheet()->getColumnDimension('W')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('X')->setWidth(5);

		
		$excel->getActiveSheet()->setTitle("Profil Individu");

		foreach (range('A', $excel->getActiveSheet()->getHighestDataColumn()) as $col) {
	        $excel->getActiveSheet()
	                ->getColumnDimension($col)
	                ->setAutoSize(false);
	    }

	    // $excel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);

	    $sheet->setCellValue("B38","Mengetahui,");
	    $sheet->setCellValue("B39","Kepala Sekolah");
		$sheet->setCellValue("B45",$this->Clsglobal->site_info("kepala_sekolah"));
		$sheet->setCellValue("M38","Mengetahui,");
	    $sheet->setCellValue("M39","Guru Pembimbing");
		$sheet->setCellValue("M45",$this->Clsglobal->site_info("guru_pembimbing"));
		$excel->getActiveSheet()->getStyle('B38:W45')->applyFromArray(
		    array(
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFFFF')
		        )
		    )
		);

		$sheet->setCellValue("A72", "GRAFIK PROFIL INDIVIDUAL");
		$sheet->setCellValue("A73", strtoupper($siswa['nama_siswa']));
		$excel->getActiveSheet()->getStyle('A72')->applyFromArray($headerStyle);
		$excel->getActiveSheet()->getStyle('A73')->applyFromArray($headerStyle);
		$sheet->mergeCells("A72:X72");
		$sheet->mergeCells("A73:X73");

		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Chart 1');
		$objDrawing->setDescription('Chart 1');
		$objDrawing->setPath("./assets/chart_img/" . $chart1 . ".jpg");
		$objDrawing->setCoordinates('B75');   
		$objDrawing->setOffsetX(50); 
		$objDrawing->setOffsetY(0);    
		$objDrawing->setWidth(450); 
		$objDrawing->setHeight(450); 
		$objDrawing->setWorksheet($excel->getActiveSheet());

		$objDrawing2 = new PHPExcel_Worksheet_Drawing();
		$objDrawing2->setName('Chart 2');
		$objDrawing2->setDescription('Chart 2');
		$objDrawing2->setPath("./assets/chart_img/" . $chart2 . ".jpg");
		$objDrawing2->setCoordinates('B99');   
		$objDrawing2->setOffsetX(50); 
		$objDrawing2->setOffsetY(0);    
		$objDrawing2->setWidth(450); 
		$objDrawing2->setHeight(450); 
		$objDrawing2->setWorksheet($excel->getActiveSheet());

		$excel->getActiveSheet()->getStyle('B75:W97')->applyFromArray(
		    array(
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFFFF')
		        )
		    )
		);
		$excel->getActiveSheet()->getStyle('B99:W121')->applyFromArray(
		    array(
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFFFF')
		        )
		    )
		);

		$sheet->getPageSetup()->setFitToWidth(1);    
	    $sheet->getPageSetup()->setFitToHeight(0);

	    $excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$namafile.'.xlsx"');
		header('Cache-Control: max-age=1');
		 
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->setIncludeCharts(TRUE);
		$objWriter->save('php://output');
		unlink("./assets/chart_img/" . $chart1 . ".jpg");
		unlink("./assets/chart_img/" . $chart2 . ".jpg");
		// $this->load->view('templates/head', $data);
		// $this->load->view('profil_individu/show', $data);
		// $this->load->view('templates/print', $data);
	}
}