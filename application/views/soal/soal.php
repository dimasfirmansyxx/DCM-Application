<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Manajemen Soal</h1>
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
      
      <div class="row">
        <div class="col-12">
          <button class="btn btn-primary btn-sm btntambah">
            <i class="fas fa-plus"></i> Tambah Soal
          </button>
          <button class="btn btn-success btn-sm btntambah">
            <i class="fas fa-arrow-down"></i> Import Soal dari Excel
          </button>
        </div>
      </div>

      <div class="row mt-3">
        <section class="col-lg-12">
          <div class="card">
            <div class="card-header bg-secondary">
              Soal
            </div>
            <div class="card-body table-responsive">
              <table class="table table-bordered table-hover" id="data_table">
                <thead>
                  <tr>
                    <th>No. Soal</th>
                    <th>Kategori</th>
                    <th>Soal</th>
                    <th>Jenis</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
          </div>
        </section>

        <section class="col-lg-5 ">

        </section>
      </div>

    </div>
  </section>
</div>

<div class="modal fade" id="tambahmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Soal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmtambah">
          <div class="form-group">
            <label>Kategori</label>
            <select class="form-control" name="kategori" required>
              <?php foreach ($kategori_soal as $kategori): ?>
                <option value="<?= $kategori['id_kategori'] ?>"><?= $kategori['nama_kategori'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Soal</label>
            <textarea class="form-control" required name="soal"></textarea>
          </div>
          <div class="form-group">
            <label>Jenis</label>
            <select class="form-control" name="jenis" required>
              <option value="check">Check</option>
              <option value="essay">Essay</option>
            </select>
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
        <h5 class="modal-title" id="exampleModalLabel">Edit Soal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmedit">
          <div class="form-group">
            <label>Kategori</label>
            <select class="form-control cmbkategoriedit" name="kategori" required>
              <?php foreach ($kategori_soal as $kategori): ?>
                <option value="<?= $kategori['id_kategori'] ?>"><?= $kategori['nama_kategori'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label>Soal</label>
            <textarea class="form-control txtsoaledit" required name="soal"></textarea>
          </div>
          <div class="form-group">
            <label>Jenis</label>
            <select class="form-control cmbjenisedit" name="jenis" required>
              <option value="check">Check</option>
              <option value="essay">Essay</option>
            </select>
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
            "url": base_url + "soal/get_soal",
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
        url : base_url + "soal/insert_soal",
        data : new FormData(this),
        processData : false,
        contentType : false,
        cache : false,
        type : "post",
        dataType : "text",
        success : function(result) {
          if ( result == 0 ) {
            swal("Sukses","Sukses menambah soal","success");
            reloadData();
            $("#frmtambah").trigger("reset");
          } else if ( result == 2 ) {
            swal("Gagal!","Soal Sudah Ada","warning");
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
        title : "Yakin ?",
        text : "Yakin ingin menghapus soal ini ?",
        icon : "warning",
        buttons : [
          "Batal",
          "Ya, lanjutkan"
        ],
        dangerMode : true
      }).then(function(confirm) {
        if ( confirm ) {
          $.ajax({
            url : base_url + "soal/delete_soal",
            data : { no_soal : id },
            type : "post",
            dataType : "text",
            success : function(result) {
              if ( result == 0 ) {
                swal("Sukses","Sukses menghapus soal","success");
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

    var no_soal;
    $("#data_table").on("click",".btnedit",function(){
      no_soal = $(this).attr("data-id");
      setTxt(".cmbkategoriedit","Loading...");
      setTxt(".txtsoaledit","Loading...");
      setTxt(".cmbjenisedit","Loading...");
      $("#editmodal").modal("show");
      $.ajax({
        url : base_url + "soal/get_soal_by_id",
        data : { no_soal : no_soal },
        type : "post",
        dataType : "json",
        success : function(result) {
          unsetTxt(".txtsoaledit",result.soal);
          unsetTxt(".cmbkategoriedit",result.id_kategori);
          unsetTxt(".cmbjenisedit",result.jenis);
        }
      });
    });

    $("#frmedit").on("submit",function(e){
      e.preventDefault();
      setButton(".btnsave","Menyimpan...");
      var data = new FormData(this);
      data.append("no_soal",no_soal);
      $.ajax({
        url : base_url + "soal/update_soal",
        data : data,
        processData : false,
        cache : false,
        contentType : false,
        type : "post",
        dataType : "text",
        success : function(result) {
          if ( result == 0 ) {
            swal("Sukses","Sukses mengubah soal","success");
            $("#editmodal").modal("hide");
            reloadData();
          } else if ( result == 2 ) {
            swal("Gagal","Soal sudah ada","warning");
          } else {
            swal("Error","Kesalahan pada server","error");
          }
          unsetButton(".btnsave","Simpan");
        }
      });
    });

  });
</script>