<?php

/**
 * เป็นไฟล์ Route สำหรับ Ajax 
 * เพื่อที่จะ Route ไปหาไฟล์ ajax 
 * จะไม่ ajax แบบไฟล์ตรงๆ แต่จะใช้ Route เป็นตัวกำหนด
 */

#TEST------------------------------------------------------------------------------
Route::resource('/api/v1.0/test', function () {
  global $App;

  #ตรวจสอบ http request header
  if (!Websec::isHTTPRequestHeaders()) {
    echo json_encode(["message" => "isRequestHeaders Error"]);
    exit();
  }

  #ตรวจสอบว่า login แล้วหรือยัง ถ้ายัง
  if ($App->isLogin(false)) {
    header('Location: /');
    exit();
  }

  #ตรวจสอบ csrf
  $csrf = Websec::getHttpRequestHeaders("token-csrf", "csrf");
  if (!Websec::verifyCSRF($_SESSION['csrf_record_buy'], $csrf)) {
    $newCSRF = Websec::generateCSRF("csrf_record_buy");
    $res = ["status" => 400, "status_receive" => false, "message" => "csrf_invalid", "newcsrf" => base64_encode($newCSRF)];
    echo json_encode([
      "res" => $res
    ]);
    exit();
  }

  echo json_encode([
    "method" => $_SERVER['REQUEST_METHOD'],
    "server" => $_SERVER
  ]);
  exit();
});
Route::get('/test',function(){
  $numbers = [-1,0,1,'-1','0','1','','a'];
  foreach($numbers as $key => $number){
    echo is_numeric($number)?"Number {$number}":"Not Number {$number}";
    echo "<br>";
  }
  exit();
});
#TEST------------------------------------------------------------------------------




#[*]=======================================
#[*]             หน้าแรก
#[*]=======================================

#ดึงเวลารอบ
Route::get('/api/v1.0/timeround', function () {
  global $App;
  require_once "api/juad/api.booking.php";

  $input = json_encode(file_get_contents("php://input"));

  #ตรวจสอบ http request header
  if (!WebSec::isHTTPRequestHeaders()) {
    echo json_encode([
      "message" => "HTTP_Header_Invalid",
      "status_" => false
    ]);
    exit();
  }

  // ตรวจสอบ csrf
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf-timeround", "csrf");
  if (!WebSec::verifyCSRF($_SESSION['token-csrf-timeround'], $csrf)) {
    echo json_encode([
      "message" => "Token_CSRF_timeround_Invalid",
      "status_" => false
    ]);
    exit();
  }


  $newCSRF = WebSec::generateCSRF("token-csrf-timeround");

  $booking = new Booking();
  $timeRoundArch = $booking->getTimeRound('arch');
  $timeRoundTable = $booking->getTimeRound('table');
  echo json_encode([
    "newcsrf" => base64_encode($newCSRF),
    "status_" => true,
    "message" => "success",
    "timeround_arch" => $timeRoundArch,
    "timeround_table" => $timeRoundTable,
  ]);
  exit();
});


#ดึงรายชื่อจังหวัด
Route::get('/api/v1.0/province', function () {
  global $App;

  #ตรวจสอบ http request header
  if (!WebSec::isHTTPRequestHeaders()) {
    echo json_encode([
      "message" => "HTTP_Header_Invalid",
      "status_" => false
    ]);
    exit();
  }


  #ตรวจสอบ CSRF
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf-province", "csrf");
  if (!WebSec::verifyCSRF($_SESSION['token-csrf-province'], $csrf)) {
    echo json_encode([
      "status_" => false,
      "message" => "Token_CSRF_Province_Invalid"
    ]);
    exit();
  }

  require_once "api/juad/api.province.php";
  $province = new Province();

  #ดึงข้อมูลจังหวัด
  $resprovince = $province->getProvince();
  #reden html จังหวัด
  $renderProvince = $province->render_province($resprovince);
  #สร้าง csrf ใหม่
  $newCSRF = WebSec::generateCSRF('token-csrf-province');
  echo json_encode([
    "status_" => true,
    "province" => $renderProvince,
    "newcsrf" => base64_encode($newCSRF),
    "message" => "success"
  ]);
  exit();
});


