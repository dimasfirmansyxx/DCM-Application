<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Pengaturan Akun</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
            <li class="breadcrumb-item active">Akun</li>
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
              Informasi Akun
            </div>
            <div class="card-body table-responsive">
              <div class="row mb-3">
                <div class="col-2">Nama</div>
                <div class="col-10">: <?= $userinfo['nama'] ?></div>
                <div class="col-2">Username</div>
                <div class="col-10">: <?= $userinfo['username'] ?></div>
              </div>

              <button id="btnnama" class="btn btn-primary">Ganti Nama</button>
              <button id="btnusername" class="btn btn-primary">Ganti Username</button>
              <button id="btnpassword" class="btn btn-primary">Ganti Password</button>
            </div>
          </div>
        </section>

        <section class="col-lg-4">
          <div class="card">
            <div class="card-header bg-secondary">
              Foto Profil
            </div>
            <div class="card-body text-center">
              <img src="<?= base_url() ?>assets/img/core/logo.png" class="img-fluid">
            </div>
            <div class="card-footer">
              <form id="frmphoto">
                <div class="form-group">
                  <input type="file" name="foto" class="form-control" required>
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

<div class="modal fade" id="namamodal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ganti Nama</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmnama">
          <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required value="<?= $userinfo['nama'] ?>" id="txtnama" autocomplete="off">
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btnsave">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    var base_url = "<?= base_url() ?>";
    var user_id = "<?= $userinfo['id_user'] ?>";

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

    $("#btnnama").on("click",function(){
      $("#namamodal").modal("show");
    });

    $("#frmnama").on("submit",function(e){
      e.preventDefault();
      var newname = $("#txtnama").val();
      setButton(".btnsave","Saving...");
      $.ajax({
        url : base_url + "myprofile/change_name",
        data : { id_user : user_id, nama : newname },
        type : "post",
        dataType : "text",
        success : function(result) {
          if ( result == 0 ) {
            swal("Sukses","Sukses mengubah nama","success");
            setTimeout(function(){
              window.location = base_url + "myprofile";
            },1000);
          } else {
            swal("Error","Kesalahan pada server","error");
          }
          setButton(".btnsave","Save changes");
        }
      });
    });

  });
</script>