<h3>
	<?= $this->Clsglobal->romawi($kategori['id_kategori']) ?>. 
	<?= strtoupper($kategori['nama_kategori']) ?>
</h3>

<form id="frmjawab">
	<div class="row">
		<?php foreach ($soal as $row): ?>
			<?php $acak = rand(0,1) ?>
			<div class="col-md-6 mt-5">
				<p><?= $row['no_soal'] . ". " . $row['soal'] ?></p>
				<?php if ( $row['jenis'] == "check" ): ?>
					<div class="form-check">
						<?php if ($acak == 1): ?>
						<input class="form-check-input" type="radio" name="<?= $row['no_soal'] ?>" id="<?= $row['no_soal'] ?>ya" value="y" required checked>
							<?php else: ?>
						<input class="form-check-input" type="radio" name="<?= $row['no_soal'] ?>" id="<?= $row['no_soal'] ?>ya" value="y" required>
						<?php endif ?>
						<label class="form-check-label" for="<?= $row['no_soal'] ?>ya">
							Ya
						</label>
					</div>
					<div class="form-check">
						<?php if ( $acak == 0 ): ?>
						<input class="form-check-input" type="radio" name="<?= $row['no_soal'] ?>" id="<?= $row['no_soal'] ?>tidak" value="g" required checked>
							<?php else: ?>

						<input class="form-check-input" type="radio" name="<?= $row['no_soal'] ?>" id="<?= $row['no_soal'] ?>tidak" value="g" required>
						<?php endif ?>
						<label class="form-check-label" for="<?= $row['no_soal'] ?>tidak">
							Tidak
						</label>
					</div>
				<?php else: ?>
					<div class="form-group">
						<textarea required class="form-control" name="<?= $row['no_soal'] ?>"></textarea>
					</div>
				<?php endif ?>
			</div>
		<?php endforeach ?>
		<?php if ( $kategori['id_kategori'] <= $jmlkategori ): ?>
			<div class="mt-5 col-12 text-center">
				<button type="submit" class="btn btn-primary btnsubmit">Selanjutnya</button>
			</div>
		<?php endif ?>
	</div>
</form>

<script>
	$("#frmjawab").on("submit",function(){
		$(".btnsubmit").attr("disabled","disabled");
	});
</script>