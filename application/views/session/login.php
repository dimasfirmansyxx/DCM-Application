<div class="container mt-5">
  <div class="row">
    <div class="col-md-7 my-auto">
      <div class="card">
        <div class="card-body">
          <?= $this->Clsglobal->site_info("login_message") ?>
        </div>
      </div>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-4">
      <div class="login-box">
        <div class="login-logo">
          <img src="<?= base_url() ?>assets/img/core/logo.png" height="100">
        </div>
        <!-- /.login-logo -->
        <div class="card">
          <div class="card-body login-card-body">
            <div class="alert" role="alert">
              #alertarea
            </div>
            <p class="login-box-msg">Masuk untuk memulai sesi</p>
            <form id="frmlogin">
              <div class="input-group mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-user"></span>
                  </div>
                </div>
              </div>
              <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-block btnlogin">Masuk</button>
                </div>
              </div>
            </form>

            <p class="mb-0">
              Belum memiliki akun ?
              <a href="<?= base_url() ?>auth/register" class="text-center">Registrasi</a>
            </p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    var base_url = "<?= base_url() ?>";

    $("body").addClass("login-page");
    $("body").removeClass("sidebar-mini");
    $("body").removeClass("layout-fixed");

    function setButton(attribute,word) {
      $(attribute).attr("disabled","disabled");
      $(attribute).html(word);
    }

    function unsetButton(attribute,word) {
      $(attribute).removeAttr("disabled");
      $(attribute).html(word);
    }

    function do_alert(msg,type){
      $(".alert").html(msg);
      $(".alert").addClass("alert-"+type);
      $(".alert").css("display","block");
    }

    function hide_alert(){
      $(".alert").removeClass("alert-success");
      $(".alert").removeClass("alert-danger");
      $(".alert").css("display","none");
    }

    hide_alert();

    $("#frmlogin").on("submit",function(e){
      e.preventDefault();
      setButton(".btnlogin","Cek Data ...");
      hide_alert();
      $.ajax({
        url : base_url + "auth/login_check",
        data : new FormData(this),
        processData : false,
        contentType : false,
        cache : false,
        type : "post",
        dataType : "text",
        success : function(result) {
          if ( result == 0 ) {
            do_alert("Login sukses!, anda akan dialihkan...","success");
            setTimeout(function(){
              window.location = base_url + "beranda";
            });
          } else if ( result == 4 ) {
            do_alert("Gagal!, username/password salah","danger");
          } else if ( result == 2 ) {
            swal("Gagal!","Siswa sudah melakukan tes","warning");
          } else {
            swal("Error","Kesalahan pada server","error");
          }
          unsetButton(".btnlogin","Masuk");
        }
      });
    });
  });
</script>