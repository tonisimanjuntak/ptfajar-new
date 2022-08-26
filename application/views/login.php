<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo strtoupper($rowpengaturan->namaperusahaan) ?> | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo $logoperusahaan ?>" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo(base_url()) ?>assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo(base_url()) ?>assets/ionicons/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo(base_url()) ?>assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo(base_url()) ?>assets/adminlte/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="<?php echo(base_url()) ?>assets/googleapis/googleapis.css" rel="stylesheet">
  <style>
    .login-page{
     /*background-color:  #0AA306 !important; */
     background: linear-gradient(120deg, #6086D3  10%, #E6EC88 100%) !important;
    }
    .mt-3 {
      margin-top: 30px !important;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <div class="text-center mb-3">
        <img src="<?php echo $logoperusahaan ?>" alt="" style="width: 40%;">
      </div>
      <h3 class="text-center"><?php echo $rowpengaturan->namaperusahaan ?></h3>
      <p class="login-box-msg">Silahkan login untuk melanjutkan</p>
      
      <?php 
          $pesan = $this->session->flashdata('pesan');
          if (!empty($pesan)) {
            echo $pesan;
          }
       ?>

      <form action="<?php echo(site_url('login/cek_login')) ?>" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Username" name="username" id="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" id="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      
      
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo(base_url()) ?>assets/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo(base_url()) ?>assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo(base_url()) ?>assets/adminlte/dist/js/adminlte.min.js"></script>

<script src="<?php echo base_url(); ?>assets/sweetalert/sweetalert.js"></script>

  <?php 
    $pesan = $this->session->flashdata("pesan");
    if (!empty($pesan)) {
      echo $pesan;
    }
  ?>

</body>
</html>

