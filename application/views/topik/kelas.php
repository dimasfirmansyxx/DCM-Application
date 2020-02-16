<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Analisis Topik : Perkelas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item"><a href="#">Analisis Topik</a></li>
            <li class="breadcrumb-item active">Perkelas</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <section class="col-lg-3">
          <button type="button" class="btn btn-success btn-sm btn-block btnprint">
            <i class="fas fa-arrow-up"></i> Export Laporan ke Excel
          </button>
        </section>
      </div>

      <div class="row mt-3">
        <div class="col-12">
          <div class="form-group">
            <select class="form-control" id="cmbkelas">
              <option value="0">--- Pilih Kelas ---</option>
              <?php foreach ($all_kelas as $kelas): ?>
                <option value="<?= $kelas['id_kelas'] ?>"><?= $kelas['kelas'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row mt-3">

        <section class="col-lg-12">
          <div class="card">
            <div class="card-header bg-secondary">
              <div class="row">
                <div class="col-md-8">
                    Analisa
                </div>
                <div class="col-md-4">
                  <select class="form-control cmbderajat">
                    <option value="0">Semua</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="card-body" id="data_area">
              <p class="text-center">Pilih Kelas Terlebih dahulu</p>
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

    function load(id_kelas) {
      $("#data_area").load(base_url + "topik/kelas/show/" + id_kelas);
    }

    $("#cmbkelas").on("change",function(){
      $("#data_area").html("<p class='text-center'>Sedang Memuat...</p>");
      var id_kelas = $(this).val();
      load(id_kelas);
    });

    $(".cmbderajat").on("change",function(){
      var kelas = $("#cmbkelas").val();
      var derajat = $(this).val();

      if ( kelas == 0 ) {
        swal("Gagal","Pilih kelas terlebih dahulu","warning");
        $(".cmbderajat").val(0);
      } else {
        $("#data_area").html("<p class='text-center'>Sedang memuat...</p>")
        if ( derajat == 0 ) {
          $("#data_area").load(base_url + "topik/kelas/show/" + kelas);
        } else {
          $("#data_area").load(base_url + "topik/kelas/show/" + kelas + "/" + derajat);
        }
      }
    });

    $(".btnprint").on("click",function(){
      var kelas = $("#cmbkelas").val();
      var derajat = $(".cmbderajat").val();

      if ( kelas == 0 ) {
        swal("Gagal","Pilih kelas terlebih dahulu","warning");
        $(".cmbderajat").val(0);
      } else {
        if ( derajat == 0 ) {
          window.location = base_url + "topik/kelas/print_laporan/" + kelas;
        } else {
          window.location = base_url + "topik/kelas/print_laporan/" + kelas + "/" + derajat;
        }
      }
    });

  });
</script>