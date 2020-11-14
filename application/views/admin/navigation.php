<?php $user_level_id = $this->session->userdata('user_level_id'); ?>
<ul class="sidebar-menu">
  <li class="header">AYO IMUNISASI</li>
  <li>
    <a href="<?php echo base_url('dashboard'); ?>">
      <i class="fa fa-dashboard"></i> <span>Dashboard</span>
      <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
  </li>

  <li class="treeview">
    <a href="<?php echo base_url() ?>">
      <i class="glyphicon glyphicon-tasks"></i>
      <span>Master</span>
      <span class="pull-right-container">
        <span class="fa fa-angle-left pull-right"></span>
      </span>
    </a>

    <ul class="treeview-menu">
      <li><a href="<?php echo base_url('video')?>"><i class="fa fa-circle-o"></i> Video</a></li>
      <li><a href="<?php echo base_url('jadwal')?>"><i class="fa fa-circle-o"></i> Jadwal</a></li>
    </ul>
  </li>

  <li class="treeview">
    <a href="<?php echo base_url() ?>">
      <i class="glyphicon glyphicon-tasks"></i>
      <span>Pasien</span>
      <span class="pull-right-container">
        <span class="fa fa-angle-left pull-right"></span>
      </span>
    </a>

    <ul class="treeview-menu">
      <li><a href="<?php echo base_url('pasien')?>"><i class="fa fa-circle-o"></i> Data Pasien</a></li>
      <li><a href="<?php echo base_url('reminder')?>"><i class="fa fa-circle-o"></i> Reminder</a></li>
    </ul>
  </li>

  <li class="treeview">
    <a href="<?php echo base_url() ?>">
      <i class="glyphicon glyphicon-tasks"></i>
      <span>User</span>
      <span class="pull-right-container">
        <span class="fa fa-angle-left pull-right"></span>
      </span>
    </a>

    <ul class="treeview-menu">
      <li><a href="<?php echo base_url('user')?>"><i class="fa fa-circle-o"></i> User</a></li>
      <li><a href="<?php echo base_url('user_level')?>"><i class="fa fa-circle-o"></i> Level</a></li>
    </ul>
  </li>
  <li><a href="<?= base_url('upload/apk/app-debug.apk') ?>" target="_blank"><i class="fa fa-android"></i> <span>APK</span></a></li>
</ul>