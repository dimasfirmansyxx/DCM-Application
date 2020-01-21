<?php 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$filename.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
?>
<table border="1" cellspacing="0" cellpadding="20">
	<thead>
		<tr>
			<th colspan="2">KATEGORI</th>
			<th></th>
			<th colspan="4">PENGISIAN SOAL</th>
		</tr>
		<tr>
			<th>id_kategori</th>
			<th>nama_kategori</th>
			<th></th>
			<th>no_soal</th>
			<th>id_kategori</th>
			<th>soal</th>
			<th>jenis</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($kategori_soal as $kategori): ?>
			<tr>
				<td><?= $kategori['id_kategori'] ?></td>
				<td><?= $kategori['nama_kategori'] ?></td>
				<td></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>