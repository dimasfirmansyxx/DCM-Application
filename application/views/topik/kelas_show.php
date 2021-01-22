<table width="100%">
	<tbody>
		<tr>
			<td colspan="17" align="middle"><h4>
				HASIL ANALISIS TOPIK MASALAH PER KELAS
			</h4></td>
		</tr>
	</tbody>
</table>
<table>
	<tr>
		<td width="200">Kelas</td>
		<td width="500">: <?= $this->kelas->get_kelas($id_kelas)['kelas'] ?></td>
	</tr>
	<tr>
		<td width="200">Sekolah</td>
		<td width="500">: <?= $this->Clsglobal->site_info("nama_sekolah") ?></td>
	</tr>
	<tr>
		<td width="200">Alamat</td>
		<td width="500">: <?= $this->Clsglobal->site_info("alamat") ?></td>
	</tr>
</table>
<div class="row">
	<div class="col-12 table-responsive">
		<table class="table table-bordered" border="1">
			<thead class="text-center">
				<tr>
					<th>No.</th>
					<th>Topik</th>
					<th width="180">Nm</th>
					<th width="180">N</th>
					<th width="180">N x M</th>
					<th width="180">(Nm : N x M) x 100%</th>
					<th width="180">Derajat</th>
				</tr>
			</thead>
			<tbody>
				<tr class="bg-primary">
					<td width="50">I.</td>
					<td colspan="4">PRIBADI</td>
					<td id="lblpersenpribadi" align="center"></td>
					<td id="lblderajatpribadi" align="center"></td>
				</tr>
				<?php 
					$persenpribadi = 0;
					$derajatpribadi;
					$iteration = 1;
				?>
				<?php foreach ($pribadi_kategori as $kategori): ?>
					<?php 
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
					?>
					<?php if ( !($sortir == null) ): ?>
						<?php if ( $sortir == $derajat ): ?>
							<tr>
								<td><?= $iteration++ ?></td>
								<td><?= $kategori['nama_kategori'] ?></td>
								<td align="center"><?= $jml ?></td>
								<td align="center"><?= $jmlsoal ?></td>
								<td align="center"><?= $n_m ?></td>
								<td align="center"><?= $persen ?>%</td>
								<td align="center"><?= $derajat ?></td>
							</tr>
						<?php endif ?>
					<?php else: ?>
						<tr>
							<td><?= $iteration++ ?></td>
							<td><?= $kategori['nama_kategori'] ?></td>
							<td align="center"><?= $jml ?></td>
							<td align="center"><?= $jmlsoal ?></td>
							<td align="center"><?= $n_m ?></td>
							<td align="center"><?= $persen ?>%</td>
							<td align="center"><?= $derajat ?></td>
						</tr>
					<?php endif ?>
					<?php $persenpribadi += $persen ?>
				<?php endforeach ?>
				<?php 
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
				?>
				<script>
					$("#lblpersenpribadi").html("<?= $persenpribadi ?>%")
					$("#lblderajatpribadi").html("<?= $derajatpribadi ?>")
				</script>

				<tr class="bg-danger">
					<td width="50">II.</td>
					<td colspan="4">SOSIAL</td>
					<td id="lblpersensosial" align="center"></td>
					<td id="lblderajatsosial" align="center"></td>
				</tr>
				<?php 
					$persensosial = 0;
					$derajatsosial;
					$iteration = 1;
				?>
				<?php foreach ($sosial_kategori as $kategori): ?>
					<?php 
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
					?>
					<?php if ( !($sortir == null) ): ?>
						<?php if ( $sortir == $derajat ): ?>
							<tr>
								<td><?= $iteration++ ?></td>
								<td><?= $kategori['nama_kategori'] ?></td>
								<td align="center"><?= $jml ?></td>
								<td align="center"><?= $jmlsoal ?></td>
								<td align="center"><?= $n_m ?></td>
								<td align="center"><?= $persen ?>%</td>
								<td align="center"><?= $derajat ?></td>
							</tr>
						<?php endif ?>
					<?php else: ?>
						<tr>
							<td><?= $iteration++ ?></td>
							<td><?= $kategori['nama_kategori'] ?></td>
							<td align="center"><?= $jml ?></td>
							<td align="center"><?= $jmlsoal ?></td>
							<td align="center"><?= $n_m ?></td>
							<td align="center"><?= $persen ?>%</td>
							<td align="center"><?= $derajat ?></td>
						</tr>
					<?php endif ?>
					<?php $persensosial += $persen ?>
				<?php endforeach ?>
				<?php 
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
				?>
				<script>
					$("#lblpersensosial").html("<?= $persensosial ?>%")
					$("#lblderajatsosial").html("<?= $derajatsosial ?>")
				</script>

				<tr class="bg-success">
					<td width="50">III.</td>
					<td colspan="4">BELAJAR</td>
					<td id="lblpersenbelajar" align="center"></td>
					<td id="lblderajatbelajar" align="center"></td>
				</tr>
				<?php 
					$persenbelajar = 0;
					$derajatbelajar;
					$iteration = 1;
				?>
				<?php foreach ($belajar_kategori as $kategori): ?>
					<?php 
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
					?>
					<?php if ( !($sortir == null) ): ?>
						<?php if ( $sortir == $derajat ): ?>
							<tr>
								<td><?= $iteration++ ?></td>
								<td><?= $kategori['nama_kategori'] ?></td>
								<td align="center"><?= $jml ?></td>
								<td align="center"><?= $jmlsoal ?></td>
								<td align="center"><?= $n_m ?></td>
								<td align="center"><?= $persen ?>%</td>
								<td align="center"><?= $derajat ?></td>
							</tr>
						<?php endif ?>
					<?php else: ?>
						<tr>
							<td><?= $iteration++ ?></td>
							<td><?= $kategori['nama_kategori'] ?></td>
							<td align="center"><?= $jml ?></td>
							<td align="center"><?= $jmlsoal ?></td>
							<td align="center"><?= $n_m ?></td>
							<td align="center"><?= $persen ?>%</td>
							<td align="center"><?= $derajat ?></td>
						</tr>
					<?php endif ?>
					<?php $persenbelajar += $persen ?>
				<?php endforeach ?>
				<?php 
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
				?>
				<script>
					$("#lblpersenbelajar").html("<?= $persenbelajar ?>%")
					$("#lblderajatbelajar").html("<?= $derajatbelajar ?>")
				</script>

				<tr class="bg-warning">
					<td width="50">IV.</td>
					<td colspan="4">KARIR</td>
					<td id="lblpersenkarir" align="center"></td>
					<td id="lblderajatkarir" align="center"></td>
				</tr>
				<?php 
					$persenkarir = 0;
					$derajatkarir;
					$iteration = 1;
				?>
				<?php foreach ($karir_kategori as $kategori): ?>
					<?php 
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
					?>
					<?php if ( !($sortir == null) ): ?>
						<?php if ( $sortir == $derajat ): ?>
							<tr>
								<td><?= $iteration++ ?></td>
								<td><?= $kategori['nama_kategori'] ?></td>
								<td align="center"><?= $jml ?></td>
								<td align="center"><?= $jmlsoal ?></td>
								<td align="center"><?= $n_m ?></td>
								<td align="center"><?= $persen ?>%</td>
								<td align="center"><?= $derajat ?></td>
							</tr>
						<?php endif ?>
					<?php else: ?>
						<tr>
							<td><?= $iteration++ ?></td>
							<td><?= $kategori['nama_kategori'] ?></td>
							<td align="center"><?= $jml ?></td>
							<td align="center"><?= $jmlsoal ?></td>
							<td align="center"><?= $n_m ?></td>
							<td align="center"><?= $persen ?>%</td>
							<td align="center"><?= $derajat ?></td>
						</tr>
					<?php endif ?>
					<?php $persenkarir += $persen ?>
				<?php endforeach ?>
				<?php 
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
				?>
				<script>
					$("#lblpersenkarir").html("<?= $persenkarir ?>%")
					$("#lblderajatkarir").html("<?= $derajatkarir ?>")
				</script>
				
			</tbody>
		</table>
	</div>
