<table class="">
  <tr>
    <td width="200">Nama Sekolah</td>
    <td width="500">: <?= $this->Clsglobal->site_info("nama_sekolah") ?></td>
  </tr>
  <tr>
    <td width="200">Alamat</td>
    <td width="500">: <?= $this->Clsglobal->site_info("alamat") ?></td>
  </tr>
  <tr>
    <td width="200">Nama Guru Pembimbing</td>
    <td width="500">: <?= $this->Clsglobal->site_info("guru_pembimbing") ?></td>
  </tr>
  <tr>
    <td width="200">Nama Kepala Sekolah</td>
    <td width="500">: <?= $this->Clsglobal->site_info("kepala_sekolah") ?></td>
  </tr>
</table>
<table border="1" class="table table-bordered table-hover mt-3">
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
        $jmltopik = [];
        foreach ($kategori_soal as $kategori) {
          if ( !($kategori['id_kategori'] == 13) ) {
            $jmltopik[$kategori['id_kategori']] = 0;
          }
        }
      ?>
      <?php foreach ($all_siswa as $siswa): ?>
        <?php 
          $get_jawaban = $this->tabulasi->get_jawaban($siswa['id_siswa']);
          $iteration = 1;
          $terjawab = 0;
          unset($sisa_kosong);
        ?>
        <tr>
          <td><?= $siswa['no_urut'] ?></td>
          <td><?= $siswa['nama_siswa'] ?></td>
          <td><?= ucwords($siswa['jenis_kelamin']) ?></td>
          <td><?= $this->kelas->get_kelas($siswa['id_kelas'])['kelas'] ?></td>
          <?php if ( count($get_jawaban) > 0 ): ?>
            <?php foreach ($get_jawaban as $jawaban): ?>
              <?php 
                $get_soal = $this->soal->get_soal($jawaban['no_soal']);
                $get_num_soal = $this->tabulasi->num_soal($get_soal['id_kategori']);
                if ( !isset($sisa_kosong) ) {
                  $sisa_kosong = $get_num_soal;
                }
              ?>

              <?php if ( !($get_soal['id_kategori'] == 13) ): ?>
                <?php if ( $jawaban['remarks'] == "y" ): ?>
                  <td><?= $jawaban['no_soal'] ?></td>
                  <?php 
                    $terjawab++;
                    $sisa_kosong--;
                  ?>
                <?php endif ?>

                <?php if ( $iteration == $get_num_soal ): ?>
                  <?php for($i = 0;$i < $sisa_kosong; $i++ ): ?>
                    <td></td>
                  <?php endfor ?>
                  <td class="bg-primary"><?= $terjawab ?></td>
                  <?php 
                    $jmltopik[$get_soal['id_kategori']] += $terjawab; 
                    $iteration = 1; 
                    $terjawab = 0;
                    unset($sisa_kosong);
                  ?>
                <?php else: ?>
                  <?php $iteration++ ?>
                <?php endif ?>
              <?php endif ?>

            <?php endforeach ?>
          <?php else: ?>
            <?php 
              $get_jml_soal = $this->tabulasi->get_jml_soal();
              $iteration = 1;
            ?>
            <?php for ($i=0; $i < $get_jml_soal; $i++) : ?>
              <td></td>
              <?php if ( $iteration == 20 ): ?>
                <td class="bg-primary">0</td>
                <?php $iteration = 1 ?>
              <?php else: ?>
                <?php $iteration += 1 ?>
              <?php endif ?>
            <?php endfor ?>
          <?php endif ?>
        </tr>
      
      <?php endforeach ?>
      <tr class="bg-primary">
        <td colspan="3">
          Jumlah Siswa Kelas <?= $kelas['kelas'] ?> : 
        </td>
        <td><?= $get_num_siswa ?></td>
        <?php foreach ($jmltopik as $jml): ?>
          <td colspan="20"></td>
          <td><?= $jml ?></td>
        <?php endforeach ?>
      </tr>
    <?php endforeach ?>
  </tbody>
  <tfoot>
    <tr class="bg-warning">
      <td colspan="3">JUMLAH PARALEL</td>
      <td><?= $this->tabulasi->get_jml_siswa() ?></td>
      <?php foreach ($kategori_soal as $kategori): ?>
        <?php if ( !($kategori['id_kategori'] == 13) ): ?>
          <?php $score_paralel = $this->tabulasi->get_score_paralel($kategori['id_kategori']) ?>
          <td colspan="20"></td>
          <td><?= $score_paralel ?></td>
        <?php endif ?>
      <?php endforeach ?>
    </tr>
  </tfoot>
</table>
