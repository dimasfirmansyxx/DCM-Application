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
		$answered = $this->profil->get_answered($id_siswa);
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
		$sheet->setCellValue("B9","Sekolah");
		$sheet->setCellValue("C9",": " . $this->Clsglobal->site_info("nama_sekolah"));
		$sheet->setCellValue("B10","Tahun Pelajaran");
		$sheet->setCellValue("C10",": " . $this->Clsglobal->site_info("tahun_ajar"));
		$sheet->setCellValue("B11","Tanggal Mengisi");
		$sheet->setCellValue("C11",": " . $this->profil->get_jawaban($siswa['id_siswa'],"1")[0]['tgl']);

		// HEAD LABEL
		$sheet->setCellValue("A13","BIDANG DAN FREKUENSI MASALAH");
		$sheet->mergeCells("A13:X13");
		$excel->getActiveSheet()->getStyle('A13')->applyFromArray($headerStyle);

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
		$sheet->setCellValue("A14","KODE TOPIK MASALAH");
		$sheet->setCellValue("C14","JENIS MASALAH");
		$sheet->setCellValue("C15","NOMOR MASALAH");
		$sheet->setCellValue("W14","JML");
		$sheet->setCellValue("X14","%");
		// head style
		$sheet->mergeCells("A14:B15");
		$sheet->mergeCells("C14:V14");
		$sheet->mergeCells("C15:V15");
		$sheet->mergeCells("W14:W15");
		$sheet->mergeCells("X14:X15");
		$excel->getActiveSheet()->getStyle('A14:B15')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('C14:V14')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('C15:V15')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('W14:W15')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('X14:X15')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('A14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('A14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle('C14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('C15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('W14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('W14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle('X14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle('X14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		// data
		$colsjawaban = ["C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V"];
			// pribadi
			$sheet->setCellValue("A16","I.");
			$sheet->setCellValue("B16","PRIBADI");
			$sheet->mergeCells("B16:V16");
			$excel->getActiveSheet()->getStyle('A16')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B16:V16')->applyFromArray($tableborderStyle);
			$jmlkeseluruhan = 0;

			$i = 1;
			$jmlpribadi = 0;
			$begin = 17;
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
			$sheet->setCellValue("W16",$jmlpribadi);
			$sheet->setCellValue("X16",ceil($jmlpribadi / 100 * 100) . "%");
			$excel->getActiveSheet()->getStyle("W16")->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("X16")->applyFromArray($tableborderStyle);
			// sosial
			$sheet->setCellValue("A22","II.");
			$sheet->setCellValue("B22","SOSIAL");
			$sheet->mergeCells("B22:V22");
			$excel->getActiveSheet()->getStyle('A22')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B22:V22')->applyFromArray($tableborderStyle);
			$i = 1;
			$jmlsosial = 0;
			$begin = 23;
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
			$sheet->setCellValue("W22",$jmlsosial);
			$sheet->setCellValue("X22",ceil($jmlsosial / 100 * 100) . "%");
			$excel->getActiveSheet()->getStyle("W22")->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("X22")->applyFromArray($tableborderStyle);
			// belajar
			$sheet->setCellValue("A26","III.");
			$sheet->setCellValue("B26","BELAJAR");
			$sheet->mergeCells("B26:V26");
			$excel->getActiveSheet()->getStyle('A26')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B26:V26')->applyFromArray($tableborderStyle);
			$i = 1;
			$jmlbelajar = 0;
			$begin = 27;
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
			$sheet->setCellValue("W26",$jmlbelajar);
			$sheet->setCellValue("X26",ceil($jmlbelajar / 100 * 100) . "%");
			$excel->getActiveSheet()->getStyle("W26")->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("X26")->applyFromArray($tableborderStyle);
			// karir
			$sheet->setCellValue("A30","IV.");
			$sheet->setCellValue("B30","KARIR");
			$sheet->mergeCells("B30:V30");
			$excel->getActiveSheet()->getStyle('A30')->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B30:V30')->applyFromArray($tableborderStyle);
			$i = 1;
			$jmlkarir = 0;
			$begin = 31;
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
			$sheet->setCellValue("W30",$jmlkarir);
			$sheet->setCellValue("X30",ceil($jmlkarir / 100 * 100) . "%");
			$excel->getActiveSheet()->getStyle("W30")->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("X30")->applyFromArray($tableborderStyle);
			// essay
			$i = 0;
			$begin = 33;
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
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(10.57);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(39);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('J')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('K')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('L')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('M')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('N')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('O')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('P')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('Q')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('R')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('S')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('T')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('U')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('V')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('W')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('X')->setWidth(5);

		
		$excel->getActiveSheet()->setTitle("Profil Individu");

	    $sheet->setCellValue("B41","Mengetahui,");
	    $sheet->setCellValue("B42","Kepala Sekolah");
		$sheet->setCellValue("B48",$this->Clsglobal->site_info("kepala_sekolah"));
		$sheet->setCellValue("M41","Mengetahui,");
	    $sheet->setCellValue("M42","Guru Pembimbing");
		$sheet->setCellValue("M48",$this->Clsglobal->site_info("guru_pembimbing"));
		$excel->getActiveSheet()->getStyle('B41:W48')->applyFromArray(
		    array(
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFFFF')
		        )
		    )
		);

		$sheet->setCellValue("A80", "GRAFIK PROFIL INDIVIDUAL");
		$sheet->setCellValue("A79", strtoupper($siswa['nama_siswa']));
		$excel->getActiveSheet()->getStyle('A80')->applyFromArray($headerStyle);
		$excel->getActiveSheet()->getStyle('A79')->applyFromArray($headerStyle);
		$sheet->mergeCells("A80:X80");
		$sheet->mergeCells("A79:X79");

		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Chart 1');
		$objDrawing->setDescription('Chart 1');
		$objDrawing->setPath("./assets/chart_img/" . $chart1 . ".jpg");
		$objDrawing->setCoordinates('B82');   
		$objDrawing->setOffsetX(50); 
		$objDrawing->setOffsetY(0);    
		$objDrawing->setWidth(450); 
		$objDrawing->setHeight(450); 
		$objDrawing->setWorksheet($excel->getActiveSheet());

		$objDrawing2 = new PHPExcel_Worksheet_Drawing();
		$objDrawing2->setName('Chart 2');
		$objDrawing2->setDescription('Chart 2');
		$objDrawing2->setPath("./assets/chart_img/" . $chart2 . ".jpg");
		$objDrawing2->setCoordinates('B106');   
		$objDrawing2->setOffsetX(50); 
		$objDrawing2->setOffsetY(0);    
		$objDrawing2->setWidth(450); 
		$objDrawing2->setHeight(450); 
		$objDrawing2->setWorksheet($excel->getActiveSheet());

		$excel->getActiveSheet()->getStyle('B82:W110')->applyFromArray(
		    array(
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFFFF')
		        )
		    )
		);
		$excel->getActiveSheet()->getStyle('B106:W128')->applyFromArray(
		    array(
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFFFF')
		        )
		    )
		);

		// TOPIK MASALAH SOAL
		$sheet->setCellValue("A138", "TOPIK MASALAH");
		// HEADER STYLE
		$headerStyle = [
			'font' => ['bold' => true, 'size' => '16'],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			],

		];
		$excel->getActiveSheet()->getStyle('A138')->applyFromArray($headerStyle);
		$sheet->mergeCells("A138:X138");

		$sheet->setCellValue("A140", "Nomor Soal");
		$sheet->setCellValue("B140", "Soal");
		$sheet->mergeCells("B140:X140");
		$excel->getActiveSheet()->getStyle('A140')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('B140:X140')->applyFromArray($tableborderStyle);
		$begin = 141;
		foreach ($answered as $soal) {
			$get = $this->soal->get_soal($soal['no_soal']);
			$sheet->setCellValue("A" . $begin, $get['no_soal']);
			$sheet->setCellValue("B" . $begin, $get['soal']);
			$excel->getActiveSheet()->getStyle('A' . $begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('B' . $begin . ":X" . $begin)->applyFromArray($tableborderStyle);
			$sheet->mergeCells('B' . $begin . ":X" . $begin);
			$begin++;
		}


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