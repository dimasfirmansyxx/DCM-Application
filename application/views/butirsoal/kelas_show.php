<div class="row table-responsive">
	<table class="table table-bordered" border="1">
		<thead>
			<tr align="center">
				<th>NO</th>
				<th>TOPIK</th>
				<th>Nm</th>
				<th>(Nm : N) x 100%</th>
				<th>Derajat Masalah</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($get_kategori as $kategori): ?>
				<?php if ( !($kategori['id_kategori'] == 13) ): ?>
					<tr>
						<td colspan="5" class="bg-secondary">
							<?= $this->Clsglobal->romawi($kategori['id_kategori']) ?>. 
							<?= $kategori['nama_kategori'] ?>
						</td>
					</tr>
					<?php 
						$get_soal = $this->butirsoal->get_soal($kategori['id_kategori']); 
						$jumlah = 0;
					?>
					<?php foreach ($get_soal as $soal): ?>
						<?php 
							$get_jawaban = $this->butirsoal->get_jawaban($soal['no_soal'],$id_kelas);
							$jmlsiswa = $this->butirsoal->jmlsiswa($id_kelas);
							$persentase = $get_jawaban / $jmlsiswa * 100;

							if ( $persentase >= 0 && $persentase < 1 ) {
								$derajat = "A";
							} elseif ( $persentase >= 1 && $persentase < 11 ) {
								$derajat = "B";
							} elseif ( $persentase >= 11 && $persentase < 26 ) {
								$derajat = "C";
							} elseif ( $persentase >= 26 && $persentase < 51 ) {
								$derajat = "D";
							} else {
								$derajat = "E";
							}

							if ( !($sortir == null) ) {
								if ( $sortir == $derajat ) {
									$jumlah += $get_jawaban;
								}
							} else {	
								$jumlah += $get_jawaban;
							}
						?>
						<?php if ( !($sortir == null) ): ?>
							<?php if ( $sortir == $derajat ): ?>
								<tr>
									<td><?= $soal['no_soal'] ?></td>
									<td><?= $soal['soal'] ?></td>
									<td><?= $get_jawaban ?></td>
									<td><?= $persentase ?>%</td>
									<td><?= $derajat ?></td>
								</tr>
							<?php endif ?>
						<?php else: ?>
							<tr>
								<td><?= $soal['no_soal'] ?></td>
								<td><?= $soal['soal'] ?></td>
								<td><?= $get_jawaban ?></td>
								<td><?= $persentase ?>%</td>
								<td><?= $derajat ?></td>
							</tr>
						<?php endif ?>
					<?php endforeach ?>
					<tr class="bg-warning">
						<th colspan="2" class="text-right">Jumlah</th>
						<th colspan="3"><?= $jumlah ?></th>
					</tr>
				<?php endif ?>
			<?php endforeach ?>
		</tbody>
	</table>
</div>