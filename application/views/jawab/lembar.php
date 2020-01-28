<h3>
	<?= $this->Clsglobal->romawi($kategori['id_kategori']) ?>. 
	<?= strtoupper($kategori['nama_kategori']) ?>
</h3>

<form id="frmjawab">
	<div class="row">
		<?php foreach ($soal as $row): ?>
			<div class="col-md-6 mt-5">
				<p><?= $row['no_soal'] . ". " . $row['soal'] ?></p>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="<?= $row['no_soal'] ?>" id="<?= $row['no_soal'] ?>ya" value="ya" required>
					<label class="form-check-label" for="<?= $row['no_soal'] ?>ya">
						Ya
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="<?= $row['no_soal'] ?>" id="<?= $row['no_soal'] ?>tidak" value="tidak" required>
					<label class="form-check-label" for="<?= $row['no_soal'] ?>tidak">
						Tidak
					</label>
				</div>
			</div>
		<?php endforeach ?>
		<?php if ( $jmlkategori != $kategori['id_kategori'] ): ?>
			<div class="mt-5 col-12 text-center">
				<button type="submit" class="btn btn-primary btnsubmit">Selanjutnya</button>
			</div>
		<?php endif ?>
	</div>
</form>