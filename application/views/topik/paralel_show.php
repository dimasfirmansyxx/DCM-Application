<div class="row">
	<div class="col-12 text-center">
		<h4>Hasil Analisis Topik Masalah Kelas Paralel</h4>
	</div>
	<div class="col-12 table-responsive">
		<table class="table table-bordered">
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
					<td id="lblpersenpribadi"></td>
					<td id="lblderajatpribadi"></td>
				</tr>
				<?php 
					$persenpribadi = 0;
					$derajatpribadi;
					$iteration = 1;
				?>
				<?php foreach ($pribadi_kategori as $kategori): ?>
					<?php 
						$jml = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
						$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
						$jmlsiswa = $this->tabulasi->get_jml_siswa();
					?>
					<tr>
						<td><?= $iteration++ ?></td>
						<td><?= $kategori['nama_kategori'] ?></td>
						<td align="center"><?= $jml ?></td>
						<td align="center"><?= $jmlsoal ?></td>
						<td align="center"><?= $jmlsoal * $jmlsiswa ?></td>
						<td align="center">
							<?= $persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100) ?>%
						</td>
						<td align="center">
							<?php 
								if ( $persen >= 0 && $persen < 1 ) {
									echo "A";
								} elseif ( $persen >= 1 && $persen < 11 ) {
									echo "B";
								} elseif ( $persen >= 11 && $persen < 26 ) {
									echo "C";
								} elseif ( $persen >= 26 && $persen < 51 ) {
									echo "D";
								} else {
									echo "E";
								}

								$persenpribadi += $persen
							?>
						</td>
					</tr>
				<?php endforeach ?>

				<tr class="bg-danger">
					<td width="50">II.</td>
					<td colspan="4">SOSIAL</td>
					<td id="lblpersensosial"></td>
					<td id="lblderajatsosial"></td>
				</tr>
				<?php 
					$persensosial = 0;
					$derajatsosial;
					$iteration = 1;
				?>
				<?php foreach ($sosial_kategori as $kategori): ?>
					<?php 
						$jml = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
						$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
						$jmlsiswa = $this->tabulasi->get_jml_siswa();
					?>
					<tr>
						<td><?= $iteration++ ?></td>
						<td><?= $kategori['nama_kategori'] ?></td>
						<td align="center"><?= $jml ?></td>
						<td align="center"><?= $jmlsoal ?></td>
						<td align="center"><?= $jmlsoal * $jmlsiswa ?></td>
						<td align="center">
							<?= $persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100) ?>%
						</td>
						<td align="center">
							<?php 
								if ( $persen >= 0 && $persen < 1 ) {
									echo "A";
								} elseif ( $persen >= 1 && $persen < 11 ) {
									echo "B";
								} elseif ( $persen >= 11 && $persen < 26 ) {
									echo "C";
								} elseif ( $persen >= 26 && $persen < 51 ) {
									echo "D";
								} else {
									echo "E";
								}

								$persensosial += $persen
							?>
						</td>
					</tr>
				<?php endforeach ?>

				<tr class="bg-success">
					<td width="50">III.</td>
					<td colspan="4">BELAJAR</td>
					<td id="lblpersenbelajar"></td>
					<td id="lblderajatbelajar"></td>
				</tr>
				<?php 
					$persenbelajar = 0;
					$derajatbelajar;
					$iteration = 1;
				?>
				<?php foreach ($belajar_kategori as $kategori): ?>
					<?php 
						$jml = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
						$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
						$jmlsiswa = $this->tabulasi->get_jml_siswa();
					?>
					<tr>
						<td><?= $iteration++ ?></td>
						<td><?= $kategori['nama_kategori'] ?></td>
						<td align="center"><?= $jml ?></td>
						<td align="center"><?= $jmlsoal ?></td>
						<td align="center"><?= $jmlsoal * $jmlsiswa ?></td>
						<td align="center">
							<?= $persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100) ?>%
						</td>
						<td align="center">
							<?php 
								if ( $persen >= 0 && $persen < 1 ) {
									echo "A";
								} elseif ( $persen >= 1 && $persen < 11 ) {
									echo "B";
								} elseif ( $persen >= 11 && $persen < 26 ) {
									echo "C";
								} elseif ( $persen >= 26 && $persen < 51 ) {
									echo "D";
								} else {
									echo "E";
								}

								$persenbelajar += $persen
							?>
						</td>
					</tr>
				<?php endforeach ?>

				<tr class="bg-warning">
					<td width="50">IV.</td>
					<td colspan="4">KARIR</td>
					<td id="lblpersenkarir"></td>
					<td id="lblderajatkarir"></td>
				</tr>
				<?php 
					$persenkarir = 0;
					$derajatkarir;
					$iteration = 1;
				?>
				<?php foreach ($karir_kategori as $kategori): ?>
					<?php 
						$jml = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
						$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
						$jmlsiswa = $this->tabulasi->get_jml_siswa();
					?>
					<tr>
						<td><?= $iteration++ ?></td>
						<td><?= $kategori['nama_kategori'] ?></td>
						<td align="center"><?= $jml ?></td>
						<td align="center"><?= $jmlsoal ?></td>
						<td align="center"><?= $jmlsoal * $jmlsiswa ?></td>
						<td align="center">
							<?= $persen = ceil($jml / ($jmlsoal * $jmlsiswa) * 100) ?>%
						</td>
						<td align="center">
							<?php 
								if ( $persen >= 0 && $persen < 1 ) {
									echo "A";
								} elseif ( $persen >= 1 && $persen < 11 ) {
									echo "B";
								} elseif ( $persen >= 11 && $persen < 26 ) {
									echo "C";
								} elseif ( $persen >= 26 && $persen < 51 ) {
									echo "D";
								} else {
									echo "E";
								}

								$persenkarir += $persen
							?>
						</td>
					</tr>
				<?php endforeach ?>
				
			</tbody>
		</table>
	</div>
</div>
<div class="row mt-3">
	<div class="col-6">
		<canvas id="kategoriChart" height="200"></canvas>
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
	        			$jml = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
						$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
						$jmlsiswa = $this->tabulasi->get_jml_siswa();
	        		?>
		        	'<?= $kategori['nama_kategori'] ?> ( <?= ceil($jml / ($jmlsoal * $jmlsiswa) * 100) ?>% )',
	        	<?php endforeach ?>
	        ],
	        datasets: [{
	            data: [
	            	<?php foreach ($get_kategori as $kategori): ?>
		        		<?php 
		        			$jml = $this->tabulasi->get_score_paralel($kategori['id_kategori']);
							$jmlsoal = $this->tabulasi->num_soal($kategori['id_kategori']);
							$jmlsiswa = $this->tabulasi->get_jml_siswa();
		        		?>
			        	'<?= ceil($jml / ($jmlsoal * $jmlsiswa) * 100) ?>',
		        	<?php endforeach ?>
	            ],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero: true
	                }
	            }]
	        }
	    }
	});
</script>