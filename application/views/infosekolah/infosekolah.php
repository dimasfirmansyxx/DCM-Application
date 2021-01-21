<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Pengaturan Informasi Sekolah</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
            <li class="breadcrumb-item active">Informasi Sekolah</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      
      <div class="row mt-3">
        <section class="col-lg-8">
          <div class="card">
            <div class="card-header bg-secondary">
              Informasi Sekolah
            </div>
            <div class="card-body table-responsive">
              <form id="frminfo">
                <div class="form-group">
                  <label>Nama Sekolah</label>
                  <input type="text" name="nama_sekolah" class="form-control" required value="<?= $this->Clsglobal->site_info("nama_sekolah") ?>">
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <input type="text" name="alamat" class="form-control" required value="<?= $this->Clsglobal->site_info("alamat") ?>">
                </div>
                <div class="form-group">
                  <label>Kepala Sekolah</label>
                  <input type="text" name="kepala_sekolah" class="form-control" required value="<?= $this->Clsglobal->site_info("kepala_sekolah") ?>">
                </div>
                <div class="form-group">
                  <label>Guru Pembimbing</label>
                  <input type="text" name="guru_pembimbing" class="form-control" required value="<?= $this->Clsglobal->site_info("guru_pembimbing") ?>">
                </div>
                <div class="form-group">
                  <label>Teks Dasbor</label>
                  <textarea id="summernote" name="welcome_message" required>
                    <?= $this->Clsglobal->site_info("welcome_message") ?>
                  </textarea>
                </div>
                <button type="submit" class="btn btn-sm btn-block btn-primary btnsave">
                  Submit
                </button>
              </form>
            </div>
          </div>
        </section>

        <section class="col-lg-4">
          <div class="card">
            <div class="card-header bg-secondary">
              Logo Sekolah
            </div>
            <div class="card-body text-center">
              <img src="<?= base_url() ?>assets/img/core/logo.png" class="img-fluid">
            </div>
            <div class="card-footer">
              <form id="frmlogo">
                <div class="form-group">
                  <input type="file" name="logo" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-sm btn-block btn-primary btnupload">
                  Upload
                </button>
              </form>
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

    function setTxt(attribute,word) {
      $(attribute).attr("disabled","disabled");
      $(attribute).val(word);
    }

    function unsetTxt(attribute,word) {
      $(attribute).removeAttr("disabled");
      $(attribute).val(word);
    }

    $("#frminfo").on("submit",function(e){
      e.preventDefault();
      setButton(".btnsave","Submitting...");
      $.ajax({
        url : base_url + "config_sekolah/change_info",
        data : new FormData(this),
        cache : false,
        contentType : false,
        processData : false,
        type : "post",
        dataType : "text",
        success : function(result) {
          if ( result == 0 ) {
            swal("Sukses!","Sukses mengubah informasi sekolah","success");
            setTimeout(function(){
              window.location = base_url + "config_sekolah";
            },1000);
          } else {
            swal("Gagal!","Kesalahan pada server","error");
          }
          unsetButton(".btnsave","Submit");
        }
      });
    });

    $("#frmlogo").on("submit",function(e){
      e.preventDefault();
      setButton(".btnupload","Uploading...");
      $.ajax({
        url : base_url + "config_sekolah/change_logo",
        data : new FormData(this),
        cache : false,
        contentType : false,
        processData : false,
        type : "post",
        dataType : "text",
        success : function(result) {
          if ( result == 0 ) {
            swal("Sukses!","Sukses mengubah logo sekolah","success");
            setTimeout(function(){
              window.location = base_url + "config_sekolah";
            },1000);
          } else if ( result == 5 ) {
            swal("Gagal!","Pastikan format png","warning");
          } else {
            swal("Gagal!","Kesalahan pada server","error");
          }
          unsetButton(".btnupload","Upload");
        }
      });
    });

    $('#summernote').summernote()

  });
</script>