#ดึงตำแหน่งที่นั่ง ซุ้มและโต๊ะ
Route::get('/api/v1.0/positionArchAndTable', function () {
  global $App;

  #ตรวจสอบ http request header
  if (!WebSec::isHTTPRequestHeaders()) {
    echo json_encode([
      "message" => "HTTP_Header_Invalid",
      "status_" => false
    ]);
    exit();
  }


  #ตรวจสอบ CSRF
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf-position-arch-table", "csrf");
  if (!WebSec::verifyCSRF($_SESSION['token-csrf-position-arch-table'], $csrf)) {
    echo json_encode([
      "status_" => false,
      "message" => "Token_CSRF_Province_Invalid"
    ]);
    exit();
  }

  require_once "api/juad/api.position.php";
  $newCSRF = WebSec::generateCSRF('token-csrf-position-arch-table');
  $position = new Position();
  $resPositionArch = $position->getPosition('arch');
  $renderPositionArch = $position->render_position($resPositionArch);

  $resPositionTable = $position->getPosition('table');
  $renderPositionTable = $position->render_position($resPositionTable);

  $renderHandlePositionArch = $position->render_handleclick_position($resPositionArch, "arch");
  $renderHandlePositionTable = $position->render_handleclick_position($resPositionTable, "table");


  echo json_encode([
    "status_" => true,
    "positionArch" => $renderPositionArch,
    "positionTable" => $renderPositionTable,
    "hdlClickpositionArch" => $renderHandlePositionArch,
    "hdlClickpositionTable" => $renderHandlePositionTable,
    "newcsrf" => base64_encode($newCSRF),
    "message" => "success"
  ]);
  exit();
});


#เมื่อกดเลือกเวลารอบเวลา (ซุ้ม) จะเอาข้อมูล (วันเดือนปี และ เวลา) ไปหาข้อมูล และเอามาโชว์ว่า ซุ้มไหนถูกจอง
Route::post('/api/v1.0/positionArchWithTimeround', function () {
  global $App;

  #ตรวจสอบ http request header
  if (!WebSec::isHTTPRequestHeaders()) {
    echo json_encode([
      "message" => "HTTP_Header_Invalid",
      "status_" => false
    ]);
    exit();
  }


  #ตรวจสอบ CSRF
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf", "csrf");
  if (!WebSec::verifyCSRF($_SESSION['token-csrf-timeround-arch'], $csrf)) {
    echo json_encode([
      "status_" => false,
      "message" => "Token_CSRF_Invalid"
    ]);
    exit();
  }

  require_once "api/juad/api.booking.php";
  $booking = new Booking();
  $input = json_decode(file_get_contents("php://input"));
  $res = $booking->getPositionArchWithDateAndTimeround($input->date, $input->timeround);
  echo $res;
  exit();
});

#เมื่อกดเลือกเวลารอบเวลา (โต๊ะ) จะเอาข้อมูล (วันเดือนปี และ เวลา) ไปหาข้อมูล และเอามาโชว์ว่า โต๊ะไหนถูกจอง
Route::post('/api/v1.0/positionTableWithTimeround', function () {
  global $App;

  #ตรวจสอบ HTTP Request Headers
  if (!WebSec::isHTTPRequestHeaders()) {
    echo json_encode([
      "message" => "HTTP_Header_Invalid",
      "status_" => false
    ]);
    exit();
  }

  #ตรวจสอบ csrf
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf", "csrf");
  if (!WebSec::verifyCSRF($_SESSION['token-csrf-timeround-table'], $csrf)) {
    echo json_encode([
      "message" => "Token_CSRF_Invalid",
      "status_" => false
    ]);
    exit();
  }

  require_once "api/juad/api.booking.php";
  $booking = new Booking();
  $input = json_decode(file_get_contents("php://input"));
  $res = $booking->getPositionTableWithDateAndTimeround($input->date, $input->timeround);
  echo $res;
  exit();
});


