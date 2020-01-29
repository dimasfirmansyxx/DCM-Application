<div class="login-box mt-5">
  <div class="login-logo">
    <img src="<?= base_url() ?>assets/img/core/logo.png" height="100">
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <div class="alert" role="alert">
        #alertarea
      </div>
      <p class="login-box-msg">Data Diri</p>
      <div class="form-group mb-3">
        <label>Nomor Urut</label>
        <input type="number" name="no_urut" class="form-control txtnourut" required>
      </div>
      <div class="form-group mb-3">
        <label>Kelas</label>
        <select name="kelas" class="form-control cmbkelas">
          <?php foreach ($kelas as $row): ?>
            <option value="<?= $row['id_kelas'] ?>"><?= $row['kelas'] ?></option>
          <?php endforeach ?>
        </select>
      </div>
      <form id="frmregister">
        <div class="form-group mb-3">
          <label>Nama Siswa</label>
          <input type="text" name="nama_siswa" class="form-control txtnama" required readonly>
        </div>
        <p class="login-box-msg">Informasi Login</p>
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
            <button type="submit" class="btn btn-primary btn-block btnregister">Daftar</button>
          </div>
        </div>
      </form>

      <p class="mb-0">
        Sudah memiliki akun ?
        <a href="<?= base_url() ?>auth/login" class="text-center">Login</a>
      </p>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    var base_url = "<?= base_url() ?>";
    var id_siswa;

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

    $(".txtnourut").on("change",function(){
      var no_urut = $(".txtnourut").val();
      var kelas = $(".cmbkelas").val();
      if ( no_urut.trim() == "" ) {
        id_siswa = "";
        $(".txtnama").val("");
      } else {
        $.ajax({
          url : base_url + "auth/get_siswa",
          data : { no_urut : no_urut, kelas : kelas },
          type : "post",
          dataType : "json",
          success : function(result) {
            if ( result != 3 ) {
              id_siswa = result.id_siswa;
              $(".txtnama").val(result.nama_siswa);
            } else {
              id_siswa = "";
              $(".txtnama").val("");
            }
          }
        });
      }
    });

    $(".cmbkelas").on("change",function(){
      var no_urut = $(".txtnourut").val();
      var kelas = $(".cmbkelas").val();
      if ( no_urut.trim() == "" ) {
        id_siswa = "";
        $(".txtnama").val("");
      } else {
        $.ajax({
          url : base_url + "auth/get_siswa",
          data : { no_urut : no_urut, kelas : kelas },
          type : "post",
          dataType : "json",
          success : function(result) {
            if ( result != 3 ) {
              id_siswa = result.id_siswa;
              $(".txtnama").val(result.nama_siswa);
            } else {
              id_siswa = "";
              $(".txtnama").val("");
            }
          }
        });
      }
    });

    $("#frmregister").on("submit",function(e){
      e.preventDefault();
      if ( $(".txtnama").val().trim() == "" ) {
        swal("Belum lengkap","Masukkan nomor urut dan kelas dengan benar","warning");
      } else {
        hide_alert();
        setButton(".btnregister","Mendaftar...");
        var data = new FormData(this);
        data.append("id_siswa",id_siswa);
        $.ajax({
          url : base_url + "auth/register_act",
          data : data,
          processData : false,
          cache : false,
          contentType : false,
          type : "post",
          dataType : "text",
          success : function(result) {
            if ( result == 0 ) {
              do_alert("Registrasi Sukses, sedang mengalihkan ...","success");
              setTimeout(function(){
                window.location = base_url + "auth/login";
              },500);
            } else if ( result == 201 ) {
              do_alert("Siswa telah melakukan registrasi","danger");
            } else if ( result == 202 ) {
              do_alert("Username sudah ada","danger");
            } else {
              swal("Gagal!","Kesalahan pada server","error");
            }
            unsetButton(".btnregister","Daftar");
          }
        });
      }
    });
  });
</script>