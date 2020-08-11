<?php
  $category = $mydata->get_category();
  //print_r($category);
  $category_on_menu = $mydata->get_all_category($getpost, $_REQUEST['status']);
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-tags"></i> หมวดหมู่ยี่ห้อรถ
      <small>( <?php echo $language_name['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
      <li class="active">หมวดหมู่ยี่ห้อรถ</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">


      <div class="col-md-12">
        <div class="box box-primary">

          <div class="box-header with-border">
            <?php
            if (empty($_REQUEST['search'])) {          

            ?>
                <h3 class="box-title cate-title"></h3>
            
            <?php

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
          
          <div class="box-body no-padding">
            <div class="categorybox-controls category-box">

              <div class="pull-right">
                <button type="button" class="btn btn-attree" data-toggle="modal" data-target="#modalAddCategory"><i class="fa fa-plus"></i> เพิ่มหมวดหมู่สินค้า</button>
              </div>

            </div>

            <div class="categorybox-controls category-search">
              <span id="count-search">
                ผลการค้นหาทั้งหมด <?php echo count($category_on_menu); ?> รายการ
              </span>
            </div>

          </div>

          <div class="box-body box-category">
            <?php
            if (!empty($category_on_menu)) {
              foreach($category_on_menu as $a) {
            ?>
            <div class="attachment-block clearfix" <?php if ($a['display'] == 'no') {echo 'style="opacity: 0.6;"';} ?>
            >
              <div class="content-img">
                <a class="fancybox" href="<?php echo ROOT_URL.$a['thumbnail']; ?>" title="<?php echo $a['cate_name']; ?>">
                  <img src="<?php echo ROOT_URL.$a['thumbnail']; ?>" alt="">
                </a>
              </div>

              <div class="content-info">
                <h1>
                  <?php echo $a['cate_name']; ?>
                  <?php
                    if ($a['display'] == 'no') {
                      echo '
                      <small class="label label-warning" style="position: absolute; margin: 2px 10px 0;"><i class="fa fa-eye-slash"></i> ซ่อน</small>';
                    }
                  ?>
                </h1>

                <p class="text-datetime"><i class="fa fa-folder-open-o" aria-hidden="true"></i> <?php echo ($category[$a['parent_id']]['cate_name'] == '') ? 'หมวดหมู่หลัก' : $category[$a['parent_id']]['cate_name']; ?></p>
                <p class="text-editor"><i class="fa fa-globe" aria-hidden="true"></i> <?php echo substr($a['lang_info'],1); ?></p>
              </div>
              <div class="content-button pull-right">
                <?php
                  if(strpos($a['lang_info'],$_SESSION['backend_language'])){
                ?>
                  <button type="button" class="btn btn-success margin-r-10 btn-edit-category" data-id="<?php echo $a['cate_id']; ?>" data-type="edit" data-toggle="modal" data-target="#modalEditCategory">
                    <i class="fa fa-pencil-square-o"></i> แก้ไข
                  </button>

                  <!-- <button type="button" class="btn btn-danger" data-id="">
                    <i class="fa fa-trash-o delete-user"></i> ลบ
                  </button> -->
                <?php
                  }else {
                ?>
                  <button type="button" class="btn btn-attree margin-r-10 btn-edit-category" data-id="<?php echo $a['cate_id']; ?>" data-type="add" data-toggle="modal" data-target="#modalEditCategory">
                    <i class="fa fa-plus"></i> เพิ่ม
                  </button>
                <?php
                  }
                ?>
              </div>
            </div>
            <?php
              }
            }else {
              echo '
              <div class="search-found">
                <i class="fa fa-warning" aria-hidden"true"=""></i> ไม่พบผลลัพธ์การค้นหา
              </div>';
            }
            ?>
          </div> 
          <!-- <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
          </div> -->
          <div class="box-footer clearfix category-footer">
            <ul class="pagination pagination-sm no-margin pull-right">
            </ul>
          </div> 
          
        </div>
      </div>
    </div>
  </section>
</div>

  <?php
    include 'template/car_brand/addcar_brand.php';
    include 'template/car_brand/editcar_brand.php';
  ?>
<!-- css -->
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="<?php echo SITE_URL; ?>js/pages/car_brand/car_brand.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/car_brand/addcar_brand.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/car_brand/editcar_brand.js"></script>