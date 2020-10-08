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
		$this->load->view("templates/head",$data);
		$this->load->view("profil_individu/show",$data);
	}

	public function print_laporan($kelas, $no_urut)
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
		$sheet->setCellValue("W11","JUMLAH");
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
			$sheet->mergeCells("B13:X13");
			$excel->getActiveSheet()->getStyle('A13')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B13:X13')->applyFromArray($tableborderStyle);
			$i = 1;
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

				$sheet->setCellValue("W" . $begin,$jumlah);
				$sheet->setCellValue("X" . $begin,$jumlah / 20 * 100 . "%");
				$excel->getActiveSheet()->getStyle("W" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("X" . $begin)->applyFromArray($tableborderStyle);
				$begin++;
			}
			// sosial
			$sheet->setCellValue("A19","II.");
			$sheet->setCellValue("B19","SOSIAL");
			$sheet->mergeCells("B19:X19");
			$excel->getActiveSheet()->getStyle('A19')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B19:X19')->applyFromArray($tableborderStyle);
			$i = 1;
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

				$sheet->setCellValue("W" . $begin,$jumlah);
				$sheet->setCellValue("X" . $begin,$jumlah / 20 * 100 . "%");
				$excel->getActiveSheet()->getStyle("W" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("X" . $begin)->applyFromArray($tableborderStyle);
				$begin++;

			}
			// belajar
			$sheet->setCellValue("A23","III.");
			$sheet->setCellValue("B23","BELAJAR");
			$sheet->mergeCells("B23:X23");
			$excel->getActiveSheet()->getStyle('A23')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B23:X23')->applyFromArray($tableborderStyle);
			$i = 1;
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

				$sheet->setCellValue("W" . $begin,$jumlah);
				$sheet->setCellValue("X" . $begin,$jumlah / 20 * 100 . "%");
				$excel->getActiveSheet()->getStyle("W" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("X" . $begin)->applyFromArray($tableborderStyle);
				$begin++;

			}
			// karir
			$sheet->setCellValue("A27","IV.");
			$sheet->setCellValue("B27","KARIR");
			$sheet->mergeCells("B27:X27");
			$excel->getActiveSheet()->getStyle('A27')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B27:X27')->applyFromArray($tableborderStyle);
			$i = 1;
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

				$sheet->setCellValue("W" . $begin,$jumlah);
				$sheet->setCellValue("X" . $begin,$jumlah / 20 * 100 . "%");
				$excel->getActiveSheet()->getStyle("W" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle("X" . $begin)->applyFromArray($tableborderStyle);
				$begin++;

			}
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
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(22);
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
		$excel->getActiveSheet()->getColumnDimension('W')->setWidth(8.3);
		$excel->getActiveSheet()->getColumnDimension('X')->setWidth(5);


		$excel->getActiveSheet()->setTitle("Profil Individu");

		foreach (range('A', $excel->getActiveSheet()->getHighestDataColumn()) as $col) {
	        $excel->getActiveSheet()
	                ->getColumnDimension($col)
	                ->setAutoSize(false);
	    } 

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$namafile.'.xlsx"');
		header('Cache-Control: max-age=0');
		 
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save('php://output');



		// $this->load->view('templates/head', $data);
		// $this->load->view('profil_individu/show', $data);
		// $this->load->view('templates/print', $data);
	}
}