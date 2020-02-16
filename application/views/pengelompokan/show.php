<div class="row">
	<div class="col-12 text-center">
		<h4>Pengelompokan Siswa per Masalah</h4>
	</div>
	<div class="col-12 table-responsive">
		<table class="table table-bordered" border="1">
			<thead class="text-center">
				<tr>
					<th>No.</th>
					<th>Masalah</th>
					<th>Nomor Urut (Kelas)</th>
					<th>Jumlah</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 1; ?>
				<?php foreach ($all_soal as $soal): ?>
					<?php $get_kelompok = $this->pengelompokan->get_kelompok($soal['no_soal']) ?>
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