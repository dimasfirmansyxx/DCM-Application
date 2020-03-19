<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Profil Kelas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Laporan</a></li>
            <li class="breadcrumb-item active">Profil Kelas</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row mt-3">
        <section class="col-lg-2">
          <div class="card">
            <div class="card-header bg-secondary">
              Pilih Kelas
            </div>
            <div class="card-body">
              <form id="frmpilih" class="mb-2">
                <div class="form-group">
                  <label>Kelas</label>
                  <select class="form-control cmbkelas" name="kelas">
                    <?php foreach ($all_kelas as $kelas): ?>
                      <option value="<?= $kelas['id_kelas'] ?>"><?= $kelas['kelas'] ?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-block btnshow">Lihat</button>
              </form>
              <button type="button" class="btn btn-success btn-sm btn-block btnprint">
                Export ke Excel
              </button>
            </div>
          </div>
        </section>

        <section class="col-lg-10">
          <div class="card">
            <div class="card-header bg-secondary">
              Profil Kelas
            </div>
            <div class="card-body" id="data_area">
              
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

    $("#frmpilih").on("submit",function(e){
      e.preventDefault();
      $("#data_area").html("<center>Sedang memuat...</center>");
      var kelas = $(".cmbkelas").val();
      $("#data_area").load(base_url + "profil_kelas/show/" + kelas);
    });

    $(".btnprint").on("click",function(){
      var kelas = $(".cmbkelas").val();
      window.location = base_url + "profil_kelas/print_laporan/" + kelas;
    });

  });
</script>