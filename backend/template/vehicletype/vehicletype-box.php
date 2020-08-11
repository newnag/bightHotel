  <div class="attachment-block clearfix">
    <div class="content-img">
      <a class="fancybox" href="<?php echo ROOT_URL.$a['thumbnail']; ?>" title="<?php echo $a['cate_name']; ?>">
        <img src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.ROOT_URL.$a['thumbnail'].'&size=x95'; ?>" alt="">
      </a>
    </div>

    <div class="content-info">
      <h1><?php echo $a['cate_name']; ?></h1>

      <!-- <p class="text-datetime"><i class="fa fa-folder-open-o" aria-hidden="true"></i> <?php echo ($category[$a['parent_id']]['cate_name'] == '') ? 'หน้าหลัก' : $category[$a['parent_id']]['cate_name']; ?></p> -->
      <p class="text-datetime"><?= $a['seats'].' ที่นั่ง, '.$a['doors'].' ประตู' ?></p>
      <p class="text-editor"><i class="fa fa-globe" aria-hidden="true"></i> <?php echo substr($a['lang_info'],1); ?></p>
    </div>
    <div class="content-button pull-right">
      <?php
        if(strpos($a['lang_info'],$_SESSION['backend_language'])){
      ?>
        <button type="button" class="btn btn-success margin-r-10 btn-edit-category" data-id="<?php echo $a['cate_id']; ?>" data-type="edit" data-toggle="modal" data-target="#modalEditCategory">
          <i class="fa fa-pencil-square-o"></i> แก้ไข
        </button>

        <button type="button" class="btn btn-danger margin-r-10 btn-delete-category" data-id="<?php echo $a['cate_id']; ?>">
          <i class="fa fa-trash-o" aria-hidden="true"></i> ลบ
        </button>
      <?php
        }else {
      ?>
        <button type="button" class="btn btn-primary margin-r-10 btn-edit-category" data-id="<?php echo $a['cate_id']; ?>" data-type="add" data-toggle="modal" data-target="#modalEditCategory">
          <i class="fa fa-plus"></i> เพิ่ม
        </button>
      <?php
        }
      ?>
    </div>
  </div>