</div>
<div class="row mt-3">
	<div class="col-12">
		<canvas id="kategoriChart" height="200"></canvas>
	</div>
	<div class="col-12">
		<canvas id="sectionChart" height="200"></canvas>
	</div>
</div>

<script>
	var catChart = document.getElementById('kategoriChart').getContext('2d');
	var categoryChart = new Chart(catChart, {
	    type: 'bar',
	    data: {
	        labels: [
	        	<?php foreach ($get_kategori as $kategori): ?>
	        		<?php 
						$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
						$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
						$jmlsiswa = $this->tabulasi->get_jml_siswa($id_kelas);
	        		?>
		        	'<?= $kategori['nama_kategori'] ?> ( <?= ceil($jml / ($jmlsoal * $jmlsiswa) * 100) ?>% )',
	        	<?php endforeach ?>
	        ],
	        datasets: [{
	        	label: 'Persentase',
	            data: [
	            	<?php foreach ($get_kategori as $kategori): ?>
		        		<?php if ( $kategori['id_kategori'] != 13 ) : ?>
			        		<?php 
			        			$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
								$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
								$jmlsiswa = $this->tabulasi->get_jml_siswa($id_kelas);
			        		?>
				        	'<?= ceil($jml / ($jmlsoal * $jmlsiswa) * 100) ?>',
				        <?php endif ?>
		        	<?php endforeach ?>
	            ],
	            backgroundColor: [
	            	<?php foreach ($get_kategori as $kategori): ?>
		        		<?php if ( $kategori['id_kategori'] != 13 ) : ?>
			        		<?php 
			        			$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
								$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
								$jmlsiswa = $this->tabulasi->get_jml_siswa($id_kelas);
			        		?>
							'rgba(255,99,132,1)',
				        <?php endif ?>
		        	<?php endforeach ?>
				],
				borderColor: [
					<?php foreach ($get_kategori as $kategori): ?>
		        		<?php if ( $kategori['id_kategori'] != 13 ) : ?>
			        		<?php 
			        			$jml = $this->tabulasi->get_score_kelas($kategori['id_kategori'],$id_kelas);
								$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
								$jmlsiswa = $this->tabulasi->get_jml_siswa($id_kelas);
			        		?>
							'rgba(255,99,132,1)',
				        <?php endif ?>
		        	<?php endforeach ?>
				],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero: true,
	                    max: 100
	                }
	            }]
	        }
	    }
	});

	var secChart = document.getElementById('sectionChart').getContext('2d');
	var sectionChart = new Chart(secChart, {
	    type: 'bar',
	    data: {
	        labels: [
	        	'PRIBADI (<?= $persenpribadi ?>%)',
	        	'SOSIAL (<?= $persensosial ?>%)',
	        	'BELAJAR (<?= $persenbelajar ?>%)',
	        	'KARIR (<?= $persenkarir ?>%)'
	        ],
	        datasets: [{
	        	label: 'Persentase',
	            data: [
		        	'<?= $persenpribadi ?>',
		        	'<?= $persensosial ?>',
		        	'<?= $persenbelajar ?>',
		        	'<?= $persenkarir ?>'
	            ],
		        backgroundColor: [
					'rgba(255,99,132,1)',
					'rgba(255,99,132,1)',
					'rgba(255,99,132,1)',
					'rgba(255,99,132,1)'
				],
				borderColor: [
					'rgba(255,99,132,1)',
					'rgba(255,99,132,1)',
					'rgba(255,99,132,1)',
					'rgba(255,99,132,1)'
				],
	            borderWidth: 1
	        }],
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero: true,
	                    max: 100
	                }
	            }]
	        },
	    }
	});

	setTimeout(setSessChart,500);
	function setSessChart(){
	    var chart1 = categoryChart.toBase64Image();
	    var chart2 = sectionChart.toBase64Image();
	    $.ajax({
	    	url : "<?= base_url() ?>topik/set_chart_session/",
	    	data : { chart1 : chart1, chart2 : chart2 },
	    	type : "post",
	    	dataType : "json",
	    	success : function(result) {
	    		
	    	}
	    });
	}
</script>