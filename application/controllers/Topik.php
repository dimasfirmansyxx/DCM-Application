<?php 

class Topik extends CI_Controller {
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
		$this->load->model("Topik_model","topik");
		$this->load->model("Profil_individu_model","profil");
	}

	public function index()
	{
		redirect( base_url() . "beranda" );
	}

	public function set_chart_session()
	{
		$chart = $_POST['chart'];

		$_SESSION["chart_profil"] = $chart;
	}

	public function upload_chart()
	{
		$chart = $_SESSION["chart_profil"];

		$chartimg = $chart;
		$chartimg = str_replace('data:image/png;base64,', '', $chartimg);
		$chartimg = str_replace(' ', '+', $chartimg);
		$chartimg = base64_decode($chartimg);
		$chartimg_name = uniqid();
		$chartimg_path = './assets/chart_img/'.$chartimg_name.'.jpg';
		file_put_contents($chartimg_path, $chartimg); 

		$encrypt = base64_encode($chartimg_path);
		$decrypt = base64_decode($encrypt);

		echo json_encode(["chart" => $chartimg_name]);
	}

	public function paralel($param = null, $sortir = null)
	{
		if ( $param == "show" ) {
			$data['pagetitle'] = "show_analisis_topik_paralel";
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['pribadi_kategori'] = $this->profil->get_kategori(1,5);
			$data['sosial_kategori'] = $this->profil->get_kategori(6,8);
			$data['belajar_kategori'] = $this->profil->get_kategori(9,11);
			$data['karir_kategori'] = $this->profil->get_kategori(12,12);
			$data['sortir'] = $sortir;
			$this->load->view("templates/head",$data);
			$this->load->view("topik/paralel_show",$data);
		} else {
			$data['pagetitle'] = "Analisis Topik";
			$this->load->view("templates/head",$data);
			$this->load->view("templates/header");
			$this->load->view("templates/navbar");
			$this->load->view("topik/paralel");
			$this->load->view("templates/footer");
		}
	}

	public function print_paralel($chart = null, $sortir = null)
	{
		$get_kategori = $this->kategori->get_all_kategori();
		$pribadi_kategori = $this->profil->get_kategori(1,5);
		$sosial_kategori = $this->profil->get_kategori(6,8);
		$belajar_kategori = $this->profil->get_kategori(9,11);
		$karir_kategori = $this->profil->get_kategori(12,12);
		$sortir = $sortir;
		$namafile = "Laporan Analisis Topik Paralel";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$kelas = $this->kelas->get_all_kelas();
		$excel = new PHPExcel;
 
		$excel->getProperties()->setCreator("Dimas Firmansyah");
		$excel->getProperties()->setLastModifiedBy("Dimas Firmansyah");
		$excel->getProperties()->setTitle("Topik Paralel");
		$excel->removeSheetByIndex(0);
		
		// HEADER
		$sheet = $excel->createSheet();
		$sheet->setTitle("Profil Individu");
		$sheet->setCellValue("A1", "HASIL ANALISIS TOPIK MASALAH");
		$sheet->setCellValue("A2", "KELAS PARALEL");
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
		$sheet->mergeCells("A1:G1");
		$sheet->mergeCells("A2:G2");

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
		$sheet->setCellValue("A7","No.");
		$sheet->setCellValue("B7","Topik");
		$sheet->setCellValue("C7","Nm");
		$sheet->setCellValue("D7","N");
		$sheet->setCellValue("E7","N x M");
		$sheet->setCellValue("F7","(Nm : N x M) x 100%");
		$sheet->setCellValue("G7","Derajat");
		// head style
		$excel->getActiveSheet()->getStyle('A7')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('B7')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('C7')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('D7')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('E7')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('F7')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('G7')->applyFromArray($tableborderStyle);
		// data
		$begin = 8;
			// PRIBADI
			$sheet->setCellValue("A" . $begin,"I.");
			$sheet->setCellValue("B" . $begin,"PRIBADI");
			$sheet->mergeCells("B".$begin.":G".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":G".$begin)->applyFromArray($tableborderStyle);
			$begin++;
			$iteration = 1;
			foreach ($pribadi_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
				$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
				$jmlsiswa = $this->tabulasi->get_jml_siswa();

				$n_m = $jmlsoal * $jmlsiswa;
				$persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100);

				if ( $persen >= 0 && $persen < 1 ) {
					$derajat = "A";
				} elseif ( $persen >= 1 && $persen < 11 ) {
					$derajat = "B";
				} elseif ( $persen >= 11 && $persen < 26 ) {
					$derajat = "C";
				} elseif ( $persen >= 26 && $persen < 51 ) {
					$derajat = "D";
				} else {
					$derajat = "E";
				}

				if ( !($sortir == null) ) {
					if ( $sortir == $derajat ) {
						$sheet->setCellValue("A" . $begin,$iteration++);
						$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
						$sheet->setCellValue("C" . $begin,$jml);
						$sheet->setCellValue("D" . $begin,$jmlsoal);
						$sheet->setCellValue("E" . $begin,$n_m);
						$sheet->setCellValue("F" . $begin,$persen . "%");
						$sheet->setCellValue("G" . $begin,$derajat);
						$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
				} else {
					$sheet->setCellValue("A" . $begin,$iteration++);
					$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
					$sheet->setCellValue("C" . $begin,$jml);
					$sheet->setCellValue("D" . $begin,$jmlsoal);
					$sheet->setCellValue("E" . $begin,$n_m);
					$sheet->setCellValue("F" . $begin,$persen . "%");
					$sheet->setCellValue("G" . $begin,$derajat);
					$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$begin++;
			}
			// SOSIAL
			$sheet->setCellValue("A" . $begin,"II.");
			$sheet->setCellValue("B" . $begin,"SOSIAL");
			$sheet->mergeCells("B".$begin.":G".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":G".$begin)->applyFromArray($tableborderStyle);
			$begin++;
			$iteration = 1;
			foreach ($sosial_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
				$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
				$jmlsiswa = $this->tabulasi->get_jml_siswa();

				$n_m = $jmlsoal * $jmlsiswa;
				$persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100);

				if ( $persen >= 0 && $persen < 1 ) {
					$derajat = "A";
				} elseif ( $persen >= 1 && $persen < 11 ) {
					$derajat = "B";
				} elseif ( $persen >= 11 && $persen < 26 ) {
					$derajat = "C";
				} elseif ( $persen >= 26 && $persen < 51 ) {
					$derajat = "D";
				} else {
					$derajat = "E";
				}

				if ( !($sortir == null) ) {
					if ( $sortir == $derajat ) {
						$sheet->setCellValue("A" . $begin,$iteration++);
						$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
						$sheet->setCellValue("C" . $begin,$jml);
						$sheet->setCellValue("D" . $begin,$jmlsoal);
						$sheet->setCellValue("E" . $begin,$n_m);
						$sheet->setCellValue("F" . $begin,$persen . "%");
						$sheet->setCellValue("G" . $begin,$derajat);
						$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
				} else {
					$sheet->setCellValue("A" . $begin,$iteration++);
					$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
					$sheet->setCellValue("C" . $begin,$jml);
					$sheet->setCellValue("D" . $begin,$jmlsoal);
					$sheet->setCellValue("E" . $begin,$n_m);
					$sheet->setCellValue("F" . $begin,$persen . "%");
					$sheet->setCellValue("G" . $begin,$derajat);
					$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$begin++;
			}
			// BELAJAR
			$sheet->setCellValue("A" . $begin,"III.");
			$sheet->setCellValue("B" . $begin,"BELAJAR");
			$sheet->mergeCells("B".$begin.":G".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":G".$begin)->applyFromArray($tableborderStyle);
			$begin++;
			$iteration = 1;
			foreach ($belajar_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
				$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
				$jmlsiswa = $this->tabulasi->get_jml_siswa();

				$n_m = $jmlsoal * $jmlsiswa;
				$persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100);

				if ( $persen >= 0 && $persen < 1 ) {
					$derajat = "A";
				} elseif ( $persen >= 1 && $persen < 11 ) {
					$derajat = "B";
				} elseif ( $persen >= 11 && $persen < 26 ) {
					$derajat = "C";
				} elseif ( $persen >= 26 && $persen < 51 ) {
					$derajat = "D";
				} else {
					$derajat = "E";
				}

				if ( !($sortir == null) ) {
					if ( $sortir == $derajat ) {
						$sheet->setCellValue("A" . $begin,$iteration++);
						$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
						$sheet->setCellValue("C" . $begin,$jml);
						$sheet->setCellValue("D" . $begin,$jmlsoal);
						$sheet->setCellValue("E" . $begin,$n_m);
						$sheet->setCellValue("F" . $begin,$persen . "%");
						$sheet->setCellValue("G" . $begin,$derajat);
						$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
				} else {
					$sheet->setCellValue("A" . $begin,$iteration++);
					$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
					$sheet->setCellValue("C" . $begin,$jml);
					$sheet->setCellValue("D" . $begin,$jmlsoal);
					$sheet->setCellValue("E" . $begin,$n_m);
					$sheet->setCellValue("F" . $begin,$persen . "%");
					$sheet->setCellValue("G" . $begin,$derajat);
					$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$begin++;
			}
			// KARIR
			$sheet->setCellValue("A" . $begin,"IV.");
			$sheet->setCellValue("B" . $begin,"KARIR");
			$sheet->mergeCells("B".$begin.":G".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":G".$begin)->applyFromArray($tableborderStyle);
			$begin++;
			$iteration = 1;
			foreach ($karir_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
				$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
				$jmlsiswa = $this->tabulasi->get_jml_siswa();

				$n_m = $jmlsoal * $jmlsiswa;
				$persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100);

				if ( $persen >= 0 && $persen < 1 ) {
					$derajat = "A";
				} elseif ( $persen >= 1 && $persen < 11 ) {
					$derajat = "B";
				} elseif ( $persen >= 11 && $persen < 26 ) {
					$derajat = "C";
				} elseif ( $persen >= 26 && $persen < 51 ) {
					$derajat = "D";
				} else {
					$derajat = "E";
				}

				if ( !($sortir == null) ) {
					if ( $sortir == $derajat ) {
						$sheet->setCellValue("A" . $begin,$iteration++);
						$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
						$sheet->setCellValue("C" . $begin,$jml);
						$sheet->setCellValue("D" . $begin,$jmlsoal);
						$sheet->setCellValue("E" . $begin,$n_m);
						$sheet->setCellValue("F" . $begin,$persen . "%");
						$sheet->setCellValue("G" . $begin,$derajat);
						$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
				} else {
					$sheet->setCellValue("A" . $begin,$iteration++);
					$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
					$sheet->setCellValue("C" . $begin,$jml);
					$sheet->setCellValue("D" . $begin,$jmlsoal);
					$sheet->setCellValue("E" . $begin,$n_m);
					$sheet->setCellValue("F" . $begin,$persen . "%");
					$sheet->setCellValue("G" . $begin,$derajat);
					$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$begin++;
			}


		// CHART
		$begin++;
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Chart');
		$objDrawing->setDescription('Chart');
		$objDrawing->setPath("./assets/chart_img/" . $chart . ".jpg");
		$objDrawing->setCoordinates('A' . $begin);   
		$objDrawing->setOffsetX(0); 
		$objDrawing->setOffsetY(0);    
		$objDrawing->setWidth(400); 
		$objDrawing->setHeight(400); 
		$objDrawing->setWorksheet($excel->getActiveSheet());
		$excel->getActiveSheet()->getStyle("A" . $begin .":G" . ($begin + 20))->applyFromArray(
		    array(
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFFFF')
		        )
		    )
		);
		$begin = $begin + 20;

		// SET WIDTH OF COLUMN
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(38);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(6);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(7);

		
		$excel->getActiveSheet()->setTitle("Analisis Topik Paralel");

	    $headsignature = $begin + 2;
		$subheadsignature = $headsignature + 1;
		$namesignature = $subheadsignature + 6;
	    $sheet->setCellValue("B" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("B" . $subheadsignature,"Kepala Sekolah");
		$sheet->setCellValue("B" . $namesignature,$this->Clsglobal->site_info("kepala_sekolah"));
		$sheet->setCellValue("F" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("F" . $subheadsignature,"Guru Pembimbing");
		$sheet->setCellValue("F" . $namesignature,$this->Clsglobal->site_info("guru_pembimbing"));

		$sheet->getPageSetup()->setFitToWidth(1);    
	    $sheet->getPageSetup()->setFitToHeight(0);

	    $excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$namafile.'.xlsx"');
		header('Cache-Control: max-age=1');
		 
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->setIncludeCharts(TRUE);
		$objWriter->save('php://output');
		unlink("./assets/chart_img/" . $chart . ".jpg");
	}

	public function kelas($param = null, $id_kelas = null, $sortir = null)
	{
		if ( $param == "show" ) {
			$data['pagetitle'] = "show_analisis_topik_paralel";
			$data['id_kelas'] = $id_kelas;
			$data['get_kategori'] = $this->kategori->get_all_kategori();
			$data['pribadi_kategori'] = $this->profil->get_kategori(1,5);
			$data['sosial_kategori'] = $this->profil->get_kategori(6,8);
			$data['belajar_kategori'] = $this->profil->get_kategori(9,11);
			$data['karir_kategori'] = $this->profil->get_kategori(12,12);
			$data['sortir'] = $sortir;
			$this->load->view("templates/head",$data);
			$this->load->view("topik/kelas_show",$data);
		} else {
			$data['pagetitle'] = "Analisis Topik";
			$data['all_kelas'] = $this->kelas->get_all_kelas();
			$this->load->view("templates/head",$data);
			$this->load->view("templates/header");
			$this->load->view("templates/navbar");
			$this->load->view("topik/kelas");
			$this->load->view("templates/footer");
		}
	}

	public function print_kelas($chart = null, $id_kelas = null, $sortir = null)
	{
		$get_kategori = $this->kategori->get_all_kategori();
		$pribadi_kategori = $this->profil->get_kategori(1,5);
		$sosial_kategori = $this->profil->get_kategori(6,8);
		$belajar_kategori = $this->profil->get_kategori(9,11);
		$karir_kategori = $this->profil->get_kategori(12,12);
		$sortir = $sortir;
		$namafile = "Laporan Analisis Topik Perkelas";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$kelas = $this->kelas->get_all_kelas();
		$excel = new PHPExcel;
 
		$excel->getProperties()->setCreator("Dimas Firmansyah");
		$excel->getProperties()->setLastModifiedBy("Dimas Firmansyah");
		$excel->getProperties()->setTitle("Topik Paralel");
		$excel->removeSheetByIndex(0);
		
		// HEADER
		$sheet = $excel->createSheet();
		$sheet->setTitle("Profil Individu");
		$sheet->setCellValue("A1", "HASIL ANALISIS TOPIK MASALAH");
		$sheet->setCellValue("A2", "PERKELAS");
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
		$sheet->mergeCells("A1:G1");
		$sheet->mergeCells("A2:G2");

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
		$sheet->setCellValue("B8","Topik");
		$sheet->setCellValue("C8","Nm");
		$sheet->setCellValue("D8","N");
		$sheet->setCellValue("E8","N x M");
		$sheet->setCellValue("F8","(Nm : N x M) x 100%");
		$sheet->setCellValue("G8","Derajat");
		// head style
		$excel->getActiveSheet()->getStyle('A8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('B8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('C8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('D8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('E8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('F8')->applyFromArray($tableborderStyle);
		$excel->getActiveSheet()->getStyle('G8')->applyFromArray($tableborderStyle);
		// data
		$begin = 9;
			// PRIBADI
			$sheet->setCellValue("A" . $begin,"I.");
			$sheet->setCellValue("B" . $begin,"PRIBADI");
			$sheet->mergeCells("B".$begin.":G".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":G".$begin)->applyFromArray($tableborderStyle);
			$begin++;
			$iteration = 1;
			foreach ($pribadi_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
				$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
				$jmlsiswa = $this->tabulasi->get_jml_siswa($id_kelas);

				$n_m = $jmlsoal * $jmlsiswa;
				$persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100);

				if ( $persen >= 0 && $persen < 1 ) {
					$derajat = "A";
				} elseif ( $persen >= 1 && $persen < 11 ) {
					$derajat = "B";
				} elseif ( $persen >= 11 && $persen < 26 ) {
					$derajat = "C";
				} elseif ( $persen >= 26 && $persen < 51 ) {
					$derajat = "D";
				} else {
					$derajat = "E";
				}

				if ( !($sortir == null) ) {
					if ( $sortir == $derajat ) {
						$sheet->setCellValue("A" . $begin,$iteration++);
						$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
						$sheet->setCellValue("C" . $begin,$jml);
						$sheet->setCellValue("D" . $begin,$jmlsoal);
						$sheet->setCellValue("E" . $begin,$n_m);
						$sheet->setCellValue("F" . $begin,$persen . "%");
						$sheet->setCellValue("G" . $begin,$derajat);
						$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
				} else {
					$sheet->setCellValue("A" . $begin,$iteration++);
					$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
					$sheet->setCellValue("C" . $begin,$jml);
					$sheet->setCellValue("D" . $begin,$jmlsoal);
					$sheet->setCellValue("E" . $begin,$n_m);
					$sheet->setCellValue("F" . $begin,$persen . "%");
					$sheet->setCellValue("G" . $begin,$derajat);
					$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$begin++;
			}
			// SOSIAL
			$sheet->setCellValue("A" . $begin,"II.");
			$sheet->setCellValue("B" . $begin,"SOSIAL");
			$sheet->mergeCells("B".$begin.":G".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":G".$begin)->applyFromArray($tableborderStyle);
			$begin++;
			$iteration = 1;
			foreach ($sosial_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
				$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
				$jmlsiswa = $this->tabulasi->get_jml_siswa($id_kelas);

				$n_m = $jmlsoal * $jmlsiswa;
				$persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100);

				if ( $persen >= 0 && $persen < 1 ) {
					$derajat = "A";
				} elseif ( $persen >= 1 && $persen < 11 ) {
					$derajat = "B";
				} elseif ( $persen >= 11 && $persen < 26 ) {
					$derajat = "C";
				} elseif ( $persen >= 26 && $persen < 51 ) {
					$derajat = "D";
				} else {
					$derajat = "E";
				}

				if ( !($sortir == null) ) {
					if ( $sortir == $derajat ) {
						$sheet->setCellValue("A" . $begin,$iteration++);
						$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
						$sheet->setCellValue("C" . $begin,$jml);
						$sheet->setCellValue("D" . $begin,$jmlsoal);
						$sheet->setCellValue("E" . $begin,$n_m);
						$sheet->setCellValue("F" . $begin,$persen . "%");
						$sheet->setCellValue("G" . $begin,$derajat);
						$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
				} else {
					$sheet->setCellValue("A" . $begin,$iteration++);
					$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
					$sheet->setCellValue("C" . $begin,$jml);
					$sheet->setCellValue("D" . $begin,$jmlsoal);
					$sheet->setCellValue("E" . $begin,$n_m);
					$sheet->setCellValue("F" . $begin,$persen . "%");
					$sheet->setCellValue("G" . $begin,$derajat);
					$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$begin++;
			}
			// BELAJAR
			$sheet->setCellValue("A" . $begin,"III.");
			$sheet->setCellValue("B" . $begin,"BELAJAR");
			$sheet->mergeCells("B".$begin.":G".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":G".$begin)->applyFromArray($tableborderStyle);
			$begin++;
			$iteration = 1;
			foreach ($belajar_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
				$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
				$jmlsiswa = $this->tabulasi->get_jml_siswa($id_kelas);

				$n_m = $jmlsoal * $jmlsiswa;
				$persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100);

				if ( $persen >= 0 && $persen < 1 ) {
					$derajat = "A";
				} elseif ( $persen >= 1 && $persen < 11 ) {
					$derajat = "B";
				} elseif ( $persen >= 11 && $persen < 26 ) {
					$derajat = "C";
				} elseif ( $persen >= 26 && $persen < 51 ) {
					$derajat = "D";
				} else {
					$derajat = "E";
				}

				if ( !($sortir == null) ) {
					if ( $sortir == $derajat ) {
						$sheet->setCellValue("A" . $begin,$iteration++);
						$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
						$sheet->setCellValue("C" . $begin,$jml);
						$sheet->setCellValue("D" . $begin,$jmlsoal);
						$sheet->setCellValue("E" . $begin,$n_m);
						$sheet->setCellValue("F" . $begin,$persen . "%");
						$sheet->setCellValue("G" . $begin,$derajat);
						$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
				} else {
					$sheet->setCellValue("A" . $begin,$iteration++);
					$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
					$sheet->setCellValue("C" . $begin,$jml);
					$sheet->setCellValue("D" . $begin,$jmlsoal);
					$sheet->setCellValue("E" . $begin,$n_m);
					$sheet->setCellValue("F" . $begin,$persen . "%");
					$sheet->setCellValue("G" . $begin,$derajat);
					$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$begin++;
			}
			// KARIR
			$sheet->setCellValue("A" . $begin,"IV.");
			$sheet->setCellValue("B" . $begin,"KARIR");
			$sheet->mergeCells("B".$begin.":G".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":G".$begin)->applyFromArray($tableborderStyle);
			$begin++;
			$iteration = 1;
			foreach ($karir_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
				$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
				$jmlsiswa = $this->tabulasi->get_jml_siswa($id_kelas);

				$n_m = $jmlsoal * $jmlsiswa;
				$persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100);

				if ( $persen >= 0 && $persen < 1 ) {
					$derajat = "A";
				} elseif ( $persen >= 1 && $persen < 11 ) {
					$derajat = "B";
				} elseif ( $persen >= 11 && $persen < 26 ) {
					$derajat = "C";
				} elseif ( $persen >= 26 && $persen < 51 ) {
					$derajat = "D";
				} else {
					$derajat = "E";
				}

				if ( !($sortir == null) ) {
					if ( $sortir == $derajat ) {
						$sheet->setCellValue("A" . $begin,$iteration++);
						$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
						$sheet->setCellValue("C" . $begin,$jml);
						$sheet->setCellValue("D" . $begin,$jmlsoal);
						$sheet->setCellValue("E" . $begin,$n_m);
						$sheet->setCellValue("F" . $begin,$persen . "%");
						$sheet->setCellValue("G" . $begin,$derajat);
						$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					}
				} else {
					$sheet->setCellValue("A" . $begin,$iteration++);
					$sheet->setCellValue("B" . $begin,$kategori['nama_kategori']);
					$sheet->setCellValue("C" . $begin,$jml);
					$sheet->setCellValue("D" . $begin,$jmlsoal);
					$sheet->setCellValue("E" . $begin,$n_m);
					$sheet->setCellValue("F" . $begin,$persen . "%");
					$sheet->setCellValue("G" . $begin,$derajat);
					$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('B'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('D'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('E'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('C' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('D' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('E' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('F' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$excel->getActiveSheet()->getStyle('G' . $begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$begin++;
			}


		// CHART
		$begin++;
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Chart');
		$objDrawing->setDescription('Chart');
		$objDrawing->setPath("./assets/chart_img/" . $chart . ".jpg");
		$objDrawing->setCoordinates('A' . $begin);   
		$objDrawing->setOffsetX(0); 
		$objDrawing->setOffsetY(0);    
		$objDrawing->setWidth(400); 
		$objDrawing->setHeight(400); 
		$objDrawing->setWorksheet($excel->getActiveSheet());
		$excel->getActiveSheet()->getStyle("A" . $begin .":G" . ($begin + 20))->applyFromArray(
		    array(
		        'fill' => array(
		            'type' => PHPExcel_Style_Fill::FILL_SOLID,
		            'color' => array('rgb' => 'FFFFFF')
		        )
		    )
		);
		$begin = $begin + 20;

		// SET WIDTH OF COLUMN
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(38);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(6);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(7);

		
		$excel->getActiveSheet()->setTitle("Analisis Topik Perkelas");

	    $headsignature = $begin + 2;
		$subheadsignature = $headsignature + 1;
		$namesignature = $subheadsignature + 6;
	    $sheet->setCellValue("B" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("B" . $subheadsignature,"Kepala Sekolah");
		$sheet->setCellValue("B" . $namesignature,$this->Clsglobal->site_info("kepala_sekolah"));
		$sheet->setCellValue("F" . $headsignature,"Mengetahui,");
	    $sheet->setCellValue("F" . $subheadsignature,"Guru Pembimbing");
		$sheet->setCellValue("F" . $namesignature,$this->Clsglobal->site_info("guru_pembimbing"));

		$sheet->getPageSetup()->setFitToWidth(1);    
	    $sheet->getPageSetup()->setFitToHeight(0);

	    $excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$namafile.'.xlsx"');
		header('Cache-Control: max-age=1');
		 
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->setIncludeCharts(TRUE);
		$objWriter->save('php://output');
		unlink("./assets/chart_img/" . $chart . ".jpg");
	}
}