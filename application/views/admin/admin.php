<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Manajemen Admin</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Akun</a></li>
            <li class="breadcrumb-item active">Manajemen Admin</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      
      <div class="row">
        <div class="col-12">
          <button class="btn btn-primary btn-sm btntambah">
            <i class="fas fa-plus"></i> Tambah Admin
          </button>
        </div>
      </div>

      <div class="row mt-3">
        <section class="col-lg-12">
          <div class="card">
            <div class="card-header bg-secondary">
              Daftar Admin
            </div>
            <div class="card-body table-responsive">
              <table class="table table-bordered table-hover" id="data_table">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>

    </div>
  </section>
</div>

<div class="modal fade" id="tambahmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Admin</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmtambah">
          <div class="form-group">
            <label>Kelas</label>
            <input type="text" name="kelas" class="form-control" required autocomplete="off">
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            Tutup
          </button>
          <button type="submit" class="btn btn-primary btn-sm btnsave">
            Simpan
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Kelas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmedit">
          <div class="form-group">
            <label>Kelas</label>
            <input type="text" name="kelas" class="form-control txtkelasedit" required autocomplete="off">
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            Tutup
          </button>
          <button type="submit" class="btn btn-primary btn-sm btnsave">
            Simpan
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    var base_url = "<?= base_url() ?>";
    function loadData() {
      $('#data_table').DataTable({ 
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": base_url + "admin/get_admin",
            "type": "POST"
        },
        "columnDefs": [
        { 
            "targets": [ 0 ], 
            "orderable": false,
        },
        ],
      });
    }

    function reloadData() {
      $("#data_table").DataTable().ajax.reload(null, false );
    }

    loadData();

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

    $(".btntambah").on("click",function(){
      $("#frmtambah").trigger("reset");
      $("#tambahmodal").modal("show");
    });

    $("#frmtambah").on("submit",function(e){
      e.preventDefault();
      setButton(".btnsave","Menyimpan...");
      $.ajax({
        url : base_url + "kelas/insert_kelas",
        data : new FormData(this),
        processData : false,
        contentType : false,
        cache : false,
        type : "post",
        dataType : "text",
        success : function(result) {
          if ( result == 0 ) {
            swal("Sukses","Sukses menambah kelas","success");
            reloadData();
            $("#frmtambah").trigger("reset");
          } else if ( result == 2 ) {
            swal("Gagal!","Kelas Sudah Ada","warning");
          } else {
            swal("Error","Kesalahan pada server","error");
          }
          unsetButton(".btnsave","Simpan");
        }
      });
    });

    $("#data_table").on("click",".btnhapus",function(){
      var id = $(this).attr("data-id");
      setButton(this,"Menghapus ...");
      swal({
        title : "Yakin menghapus kelas ini ?",
        text : "Dengan menghapus, siswa serta laporan siswa juga akan terhapus",
        icon : "warning",
        buttons : [
          "Batal",
          "Ya, lanjutkan"
        ],
        dangerMode : true
      }).then(function(confirm) {
        if ( confirm ) {
          $.ajax({
            url : base_url + "kelas/delete_kelas",
            data : { id_kelas : id },
            type : "post",
            dataType : "text",
            success : function(result) {
              if ( result == 0 ) {
                swal("Sukses","Sukses menghapus kelas","success");
                reloadData();
              } else {
                swal("Error","Kesalahan pada server","error");
              }
            }
          }); 
        }
      });
      unsetButton(".btnhapus","Hapus");
    });

    var id_kelas;
    $("#data_table").on("click",".btnedit",function(){
      id_kelas = $(this).attr("data-id");
      setTxt(".txtkelasedit","Loading...");
      $("#editmodal").modal("show");
      $.ajax({
        url : base_url + "kelas/get_kelas_by_id",
        data : { id_kelas : id_kelas },
        type : "post",
        dataType : "json",
        success : function(result) {
          unsetTxt(".txtkelasedit",result.kelas);
        }
      });
    });

    $("#frmedit").on("submit",function(e){
      e.preventDefault();
      setButton(".btnsave","Menyimpan...");
      var data = new FormData(this);
      data.append("id_kelas",id_kelas);
      $.ajax({
        url : base_url + "kelas/update_kelas",
        data : data,
        processData : false,
        cache : false,
        contentType : false,
        type : "post",
        dataType : "text",
        success : function(result) {
          if ( result == 0 ) {
            swal("Sukses","Sukses mengubah Kelas","success");
            $("#editmodal").modal("hide");
            reloadData();
          } else if ( result == 2 ) {
            swal("Gagal","Kelas sudah ada","warning");
          } else {
            swal("Error","Kesalahan pada server","error");
          }
          unsetButton(".btnsave","Simpan");
        }
      });
    });

  });
</script>