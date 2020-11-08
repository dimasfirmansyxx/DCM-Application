<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Dasbor</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dasbor</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <?php if ( $this->Clsglobal->user_info($this->session->user_id)["privilege"] == "siswa" ): ?>
        <div class="row">
          <section class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <?= $this->Clsglobal->site_info("welcome_message") ?>
              </div>
            </div>
          </section>
        </div>
      <?php else: ?>
        <div class="row">
        
          <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $jmlsiswa ?></h3>

                <p>Siswa</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $jmlkelas ?></h3>

                <p>Kelas</p>
              </div>
              <div class="icon">
                <i class="fas fa-building"></i>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= $jmlsoal ?></h3>

                <p>Soal</p>
              </div>
              <div class="icon">
                <i class="fas fa-tasks"></i>
              </div>
            </div>
          </div>

        </div>
      <?php endif ?>

    </div>
  </section>
</div>
