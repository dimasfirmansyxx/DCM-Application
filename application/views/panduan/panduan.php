<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Panduan Aplikasi</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Panduan</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row mt-3">

        <section class="col-lg-12">
          <div class="card">
            <div class="card-header bg-secondary">
              <h3 class="card-title">Panduan</h3>
              <?php if ($this->Clsglobal->user_info($this->session->user_id)["privilege"] == "admin"): ?>
                <div class="card-tools">
                  <a href="<?= base_url() ?>panduan/edit" class="text-white">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                </div>
              <?php endif ?>
            </div>
            <div class="card-body">
              <?= $this->Clsglobal->site_info("panduan_app") ?>
            </div>
          </div>
        </section>
      </div>

    </div>
  </section>
</div>
