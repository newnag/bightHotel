<?php

/**
 * เป็นไฟล์ Route สำหรับ Route ไปหาไฟล์ template ต่างๆ
 * Route แต่ละเส้นทางจะเหมือนกันกับ route.seo.php
 */

/**
 * #############################################################
 * ################# ROUTE CATEGORY ############################
 * #############################################################
 */ 
#[*]=======================================
#[*]            หน้าแรก
#[*]=======================================
Route::get(2, function () {
  global $App,$thumbgenerator,$myAds, $Route, $CATEGORY, $CONTENT, $CONTACT_WEB ,$MYNAV_MENU_TOP ,$lang_config;
  $home_facilities = $App->get_category_facilities_by_pin();
  // เกี่ยวกับเรา
  // $aboutus = $App->getCategoryFieldByCateID(5,"title,thumbnail,description");
  // บรรยากาศดี/ อาหารอร่อย
  // $goodAtmosphere = $App->getCategoryFieldByCateID(7,"title,description");
  // $CSRF_TIMERROUND = WebSec::generateCSRF('token-csrf-timeround');
  // $CSRF_PROVINCE = WebSec::generateCSRF('token-csrf-province');
  // $CSRF_POSITION_ARCH_TABLE = WebSec::generateCSRF('token-csrf-position-arch-table');
  // #csrf เวลาคลิ๊กเลือกเวลา/รอบ (ซุ้ม)
  $CSRF_TIMEROUND_ARCH = WebSec::generateCSRF('token-csrf-timeround-arch');
  // #csrf เวลาคลิ๊กเลือกเวลา/รอบ (โต๊ะ)
  // $CSRF_TIMEROUND_TABLE = WebSec::generateCSRF('token-csrf-timeround-table');
  // #csrf save booking (ซุ้ม)
  // $CSRF_BOOKING_ARCH = WebSec::generateCSRF('token-csrf-booking-arch');
  // #csrf save booking (โต๊ะ)
  //  $CSRF_BOOKING_TABLE = WebSec::generateCSRF('token-csrf-booking-table');
  $get = $App->get_room();
  $post = $App->get_content_by_id(81);
  $article = $App->get_content_by_id(83);


  require_once "template/home.php";
});

#[*]=======================================
#[*]            อื่นๆ
#[*]=======================================
Route::get('other', function () {
  global $App,$thumbgenerator,$myAds, $Route, $CATEGORY, $CONTENT, $CONTACT_WEB ,$MYNAV_MENU_TOP ,$lang_config;
});

// Route::get('other', function () {
//   global $Route,$cateID;
//   return "Route Other ".$cateID;
//   exit();
// }); 
 

#[*]=======================================
#[*]            หน้า ห้อง
#[*]=======================================
Route::get(3,function(){
  global $App,$thumbgenerator,$myAds, $Route, $CATEGORY, $CONTENT, $CONTACT_WEB ,$MYNAV_MENU_TOP ,$lang_config,$head;
  $dateInArr = explode("-", date('Y-m-d',strtotime($_SESSION['cart']['result']['datein'])));
  $dateOutArr =explode("-",date('Y-m-d',strtotime($_SESSION['cart']['result']['dateout']))); 
  $dateIn =  $dateInArr[2].'-'.$dateInArr[1].'-'.$dateInArr[0];
  $dateOut =  $dateOutArr[2].'-'.$dateOutArr[1].'-'.$dateOutArr[0];
   
  $lvl2 = $App->get_level_2($Route['level_2']); 
  $lvl3 = $Route['level_3'];
  $lvl4 = $Route['level_4'];

  $cart_list = $App->set_cart_detail();
  switch($lvl2['id']){
    case'9': #ห้องประชุม
             $article = $App->get_content_by_id(1);
             $meeting = $App->get_meeting_room(); 
             require_once "template/meeting.php";
          break;
    case'8': #ห้องพัก
            if( $dateIn != $lvl3 || $dateOut != $lvl4 ){
              unset($_SESSION['my_order']); 
            } 
            $date1=date_create($lvl3);
            $date2=date_create($lvl4);
            $diff = date_diff($date1,$date2);
            if($diff->invert > 0 || ($date1 == $date2)){
              echo "<script>location.href='".ROOT_URL."ห้อง/ห้องพัก' ;</script>";
            }
            $article = $App->get_content_by_id(2);
            $rooms = $App->get_all_product_detail($Route);
            $cart_result = $App->calculate_cost();
            require_once "template/room.php";
            echo"<script> $('#input_checkin').val('".$lvl3."') </script>";
            echo"<script> $('#input_checkout').val('".$lvl4."') </script>";
          break;
     default: echo "<script>location.href='".ROOT_URL."' ;</script>";
          break;
      
  }
   
  
});