#เมื่อคลิกเลือกซุ้มจะต้องไปเช็คดูว่า ว่างอยู่หรือไม่ (วันเดือนปี และ เวลา และ position_id)


#Booking (บันทึก)
Route::post('/api/v1.0/booking/:action', function ($action) {
  global $App;

  #ตรวจสอบ HTTP Request Header
  if (!WebSec::isHTTPRequestHeaders()) {
    echo json_encode([
      "message" => "HTTP_Header_Invalid",
      "status_" => false
    ]);
    exit();
  }

  $input = json_decode(file_get_contents("php://input"));

  #ตรวจสอบ csrf
  $csrf = WebSec::getHTTPRequestHeaders("Authorization", "Bearer");
  if ($input->action == "arch") {
    if (!WebSec::verifyCSRF($_SESSION['token-csrf-booking-arch'], $csrf)) {
      echo json_encode([
        "message" => "Token_CSRF_Invalid",
        "status_" => false
      ]);
      exit();
    }
  } else if ($input->action == "table") {
    if (!WebSec::verifyCSRF($_SESSION['token-csrf-booking-table'], $csrf)) {
      echo json_encode([
        "message" => "Token_CSRF_Invalid",
        "status_" => false
      ]);
      exit();
    }
  }

  require_once "api/juad/api.booking.php";
  $booking = new Booking();

  #ตรวจสอบว่า ซุ้มหรือโต๊ะที่จองนั้นมีมากกว่า 1 หรือไม่
  if(is_numeric($input->table)){
    if ($input->action == "arch") {
      #บันทึกข้อมูล (ซุ้ม)
      $res = $booking->store("arch",$input);
    }
    else if ($input->action == "table") {
      #บันทึกข้อมูล (โต๊ะ)
      $res = $booking->store("table",$input);
    }
  }else{
    $tables = explode(",",$input->table);
    
    if ($input->action == "arch") {
      #บันทึกข้อมูล (ซุ้ม)
      $res = $booking->storeMulti("arch",$input);
    }
    else if ($input->action == "table") {
      #บันทึกข้อมูล (โต๊ะ)
      $res = $booking->storeMulti("table",$input);
    }
  }

  echo $res;
  exit();
});

// ตรวจสอบว่า ได้ทำการ register line แล้วหรือยัง
Route::get('/api/v1.0/checkregister/:phone/line',function($phone){
  global $App;
  require_once "api/juad/api.booking.php";
  $booking = new Booking();
  $res = $booking->checkRegisterLine($phone);
  echo $res;
  exit();
});


#[*]=======================================
#[*]            อัพโหลด รูปภาพ
#[*]=======================================
Route::post('/api/v1.0/uploadimg',function(){
  global $App;

  #ตรวจสอบ HTTP Request Headers
  if(!WebSec::isHTTPRequestHeaders()){
    echo json_encode([
      "message" => "HTTP_Request_Invalid",
      "status_upload" => false
    ]);exit();
  }

  #ตรวจสอบ csrf
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf-upload","csrf");
  if(!WebSec::verifyCSRF($_SESSION['token-csrf-upload'],$csrf)){
    $newCSRF = WebSec::generateCSRF('token-csrf-upload');
    echo json_encode([
      "res" => [
        "message" => "Token_CSRF_Upload_Invalid",
        "status_upload" => false,
      ],
      "csrf" => base64_encode($newCSRF)
    ]);exit();
  }
  
  require_once "api/juad/api.upload.php";
  $upload = new Upload();
  $res = $upload->uploadImg(null,$_FILES['img'],$csrf);

  $newCSRF = WebSec::generateCSRF('token-csrf-upload');

  echo json_encode([
    "res" => $res,
    "csrf" => base64_encode($newCSRF)
  ]);
  exit();
});




