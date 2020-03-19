<table width="100%">
	<tbody>
		<tr>
			<td colspan="17" align="middle"><h4>HASIL PENGOLAHAN</h4></td>
		</tr>
		<tr>
			<td colspan="17" align="middle"><h2>DCM (DAFTAR CEK MASALAH)</h2></td>
		</tr>
		<tr>
			<td colspan="17" align="middle"><h4>(KLASIKAL)</h4></td>
		</tr>
	</tbody>
</table>
<tbody>
	<table>
		<tr>
			<td width="200">Kelas</td>
			<td width="500">: <?= $get_kelas['kelas'] ?></td>
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
</tbody>
<div class="row table-responsive">
	<table class="table table-bordered" border="1">
		<thead class="text-center">
			<tr>
				<th rowspan="3" style="vertical-align: middle;">No</th>
				<th rowspan="3" style="vertical-align: middle;">Nama</th>
				<th rowspan="3" style="vertical-align: middle;">Jenis Kelamin</th>
				<th colspan="12">Topik Masalah</th>
				<th rowspan="3" style="vertical-align: middle;">Jumlah</th>
			</tr>
			<tr>
				<th colspan="5">Pribadi</th>
				<th colspan="3">Sosial</th>
				<th colspan="3">Belajar</th>
				<th>Karir</th>
			</tr>
			<tr>
				<?php for($i = 0; $i < count($get_kategori) - 1; $i++) : ?>
					<th><?= $get_kategori[$i]['nama_kategori'] ?></th>
				<?php endfor ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($get_siswa as $siswa): ?>
				<tr>
					<td><?= $siswa['no_urut'] ?></td>
					<td><?= $siswa['nama_siswa'] ?></td>
					<td><?= ucwords($siswa['jenis_kelamin']) ?></td>
					<?php $get_score = $this->profil->get_score($siswa['id_siswa']) ?>
					<?php foreach ($get_score as $score): ?>
						<td><?= $score ?></td>
					<?php endforeach ?>
				</tr>
			<?php endforeach ?>
			<tr>
			</tr>
		</tbody>
	</table>
</div>
<table width="100%">
	<tbody>
		<tr>
			<td colspan="8">
				<table>
					<tr><td colspan="8">Mengetahui</td></tr>
					<tr><td colspan="8">Kepala Sekolah</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td colspan="8"><?= $this->Clsglobal->site_info("kepala_sekolah") ?></td></tr>
				</table>
			</td>
			<td colspan="8">
				<table>
					<tr><td colspan="8">Mengetahui</td></tr>
					<tr><td colspan="8">Guru Pembimbing</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td colspan="8"><?= $this->Clsglobal->site_info("guru_pembimbing") ?></td></tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>