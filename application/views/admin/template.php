<?php 
$user_full_name = $this->session->userdata('user_full_name');
$user_photo = $this->session->userdata('user_photo');
$user_level = $this->session->userdata('user_level_id');

if ($user_photo=="")
{
  $user_photo = '1561504645442.jpg';
}

$user_level_name = $this->session->userdata('user_level_name');

$image_url = base_url('upload/user/thumbs/'.$user_photo);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo isset($title) ? $title : ''; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url('bootstrap/css/bootstrap.min.css'); ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/datepicker/datepicker3.css'); ?>">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/timepicker/bootstrap-timepicker.min.css'); ?>">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/datatables/dataTables.bootstrap.css'); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('dist/css/AdminLTE.min.css'); ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url('dist/css/skins/_all-skins.min.css'); ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url('plugins/select2/select2.min.css'); ?>">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url(); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b></span>
      <!-- logo for regular state and mobile devices -->
      <?php if ($this->session->userdata('user_level_id')==1) { ?> 
      <span class="logo-lg"><b>Admin</b></span>
    <?php } else { ?> 
      <span class="logo-lg"><b>User</b></span>
    <?php }?>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $image_url; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $user_full_name; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $image_url; ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $user_full_name; ?> - <?php echo $user_level_name; ?>
                  <small>Terdaftar sejak <?php echo tgl_indonesia2($this->session->userdata('user_date_entri')); ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                 
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('login/process_logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $image_url; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo singkat_nama($user_full_name); ?></p>
          <a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
    <?php $this->load->view('admin/navigation'); ?>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php $this->load->view('admin/breadcrumb'); ?>
    <?php $this->load->view($main_view); ?>

    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2020 <a href="<?php echo base_url(); ?>">AYO IMUNISASI</a>.</strong> All rights
    reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url('plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url('bootstrap/js/bootstrap.min.js'); ?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url('plugins/select2/select2.full.min.js'); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo base_url('plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url('plugins/timepicker/bootstrap-timepicker.min.js'); ?>"></script>

<!-- DataTables -->
<script src="<?php echo base_url('plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url('plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url('plugins/fastclick/fastclick.js'); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('dist/js/app.min.js'); ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('dist/js/demo.js'); ?>"></script>
<!-- page script -->
<!-- InputMask -->
<script src="<?php echo base_url('plugins/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?php echo base_url('plugins/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?php echo base_url('plugins/input-mask/jquery.inputmask.extensions.js'); ?>"></script>

<?php $this->load->view($form_view); ?>
</body>
</html>
