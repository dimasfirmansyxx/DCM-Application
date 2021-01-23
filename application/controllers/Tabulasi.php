<?php 

class Tabulasi extends CI_Controller {
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
	}

	public function index() 
	{
		$data['pagetitle'] = "Tabulasi Hasil";
		$this->load->view("templates/head",$data);
		$this->load->view("templates/header");
		$this->load->view("templates/navbar");
		$this->load->view("tabulasi/tabulasi");
		$this->load->view("templates/footer");
	}

	public function show()
	{
		$data['pagetitle'] = "show_tabulasi";
		$data['kategori_soal'] = $this->kategori->get_all_kategori();
		$data['all_kelas'] = $this->kelas->get_all_kelas();
		$this->load->view("templates/head",$data);
		$this->load->view("tabulasi/show");
	}

	public function print_laporan()
	{
		$kategori_soal = $this->kategori->get_all_kategori();
		$all_kelas = $this->kelas->get_all_kelas();
		$namafile = "Tabulasi Hasil";

		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		$excel = new PHPExcel;

		$excel->getProperties()->setCreator("Dimas Firmansyah");
		$excel->getProperties()->setLastModifiedBy("Dimas Firmansyah");
		$excel->getProperties()->setTitle("Tabulasi Hasil");
		$excel->removeSheetByIndex(0);
		
		// HEADER
		$sheet = $excel->createSheet();
		$sheet->setTitle("Tabulasi Hasil");
		$sheet->setCellValue("A1", "TABULASI HASIL");
		// HEADER STYLE
		$headerStyle = [
			'font' => ['bold' => true, 'size' => '16'],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
			],

		];
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($headerStyle);
		$sheet->mergeCells("A1:Y1");

		// MASTER DATA
		$sheet->setCellValue("B2","Nama Sekolah");
		$sheet->setCellValue("C2",": " . $this->Clsglobal->site_info("nama_sekolah"));
		$sheet->setCellValue("B3","Alamat");
		$sheet->setCellValue("C3",": " . $this->Clsglobal->site_info("alamat"));
		$sheet->setCellValue("B4","Nama Guru Pembimbing");
		$sheet->setCellValue("C4",": " . $this->Clsglobal->site_info("guru_pembimbing"));
		$sheet->setCellValue("B5","Nama Kepala Sekolah");
		$sheet->setCellValue("C5",": " . $this->Clsglobal->site_info("kepala_sekolah"));

		// DATA TABLE
		$tableborderStyle = [
        	'borders' => [
        		'top' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
        		'right' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
        		'bottom' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
        		'left' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
        	],
        ];

		$begin = 7;
        foreach ($kategori_soal as $kategori) {
        	if ( !($kategori['id_kategori'] == 13) ) {

        		$headLabel = $this->Clsglobal->romawi($kategori['id_kategori']) . ". " . $kategori['nama_kategori'];
				// head tabble
				$sheet->setCellValue("A" . $begin,"No.");
				$sheet->setCellValue("B" . $begin,"Nama");
				$sheet->setCellValue("C" . $begin,"Jenis Kelamin");
				$sheet->setCellValue("D" . $begin,"Kelas");
				$sheet->setCellValue("E" . $begin,$headLabel);

				// head style
				$excel->getActiveSheet()->getStyle('A' . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle('B' . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle('C' . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle('D' . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle('E' . $begin . ":Y" . $begin)->applyFromArray($tableborderStyle);
				$sheet->mergeCells("E".$begin.":Y".$begin);
				$excel->getActiveSheet()->getStyle('E'.$begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$begin++;

				// data
				$colsjawaban = ["E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y"];

				foreach ($all_kelas as $kelas) {
					$all_siswa = $this->tabulasi->get_siswa($kelas['id_kelas']);
			        $get_num_siswa = $this->tabulasi->get_num_siswa($kelas['id_kelas']);
			        $jmltopik = 0;

			        foreach ($all_siswa as $siswa) {
			        	$get_jawaban = $this->tabulasi->get_jawaban($siswa['id_siswa']);
						$iteration = 1;
						$terjawab = 0;
						unset($sisa_kosong);

						$sheet->setCellValue("A" . $begin,$siswa['no_urut']);
						$sheet->setCellValue("B" . $begin,$siswa['nama_siswa']);
						$sheet->setCellValue("C" . $begin,ucwords($siswa['jenis_kelamin']));
						$sheet->setCellValue("D" . $begin,$this->kelas->get_kelas($siswa['id_kelas'])['kelas']);
						$excel->getActiveSheet()->getStyle('A' . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('B' . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('C' . $begin)->applyFromArray($tableborderStyle);
						$excel->getActiveSheet()->getStyle('D' . $begin)->applyFromArray($tableborderStyle);
						if ( count($get_jawaban) > 0 ) {
							$iteratekolom = 0;
			                foreach ($get_jawaban as $jawaban) {
								$get_soal = $this->soal->get_soal($jawaban['no_soal']);
				                $get_num_soal = $this->tabulasi->num_soal($get_soal['id_kategori']);
				                if ( !isset($sisa_kosong) ) {
				                  $sisa_kosong = $get_num_soal;
				                }

				                if ( $get_soal['id_kategori'] == $kategori['id_kategori'] ) {
				                	if ( $jawaban['remarks'] == "y" ) {
				                		$sheet->setCellValue($colsjawaban[$iteratekolom] . $begin,$jawaban['no_soal']);
										$excel->getActiveSheet()->getStyle($colsjawaban[$iteratekolom] . $begin)->applyFromArray($tableborderStyle);

				                		$terjawab++;
					                    $sisa_kosong--;
					                    $iteratekolom++;
				                	}

				                	if ( $iteration == $get_num_soal ) {
				                		for($i = 0;$i < $sisa_kosong; $i++) {
											$excel->getActiveSheet()->getStyle($colsjawaban[$iteratekolom] . $begin)->applyFromArray($tableborderStyle);
											$iteratekolom++;
				                		}
				                		$sheet->setCellValue(($colsjawaban[$iteratekolom]) . $begin,$terjawab);
										$excel->getActiveSheet()->getStyle(($colsjawaban[$iteratekolom] ) . $begin)->applyFromArray($tableborderStyle);
				                		$jmltopik += $terjawab;
				                		$iteration = 1; 
					                    $terjawab = 0;
					                    unset($sisa_kosong);
				                	} else {
										$iteration++;
				                	}
				                }
			                }
						} else {
							$get_jml_soal = $this->tabulasi->get_jml_soal();
							$iteration = 0;
							for ($i=0; $i < $get_jml_soal; $i++) {
								$excel->getActiveSheet()->getStyle($colsjawaban[$iteration] . $begin)->applyFromArray($tableborderStyle);
								if ( $iteration == 20 ) {
			                		$sheet->setCellValue("Y" . $begin,'0');
									$excel->getActiveSheet()->getStyle("Y" . $begin)->applyFromArray($tableborderStyle);
			                		$iteration = 0;
								} else {
									$iteration++;
								}
							}
						}
		                $begin++;
			        }
				
            		$sheet->setCellValue("A" . $begin,"JUMLAH SISWA KELAS " . $kelas['kelas']);
            		$sheet->setCellValue("D" . $begin,$get_num_siswa);
            		$sheet->setCellValue("Y" . $begin,$jmltopik);
					$sheet->mergeCells("A" . $begin . ":C" . $begin);
					$sheet->mergeCells("E" . $begin . ":X" . $begin);
					$excel->getActiveSheet()->getStyle('A' . $begin . ":C" . $begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('E' . $begin . ":X" . $begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('D' . $begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('Y' . $begin)->applyFromArray($tableborderStyle);
					$excel->getActiveSheet()->getStyle('A'.$begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					$begin++;


				}

				$score_paralel = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
				$sheet->setCellValue("A" . $begin,"JUMLAH PARALEL");
        		$sheet->setCellValue("D" . $begin,$this->tabulasi->get_jml_siswa());
        		$sheet->setCellValue("Y" . $begin,$score_paralel);
				$sheet->mergeCells("A" . $begin . ":C" . $begin);
				$sheet->mergeCells("E" . $begin . ":X" . $begin);
				$excel->getActiveSheet()->getStyle('A' . $begin . ":C" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle('E' . $begin . ":X" . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle('D' . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle('Y' . $begin)->applyFromArray($tableborderStyle);
				$excel->getActiveSheet()->getStyle('A'.$begin)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$begin += 2;

        	}
        }

        // SET WIDTH OF COLUMN
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(39);
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('H')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('J')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('N')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('O')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('P')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('Q')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('R')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('T')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('U')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('V')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('W')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('X')->setWidth(5);
		$excel->getActiveSheet()->getColumnDimension('Y')->setWidth(5);

		$excel->getActiveSheet()->setTitle("Tabulasi Hasil");

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