<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Analisis Butir Soal : Perkelas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item"><a href="#">Analisis Butir Soal</a></li>
            <li class="breadcrumb-item active">Perkelas</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

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
              Analisa
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
      $("#data_area").load(base_url + "butirsoal/kelas/show/" + id_kelas);
    }

    $("#cmbkelas").on("change",function(){
      var id_kelas = $(this).val();
      load(id_kelas);
    });

  });
</script>