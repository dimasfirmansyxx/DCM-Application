<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Tabulasi</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item active">Tabulasi Hasil</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">
          <a href="<?= base_url() ?>tabulasi/print_laporan" class="btn btn-success btn-sm">
            <i class="fas fa-arrow-up"></i> Export Laporan ke Excel
          </a>
        </div>
      </div>

      <div class="row mt-3">
        <section class="col-lg-12">
          <div class="card">
            <div class="card-header bg-secondary">
              Tabulasi Hasil
            </div>
            <div class="card-body table-responsive" id="data_area">
              <p class="text-center">Sedang Memuat ...</p>
            </div>
          </div>
        </section>
      </div>

    </div>
  </section>
</div>

<script>
  $(document).ready(function() {
    var base_url = "<?= base_url() ?>";

    function setButton(attribute,word) {
      $(attribute).attr("disabled","disabled");
      $(attribute).html(word);
    }

    function unsetButton(attribute,word) {
      $(attribute).removeAttr("disabled");
      $(attribute).html(word);
    }

    function load(){
      $("#data_area").load(base_url + "tabulasi/show");
    }

    load();

  });
</script>