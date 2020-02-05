<div class="row">
	<div class="col-2">Nomor Urut</div>
	<div class="col-10">: <?= $siswa['no_urut'] ?></div>
</div>
<div class="row">
	<div class="col-2">Nama</div>
	<div class="col-10">: <?= $siswa['nama_siswa'] ?></div>
</div>
<div class="row">
	<div class="col-2">Jenis Kelamin</div>
	<div class="col-10">: <?= ucwords($siswa['jenis_kelamin']) ?></div>
</div>
<div class="row">
	<div class="col-2">Kelas</div>
	<div class="col-10">: <?= $this->kelas->get_kelas($siswa['id_kelas'])['kelas'] ?></div>
</div>
<div class="row">
	<div class="col-12 text-center">
		<h4>Bidang dan Frekuensi masalah</h4>
	</div>
	<div class="col-12 table-responsive">
		<table class="table table-bordered">
			<thead class="text-center">
				<tr>
					<th rowspan="2" colspan="2" style="vertical-align: middle;">
						KODE TOPIK MASALAH
					</th>
					<th colspan="22">JENIS MASALAH</th>
				</tr>
				<tr>
					<th colspan="20">NOMOR MASALAH</th>
					<th width="100">JUMLAH</th>
					<th width="100">%</th>
				</tr>
			</thead>
			<tbody>
				<tr class="bg-primary">
					<td width="50">I.</td>
					<td colspan="21">PRIBADI</td>
					<td id="lbljmlpribadi"></td>
					<td id="lblpersenpribadi"></td>
				</tr>
				<?php 
					$i = 1;
					$jmlpribadi = 0;
					$jmlkeseluruhan = 0;
					$persenkeseluruhan = 0;
				?>
				<?php foreach ($pribadi_kategori as $kategori): ?>
					<?php 
			          $get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			          $jumlah = 0;
					?>
					<tr>
						<td><?= $i++ ?></td>
						<td><?= $kategori['nama_kategori'] ?></td>
						<?php foreach ($get_jawaban as $jawaban): ?>
			              <?php if ( $jawaban['remarks'] == "y" ): ?>
			                <td><?= $jawaban['no_soal'] ?></td>
			                <?php $jumlah++ ?>
			              <?php elseif ( $jawaban['remarks'] == "g" ): ?>
			                <td></td>
			              <?php endif ?>
						<?php endforeach ?>
						<td><?= $jumlah ?></td>
						<td><?= $jumlah / 20 * 100 ?>%</td>
					</tr>
					<?php 
						$jmlpribadi += $jumlah;
						$jmlkeseluruhan += $jumlah;
					?>
					<script>
						$("#lbljmlpribadi").html("<?= $jmlpribadi ?>");
						$("#lblpersenpribadi").html("<?= ceil($jmlpribadi / 100 * 100) ?>%");
					</script>
				<?php endforeach ?>

				<tr class="bg-danger">
					<td width="50">II.</td>
					<td colspan="21">SOSIAL</td>
					<td id="lbljmlsosial"></td>
					<td id="lblpersensosial"></td>
				</tr>
				<?php 
					$i = 1;
					$jmlsosial = 0;
				?>
				<?php foreach ($sosial_kategori as $kategori): ?>
					<?php 
			          $get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			          $jumlah = 0;
					?>
					<tr>
						<td><?= $i++ ?></td>
						<td><?= $kategori['nama_kategori'] ?></td>
						<?php foreach ($get_jawaban as $jawaban): ?>
			              <?php if ( $jawaban['remarks'] == "y" ): ?>
			                <td><?= $jawaban['no_soal'] ?></td>
			                <?php $jumlah++ ?>
			              <?php elseif ( $jawaban['remarks'] == "g" ): ?>
			                <td></td>
			              <?php endif ?>
						<?php endforeach ?>
						<td><?= $jumlah ?></td>
						<td><?= $jumlah / 20 * 100 ?>%</td>
					</tr>
					<?php 
						$jmlsosial += $jumlah;
						$jmlkeseluruhan += $jumlah;
					?>
					<script>
						$("#lbljmlsosial").html("<?= $jmlsosial ?>");
						$("#lblpersensosial").html("<?= ceil($jmlsosial / 60 * 100) ?>%");
					</script>
				<?php endforeach ?>

				<tr class="bg-success">
					<td width="50">III.</td>
					<td colspan="21">BELAJAR</td>
					<td id="lbljmlbelajar"></td>
					<td id="lblpersenbelajar"></td>
				</tr>
				<?php 
					$i = 1;
					$jmlbelajar = 0;
				?>
				<?php foreach ($belajar_kategori as $kategori): ?>
					<?php 
			          $get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			          $jumlah = 0;
					?>
					<tr>
						<td><?= $i++ ?></td>
						<td><?= $kategori['nama_kategori'] ?></td>
						<?php foreach ($get_jawaban as $jawaban): ?>
			              <?php if ( $jawaban['remarks'] == "y" ): ?>
			                <td><?= $jawaban['no_soal'] ?></td>
			                <?php $jumlah++ ?>
			              <?php elseif ( $jawaban['remarks'] == "g" ): ?>
			                <td></td>
			              <?php endif ?>
						<?php endforeach ?>
						<td><?= $jumlah ?></td>
						<td><?= $jumlah / 20 * 100 ?>%</td>
					</tr>
					<?php 
						$jmlbelajar += $jumlah;
						$jmlkeseluruhan += $jumlah;
					?>
					<script>
						$("#lbljmlbelajar").html("<?= $jmlbelajar ?>");
						$("#lblpersenbelajar").html("<?= ceil($jmlbelajar / 60 * 100) ?>%");
					</script>
				<?php endforeach ?>

				<tr class="bg-warning">
					<td width="50">IV.</td>
					<td colspan="21">KARIR</td>
					<td id="lbljmlkarir"></td>
					<td id="lblpersenkarir"></td>
				</tr>
				<?php 
					$i = 1;
					$jmlkarir = 0;
				?>
				<?php foreach ($karir_kategori as $kategori): ?>
					<?php 
			          $get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
			          $jumlah = 0;
					?>
					<tr>
						<td><?= $i++ ?></td>
						<td><?= $kategori['nama_kategori'] ?></td>
						<?php foreach ($get_jawaban as $jawaban): ?>
			              <?php if ( $jawaban['remarks'] == "y" ): ?>
			                <td><?= $jawaban['no_soal'] ?></td>
			                <?php $jumlah++ ?>
			              <?php elseif ( $jawaban['remarks'] == "g" ): ?>
			                <td></td>
			              <?php endif ?>
						<?php endforeach ?>
						<td><?= $jumlah ?></td>
						<td><?= $jumlah / 20 * 100 ?>%</td>
					</tr>
					<?php 
						$jmlkarir += $jumlah;
						$jmlkeseluruhan += $jumlah;
					?>
					<script>
						$("#lbljmlkarir").html("<?= $jmlkarir ?>");
						$("#lblpersenkarir").html("<?= ceil($jmlkarir / 20 * 100) ?>%");
					</script>
				<?php endforeach ?>
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