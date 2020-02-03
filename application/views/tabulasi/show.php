<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>No.</th>
      <th>Nama</th>
      <th>Jenis Kelamin</th>
      <th>Kelas</th>
      <?php foreach ($kategori_soal as $kategori): ?>
        <?php $get_num_soal = $this->tabulasi->num_soal($kategori['id_kategori']); ?>
        <?php if ( !($kategori['id_kategori'] == 13) ): ?>
          <th colspan="<?= $get_num_soal + 1 ?>">
            <?= $this->Clsglobal->romawi($kategori['id_kategori']) ?>. 
            <?= $kategori['nama_kategori'] ?>
          </th>
        <?php endif ?>
      <?php endforeach ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($all_kelas as $kelas): ?>
      <?php 
        $all_siswa = $this->tabulasi->get_siswa($kelas['id_kelas']);
        $get_num_siswa = $this->tabulasi->get_num_siswa($kelas['id_kelas']);
      ?>
      <?php foreach ($all_siswa as $siswa): ?>
        <?php 
          $get_jawaban = $this->tabulasi->get_jawaban($siswa['id_siswa']);
          $iteration = 1;
          $terjawab = 0;
        ?>
        <tr>
          <td><?= $siswa['no_urut'] ?></td>
          <td><?= $siswa['nama_siswa'] ?></td>
          <td><?= ucwords($siswa['jenis_kelamin']) ?></td>
          <td><?= $this->kelas->get_kelas($siswa['id_kelas'])['kelas'] ?></td>
          <?php foreach ($get_jawaban as $jawaban): ?>
            <?php 
              $get_soal = $this->soal->get_soal($jawaban['no_soal']);
              $get_num_soal = $this->tabulasi->num_soal($get_soal['id_kategori']);
            ?>

            <?php if ( !($get_soal['id_kategori'] == 13) ): ?>
              <?php if ( $jawaban['remarks'] == "y" ): ?>
                <td><?= $jawaban['no_soal'] ?></td>
                <?php $terjawab++ ?>
              <?php elseif ( $jawaban['remarks'] == "g" ): ?>
                <td></td>
              <?php else: ?>
                <td><?= $jawaban['remarks'] ?></td>
              <?php endif ?>

              <?php if ( $iteration == $get_num_soal ): ?>
                <td class="bg-primary"><?= $terjawab ?></td>
                <?php 
                  $iteration = 1; 
                  $terjawab = 0;
                ?>
              <?php else: ?>
                <?php $iteration++ ?>
              <?php endif ?>
            <?php endif ?>

          <?php endforeach ?>
        </tr>
      
      <?php endforeach ?>
      <tr>
        <td colspan="4" class="bg-primary">
          Jumlah Siswa Kelas : <?= $kelas['kelas'] ?>
        </td>
        <td colspan="500" class="bg-primary"><?= $get_num_siswa ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>