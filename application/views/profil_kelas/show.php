<div class="row">
	<div class="col-2">Kelas</div>
	<div class="col-10">: <?= $get_kelas['kelas'] ?></div>

	<div class="col-2">Sekolah</div>
	<div class="col-10">: <?= $this->Clsglobal->site_info("nama_sekolah") ?></div>

	<div class="col-2">Alamat</div>
	<div class="col-10">: <?= $this->Clsglobal->site_info("alamat") ?></div>
</div>
<div class="row table-responsive">
	<table class="table table-bordered" border="1">
		<thead class="text-center">
			<tr>
				<th rowspan="3" style="vertical-align: middle;">No</th>
				<th rowspan="3" style="vertical-align: middle;">Nama</th>
				<th rowspan="3" style="vertical-align: middle;">Jenis Kelamin</th>
				<th colspan="12">Topik Masalah</th>
				<th rowspan="3" style="vertical-align: middle;">Jumlah</th>
				<th rowspan="3" style="vertical-align: middle;">Ket</th>
			</tr>
			<tr>
				<th colspan="5">Pribadi</th>
				<th colspan="3">Sosial</th>
				<th colspan="3">Belajar</th>
				<th>Karir</th>
			</tr>
			<tr>
				<?php for($i = 0; $i < count($get_kategori) - 1; $i++) : ?>
					<th><?= $abjad[$i] ?></th>
				<?php endfor ?>
			</tr>
		</thead>
		<tbody>
			<?php $showket = false ?>
			<?php foreach ($get_siswa as $siswa): ?>
				<tr>
					<td><?= $siswa['no_urut'] ?></td>
					<td><?= $siswa['nama_siswa'] ?></td>
					<td><?= ucwords($siswa['jenis_kelamin']) ?></td>
					<?php $get_score = $this->profil->get_score($siswa['id_siswa']) ?>
					<?php foreach ($get_score as $score): ?>
						<td><?= $score ?></td>
					<?php endforeach ?>
					<?php if ( $showket == false ): ?>
						<td rowspan="500">
							<?php for($i = 0; $i < count($get_kategori) - 1; $i++) : ?>
								<?= $abjad[$i] . ". " . $get_kategori[$i]['nama_kategori'] ?><br>
								<?php $showket = true ?>
							<?php endfor ?>
						</td>
					<?php endif ?>
				</tr>
			<?php endforeach ?>
			<tr>
			</tr>
		</tbody>
	</table>
</div>