<?php
  $getpost = array();
  $getpost['sortby'] = isset($_GET['sortby']) ? $_GET['sortby'] : "";
  $getpost['product_cate'] = isset($_GET['bycate']) ? $_GET['bycate'] : "0";
  $getpost['product_subcate'] = isset($_GET['subcate']) ? $_GET['subcate'] : "";
  $getpost['pin'] = isset($_GET['bypin']) ? $_GET['bypin'] : ""; 
  $getpost['search'] = isset($_GET['search']) ? $_GET['search'] : "";    
  $getpost['status'] = isset($_GET['status']) ? $_GET['status'] : "";  
  $all_posts =  $mydata->get_product_room();   
 
?>
<style>
  .form-group.has-error .cke_chrome { 
      border-color: #dd4b39;
      box-shadow: none;
  } 
  .box-content-cate.error,.box-content-cate-edit.error { 
    border: 1px solid #dd4b39;
  }
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-newspaper-o"></i> จัดการห้องพัก <?php //echo $LANG_LABEL['content'];?> 
        <small>( <?php echo $language_fullname['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i><?php echo $LANG_LABEL['home']?></a></li>
      <!-- <li class="active"><?php echo $LANG_LABEL['content'];?></li> -->
      <li class="active"> ห้องพัก </li>

    </ol>
  </section>

  <section id="backendContent" class="content">
    <div class="row" id="product_list">
      <section class="col-lg-12 connectedSortable">
        <div class="box box-primary">
          <div id="slc_category_product">
            <div class="row" style="margin-top:10px;">
             <div class="col-md-12"> 
                  <label><?php echo "หมวดหมู่สินค้า";?> : </label> 
                  <select class="form-control" id="product_type">
                    <option value="0">ทั้งหมด</option> 
                    <?php   
                      foreach ($category as $value) { 
                        if($value['id'] == 1){ continue; }
                        $slc = ($value['id'] == $_GET['bycate'])? "selected":""; 
                        echo " <option  ".$slc." value=\"".$value['id']."\">".$value['name']."</option> ";
                      } 
                    ?>
                  </select>
              </div>
             </div>

            <!-- <div class="row" style="margin-top:10px;">
                <div class="col-md-12">
                  <label><?php echo "หมวดย่อยสินค้า";?> : </label>
                    <select class="form-control" id="subproduct_type">
                      <option value="0">ทั้งหมด</option>
                      <?php foreach ($product_subcate as $value) {  
                        $slc = ($value['id'] == $_GET['subcate'])? "selected":""; 
                        echo " <option  ".$slc." value=\"".$value['id']."\">".$value['name']."</option> ";
                      } ?>
                    </select>
                </div>
              </div> -->
           

            <!-- <div class="row" style="margin-top:10px;">
              <div class="col-md-12">
                <label><?php echo "สถานะสินค้า";?> : </label>
                  <select class="form-control" id="product_status">
                    <option value="0">ทั้งหมด</option>
                    <?php foreach ($p_status as $value) {  
                      $slc = ($value['id'] == $_GET['status'])? "selected":""; 
                      echo " <option  ".$slc." value=\"".$value['id']."\">".$value['title']."</option> ";
                    } ?>
                  </select>
              </div>
            </div> -->
          </div> 

          

          <div class="box-header" style="position: unset;">
            <?php
            if (!empty($search_text)) {
              echo ' <h3 class="box-title"><i class="fa fa-search" aria-hidden="true"></i> '.$LANG_LABEL['resultsfor'].' "'.$_REQUEST['search'].'"</h3>';
            }
            ?>
            <div><span class="btn-add"><button class="btn btn-info add-room-product">เพิ่มห้องพัก</button></span></div>
            <div class="box-tools pull-right">
              <div class="has-feedback">
                <input type="text" class="form-control input-sm" id="search-content" value="<?=$_GET['search']?>"placeholder="<?php echo $LANG_LABEL['searchtext'];?>">
                <input type="hidden" id="search-hidden" value="<?php echo  $search_text; ?>">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
              </div>
            </div>
          </div> 
          
          <div class="categorybox-controls"> 
            <?php
              if (empty($search_text)) {
                $search_sortby = isset($_GET['sortby']) ? $_GET['sortby'] : ""; 
              ?>
              <label><?php echo $LANG_LABEL['sortby'];?> : </label>
              <select class="form-control" id="sort-by" style="width: 170px; display: inline-block; margin: 0 10px 5px 5px;">
                <option value=""  SELECTED  > ค่าเริ่มต้น </option>
                <option value="dc" <?php if($search_sortby=='dc'){echo 'SELECTED';} ?>> ราคามากไปน้อย </option>
                <option value="de" <?php if($search_sortby=='de'){echo 'SELECTED';} ?>> ราคาน้อยไปมาก </option>
                <option value="dd" <?php if($search_sortby=='dd'){echo 'SELECTED';} ?>> วันที่ล่าสุด </option>
                <option value="df" <?php if($search_sortby=='df'){echo 'SELECTED';} ?>> คะแนนผู้ขายสูงสุด </option>
              </select> 

              <label><?php echo "";//$LANG_LABEL['categories'];?>  </label>
  
              <select class="form-control" disabled id="cate-id" style="width: 100px; display: none; margin: 0 10px 5px 5px;"> 
                <option value="3">สินค้า</option>  
                <?php
                // $search_bycate = isset($_GET['bycate']) ? $_GET['bycate'] : "";
                ?>
            </select>
            <label class="">
              <div class="kt-checkbox <?=($_GET['bypin'] == "yes")?"checked":""?>">
                
              </div> 
              เลือกเฉพาะที่ปักหมุด(หน้าหลัก) 
            </label> 

            <div style="margin-left:3px;"class="pull-right">
               <button class="btn btn-info " type="submit" id="modalShow" style="display: none;"> <?php echo $LANG_LABEL['matchfinish'];?></button>
                <?php 
                  if($_SESSION['role'] == "admin" || $_SESSION['role'] == "superadmin"){
                    ?>
                    <!-- <button class="btn btn-primary kt:btn-info" type="submit" id="modalHide"> <?php echo $LANG_LABEL['hidebt'].' '.$LANG_LABEL['delete'];?></button> -->
                    <?php 
                  } 
                ?>
            </div>
            <div class="pull-right">
              <!-- <button type="button" class="btn btn-primary bt-add-content kt:btn-info" style="padding: 8px 40px" data-toggle="modal"><i class="fa fa-plus"></i> เพิ่มสินค้า<?php //echo $LANG_LABEL['addcontent'];?></button> -->
            </div>
            <?php
              }else {
            ?>
              <div class="categorybox-controls category-search">
                <span id="count-search">
                  <?php echo $LANG_LABEL['allresults'];?> <?php echo count($all_posts); ?> <?php echo $LANG_LABEL['record'];?>
                </span>
              </div>
            <?php    
              }
            ?>
          </div>

          <?php
          if (!empty($all_posts)) {
            
          ?>
          <div class="box-body">
            <?php
              $curDate = date("Y-m-d H:i:s");
              foreach($all_posts as $key => $post) {
                $amount = $post['room_amount'] - $post['amount']; 
            ?>
              <div class="attachment-block clearfix" <?php if ($post['display'] == 'no') {echo 'style="opacity: 0.6;"';} ?> >
                <div class="content-img">
                  <a class="fancybox" href="<?=ROOT_URL.$post['room_thumbnail']?>" title="<?=$post['room_type_name']?>">
                    <img src="<?=ROOT_URL.$post['room_thumbnail']?>" alt=""> 
                  </a>
                </div>
                <div class="content-info"> 
                  <h1>  
                    <?php echo $post['room_name']; ?> 
                    <span  style="color:red"><?=$post['room_type_name']?></span> 
                    <?=$date_result?>
                  </h1>
                  <p class="text-datetime"> 
                    <i style="color:#03a9f4;" class="fas fa-dollar-sign"></i>
                    <span style="color:#03a9f4;"> ราคาปัจจุบัน: </span>
                    <span style="color:#03a9f4;font-size: 1.3em; font-weight: bold;"> <?=number_format($post['room_current_price'])?>  </span> 
                    <span style="color:#03a9f4;"> บาท</span>
                  </p>
                  <p class="text-category">ราคาห้องปกติ <span style="text-decoration: line-through;"><?=number_format($post['room_price'])?> บาท </span>  </p>  
                  <!-- <p class="text-editor"><i class="fa fa-money" aria-hidden="true"></i> 
                    <span>ส่วนลดห้อง: <?=$post['discount']?></span>
                    </p>-->
                  <p class="text-editor" style="line-height:1.5em;">
                        <i class="fa fa-info-circle fa-lg" aria-hidden="true"></i>
                        <span>จำนวนห้องทั้งหมด: <span style="font-weight:bold;"><?=$post['room_amount']?></span> ห้อง</span> 
                        <span>คงเหลือ: <span class="room-current-amount" data-id="<?=$post['room_id']?>" style="color: #ffc41e; font-size: 1.5em; font-weight:bold;"><?=$amount?></span> ห้อง</span> 
                        <span style="color: #ffc41e;"></span> 
                    </p>
                </div>
                <div class="action-btn increase-decrease-room priceroom" style="text-align: end;">
                  <div class="input-current-price" >
                          <p>ราคาปัจจุบัน: </p>
                          <input type="text" class="form-control txt_change_price" " data-id="<?php echo $post['room_id']; ?>"  value="<?php echo $post['room_current_price']; ?>" data-old="<?php echo $post['room_current_price']; ?>">
                          <button type="button" class="btn btn-success margin-r-10 change_price kt:btn-warning" data-id="<?php echo $post['room_id']; ?>" data-name="<?php echo $post['room_type_name']; ?>"  >
                            ปรับราคา 
                          </button>
                      </div>
                      <div class="add-remove-room">
                            <button type="button" class="btn btn-success margin-r-10 edit-product kt:btn-warning" data-id="<?php echo $post['room_id']; ?>"  style="padding: 8px 40px;">
                              <i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];?>
                            </button>
                            <button type="button" class="btn btn-danger delete-product  kt:btn-danger" data-id="<?php echo $post['room_id']; ?>" style="padding: 8px 40px;"> 
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>  <?php echo $LANG_LABEL['delete'];?>
                            </button>
                      </div>
                  </div>
                <div class="action-btn increase-decrease-room inc-decRoom" style="text-align: end;">
               
                    <div class="config-room-amount">
                      <button type="button" class="btn btn-success margin-r-10 decrease_room kt:btn-warning" data-id="<?php echo $post['room_id']; ?>"  >
                        <i class="fas fa-minus"></i> ลดห้อง 
                      </button>
                      <button type="button" class="btn btn-danger increase_room  kt:btn-danger" data-id="<?php echo $post['room_id']; ?>" > 
                        เพิ่มห้อง <i class="fas fa-plus"></i>
                      </button>
                    </div>
                </div>

                <!-- <div class="action-btn add-remove" style="text-align: end;">
                  <div>
                        <button type="button" class="btn btn-success margin-r-10 edit-product kt:btn-warning" data-id="<?php echo $post['room_id']; ?>"  style="padding: 8px 40px;">
                          <i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];?>
                        </button>
                        <button type="button" class="btn btn-danger delete-product  kt:btn-danger" data-id="<?php echo $post['room_id']; ?>" style="padding: 8px 40px;"> 
                                <i class="fa fa-trash-o" aria-hidden="true"></i>  <?php echo $LANG_LABEL['delete'];?>
                        </button>
                  </div>
                </div> -->

                <?php if($post['status'] == 2 ){  ?>
                  <div class="content-button pull-right" style="position: absolute;right:0;">
                    <button type="button" class="btn btn-primary margin-r-10 pin_product <?=(($post['promote'] == 'yes')?'active':"")?> " data-id="<?=$post['p_id']?>">
                        <i class="fa <?=(($post['promote'] == 'yes')?'fa-thumb-tack':"fa-plus")?>"></i> PIN 
                      </button>
                  </div>
                <?php } ?>
              </div> 
            <?php
              }  
            ?>
          </div>
          <div class="box-footer clearfix no-border">
            <div class="box-tools pull-right">
              <ul class="pagination pagination-sm no-margin pull-right">
              </ul>
            </div>
          </div>

          <?php
          }else {
            echo '
            <div class="box-body">
              <div class="search-found">
                <i class="fa fa-warning" aria-hidden"true"=""></i> '.$LANG_LABEL['nodata'].'
              </div>
            </div>';
          }
          ?>

        </div>
      </section>
    </div>
  </section>
  
  <!-- popup status download  -->
  <div class="wrapper-pop">
    <div class="pop">
        <div class="loader10"></div>
        <h2 class="loadper" style="text-align:center;padding-top:50px;">0 %</h2>
        <h4 style="padding-top:30px">กำลังอัพโหลดรูปภาพ</h4>
    </div>
  </div>

</div>
<!-- content-wrapper -->
 

<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/jstree/style.css" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/progress-bar.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.css?v=2.1.7" media="screen" />
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.css">
<link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/meStyle.css?v=1.2.3.<?=time()?>">



<!-- script -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/jstree/jstree.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script> 

<script src="<?php echo SITE_URL; ?>js/pages/contents/hidebtn.js?v=<?=date('ymdhis')?>"></script>
<script src="<?php echo SITE_URL; ?>js/pages/hotel_manage/upload.js?v=<?=date('ymdhis')?>"></script>
<script src="<?php echo SITE_URL; ?>js/pages/hotel_manage/product.js?v=<?=date('ymdhis')?>"></script>
 
 

<!-- <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.12';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script> -->