#[*]=======================================
#[*]            หน้า ยืนยันการจอง
#[*]=======================================
Route::get('/api/v1.0/myListBooking',function(){
  global $App;

  #ตรวจสอบ HTTP Request Headers
  if (!WebSec::isHTTPRequestHeaders()) {
    echo json_encode([
      "message" => "HTTP_Header_Invalid",
      "status_" => false
    ]);
    exit();
  }

  #ตรวจสอบ csrf
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf", "csrf");
  if (!WebSec::verifyCSRF($_SESSION['token-csrf-listbooking'], $csrf)) {
    echo json_encode([
      "message" => "Token_CSRF_Invalid",
      "status_" => false
    ]);
    exit();
  }
  
  require_once "api/juad/api.booking.php";
  $booking = new Booking();
  
  $phone = WebSec::getHTTPRequestHeaders("phone","phone");
  $res = $booking->getBookingWithPhone($phone);

  echo $res;
  exit();
});
Route::post('/api/v1.0/Booking',function(){
  global $App;

  #ตรวจสอบ HTTP Request Header
  if(!WebSec::isHTTPRequestHeaders()){
    echo json_encode([
      "message" => "HTTP_Request_Header_Invalid",
      "status_" => false
    ]);exit();
  }

  #ตรวจสอบ csrf
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf-booking","csrf");
  if(!WebSec::verifyCSRF($_SESSION['token-csrf-booking'],$csrf)){
    echo json_encode([
      "message" => "Token_CSRF_Booking_Invalid",
      "status_" => false
    ]);exit();
  }

  require_once "api/juad/api.booking.php";
  $booking = new Booking();
  $input = json_decode(file_get_contents("php://input"));
  $res = $booking->savePayment($input);

  echo $res;
  exit();
});
Route::delete('/api/v1.0/Booking',function(){
  global $App;

  #ตรวจสอบ HTTP Request Header
  if(!WebSec::isHTTPRequestHeaders()){
    echo json_encode([
      "message" => "HTTP_REUQEST_HEADERS_INVALID","status_" => false
    ]); exit();
  }

  #ตรวจสอบ csrf
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf","csrf");
  if(!WebSec::verifyCSRF($_SESSION['token-csrf-booking-delete'],$csrf)){
    echo json_encode([
      "message" => "Token_CSRF_Invalid" , "status_" => false
    ]); exit();
  }

  require_once "api/juad/api.booking.php";
  $booking = new Booking();
  $input = json_decode(file_get_contents("php://input"));
  $res = $booking->deleteBookingWithBookID($input->booking_id);
  echo json_encode($res);

  exit();
});
Route::get('/api/v1.0/checktimebooking/:bookid',function($bookid){
  global $App;

  #ตรวจสอบ HTTP Request Headers
  if(!WebSec::isHTTPRequestHeaders()){
    echo json_encode([
      "message" => "HTTP_Request_Headers" , "status_" => false
    ]); exit();
  }

  require_once "api/juad/api.booking.php";
  $booking = new Booking();
  $res = $booking->checkTimeBooking6Hour($bookid);
  echo json_encode([
    "res" => $res
  ]);
  exit();
});



