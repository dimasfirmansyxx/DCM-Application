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
					<td>11</td>
					<td>22%</td>
				</tr>
				<?php $i = 1 ?>
				<?php foreach ($pribadi_kategori as $kategori): ?>
					<?php 
			          $get_jawaban = $this->profil->get_jawaban($siswa['id_siswa'],$kategori['id_kategori']);
					?>
					<?= $kategori['id_kategori'] ?>
					<tr>
						<td><?= $i++ ?></td>
						<td><?= $kategori['nama_kategori'] ?></td>
						<?php foreach ($get_jawaban as $jawaban): ?>
			              <?php if ( $jawaban['remarks'] == "y" ): ?>
			                <td><?= $jawaban['no_soal'] ?></td>
			              <?php elseif ( $jawaban['remarks'] == "g" ): ?>
			                <td></td>
			              <?php endif ?>
						<?php endforeach ?>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>