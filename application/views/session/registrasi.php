<div class="login-box mt-5">
  <div class="login-logo">
    <a href="#"><b>DCM</b>App</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <div class="alert alert-danger" role="alert">
        <strong>Gagal!</strong> Username/Password salah
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

    $("#frmlogin").on("submit",function(e){
      e.preventDefault();
      setButton(".btnlogin","Cek Data ...");
      $.ajax({
        
      });
    });
  });
</script>