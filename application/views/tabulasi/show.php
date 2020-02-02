<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>No.</th>
      <th>Nama</th>
      <th>Jenis Kelamin</th>
      <th>Kelas</th>
      <?php foreach ($kategori_soal as $kategori): ?>
        <?php 
          $get_num_soal = $this->tabulasi->num_soal($kategori['id_kategori']);
        ?>
        <th colspan="<?= $get_num_soal ?>">
          <?= $this->Clsglobal->romawi($kategori['id_kategori']) ?>. 
          <?= $kategori['nama_kategori'] ?>
        </th>
      <?php endforeach ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($all_kelas as $kelas): ?>
      <?php $all_siswa = $this->tabulasi->get_siswa($kelas['id_kelas']) ?>
      <?php foreach ($all_siswa as $siswa): ?>
        <?php $get_jawaban = $this->tabulasi->get_jawaban($siswa['id_siswa']) ?>
        <tr>
          <td><?= $siswa['no_urut'] ?></td>
          <td><?= $siswa['nama_siswa'] ?></td>
          <td><?= ucwords($siswa['jenis_kelamin']) ?></td>
          <td><?= $this->kelas->get_kelas($siswa['id_kelas'])['kelas'] ?></td>
          <?php foreach ($get_jawaban as $jawaban): ?>
            <?php if ( $jawaban['remarks'] == "y" ): ?>
              <td><?= $jawaban['no_soal'] ?></td>
            <?php elseif ( $jawaban['remarks'] == "g" ): ?>
              <td></td>
            <?php else: ?>
              <td><?= $jawaban['remarks'] ?></td>
            <?php endif ?>
          <?php endforeach ?>
        </tr>
      
      <?php endforeach ?>
      <tr>
        <td></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>