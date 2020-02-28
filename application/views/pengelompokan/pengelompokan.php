<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Pengelompokan Siswa per Masalah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item active">Pengelompokan Siswa per Masalah</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">
          <button type="button" class="btn btn-success btn-sm btnexport">
            <i class="fas fa-arrow-up"></i> Export Laporan ke Excel
          </button>
        </div>
        <div class="col-12 mt-3">
          <select class="form-control cmbkelas" name="kelas">
            <option value="0">--- Pilih kelas ---</option>
            <?php foreach ($all_kelas as $kelas): ?>
              <option value="<?= $kelas['id_kelas'] ?>"><?= $kelas['kelas'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>

      <div class="row mt-3">
        <section class="col-lg-12">
          <div class="card">
            <div class="card-header bg-secondary">
              Kelompok
            </div>
            <div class="card-body" id="data_area">
              <p class="text-center">Pilih kelas terlebih dahulu</p>
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

    function load(kelas) {
      $("#data_area").load(base_url + "pengelompokan/show/" + kelas);
    }
  
    $(".cmbkelas").on("change",function(){
      var id = $(this).val();
      if ( id == 0 ) {
        $("#data_area").html("<p class='text-center'>Pilih kelas terlebih dahulu</p>")
      } else {
        $("#data_area").html("<p class='text-center'>Sedang memuat...</p>")
        load(id);
      }
    });

    $(".btnexport").on("click",function(){
      var id = $(".cmbkelas").val();
      if ( id == 0 ) {
        swal("Gagal","Pilih kelas terlebih dahulu","warning");
      } else {
        window.location = base_url + "pengelompokan/print_laporan/" + id;
      }
    });

  });
</script>