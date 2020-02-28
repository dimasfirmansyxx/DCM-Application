<h3>
	<?= $this->Clsglobal->romawi($kategori['id_kategori']) ?>. 
	<?= strtoupper($kategori['nama_kategori']) ?>
</h3>

<form id="frmjawab">
	<div class="row">
		<?php foreach ($soal as $row): ?>
			<div class="col-md-6">
				<div class="form-check">
				  <input class="form-check-input cekbok" type="checkbox" id="check<?= $row['no_soal'] ?>" data-status="uncheck" data-id="<?= $row['no_soal'] ?>">
				  <label class="form-check-label" for="check<?= $row['no_soal'] ?>">
					<?= $row['no_soal'] . ". " . $row['soal'] ?>
				  </label>
				</div>
				<?php if ( $row['jenis'] == "check" ): ?>
					<div class="form-check" style="display: none;">
						<input class="form-check-input" type="radio" name="<?= $row['no_soal'] ?>" id="<?= $row['no_soal'] ?>ya" value="y" required>
						<label class="form-check-label" for="<?= $row['no_soal'] ?>ya">
							Ya
						</label>
					</div>
					<div class="form-check" style="display: none;">
						<input class="form-check-input" type="radio" name="<?= $row['no_soal'] ?>" id="<?= $row['no_soal'] ?>tidak" value="g" required checked>
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
	$(".cekbok").on("change",function(){
		var id = $(this).attr('data-id');
		var check = $("#check" + id).attr("data-status");
		if ( check == "uncheck" ) {
			$("#" + id + "tidak").removeAttr("checked");
			$("#" + id + "ya").attr("checked","checked");
			$("#check" + id).attr("data-status","checked");
		} else {
			$("#" + id + "ya").removeAttr("checked");
			$("#" + id + "tidak").attr("checked","checked");
			$("#check" + id).attr("data-status","uncheck");
		}
	});

	$("#frmjawab").on("submit",function(){
		$(".btnsubmit").attr("disabled","disabled");
		window.location = "#";
	});
</script>