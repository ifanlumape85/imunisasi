    <section class="content-header">
      <h1>
        <?php echo ucwords(str_replace('_', ' ', $title)); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Beranda</a></li>
        <li><a href="<?php echo base_url(); ?>"><?php echo ucwords(str_replace('_', ' ', $title)); ?></a></li>
        <li class="active"><?php echo ucwords(str_replace('_', ' ', $title)); ?> List</li>
      </ol>
    </section>