#[*]=======================================
#[*]            หน้า โปรโมชั่น
#[*]=======================================
Route::get(5,function(){
  global $App,$thumbgenerator,$myAds, $Route, $CATEGORY, $CONTENT, $CONTACT_WEB ,$MYNAV_MENU_TOP ,$lang_config;
  $promotions = $App->get_promotion();
  $article = $App->get_content_by_id(76);
  require_once "template/promotion.php";
});

#[*]=======================================
#[*]            หน้า แกลเลอรี่ 
#[*]=======================================
Route::get(6,function(){
  global $App,$thumbgenerator,$myAds, $Route, $CATEGORY, $CONTENT, $CONTACT_WEB ,$MYNAV_MENU_TOP ,$lang_config;
  #แกลลอรี่
  $article = $App->get_content_by_id(79);
  $gallery = $App->getAllMyGalleryImage();
 
  require_once "template/gallery.php";
});


#[*]=======================================
#[*]            หน้า ติดต่อเรา
#[*]=======================================
Route::get(7,function(){
  global $App,$thumbgenerator,$myAds, $Route, $CATEGORY, $CONTENT, $CONTACT_WEB ,$MYNAV_MENU_TOP ,$lang_config;
  // $postAboutus = $App->getPostBySQL(5,"","","LIMIT 1");
  $contact = $App->get_contact_website();
  $article = $App->get_content_by_id(82);
 
  require_once "template/contact.php";
});


#[*]=======================================
#[*]            หน้า ยืนยันการจอง
#[*]=======================================
Route::get(17,function(){
  global $App,$thumbgenerator,$myAds, $Route, $CATEGORY, $CONTENT, $CONTACT_WEB ,$MYNAV_MENU_TOP ,$lang_config;
  if(count($_SESSION['my_order']) == 0){
      echo "<script>location.href='".ROOT_URL."ห้อง/ห้องพัก' ;</script>";
  }

  $article = $App->get_content_by_id(3);
  $orders = $App->get_confirm_order();
  $cart_result = $App->calculate_cost();
  $date_in = date("d/m/Y",strtotime($_SESSION['cart']['result']['datein']));
  $date_out = date("d/m/Y",strtotime($_SESSION['cart']['result']['dateout']));
  require_once "template/booking-room.php";
});

#[*]=======================================
#[*]            หน้า ยืนยันการชำระ
#[*]=======================================
Route::get(4,function(){
  global $App,$thumbgenerator,$myAds, $Route, $CATEGORY, $CONTENT, $CONTACT_WEB ,$MYNAV_MENU_TOP ,$lang_config,$head;
  if(!isset($_SESSION['payment_id'])){
    echo "<script>location.href='".ROOT_URL."ห้อง/ห้องพัก' ;</script>";
  }

  $article = $App->get_content_by_id(4);
  $getpost['id'] = $_SESSION['payment_id'];
  $detail = $App->get_detail_confirm_payment($getpost);
  $date_in = date("d/m/Y",strtotime($_SESSION['cart']['result']['datein']));
  $date_out = date("d/m/Y",strtotime($_SESSION['cart']['result']['dateout']));
  $check = $App->check_reservation_order_id($getpost);
  if(empty($check) && !isset($_SESSION['payment_id'])){
    //  echo"<script> location.href= '".ROOT_URL."'</script>";
  } 

  $slc_bank = $App->select_bank_option();
  require_once "template/confirm-room.php";

});

#[*]=======================================
#[*]            หน้า ประวัติการจอง
#[*]=======================================
Route::get(18,function(){
  global $App,$thumbgenerator,$myAds, $Route, $CATEGORY, $CONTENT, $CONTACT_WEB ,$MYNAV_MENU_TOP ,$lang_config;
  // $postAboutus = $App->getPostBySQL(5,"","","LIMIT 1");
  $tel = FILTER_VAR($Route['level_2'],FILTER_SANITIZE_NUMBER_INT);
  require_once "template/history.php";
  $check =  $App->check_history_result($tel);
  if(!$check){
    echo'<script>
        Swal.fire({
          width: "400px",
          text: "ไม่พบข้อมูลการจองเบอร์นี้!",
          icon: "error",
          confirmButtonText: "ตกลง"
        }).then((result)=>{
          location.href = hostname;
        }); 
      </script>';
  }

});





#[*]=======================================
#[*]            หน้าทดสอบ
#[*]=======================================
Route::get('/test/image-preload', function () {
  global $Route,$cateID;
  require_once "template/testImagePreload.php";
});


