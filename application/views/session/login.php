<div class="login-box mt-5">
  <div class="login-logo">
    <a href="#"><b>DCM</b>App</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Masuk untuk memulai sesi</p>

      <form id="frmlogin">
        <div class="input-group mb-3">
          <input type="email" name="username" class="form-control" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
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
    $("body").addClass("login-page");
    $("body").removeClass("sidebar-mini");
    $("body").removeClass("layout-fixed")
  });
</script>