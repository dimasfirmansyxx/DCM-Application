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
					<th>Nm</th>
					<th>N</th>
					<th>N x M</th>
					<th>(Nm : N x M) x 100%</th>
					<th>Derajat</th>
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
						<td><?= $jml ?></td>
						<td><?= $jmlsoal ?></td>
						<td><?= $jmlsoal * $jmlsiswa ?></td>
					</tr>
				<?php endforeach ?>

				<!-- <tr class="bg-danger">
					<td width="50">II.</td>
					<td colspan="4">SOSIAL</td>
					<td id="lbljmlsosial"></td>
					<td id="lblpersensosial"></td>
				</tr>

				<tr class="bg-success">
					<td width="50">III.</td>
					<td colspan="4">BELAJAR</td>
					<td id="lbljmlbelajar"></td>
					<td id="lblpersenbelajar"></td>
				</tr>

				<tr class="bg-warning">
					<td width="50">IV.</td>
					<td colspan="4">KARIR</td>
					<td id="lbljmlkarir"></td>
					<td id="lblpersenkarir"></td>
				</tr> -->
				
			</tbody>
		</table>
	</div>
</div>
<div class="row mt-3">
	<div class="col-6">
		<canvas id="kategoriChart" height="200"></canvas>
	</div>
	<div class="col-6">
		<canvas id="sectionChart" height="200"></canvas>
	</div>
</div>

<script>
	var catChart = document.getElementById('kategoriChart').getContext('2d');
	var categoryChart = new Chart(catChart, {
	    type: 'bar',
	    data: {
	        labels: [
	        	<?php foreach ($kategori_chart as $key => $value): ?>
		        	'<?= $key ?> ( <?= $value ?>% )',
	        	<?php endforeach ?>
	        ],
	        datasets: [{
	            data: [
	            	<?php foreach ($kategori_chart as $key => $value): ?>
			        	'<?= $value ?>',
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

	var secChart = document.getElementById('sectionChart').getContext('2d');
	var sectionChart = new Chart(secChart, {
	    type: 'bar',
	    data: {
	        labels: [
	        	<?php foreach ($section_chart as $key => $value): ?>
		        	'<?= $key ?> ( <?= $value ?>% )',
	        	<?php endforeach ?>
	        ],
	        datasets: [{
	            data: [
	            	<?php foreach ($section_chart as $key => $value): ?>
			        	'<?= $value ?>',
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