<h3>
	<?= $this->Clsglobal->romawi($kategori['id_kategori']) ?>. 
	<?= strtoupper($kategori['nama_kategori']) ?>
	<?php 
		if ( $kategori['id_kategori'] == 1 ) {
			unset($_SESSION['jawaban_menyusahkan']);
			unset($_SESSION['jawaban_belumtercantum']);
		}
	?>
</h3>
<form id="frmjawab">
	<div class="row">
		<?php foreach ($soal as $row): ?>
			<div class="col-md-6">
				<div class="form-check">

					<?php if ( $_SESSION["jawaban_checkbox"][$row['no_soal']]=== "y" ): ?>
						<input class="form-check-input cekbok" type="checkbox" id="check<?= $row['no_soal'] ?>" data-status="uncheck" data-id="<?= $row['no_soal'] ?>" checked>
						<label class="form-check-label" for="check<?= $row['no_soal'] ?>">
							<?= $row['no_soal'] . ". " . $row['soal'] ?>
						</label>
					<?php else: ?>
						<input class="form-check-input cekbok" type="checkbox" id="check<?= $row['no_soal'] ?>" data-status="uncheck" data-id="<?= $row['no_soal'] ?>">
						<label class="form-check-label" for="check<?= $row['no_soal'] ?>">
							<?= $row['no_soal'] . ". " . $row['soal'] ?>
						</label>
					<?php endif ?>
				</div>
				<?php if ( $row['jenis'] == "check" ): ?>
					<?php if ( $_SESSION["jawaban_checkbox"][$row['no_soal']] == "y" ): ?>
						<div class="form-check" style="display: none;">
							<input class="form-check-input" type="radio" name="<?= $row['no_soal'] ?>" id="<?= $row['no_soal'] ?>ya" value="y" required checked>
							<label class="form-check-label" for="<?= $row['no_soal'] ?>ya">
								Ya
							</label>
						</div>
						<div class="form-check" style="display: none;">
							<input class="form-check-input" type="radio" name="<?= $row['no_soal'] ?>" id="<?= $row['no_soal'] ?>tidak" value="g" required>
							<label class="form-check-label" for="<?= $row['no_soal'] ?>tidak">
								Tidak
							</label>
						</div>
					<?php else: ?>
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
					<?php endif ?>
				<?php else: ?>
					<div class="form-group">
						<textarea required class="form-control" name="<?= $row['no_soal'] ?>">
							<?php if ( $row['no_soal'] == 241 && isset($_SESSION['jawaban_belumtercantum']) ): ?>
								<?= $_SESSION['jawaban_belumtercantum'] ?>
								<?php unset($_SESSION['jawaban_belumtercantum']); ?>
							<?php elseif ( $row['no_soal'] == 242  && isset($_SESSION['jawaban_menyusahkan']) ): ?>
								<?= $_SESSION['jawaban_menyusahkan'] ?>
								<?php unset($_SESSION['jawaban_menyusahkan']); ?>
							<?php endif ?>
						</textarea>
					</div>
				<?php endif ?>
			</div>
		<?php endforeach ?>
	</div>
	<?php if ( $kategori['id_kategori'] <= $jmlkategori ): ?>
		<?php if ( !($kategori['id_kategori'] == "13") ): ?>
			<div class="row mt-4">
				<div class="col-6">
					<div class="form-group">
						<label>Masalah apakah yang belum tercantum diatas ?</label>
						<textarea class="form-control" name="belumtercantum"></textarea>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label>Masalah apakah yang paling menyusahkan ?</label>
						<textarea class="form-control" name="menyusahkan"></textarea>
					</div>
				</div>
			</div>
		<?php endif ?>
		<div class="mt-2 col-12 text-center">
			<?php if ( $kategori['id_kategori'] != 1 ): ?>
				<button type="button" class="btn btn-secondary btnsebelum">Sebelumnya</button>
			<?php else: ?>
				<button type="button" class="btn btn-secondary btnsebelum" disabled="">Sebelumnya</button>
			<?php endif ?>
			<button type="submit" class="btn btn-primary btnsubmit">Selanjutnya</button>
		</div>
	<?php endif ?>
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
		$(".btnsebelum").attr("disabled","disabled");
		window.location = "#";
	});

	$(".btnsebelum").on("click",function(){
		$(".btnsubmit").attr("disabled","disabled");
		setTimeout(function(){
			$(".btnsebelum").attr("disabled","disabled");
		},100);
		window.location = "#";
	});
</script>