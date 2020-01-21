<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $pagetitle . " - " . $this->Clsglobal->site_info("nama_sekolah") ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style type="text/css">
    .loading-area {
      display: none;
      position: fixed;
      z-index: 9999;
      width: 100%;
      height: 100%;
      text-align: center;
      top: 0;
      left: 0;
      background: rgba(0,0,0,0.8);
      color: #fff;
      font-size: 50pt;
    }
    .execute-loading {
      display: block;
    }
    .loading-area .container {
      margin-top: 15%;
    }
  </style>
  <script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <script src="<?= base_url() ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
  <script src="<?= base_url() ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <script src="<?= base_url() ?>assets/plugins/chart.js/Chart.min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/sparklines/sparkline.js"></script>
  <script src="<?= base_url() ?>assets/plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <script src="<?= base_url() ?>assets/plugins/jquery-knob/jquery.knob.min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/moment/moment.min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
  <script src="<?= base_url() ?>assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/summernote/summernote-bs4.min.js"></script>
  <script src="<?= base_url() ?>assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <script src="<?= base_url() ?>assets/dist/js/adminlte.js"></script>
  <script src="<?= base_url() ?>assets/dist/js/pages/dashboard.js"></script>
  <script src="<?= base_url() ?>assets/dist/js/demo.js"></script>
  <script src="<?= base_url() ?>assets/dist/js/swal.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

  <div class="loading-area">
    <div class="container text-center">
      <i class="fas fa-sync fa-spin"></i>
    </div>
  </div>

<div class="wrapper">