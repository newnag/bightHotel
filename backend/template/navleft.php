<?php
/*
 * @$LANG_LABEL  ตัวแปรภาษาที่ประกาศไว้ที่หน้า index
 *
 */
?>
<aside class="main-sidebar">
  <section class="sidebar">
    <a href="<?php echo ROOT_URL; ?>" target="_blank">
      <div class="user-panel" style="background-color: #2a4054;text-align:center">
        <img src="<?php echo ROOT_URL . getData::get_website_logo(); ?>" alt="" >
      </div>
    </a>
    <ul class="sidebar-menu" data-widget="tree">
        <li id="dashboard">
          <a href="<?php echo SITE_URL; ?>"><i class="fas fa-tachometer-alt"> </i> <span> <?php echo $LANG_LABEL['dashboard']; ?> </span></a>
        </li>
        <li id="slide"><a href="<?php echo SITE_URL; ?>?page=slide"><i class="fas fa-images"> </i><span> <?php echo $LANG_LABEL['banner']; ?></span></a></li> 
        <li id="category">
          <a href="<?php echo SITE_URL; ?>?page=category"><i class="fa fa-sitemap"></i> <span> <?php echo $LANG_LABEL['categories']; ?> , แถบเมนู</span></a>
        </li> 
       <hr> 
       <!-- <li class="treeview ">
          <a href="#"><i class="fas fa-home" aria-hidden="true"> </i> <span>ข้อมูลการจอง</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu hotel_manager" style="display: <?=($_GET['page'] =="hotel_manager" || $_GET['page'] =="product_sel")?"block":"none"?>;">
            <li id="product"><a href="<?php echo SITE_URL; ?>?page=hotel_manager&subpage=product"><i class="fas fa-circle"></i> <span>ห้องพัก</span></a></li>
            <li id="meeting"><a href="<?php echo SITE_URL; ?>?page=hotel_manager&subpage=meeting"><i class="fas fa-circle"></i> <span>ห้องประชุม</span></a></li>
          </ul>
        </li> -->
      <li id="room"><a href="<?php echo SITE_URL; ?>?page=reserve_room"><i class="fas fa-list-alt"></i><span>  ข้อมูลการจองห้องพัก </span></a></li> 
      <li id="meeting"><a href="<?php echo SITE_URL; ?>?page=reserve_meeting"><i class="fas fa-file-contract"></i><span>  ข้อมูลติดต่อห้องประชุม </span></a></li> 
       <li class="treeview ">
          <a href="#"><i class="fas fa-home" aria-hidden="true"> </i> <span>ปรับแต่งห้อง</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu hotel_manager" style="display: <?=($_GET['page'] =="hotel_manager" || $_GET['page'] =="product_sel")?"block":"none"?>;">
            <li id="product"><a href="<?php echo SITE_URL; ?>?page=hotel_manager&subpage=product"><i class="fas fa-circle"></i> <span>ห้องพัก</span></a></li>
            <li id="meeting"><a href="<?php echo SITE_URL; ?>?page=hotel_manager&subpage=meeting"><i class="fas fa-circle"></i> <span>ห้องประชุม</span></a></li>
            <li id="facility"><a href="<?php echo SITE_URL; ?>?page=hotel_manager&subpage=facility"><i class="fas fa-circle"></i> <span>สิ่งอำนวยความสะดวก</span></a></li>
          </ul>
        </li>
 




       <!-- <li id="purchaseOrderData" class="<?=$color_tab?>"><a href="<?php echo SITE_URL; ?>?page=purchaseOrderData"> -->
         <!-- <span> <i class="fa fa-bell" aria-hidden="true"> </i> บันทึกการสั่งซื้อ </span> -->
         <!-- <span class="notify_number"><?=$_SESSION['nav_payments_notify']?></span> </a> -->
        <!-- </li> -->
        <li id="promotions"><a href="<?php echo SITE_URL; ?>?page=promotions"><i class="fas fa-tags"></i>  <span> โปรโมชั่น  </span></a></li>

        <!-- <li id="order_carrier"><a href="<?php echo SITE_URL; ?>?page=order_carrier"><i class="far fa-paper-plane" aria-hidden="true"></i>  <span> บันทึกการจัดส่ง </span></a></li> -->
        <li id="reviews"><a href="<?php echo SITE_URL; ?>?page=reviews"><i class="fas fa-image" aria-hidden="true"></i>  <span> แกลเลอรี่ </span></a></li>
 
        <li id="contentWeb"><a href="<?php echo SITE_URL; ?>?page=contentWeb"><i class="fa fa-info fa-lg" style="padding: 3px;"aria-hidden="true"></i>  <span>เนื้อหาเว็บไซต์</span></a></li>
        <!-- <li class="treeview" >
          <a href="#"><i class="fa fa-globe" aria-hidden="true"></i> <span>ติดต่อเรา</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i> 
            </span>
          </a>
          <ul class="treeview-menu contact_sel" style="display: <?=($_GET['page'] =="contact_sel")?"block":"none"?>;">
          </ul>
        </li>  -->
    
        <li id="contact_sel"><a href="<?php echo SITE_URL; ?>?page=contact_sel"><i class="fa fa-globe"></i> <span>ข้อมูลเว็บไซต์</span></a></li>
        <hr >
        <?php
        if ($_SESSION['leave_a_msg'] === 'yes') {
            ?>
                <li id="contact"><a href="<?php echo SITE_URL; ?>?page=contact"><i class="fa fa-inbox"></i><span> ข้อความติดต่อ </span></a></li>
                <?php
          }
        ?>
        <li class="treeview" >
          <a href="#"><i class="fas fa-landmark"></i> <span>บัญชีธนาคาร</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-down pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu bank" style="display: <?=($_GET['page'] =="manage_bank")?"block":"none"?>;">
            <li id="manage_bank"><a href="<?php echo SITE_URL; ?>?page=manage_bank"><i class="fas fa-circle"></i> <span>บัญชีธนาคาร</span></a></li>
          </ul>
        </li>
        <!-- <li id="subscribers"><a href="<?php echo SITE_URL; ?>?page=subscribers"><i class="fa fa-envelope"></i> <span><?php echo $LANG_LABEL['maillist']; ?></span></a></li>  -->

      <?php
        if ($_SESSION['role'] === 'superadmin' || $_SESSION['role'] === 'admin') {
      ?>
              <li id="admin"><a href="<?php echo SITE_URL; ?>?page=admin"><i class="fa fa-user-secret"></i> <span><?php echo $LANG_LABEL['admin']; ?></span></a></li>
      <?php
        }
      ?>
 

        <!-- <hr> -->
        <?php
      if ($_SESSION['role'] === 'superadmin') {
          ?>
                <li id="langconfig"><a href="<?php echo SITE_URL; ?>?page=langconfig"><i class="fa fa-language"></i> <span><?php echo $LANG_LABEL['langconfig']; ?></span></a></li>
              <?php
      }
        ?>
          <!-- <li id="map"><a href="<?php echo SITE_URL; ?>?page=map"><i class="fa  fa-map"></i> <span><?php echo $LANG_LABEL['map']; ?></span></a></li> -->
 
      <?php
        if ($_SESSION['role'] === 'superadmin' || $_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor') {
          ?>
             <!-- <li id="siteconfig"><a href="<?php echo SITE_URL; ?>?page=siteconfig"><i class="fa fa-globe"></i> <span><?php echo $LANG_LABEL['siteconfig']; ?></span></a></li> -->
          <?php
        }
        ?>

      <?php
    if ($_SESSION['role'] === 'superadmin') {
        ?>
            <li id="setting"><a href="<?php echo SITE_URL; ?>?page=setting"><i class="fa fa-cogs"></i> <span><?php echo $LANG_LABEL['settingsystem']; ?></span></a></li>
          <?php
    }
    ?>

      <li id=""><a href="#คู่มือ" target="__blank"><i class="fa fa-book"></i> <span><?php echo $LANG_LABEL['manual']; ?></span></span></a></li></li>
    </ul>
 

  </section>

    <!-- <footer class="main-footer" style="margin:0">
      <div class="pull-left hidden-xs">
        <b>Version</b> <?php echo WEB_VERSION; ?>
      </div>
      <strong>&nbsp;</strong>
    </footer> -->

</aside>