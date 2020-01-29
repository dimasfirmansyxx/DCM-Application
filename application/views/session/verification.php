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
      <p class="login-box-msg">Lakukan verifikasi data diri</p>
      <form id="frmverif">
        <div class="input-group mb-3">
          <input type="text" name="tempat_lahir" class="form-control" placeholder="Tempat Lahir" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-map-marker-alt"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="date" name="tgl_lahir" class="form-control dtpckr" placeholder="Tanggal Lahir" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-calendar-week"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block btnsubmit">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    var base_url = "<?= base_url() ?>";
    var id_user = "<?= $this->Clsglobal->user_info($this->session->user_id)['id_user'] ?>";

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

    $("#frmverif").on("submit",function(e){
      e.preventDefault();
      var data = new FormData(this);
      data.append("id_user",id_user);
      setButton(".btnsubmit","Submitting...");
      hide_alert();
      $.ajax({
        url : base_url + "auth/verification/do",
        data : data,
        processData: false,
        cache : false,
        contentType : false,
        type : "post",
        success : function(result) {
          if ( result == 0 ) {
            do_alert("Sukses melakukan verifikasi data. Anda akan dialihkan","success");
            setTimeout(function(){
              window.location = base_url + "beranda";
            },500);
          } else {
            swal("Gagal!","Kesalahan pada server!","error");
          }
          unsetButton(".btnsubmit","Submit");
        }
      });
    });
  });
</script>