<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="index3.html" class="brand-link text-center">
    <span class="brand-text font-weight-light">DCM - App</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= base_url() ?>assets/img/user-ava/<?= $this->Clsglobal->user_info($this->session->user_id)["profile_photo"] ?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= $this->Clsglobal->user_info($this->session->user_id)["nama"] ?></a>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="<?= base_url() ?>beranda" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dasbor
            </p>
          </a>
        </li>

        <?php if ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "siswa" ): ?>

        <li class="nav-item">
          <a href="<?= base_url() ?>jawab" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
              Soal
            </p>
          </a>
        </li>

        <?php elseif ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "admin" ): ?>
          <li class="nav-header">PENGATURAN</li>
          <!-- <li class="nav-item">
            <a href="<?= base_url() ?>soal" class="nav-link">
              <i class="nav-icon fas fa-tasks"></i>
              <p>
                Input Soal
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url() ?>soal/kategori" class="nav-link">
              <i class="nav-icon fas fa-tag"></i>
              <p>
                Kategori Soal
              </p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="<?= base_url() ?>siswa" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Manajemen Siswa
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url() ?>kelas" class="nav-link">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Manajemen Kelas
              </p>
            </a>
          </li>

          <li class="nav-header">LAPORAN</li>
          <li class="nav-item">
            <a href="<?= base_url() ?>tabulasi" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Tabulasi Hasil
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url() ?>profil_individu" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Profil Individu
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url() ?>profil_kelas" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Profil Kelas
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-diagnoses"></i>
              <p>
                Analisis Butir Soal
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= base_url() ?>butirsoal/paralel" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Paralel</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url() ?>butirsoal/kelas" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Per Kelas</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-diagnoses"></i>
              <p>
                Analisis Topik
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= base_url() ?>topik/paralel" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Paralel</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url() ?>topik/kelas" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Per Kelas</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-header">PENGATURAN APLIKASI</li>
          <li class="nav-item">
            <a href="<?= base_url() ?>config_sekolah" class="nav-link">
              <i class="nav-icon fas fa-industry"></i>
              <p>
                Info Sekolah
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url() ?>admin" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Manajemen Admin
              </p>
            </a>
          </li>
        <?php endif ?>

      </ul>
    </nav>
  </div>
</aside>

<script>
  $(document).ready(function(){
    $(".main-sidebar .nav-link").on("click",function(){
      if ( !($(this).attr("href") == "#") ) {
        $(".loading-area").addClass("execute-loading");
      }
    });
  });
</script>