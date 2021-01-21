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
            <li class="breadcrumb-item"><a href="#">Panduan</a></li>
            <li class="breadcrumb-item active">Edit</li>
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
              <h3 class="card-title">Edit Panduan</h3>
            </div>
            <div class="card-body">
              <form action="" method="post">
                <textarea class="summernote" name="panduan_app" required>
                  <?= $this->Clsglobal->site_info("panduan_app") ?>
                </textarea>
                <button class="btn btn-primary btn-block" type="submit">Submit</button>
              </form>
            </div>
          </div>
        </section>
      </div>

    </div>
  </section>
</div>

<script>
  $(function(){

    $('.summernote').summernote({
      minHeight: 300
    })

  })
</script>
