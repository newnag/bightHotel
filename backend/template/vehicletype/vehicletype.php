<?php
  $category = $mydata->get_vehiclecate($getpost);
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-list-alt"></i> รุ่นรถ 
      <!-- <small>( <?php echo $language_name['display_name']; ?> )</small> -->
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
      <li class="active">รุ่นรถ</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">

          <div class="box-header with-border">
            <?php
            if (empty($_REQUEST['search'])) {              
              echo '
              <h3 class="box-title cate-title"></h3>';
            }else {
              echo '
              <h3 class="box-title cate-title"><i class="fa fa-search" aria-hidden="true"></i> ผลการค้นหาสำหรับ "'.$_REQUEST['search'].'"</h3>';
            }
            ?>

            <div class="box-tools pull-right">
              <div class="has-feedback">
                <input type="text" class="form-control input-sm" id="search-cate" placeholder="ค้นหา...">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
              </div>
            </div>
          </div>
          
          <?php
          if (empty($_REQUEST['search'])) {
          ?>
          <div class="box-body no-padding">
            <div class="categorybox-controls vehiclecate-box">
              <div class="box-header ui-sortable-handle">
                <i class="fa fa-bars"></i>
                <h3 class="box-title">แสดงบนเว็บไซต์</h3>
                <div class="pull-right">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddCategory"><i class="fa fa-plus"></i> เพิ่มรุ่นรถ</button>
                </div>
              </div>
            </div>
          </div>

          <div class="box-body box-category">
            <?php
            if (!empty($category)) {
              foreach($category as $a) {
                if($a['display']=='yes' && $a['main_page']!='yes'){
                  include 'template/vehicletype/vehicletype-box.php';
                }
              }
            }
            ?>

          <div class="box-body no-padding">
            <div class="categorybox-controls vehiclecate-box">
              <div class="box-header ui-sortable-handle">
                <i class="fa fa-eye-slash"></i>
                <h3 class="box-title">ซ่อนจากเว็บไซต์</h3>
              </div>
            </div>
          </div>
          <div class="box-body box-category">
            <?php
            if (!empty($category)) {
              foreach($category as $a) {
                if($a['display']=='no' && $a['main_page']!='yes'){
                  include 'template/vehicletype/vehicletype-box.php';
                }
              }
            }
            ?>
          </div>

          <?php
          }else {
          ?>

          <div class="box-body no-padding">
            <div class="categorybox-controls category-search">
              <span id="count-search">
                ผลการค้นหาทั้งหมด <?php echo count($category); ?> รายการ
              </span>
            </div>
          </div>
          <div class="box-body box-category">
            <?php
            if (!empty($category)) {
              foreach($category as $a) {
                include 'template/vehicletype/vehicletype-box.php';
              }
            }else {
              echo '
              <div class="search-found">
                <i class="fa fa-warning" aria-hidden"true"=""></i> ไม่พบผลลัพธ์การค้นหา
              </div>';
            }
            ?>
          </div> 
          <?php
          }
          ?>
 
          
        </div>
      </div>
    </div>
  </section>
</div>

<?php
  include 'template/vehicletype/addvehicletype.php';
  include 'template/vehicletype/editvehicletype.php';
?>
<!-- css -->
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="<?php echo SITE_URL; ?>js/pages/js-vehicletype/vehicletype.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/js-vehicletype/addvehicletype.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/js-vehicletype/editvehicletype.js"></script>