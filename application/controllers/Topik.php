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

	public function print_paralel($chart1 = null, $chart2 = null, $sortir = null)
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
			$sheet->mergeCells("B".$begin.":E".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":E".$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
			$coordinateHeader = $begin;

			$begin++;
			$persenpribadi = 0;
			$derajatpribadi;
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
				$persenpribadi += $persen;
			}
			if ( $persenpribadi >= 0 && $persenpribadi < 1 ) {
				$derajatpribadi = "A";
			} elseif ( $persenpribadi >= 1 && $persenpribadi < 11 ) {
				$derajatpribadi = "B";
			} elseif ( $persenpribadi >= 11 && $persenpribadi < 26 ) {
				$derajatpribadi = "C";
			} elseif ( $persenpribadi >= 26 && $persenpribadi < 51 ) {
				$derajatpribadi = "D";
			} else {
				$derajatpribadi = "E";
			}
			$sheet->setCellValue("F" . $coordinateHeader,$persenpribadi . "%");
			$sheet->setCellValue("G" . $coordinateHeader,$derajatpribadi);
			$excel->getActiveSheet()->getStyle('F' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('G' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			// SOSIAL
			$sheet->setCellValue("A" . $begin,"II.");
			$sheet->setCellValue("B" . $begin,"SOSIAL");
			$sheet->mergeCells("B".$begin.":E".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":E".$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
			$coordinateHeader = $begin;

			$begin++;
			$persensosial = 0;
			$derajatsosial;
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
				$persensosial += $persen;
			}
			if ( $persensosial >= 0 && $persensosial < 1 ) {
				$derajatsosial = "A";
			} elseif ( $persensosial >= 1 && $persensosial < 11 ) {
				$derajatsosial = "B";
			} elseif ( $persensosial >= 11 && $persensosial < 26 ) {
				$derajatsosial = "C";
			} elseif ( $persensosial >= 26 && $persensosial < 51 ) {
				$derajatsosial = "D";
			} else {
				$derajatsosial = "E";
			}
			$sheet->setCellValue("F" . $coordinateHeader,$persensosial . "%");
			$sheet->setCellValue("G" . $coordinateHeader,$derajatsosial);
			$excel->getActiveSheet()->getStyle('F' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('G' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			// BELAJAR
			$sheet->setCellValue("A" . $begin,"III.");
			$sheet->setCellValue("B" . $begin,"BELAJAR");
			$sheet->mergeCells("B".$begin.":E".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":E".$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
			$coordinateHeader = $begin;

			$begin++;
			$persenbelajar = 0;
			$derajatbelajar;
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
				$persenbelajar += $persen;
			}
			if ( $persenbelajar >= 0 && $persenbelajar < 1 ) {
				$derajatbelajar = "A";
			} elseif ( $persenbelajar >= 1 && $persenbelajar < 11 ) {
				$derajatbelajar = "B";
			} elseif ( $persenbelajar >= 11 && $persenbelajar < 26 ) {
				$derajatbelajar = "C";
			} elseif ( $persenbelajar >= 26 && $persenbelajar < 51 ) {
				$derajatbelajar = "D";
			} else {
				$derajatbelajar = "E";
			}
			$sheet->setCellValue("F" . $coordinateHeader,$persenbelajar . "%");
			$sheet->setCellValue("G" . $coordinateHeader,$derajatbelajar);
			$excel->getActiveSheet()->getStyle('F' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('G' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			// KARIR
			$sheet->setCellValue("A" . $begin,"IV.");
			$sheet->setCellValue("B" . $begin,"KARIR");
			$sheet->mergeCells("B".$begin.":E".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":E".$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
			$coordinateHeader = $begin;

			$begin++;
			$persenkarir = 0;
			$derajatkarir;
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
				$persenkarir += $persen;
			}
			if ( $persenkarir >= 0 && $persenkarir < 1 ) {
				$derajatkarir = "A";
			} elseif ( $persenkarir >= 1 && $persenkarir < 11 ) {
				$derajatkarir = "B";
			} elseif ( $persenkarir >= 11 && $persenkarir < 26 ) {
				$derajatkarir = "C";
			} elseif ( $persenkarir >= 26 && $persenkarir < 51 ) {
				$derajatkarir = "D";
			} else {
				$derajatkarir = "E";
			}
			$sheet->setCellValue("F" . $coordinateHeader,$persenkarir . "%");
			$sheet->setCellValue("G" . $coordinateHeader,$derajatkarir);
			$excel->getActiveSheet()->getStyle('F' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('G' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


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


		// CHART
		$sheet->setCellValue("A55", "GRAFIK ANALISIS TOPIK MASALAH");
		$sheet->setCellValue("A56", "KELAS PARALEL");
		// HEADER STYLE
		$headerStyle = [
			'font' => ['bold' => true, 'size' => '16'],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			],

		];
		$excel->getActiveSheet()->getStyle('A55')->applyFromArray($headerStyle);
		$excel->getActiveSheet()->getStyle('A56')->applyFromArray($headerStyle);
		$sheet->mergeCells("A55:G55");
		$sheet->mergeCells("A56:G56");

		$begin = 57;
		$begin++;
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Chart');
		$objDrawing->setDescription('Chart');
		$objDrawing->setPath("./assets/chart_img/" . $chart1 . ".jpg");
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

		$begin += 21;
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Chart');
		$objDrawing->setDescription('Chart');
		$objDrawing->setPath("./assets/chart_img/" . $chart2 . ".jpg");
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

	public function print_kelas($chart1 = null, $chart2 = null, $id_kelas = null, $sortir = null)
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
			$sheet->mergeCells("B".$begin.":E".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":E".$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
			$coordinateHeader = $begin;

			$begin++;
			$persenpribadi = 0;
			$derajatpribadi;
			$iteration = 1;
			foreach ($pribadi_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
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
				$persenpribadi += $persen;
			}
			if ( $persenpribadi >= 0 && $persenpribadi < 1 ) {
				$derajatpribadi = "A";
			} elseif ( $persenpribadi >= 1 && $persenpribadi < 11 ) {
				$derajatpribadi = "B";
			} elseif ( $persenpribadi >= 11 && $persenpribadi < 26 ) {
				$derajatpribadi = "C";
			} elseif ( $persenpribadi >= 26 && $persenpribadi < 51 ) {
				$derajatpribadi = "D";
			} else {
				$derajatpribadi = "E";
			}
			$sheet->setCellValue("F" . $coordinateHeader,$persenpribadi . "%");
			$sheet->setCellValue("G" . $coordinateHeader,$derajatpribadi);
			$excel->getActiveSheet()->getStyle('F' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('G' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			// SOSIAL
			$sheet->setCellValue("A" . $begin,"II.");
			$sheet->setCellValue("B" . $begin,"SOSIAL");
			$sheet->mergeCells("B".$begin.":E".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":E".$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
			$coordinateHeader = $begin;

			$begin++;
			$persensosial = 0;
			$derajatsosial;
			$iteration = 1;
			foreach ($sosial_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
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
				$persensosial += $persen;
			}
			if ( $persensosial >= 0 && $persensosial < 1 ) {
				$derajatsosial = "A";
			} elseif ( $persensosial >= 1 && $persensosial < 11 ) {
				$derajatsosial = "B";
			} elseif ( $persensosial >= 11 && $persensosial < 26 ) {
				$derajatsosial = "C";
			} elseif ( $persensosial >= 26 && $persensosial < 51 ) {
				$derajatsosial = "D";
			} else {
				$derajatsosial = "E";
			}
			$sheet->setCellValue("F" . $coordinateHeader,$persensosial . "%");
			$sheet->setCellValue("G" . $coordinateHeader,$derajatsosial);
			$excel->getActiveSheet()->getStyle('F' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('G' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			// BELAJAR
			$sheet->setCellValue("A" . $begin,"III.");
			$sheet->setCellValue("B" . $begin,"BELAJAR");
			$sheet->mergeCells("B".$begin.":E".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":E".$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
			$coordinateHeader = $begin;

			$begin++;
			$persenbelajar = 0;
			$derajatbelajar;
			$iteration = 1;
			foreach ($belajar_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
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
				$persenbelajar += $persen;
			}
			if ( $persenbelajar >= 0 && $persenbelajar < 1 ) {
				$derajatbelajar = "A";
			} elseif ( $persenbelajar >= 1 && $persenbelajar < 11 ) {
				$derajatbelajar = "B";
			} elseif ( $persenbelajar >= 11 && $persenbelajar < 26 ) {
				$derajatbelajar = "C";
			} elseif ( $persenbelajar >= 26 && $persenbelajar < 51 ) {
				$derajatbelajar = "D";
			} else {
				$derajatbelajar = "E";
			}
			$sheet->setCellValue("F" . $coordinateHeader,$persenbelajar . "%");
			$sheet->setCellValue("G" . $coordinateHeader,$derajatbelajar);
			$excel->getActiveSheet()->getStyle('F' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('G' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


			// KARIR
			$sheet->setCellValue("A" . $begin,"IV.");
			$sheet->setCellValue("B" . $begin,"KARIR");
			$sheet->mergeCells("B".$begin.":E".$begin);
			$excel->getActiveSheet()->getStyle('A'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle("B".$begin.":E".$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('F'.$begin)->applyFromArray($tableborderStyle);
			$excel->getActiveSheet()->getStyle('G'.$begin)->applyFromArray($tableborderStyle);
			$coordinateHeader = $begin;

			$begin++;
			$persenkarir = 0;
			$derajatkarir;
			$iteration = 1;
			foreach ($karir_kategori as $kategori) {
				$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
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
				$persenkarir += $persen;
			}
			if ( $persenkarir >= 0 && $persenkarir < 1 ) {
				$derajatkarir = "A";
			} elseif ( $persenkarir >= 1 && $persenkarir < 11 ) {
				$derajatkarir = "B";
			} elseif ( $persenkarir >= 11 && $persenkarir < 26 ) {
				$derajatkarir = "C";
			} elseif ( $persenkarir >= 26 && $persenkarir < 51 ) {
				$derajatkarir = "D";
			} else {
				$derajatkarir = "E";
			}
			$sheet->setCellValue("F" . $coordinateHeader,$persenkarir . "%");
			$sheet->setCellValue("G" . $coordinateHeader,$derajatkarir);
			$excel->getActiveSheet()->getStyle('F' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->getActiveSheet()->getStyle('G' . $coordinateHeader)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


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


		// CHART
		$sheet->setCellValue("A55", "GRAFIK ANALISIS TOPIK MASALAH");
		$sheet->setCellValue("A56", "KELAS PARALEL");
		// HEADER STYLE
		$headerStyle = [
			'font' => ['bold' => true, 'size' => '16'],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			],

		];
		$excel->getActiveSheet()->getStyle('A55')->applyFromArray($headerStyle);
		$excel->getActiveSheet()->getStyle('A56')->applyFromArray($headerStyle);
		$sheet->mergeCells("A55:G55");
		$sheet->mergeCells("A56:G56");

		$begin = 57;
		$begin++;
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Chart');
		$objDrawing->setDescription('Chart');
		$objDrawing->setPath("./assets/chart_img/" . $chart1 . ".jpg");
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

		$begin += 21;
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Chart');
		$objDrawing->setDescription('Chart');
		$objDrawing->setPath("./assets/chart_img/" . $chart2 . ".jpg");
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