<?php 
$check = $this->Clsglobal->check_availability("tbljawaban",["id_siswa" => $siswa['id_siswa']]);
	if ( $check == 3 ) { 
		echo "<p class='text-center'>Siswa belum melakukan tes / Siswa tidak ada</p>";
		return;
	}
?>
<table width="100%">
	<tbody>
		<tr>
			<td colspan="24" align="middle"><h4>HASIL PENGOLAHAN</h4></td>
		</tr>
		<tr>
			<td colspan="24" align="middle"><h2>DCM (DAFTAR CEK MASALAH)</h2></td>
		</tr>
		<tr>
			<td colspan="24" align="middle"><h4>(INDIVIDUAL)</h4></td>
		</tr>
	</tbody>
	<tbody>
		<table>
			<tr>
				<td width="200">Nomor Urut</td>
				<td width="500">: <?= $siswa['no_urut'] ?></td>
			</tr>
			<tr>
				<td width="200">Nama</td>
				<td width="500">: <?= $siswa['nama_siswa'] ?></td>
			</tr>
			<tr>
				<td width="200">Jenis Kelamin</td>
				<td width="500">: <?= ucwords($siswa['jenis_kelamin']) ?></td>
			</tr>
			<tr>
				<td width="200">Kelas</td>
				<td width="500">: <?= $this->kelas->get_kelas($siswa['id_kelas'])['kelas'] ?></td>
			</tr>
		</table>
	</tbody>
	<tbody class="text-center">
		<tr>
			<td colspan="24" align="middle"><h4>Bidang dan Frekuensi masalah</h4></td>
		</tr>
	</tbody>
</table>
<div class="row mt-4">
	<div class="col-12 text-center">
	</div>
	<div class="col-12 table-responsive">
		<table class="table table-bordered" border="1">
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

<?php $i = 0 ?>
<?php foreach ($soal_essay as $essay): ?>
	<?php $get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],"13"); ?>
	<div class="row mt-3">
		<div class="col-12">
			<p><?= $essay['no_soal'] . ". " . $essay['soal'] ?></p>
			<p>&nbsp;&nbsp;&nbsp;= <?= $get_jawaban[$i++]['remarks'] ?></p>
		</div>
	</div>
<?php endforeach ?>

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