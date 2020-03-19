<table width="100%">
	<tbody>
		<tr>
			<td colspan="17" align="middle"><h4>
				PENGELOMPOKKAN SISWA PER MASALAH
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
					<th>Masalah</th>
					<th>Nomor Urut</th>
					<th>Jumlah</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 1; ?>
				<?php foreach ($all_soal as $soal): ?>
					<?php $get_kelompok = $this->pengelompokan->get_kelompok($soal['no_soal'], $id_kelas) ?>
					<tr>
						<td><?= $i++ ?></td>
						<td><?= $soal['soal'] ?></td>
						<td>
							<?php 
								$jumlah = 0;
								foreach ($get_kelompok as $kelompok) {
									echo $kelompok . ", ";
									$jumlah++;
								}
							?>
						</td>
						<td><?= $jumlah ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>