<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-cogs"></i> จัดการข้อมูลรถ
      <small>( <?php echo $language_fullname['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['mainpage'];//หน้าหลัก?></a></li>
      <li class="active">ข้อมูลรถ</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      
        <div class="col-md-6">
            <?php  include 'template/car_config/box_cartype.php'; ?>
            <?php  include 'template/car_config/box_carcolor.php'; ?>
        </div>

        <div class="col-md-6">
          <?php  include 'template/car_config/box_carbrand.php'; ?>
        </div>
      
    </div>
  </section>
</div>

<!-- css -->
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/custom.css">

<!-- script -->
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="<?php echo SITE_URL; ?>js/pages/car/car_config.js?v=<?php echo date('s');?>"></script>