#[*]=======================================
#[*]            หน้า แกลเลอรี่
#[*]=======================================
Route::get('/api/v1.0/gallerys/:action/:page',function($action,$page){
  global $App;

  #ตรวจสอบ HTTP Request Headers
  if(!WebSec::isHTTPRequestHeaders()){
    echo json_encode([
      "message" => "HTTP_Request_Headers" , "status_" => false
    ]); exit();
  }

  #ตรวจสอบ csrf (ไม่ต้องตรวจ)
  

  #ตรวจสอบ $page ว่าเป็น number หรือไม่
  if(!is_numeric($page)){
    echo json_encode([
      "message" => "page invalid" , "status_" => false
    ]); exit();
  }

  require_once "api/juad/api.gallery.php";
  $gallery = new Gallery();

  #จำนวน item ที่ต้องการให้โชว์ ในหน้านั้นๆ
  $perpage = 10;
  #หน้าที่ user กำลังดู
  $page = $page;

  switch($action){
    case 'all': 
      $pagination = $gallery->pagination(null,$perpage,$page); //(cateid,perpage,page)
      $res = $gallery->getDataPostImageWidthCateID(null,$pagination['start'],$pagination['perpage']);
    break;
    case 'atmosphere': 
      $pagination = $gallery->pagination(8,$perpage,$page);    //(cateid,perpage,page)
      $res = $gallery->getDataPostImageWidthCateID(8,$pagination['start'],$pagination['perpage']);
    break;
    case 'food': 
      $pagination = $gallery->pagination(9,$perpage,$page);    //(cateid,perpage,page)
      $res = $gallery->getDataPostImageWidthCateID(9,$pagination['start'],$pagination['perpage']);
    break;
    case 'review': 
      $pagination = $gallery->pagination(10,$perpage,$page);    //(cateid,perpage,page)
      $res = $gallery->getDataPostImageWidthCateID(10,$pagination['start'],$pagination['perpage']);
    break;
  }
  #render รูปภาพ บรรยากาศ อาหาร รีวิวลูกค้า
  $res = $gallery->renderPostImages($res);
  



  echo json_encode([
    "message" => "success",
    "status_" => true,
    "images" => $res,
    "pagination" => $pagination
  ]);
  exit();
});





#[*]=======================================
#[*]            หน้า ติดต่อสอบถาม
#[*]=======================================
Route::post('/api/v1.0/contactweb',function(){
  global $App;

  #ตรวจสอบ HTTP Request Headers
  if(!WebSec::isHTTPRequestHeaders()){
    echo json_encode([
      "message" => "HTTP_HEADERS_INVALID" , "status_" => false
    ]); exit();
  }

  #ตรวจสอบ csrf
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf","csrf");
  if(!WebSec::verifyCSRF($_SESSION['token-csrf-contact'],$csrf)){
    echo json_encode([
      "message" => "TOKEN_CSRF_INVALID" , "status_" => false
    ]); exit();
  }

  require_once "api/juad/api.contact.php";
  $input = json_decode(file_get_contents("php://input"));
  $contact = new Contact();
  $res = $contact->saveContact($input);
  echo $res;
  exit();
});



#[*]=======================================
#[*]            ค้นหาการจองด้วยเบอร์
#[*]=======================================
Route::get('/api/v1.0/searchq/phone/:phone',function($phone){
  global $App;

  #ตรวจสอบ HTTP Request Headers
  if(!WebSec::isHTTPRequestHeaders()){
    echo json_encode([
      "message" => "HTTP_HEADERS_INVALID" , "status_" => false
    ]); exit();
  }

  #ตรวจสอบ csrf
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf-searchq","csrf");
  if(!WebSec::verifyCSRF($_SESSION['token-csrf-searchq'],$csrf)){
    echo json_encode([
      "message" => "TOKEN_CSRF_INVALID" , "status_" => false
    ]); exit();
  }

  require_once "api/juad/api.booking.php";
  $booking = new Booking();
  $res = $booking->getBookingURLWithPhone($phone);
  echo $res;
  exit();
});
Route::get('/api/v1.0/myListBooking/search',function(){
  global $App;

  #ตรวจสอบ HTTP Request Headers
  if (!WebSec::isHTTPRequestHeaders()) {
    echo json_encode([
      "message" => "HTTP_Header_Invalid",
      "status_" => false
    ]);
    exit();
  }

  #ตรวจสอบ csrf
  $csrf = WebSec::getHTTPRequestHeaders("token-csrf", "csrf");
  if (!WebSec::verifyCSRF($_SESSION['token-csrf-listbooking'], $csrf)) {
    echo json_encode([
      "message" => "Token_CSRF_Invalid",
      "status_" => false
    ]);
    exit();
  }
  
  require_once "api/juad/api.booking.php";
  $booking = new Booking();
  
  $phone = WebSec::getHTTPRequestHeaders("phone","phone");
  $res = $booking->getBookingWithPhone_($phone);

  echo $res;
  exit();
});



