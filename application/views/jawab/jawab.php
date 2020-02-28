<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Soal</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Soal</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="row mt-3">
        <section class="col-lg-12">
          <div class="card">
            <div class="card-header bg-secondary">
              Pertanyaan
            </div>
            <div class="card-body">
              <button class="btn btn-primary btnselesai btn-block">Selesai</button>
              <div id="soal_area"></div>
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
    var id_user = <?= $this->session->user_id ?>;
    var jmlkategori = <?= $jmlkategori ?>;
    var no_kategori = 1;
    var jawaban = {};

    function setButton(attribute,word) {
      $(attribute).attr("disabled","disabled");
      $(attribute).html(word);
    }

    function unsetButton(attribute,word) {
      $(attribute).removeAttr("disabled");
      $(attribute).html(word);
    }

    function load(no_kategori){
      $("#soal_area").load(base_url + "jawab/lembar/" + no_kategori);
    }

    load(no_kategori);
    $(".btnselesai").css("display","none");

    $("#soal_area").on("submit","#frmjawab",function(e){
      e.preventDefault();
      $.ajax({
        url : base_url + "jawab/push_answer",
        data : new FormData(this),
        cache : false,
        contentType : false,
        processData : false,
        type : "post",
        dataType : "json",
        success : function(result) {
          jawaban[no_kategori] = result;
          if ( no_kategori < jmlkategori ) {
            no_kategori = no_kategori + 1;
            load(no_kategori);
          } else {
            $("#soal_area").html("");
            $(".btnselesai").css("display","block");
          }
          console.log(jawaban);
        }
      });
    });

    $(".btnselesai").on("click",function(){
      setButton(".btnselesai","Submitting...");
      $.ajax({
        url : base_url + "jawab/selesai",
        data : { id_user : id_user, jawaban : jawaban },
        type : "post",
        dataType : "text",
        success : function(result) {
          if ( result == 0 ) {
            swal("Tes Berhasil","Tes telah berhasil dilakukan. Akan dialihkan untuk keluar.","success");
            setTimeout(function(){
              window.location = base_url + "auth/logout";
            },1000);
          } else {
            swal("Gagal","Kesalahan pada server!","error");
          }
          unsetButton(".btnselesai","Selesai");
        }
      });
    });

  });
</script>