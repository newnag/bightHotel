<?php
 
session_start();
#PHP show error
error_reporting(1);
ini_set('display_errors', 1);


if(isset($_GET['dev'])){
  echo '<pre>';
  print_r($_SESSION);
  exit;
}

// if(!isset($_GET['me'])){ 
//   if(!isset($_SESSION['me'])){
//     exit();
//   }
// }

// $_SESSION['me'] = "";

#[*]Require ===================================================================

  require_once __DIR__.'/config/config.php'; 
  require_once __DIR__.'/classes/dbquery.php'; 
  require_once __DIR__.'/classes/Route.class.php'; #dev by kotbass
  require_once __DIR__.'/classes/WebSecurity.class.php'; #dev by kotbass
  require_once __DIR__.'/classes/FrontEnd.php';
  require_once __DIR__.'/classes/helper.php';
  require_once __DIR__.'/classes/application.class.php'; #dev by kotbass
  require_once __DIR__.'/classes/mobileDetect.class.php'; 
  require_once __DIR__.'/Components/components.php'; #dev by kotbass 

#[*]Require =================================================================== 
#[*]System    =================================================================
  #สร้าง instance Application ที่ extends มาจาก frontend และ Helper
  $App = new Application();

  #ตัวแปรเอาไว้เก็บค่า Category
  $CATEGORY = "";

  #ตัวแปรเอาไว้เก็บค่า Content
  $CONTENT = "";

  #ตัวแปรที่เอาไว้เช็คว่า ในการ request แต่ละครั้งนั้น ถูกต้องหรือไม่ เพื่อเอาไปใช้งานกับ Route Other
  $isRoute = false;

  #ฟังชั่น จำลองการ login
  // $App->testLogin(false);

  #แยก SLUG จาก URL และ return เป็น array มี key เป็นชื่อ level
  $Route = Route::slug();

  #ดึงค่า cate_id จาก table category ด้วย url เพื่อเอาไปทำ routing
  $cateID = $App->getCateIDByURL($Route['level_1']);

  #ตรวจสอบว่า มีการจองตัวไหน เกิน 6 ชั่วโมงไปแล้วบ้าง
  // $App->checkBookingTimeOver6Hour();

  #ตรวจสอบ Mobile
  $MOBILE_DETECT = new Mobile_Detect();
  $isDevice['webOS']   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
  $isDevice['desktop'] = stripos($_SERVER["HTTP_USER_AGENT"],"Windows");
  $isDevice['iPod']    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
  $isDevice['iPhone']  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
  $isDevice['iPad']    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
  $isDevice['android'] = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
  $myDevice = (($isDevice['desktop'] != "")  || ($isDevice['webOS'] != "" ) || $isDevice['iPad'] != "")?"browser":"mobile";

#[/*]System    ===================================================================================
 
#[*]TEST INSERT DB =============================================================

// $checkDevice = stripos($_SERVER['HTTP_USER_AGENT'],"Window");
// print_r($checkDevice);
// print_r($_SERVER['HTTP_USER_AGENT']);

// print_r(
//   $App->testInsertBooking([
//     "book_id" => sha1(uniqid(rand(),true)),
//     "book_date" => "2020-06-02",
//     "time_round_id" => rand(1,4),
//     "position_id" => rand(1,8),
//     "people_qty" => rand(5,10),
//     "name" => $App->randomString(50),
//     "phone" => "0123456789",
//     "line_id" => $App->randomString(20),
//     "province_id" => rand(1,20),
//     "type" => 'arch',`
//     "status" => 'pending', // 'booking' , 'checkout'
//     "date" => date('Y-m-d H:i:s'),
//   ])
//   );exit();
#[/*]TEST INSERT DB =============================================================
 

#[*]Route API AND SEO ===========================================================================
  #เป็น Route สำหรับ Ajax API
  require_once "routes/route.ajax.php";

  #จุดนี้จะเป็นตัวทำ Route SEO <head>
  require_once "routes/route.seo.php";
#[/*]Route API AND SEO ===========================================================================

#[*]ข้อมูลติดต่อเว็บ
# $CONTACT_WEB->field
$CONTACT_WEB = $App->getContactWeb();
 
#getheader
$MYNAV_MENU_TOP = $App->getNavtop($Route['level_1']);
$lang_config = $App->lang_config();
$thumbgenerator = ROOT_URL."classes/thumb-generator/thumb.php?src=".ROOT_URL;
$myAds = $App->get_slide_banner();
$_SESSION['encode_id'] ='wynn-bright';
 

#โฆษณา
// $ADS = $App->getImageAdsByPositionArr(
//   ['A1','A2','B1','B2','B3','C1','C2','C3','C4','D1','D2','D3','D4','F1','F2','F3','F4','G1','G2']
// );
?>
<!DOCTYPE html>
<html lang="th">
<?php 
#ส่วน <head> ของเว็บ

include ('template/mains/head-css.php');
#จุดนี้จะเป็นตัวทำ Route Category 
require_once "routes/route.php";
?>
</html>