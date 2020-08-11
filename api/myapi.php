<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);
// use \Firebase\JWT\JWT;
require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../classes/dbquery.php';
require_once __DIR__.'/../classes/FrontEnd.php';
require_once __DIR__.'/../classes/helper.php';
require_once __DIR__.'/../classes/application.class.php';
require_once __DIR__.'/../classes/WebSecurity.class.php';
// require_once './../src/autoload.php';      // ReCaptcha 
 

  #ตรวจสอบ Mobile
  $isDevice['webOS']   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
  $isDevice['desktop'] = stripos($_SERVER["HTTP_USER_AGENT"],"Windows");
  $isDevice['iPod']    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
  $isDevice['iPhone']  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
  $isDevice['iPad']    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
  $isDevice['android'] = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
  $myDevice = (($isDevice['desktop'] != "")  || ($isDevice['webOS'] != "" ) || $isDevice['iPad'] != "")?"browser":"mobile";
  $thumbgenerator = ROOT_URL."classes/thumb-generator/thumb.php?src=".ROOT_URL;

class myapi extends Application
{
  
   public function __construct($method)
   {
       parent::__construct();
       //ตรวจสอบว่ามีฟังก์ชั่นในคลาสไหมถ้ามีให้เรียกใช้งาน
       if (method_exists($this, $method)) {
         $this->App = new Application();
         $this->secret = "6Lf24NUUAAAAAAHeqYYExorg0cDxpKAm3Nedh_op";
         $this->$method();
       } else {
         echo 'Permission denied';
         exit;
       }
   }

  public function get_more_room_detail(){
    global $thumbgenerator,$myDevice;
    $xSize = ($myDevice === "browser")? "x500":"x250";
    $room = FILTER_VAR($_POST['room'],FILTER_SANITIZE_STRING,FILTER_SANITIZE_MAGIC_QUOTES);
    $sql ="SELECT * FROM room_product WHERE room_code = :room AND room_status = 'active' ";
    $result = $this->fetchObject($sql,[":room"=>$room]);
    if(!empty($result)){
      $facArr = $this->get_all_facility('detail');
      $getImg = $this->get_more_room_image($room,"product");
      $facList = explode(",",$result->room_facility);
      $setFac='';
      if(!empty($facList)){
        foreach($facList as $numb){
          $setFac .= $facArr[$numb];
        }
      }
      $setImage = "";
      if(isset($result->room_thumbnail)){
        $setImage = '<figure><img class="active" src="'.$thumbgenerator.$result->room_thumbnail.'&size='.$xSize.'" data-src="'.ROOT_URL.$result->room_thumbnail.'" alt="ภาพประกอบห้อง '.$result->title.'"></figure>';
        $setImage .= $getImg[$result->room_id];
      }
      $ret['facility'] = $setFac;
      $ret['images'] = $setImage;
      $ret['id'] = $result->room_code;
      $ret['type_name'] = $result->room_type_name;
      $ret['title'] = $result->room_title;
      $ret['description'] = $result->room_description;
      $ret['price'] = number_format($result->room_price);
      $ret['curprice'] = number_format($result->room_current_price);
      $ret['discount'] = $result->room_discount;
      $ret['extra'] = $result->room_extra;
      $ret['thumbnail'] = ROOT_URL.$result->room_thumbnail;
      $ret['message'] = "success";
      echo json_encode($ret); 
      exit();
    }else{
        echo json_encode([
            'message' => 'error',
            'detail'  => "invalid_room"
        ]); exit();
    }
  }

  public function check_room_available_on_date(){ 
    $room = FILTER_VAR($_POST['room'],FILTER_SANITIZE_STRING,FILTER_SANITIZE_MAGIC_QUOTES);
    $adult = FILTER_VAR($_POST['adult'],FILTER_SANITIZE_NUMBER_INT);
    $child = FILTER_VAR($_POST['child'],FILTER_SANITIZE_NUMBER_INT); 
    $set =  $this->set_date_format($_POST['date_in'],$_POST['date_out']);
    $date_in = $set['date_in'];
    $date_out = $set['date_out'];
    $getpost['datein'] = $date_in;
    $getpost['dateout'] = $date_out;
    $getpost['room'] = $room;
    $result = $this->check_room_available_object($getpost);
    /* sql concept 
    *  between คือห้องที่มีการจองอยู่แล้วช่วงระหว่าง in และ out 
    *  รูปแบบคือ [] = between ,n = in ,o = out 
    *  [ n o ]  เข้าทีหลัง ออกก่อน 
    *  [ n ] o  เข้าทีหลัง ออกทีหลัง 
    *  n [ o ]  เข้าก่อน ออกระหว่าง 
    */

    if(empty($result)){
      $ret['description'] = "not_found_list";
      $sql = "SELECT * FROM room_product WHERE room_code = :room AND room_status = 'active' ";
      $result = $this->fetchObject($sql,[":room"=>$room]);
    }

    $resetArr = $this->reset_cart($date_in,$date_out);
    $amount = (!isset($result->amount))?0:$result->amount;
    $amount += count($_SESSION['my_order'][$room]);
    $discount = (isset($_SESSION['discount'][$result->room_code]))?$_SESSION['discount'][$room]['discount']:0;
    if(($result->room_amount - $amount) > 0){  
      $set_position = count($_SESSION['my_order'][$room]);
      $_SESSION['my_order'][$room][$set_position]['position'] = $set_position;
      $_SESSION['my_order'][$room][$set_position]['id'] = $room;
      $_SESSION['my_order'][$room][$set_position]['room'] = $result->room_type_name;
      $_SESSION['my_order'][$room][$set_position]['price'] = $result->room_current_price;
      $_SESSION['my_order'][$room][$set_position]['extra'] = $result->room_extra;
      $_SESSION['my_order'][$room][$set_position]['adult'] = ($adult>1)?$adult:1;
      $_SESSION['my_order'][$room][$set_position]['child'] =($child>0)?$child:0; 

      $ret['message'] = "success";
      $ret['detail'] = "room_available";
      $ret['room'] = $result->room_type_name;
      $ret['order'] =  $_SESSION['my_order'];  
 
    } else {
      $ret['message'] =  'error';
      $ret['detail'] =  'not_available';
    }
    if(!empty($_SESSION['my_order']) ){
      $ret['html'] = $this->set_cart_detail();
      $ret['cart'] = $this->calculate_cost();
    } 

    echo json_encode($ret);
    exit();
  }

  public function reserve_remove_room_id(){
    $room = FILTER_VAR($_POST['id'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
    if(isset($_SESSION['my_order'][$room])){
      unset($_SESSION['my_order'][$room]);

      $ret['cart'] = $this->calculate_cost(); 
      $ret['message'] = "successfully";
      
    }else{
      $ret['message'] ="error"; 
    }
    $ret['id'] = $room;
    echo json_encode($ret); 
    exit();
  }

  // public function gift_voucher(){
  //     $code = FILTER_VAR($_POST['code'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
  //     $discount = $this->check_voucher_code($code);
  //     if($discount !== 'error'){
  //       $_SESSION['discount'][$discount->pro_code]['id'] = $discount->pro_roomtype_id;
  //       $_SESSION['discount'][$discount->pro_code]['discount'] = $discount->discount;
  //     }
  //     $html = $this->set_cart_detail();
  //     return $html;
  // }

 
 
  public function get_image_gallery(){
    global $thumbgenerator,$myDevice;
    $setSize = ($myDevice !== "mobile")?"&size=x700":"&size=x300";
    $page = (FILTER_VAR(ceil($_POST['number']),FILTER_SANITIZE_NUMBER_INT));
    $page = (isset($page))?$page:1;
    $limit = 15;
    $beginn = ($limit * $page);
    $sql ="SELECT * FROM gallery_image WHERE display = 'yes'  ORDER BY priority ASC  LIMIT ".$beginn.",".$limit;
    $result = $this->fetchAll($sql,[]);
    
    if(!empty($result)){
      $ret = '';
      foreach($result as $key => $val){
        $ret .= '<figure><img src="'.$thumbgenerator.$val['thumbnail'].$setSize.'" alt="'.$val['title'].'"></figure> ';
      }
      echo json_encode([
        'size' => $setSize,
        'amount' => count($result),
        'message' => 'success',
        'detail'  => "images",
        'images'  => $ret
      ]); exit();
    }else{
      echo json_encode([
        'message' => 'error',
        'detail'  => "no_more_image"
      ]); exit();
    }
  }

  public function decrease_room(){
    $room = FILTER_VAR($_POST['id'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
    if(isset($_SESSION['my_order'][$room])){
      array_pop($_SESSION['my_order'][$room]);

      if(count($_SESSION['my_order'][$room]) < 1){
        unset($_SESSION['my_order'][$room]);
      }
      $ret = [
        "html" => $this->set_cart_detail(),
        "cart" => $this->calculate_cost(),
        "room" => $room,
        "message" => "successfully",
        "status" => "OK"
      ];

    }else {
      $ret = [
        "message" => "not_in_order",
        "status" => "error"
      ];
    }
    echo json_encode($ret);
    exit();
  }

  public function test_function(){
    $html = $this->set_cart_detail();
    echo json_encode([
      'html' => $html,
      'message'=>"OK"
    ]);
  }

  public function submit_contact(){

    $getpost['name'] = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
    $getpost['lastname'] = FILTER_VAR($_POST['lastname'],FILTER_SANITIZE_MAGIC_QUOTES);
    $getpost['tel'] = FILTER_VAR($_POST['tel'],FILTER_SANITIZE_NUMBER_INT);
    $getpost['email'] = FILTER_VAR($_POST['email'],FILTER_SANITIZE_EMAIL);
    $getpost['subject'] = FILTER_VAR($_POST['subject'],FILTER_SANITIZE_MAGIC_QUOTES);
    $getpost['message'] = FILTER_VAR($_POST['message'],FILTER_SANITIZE_MAGIC_QUOTES);

    $table = "leave_msg";
    $field = "fullname,email,phone,topic,message,submit_date";
    $param = ":fullname,:email,:phone,:topic,:message,:submit_date";						 
    $value = array(	 
      ":fullname"=> $getpost['name']." ".$getpost['lastname'],
      ":email"=>$getpost['email'],
      ":phone"=> $getpost['tel'],
      ":topic"=> $getpost['subject'],
      ":message"=> $getpost['message'],
      ":submit_date" => date('Y-m-d H:i:s') 
    );
    $ret = $this->insertPrepare($table, $field,$param, $value);

    $message = $this->form_message($getpost);
    /* get details before send mail */
    $getMail = "SELECT info_id,info_type,info_title,text_title,info_link,attribute FROM  web_info WHERE info_type = 'system_email' ORDER BY info_id ASC ";
    $resultMail = $this->fetchAll($getMail,[]); 
    $getContact = "SELECT title,thumbnail,email FROM contact_sel";
    $resultContact = $this->fetchObject($getContact,[]); 
      
      /* ส่งอีเมลแจ้งข้อมูลการสั่งซื้อสินค้า */
      // $setPW = base64_encode("w9y3n7n5s"."password1234");
      $mail = array();  
      $mail['host'] = trim($resultMail[0]['attribute']);
      $mail['port'] = trim($resultMail[1]['attribute']); 
      $mail['user'] = trim($resultMail[2]['attribute']);
      $mail['password'] = str_replace("w9y3n7n5s","",(base64_decode($resultMail[3]['attribute']))); 
      $mail['logo_web'] = $resultContact->logo; 
      $mail['store_name'] = $resultContact->title; 
      $mail['cont_name'] = $getpost['name'];
      $mail['cont_lastname'] = $getpost['lastname'];
      $mail['cont_email'] = $getpost['email'];
      $mail['cont_tel'] = $getpost['tel'];

      ob_start();
      $statusEmail = $this->send_email_google(  
        array(   
          'SMTP_USER' => $mail['user'],
          'SMTP_PASSWORD' => $mail['password'],
          'SMTP_HOST' => $mail['host'],
          'SMTP_PORT' => $mail['port'],
          'mail_system' => $resultContact->email, 
          'sendFromName' => $mail['store_name'],
          'email' => $resultContact->email,
          'subject' => $getpost['subject'],
          'addAddress' => [
            [
              'email' => $mail['cont_email'],
              'name'=>  $mail['cont_name']." ".$mail['cont_lastname']
            ]
          ],
          'addBcc' => array( 
                  array( 
                    'email' => $mail['cont_email'],
                    'name'=>  $mail['cont_name']." ".$mail['cont_lastname']
                  ),
                ), 
          'content' =>  $message, 
        )
    );
    ob_end_clean();
    if($statusEmail){
      $ret['message'] = "OK";
      echo json_encode($ret); 
    }
    
  }

  public function discount_code(){
     $code = FILTER_VAR($_POST['code'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
     $curdate = date("Y-m-d H:i:s");
     $sql ="SELECT rsp.*,rp.room_code FROM reserve_promotion as rsp 
            INNER JOIN room_product as rp ON rp.room_id = rsp.pro_roomtype_id 
            WHERE rsp.pro_status = 'publish' 
                  AND rp.room_status = :room_status
                  AND rsp.pro_code = :code 
                  AND rsp.pro_date_available <= :current 
                  AND rsp.quota > 0 
                  AND rsp.pro_date_expire >= :current";
     $result  = $this->fetchObject($sql,[":room_status"=> "active",":code"=>$code,":current"=>$curdate]);
     if(!empty($result)){
        $_SESSION['discount'][$result->room_code]['code'] = $result->pro_code;
        $_SESSION['discount'][$result->room_code]['thumbnail']= $result->pro_thumbnail;
        $_SESSION['discount'][$result->room_code]['discount'] = $result->discount;
        $_SESSION['discount'][$result->room_code]['quota'] = $result->quota;
        $_SESSION['discount'][$result->room_code]['name']= $result->pro_name;
        $_SESSION['discount'][$result->room_code]['description']= $result->pro_description;
        $_SESSION['discount'][$result->room_code]['roomtype_id']= $result->pro_roomtype_id;
        $item['status']= 'success';
        $item['message']=  "available";
      
     }else{
        $item['status']= 'error';
        $item['message']=  "not_available";
     }
     $item['cart'] = $this->calculate_cost();
     echo json_encode($item);
  }

  public function form_message($getpost){
     $html = '<div style=" background: url('.ROOT_URL.'img/new-bg.jpg); padding:20px; max-width: 768px; color:#c93; font-size: 16px" >
                <article  style="padding: 20px 0px; border: 1px solid #c93; margin: auto;  background:#22324b; font-family: LucidaGrande,tahoma,verdana,arial,sans-serif;">
                  <div style=" width:300px; margin:auto;">
                      <img src="'.ROOT_URL.'img/logo-01.png" style=" width:100%;">
                  </div>
                    <div style="padding:5px 5%;   text-align: center "> 
                        <div style="text-align: left; border-top: 2px solid #996445; ">
                          <div style="line-height: 30px;">
                              <span style="font-weight:bold;"><h1>ข้อมูลผู้ติดต่อ</h1></span> 
                          </div>
                          <div style="line-height: 30px;">
                              <span style="font-weight:bold;">ชื่อ:</span> 
                              <span>'.$getpost['name'].' '.$getpost['lastname'].'</span> 
                          </div>
                          <div style="line-height: 30px;">
                              <span style="font-weight:bold;">โทร:</span> 
                              <span>'.$getpost['tel'].'</span> 
                          </div> 
                          <div style="line-height: 30px;">
                              <span style="font-weight:bold;">เรื่อง:</span> 
                              <span>'.$getpost['subject'].'</span> 
                          </div>
                          <div style="line-height: 30px;">
                              <span style="text-indent: 5%;">
                                  <p> '.$getpost['message'].' </p>
                              </span> 
                          </div>
                        </div>
                    </div>
                </article>
              </div>';

    return $html;
  }

  public function formBookingEmail(){
    $css_body = 'background: url(' . 'img/new-bg.jpg); padding:20px; color:#c93; font-size: 16px; width:768px; margin:auto';
    $css_article = 'background:#22324b; font-family: LucidaGrande,tahoma,verdana,arial,sans-serif; padding: 20px 0px; border: 1px solid #c93;';
    $css_logo = 'width:300px; margin:auto;';
    $css_name = 'text-align:center;';
    $css_info = 'display:flex; justify-content:space-between; padding:10px 20px; border-bottom:1px solid #666666';
    $css_background_payment = 'background:#f4f4f4; padding:10px 20px';
    $css_contact = 'margin-top:30px; display: flex; justify-content: space-between;';
    $css_contactText = 'font-size:14px; padding:5px 20px';
    $address_block = 'background:#f4f4f4; margin:30px 80px; padding:20px; border-radius: 5px; text-align:center;';

    $html = '<div style="'.$css_body.'">
              <article style="'.$css_article.'">
                <div style="'.$css_logo.'">
                  <img src="'.'img/logo-01.png" style=" width:100%;">
                </div>

                <div style="'.$css_name.'"><h1>//**ชื่อผู้จอง**//</h1></div>
                <div style="'.$css_name.'"><span>ท่านได้ทำการจองเรียบร้อยแล้ว</span></div>

                <div style="'.$address_block.'">
                    <div><h2>Bright Hotel</h2></div>
                    <div><p>บริษัท ไบรท์โฮเต็ล จำกัด

                    เลขที่ 177/88 ถนนมิตรภาพ หมู่17 ต.ในเมือง อ.เมือง จ.ขอนแก่น 40000</p></div>
                </div>

                <div style="'.$css_name.'"><h2>รายละเอียดการจอง</h2></div>

                <div style="'.$css_info.'">
                  <div>การจองห้องพัก:</div>
                  <div>//**จำนวนคืนห้องพัก**//</div>
                </div>
                <div style="'.$css_info.'">
                  <div>เช็คอิน:</div>
                  <div>//**วันเช็คอิน**//</div>
                </div>
                <div style="'.$css_info.'">
                  <div>เช็คเอาท์:</div>
                  <div>//**วันเช็คเอาท์**//</div>
                </div>
                <div style="'.$css_info.'">
                  <div>ชื่อผู้เข้าพัก:</div>
                  <div></div>
                </div>
                <div style="'.$css_info.'">
                  <div>อาหารเช้า:</div>
                  <div>รับ</div>
                </div>
                <div style="'.$css_info.'">
                  <div>โปรโมชั่น:</div>
                  <div></div>
                </div>

                <div style="'.$css_background_payment.'">
                  <div style="'.$css_info.'">
                    <div>//**ชนิดของห้องพัก**//</div>
                    <div>//**ราคาของห้องพัก**//</div>
                  </div>
                  <div style="'.$css_info.'">
                    <div>ราคารวม</div>
                    <div>//**ราคารวมทั้งหมด**//</div>
                  </div>
                </div>

                <div style="'.$css_contact.'">
                    <div style="'.$css_contactText.'"><span>เบอร์โทร:098-765-4321 / 043-306777-79<span></div>
                    <div style="'.$css_contactText.'"><span>อีเมล์: brighthotel@gmail.com<span></div>
                    <div style="'.$css_contactText.'"><span>Facebook: brighthotelkhonkaen<span></div>
                </div>
              </article>
            </div>';
  }

  public function formConfirmBookingEmail(){
    $css_body = 'background: url(' . 'img/new-bg.jpg); padding:20px; color:#c93; font-size: 16px; width:768px; margin:auto';
    $css_article = 'background:#22324b; font-family: LucidaGrande,tahoma,verdana,arial,sans-serif; padding: 20px 0px; border: 1px solid #c93;';
    $css_logo = 'width:300px; margin:auto;';
    $css_name = 'text-align:center;';
    $css_info = 'display:flex; justify-content:space-between; padding:10px 20px; border-bottom:1px solid #666666';
    $css_background_payment = 'background:#f4f4f4; padding:10px 20px';
    $css_contact = 'margin-top:30px; display: flex; justify-content: space-between;';
    $css_contactText = 'font-size:14px; padding:5px 20px';
    $address_block = 'background:#f4f4f4; margin:30px 80px; padding:20px; border-radius: 5px; text-align:center;';

    $html = '<div style="'.$css_body.'">
              <article style="'.$css_article.'">
                <div style="'.$css_logo.'">
                  <img src="'.'img/logo-01.png" style=" width:100%;">
                </div>

                <div style="'.$css_name.'"><h1>//**ชื่อผู้จอง**//</h1></div>
                <div style="'.$css_name.'"><span>การจองของท่านได้รับการยืนยันแล้ว</span></div>

                <div style="'.$address_block.'">
                    <div><h2>Bright Hotel</h2></div>
                    <div><p>บริษัท ไบรท์โฮเต็ล จำกัด

                    เลขที่ 177/88 ถนนมิตรภาพ หมู่17 ต.ในเมือง อ.เมือง จ.ขอนแก่น 40000</p></div>
                </div>

                <div style="'.$css_name.'"><h2>รายละเอียดการจอง</h2></div>

                <div style="'.$css_info.'">
                  <div>การจองห้องพัก:</div>
                  <div>//**จำนวนคืนห้องพัก**//</div>
                </div>
                <div style="'.$css_info.'">
                  <div>เช็คอิน:</div>
                  <div>//**วันเช็คอิน**//</div>
                </div>
                <div style="'.$css_info.'">
                  <div>เช็คเอาท์:</div>
                  <div>//**วันเช็คเอาท์**//</div>
                </div>
                <div style="'.$css_info.'">
                  <div>ชื่อผู้เข้าพัก:</div>
                  <div></div>
                </div>
                <div style="'.$css_info.'">
                  <div>อาหารเช้า:</div>
                  <div>รับ</div>
                </div>
                <div style="'.$css_info.'">
                  <div>โปรโมชั่น:</div>
                  <div></div>
                </div>

                <div style="'.$css_background_payment.'">
                  <div style="'.$css_info.'">
                    <div>//**ชนิดของห้องพัก**//</div>
                    <div>//**ราคาของห้องพัก**//</div>
                  </div>
                  <div style="'.$css_info.'">
                    <div>ราคารวม</div>
                    <div>//**ราคารวมทั้งหมด**//</div>
                  </div>
                </div>

                <div style="'.$css_contact.'">
                    <div style="'.$css_contactText.'"><span>เบอร์โทร:098-765-4321 / 043-306777-79<span></div>
                    <div style="'.$css_contactText.'"><span>อีเมล์: brighthotel@gmail.com<span></div>
                    <div style="'.$css_contactText.'"><span>Facebook: brighthotelkhonkaen<span></div>
                </div>
              </article>
            </div>';
  }

  public function increase_decrease_room(){
      $room = FILTER_VAR($_POST['room'],FILTER_SANITIZE_MAGIC_QUOTES);
      $position = FILTER_VAR($_POST['position'],FILTER_SANITIZE_NUMBER_INT);
      
        if(isset($_SESSION['my_order'][$room][$position])){
          if($_POST['function'] == 'increase'){
            $_SESSION['my_order'][$room][$position]['adult'] += 1;
            $ret['message'] = "increase";
          }else{
            if($_SESSION['my_order'][$room][$position]['adult'] > 1){
              $_SESSION['my_order'][$room][$position]['adult'] -= 1;
            }
            $ret['message'] = "decrease";
          }
        }else{
          $ret['message'] = "no_order";
        }
 
      $ret['cart'] = $this->calculate_cost();
      echo json_encode($ret);
  }

  public function increase_decrease_children(){
    $room = FILTER_VAR($_POST['room'],FILTER_SANITIZE_MAGIC_QUOTES);
    $position = FILTER_VAR($_POST['position'],FILTER_SANITIZE_NUMBER_INT);
    if($_SESSION['my_order'][$room][$position]['child'] >= 0 ){
      if(isset($_SESSION['my_order'][$room][$position])){
        if($_POST['function'] == 'increase'){
          $_SESSION['my_order'][$room][$position]['child'] += 1;
          $ret['message'] = "increase";
        }else{
          $_SESSION['my_order'][$room][$position]['child'] -= 1;
          $ret['message'] = "decrease";
        }
      }
    }
    echo json_encode($ret);
  }

  public function reservation_confirm(){
    if(!empty($_POST)){
      $getpost = array();
      foreach($_POST as $key => $val){
        $getpost[$key] = FILTER_VAR($val,FILTER_SANITIZE_MAGIC_QUOTES);
      }
    }  
   
    $setArr = array();
    if(count($_POST['list']['room']) > 0){
      foreach($_POST['list']['room'] as $key => $value){
        $_SESSION['my_order'][$value][$_POST['list']['position'][$key]][$_POST['list']['type'][$key]] = FILTER_VAR($_POST['list']['value'][$key],FILTER_SANITIZE_MAGIC_QUOTES);
      }
    }
   
    $usage['datein'] = $_SESSION['cart']['result']['datein'];
    $usage['dateout']= $_SESSION['cart']['result']['dateout'];
    if(!empty($_SESSION['my_order'])){
      $usage['condition'] ='';
      foreach($_SESSION['my_order'] as $key => $val){
          $usage['condition'] .= ($usage['condition'] == "")?" rp.room_code = '".$key."' ":" OR rp.room_code = '".$key."'  "; 
        }
    }
    $result = $this->check_room_available_array($usage);
    if(!empty($result)){
      foreach($result as $key =>$val){  
        if(!($val['room_amount'] > $val['amount'])){
          echo json_encode(['message'=>"error"]);
          exit();
        }
      }
    }
    $total = $this->calculate_cost();
    $sql ="SELECT max(resv_id) as numb FROM reserve_order";
    $res = $this->fetchObject($sql,[]);
    $number = $res->numb+1;
    $form = "0000000";
    $ab = 0 - strlen($number);
    $setId = substr($form,0,$ab);
    $code_order = "BH".(date("md")).($setId.$number);
    
    #insert order
    $table = "reserve_order";
    $field = "resv_code,
              resv_action,
              resv_status,
              resv_datecreated,
              resv_dateupdate,
              date_checkin,
              date_checkout,
              resv_price,
              resv_discount,
              resv_extra,
              resv_netpay
              ";
    $param = ":resv_code,  
              :resv_action, 
              :resv_status, 
              :resv_datecreated,
              :resv_dateupdate,
              :date_checkin,
              :date_checkout,
              :resv_price,
              :resv_discount,
              :resv_extra,
              :resv_netpay
              ";						 
    $value = array( 
              ":resv_code" => $code_order,
              ":resv_action" => md5($code_order.$_SESSION['encode_id']),  
              ":resv_status" => 'pending',
              ":resv_datecreated" => date('Y-m-d H:i:s'),     
              ":resv_dateupdate" => date('Y-m-d H:i:s'), 
              ":date_checkin" => $usage['datein'],
              ":date_checkout" => $usage['dateout'],
              ":resv_price" => $total['result']['price'],
              ":resv_discount" => $total['result']['discount'],
              ":resv_extra" => $total['result']['extra'],
              ":resv_netpay" => $total['result']['netpay']
    );
    $ins['order'] = $this->insertPrepare($table, $field,$param, $value);

    #insert detail
    if($ins['order']['message'] === "OK"){
      $setDiscount ="";
      foreach($_SESSION['my_order'] as $key => $val){  
        $discount = (isset($_SESSION['discount'][$key]))?$_SESSION['discount'][$key]['code']:"";
        $setDiscount .= ($discount != "")? (($setDiscount !="" )?" OR ": "")." pro_code = '".$discount."' ":"";
        foreach($val as $bb){
          $list[] = array(
            'code' => $code_order,
            'guest_name' => $bb['name'],
            'guest_lastname' => $bb['lastname'],
            'room_type' => $bb['id'], 
            'adult' => $bb['adult'],
            'children' => $bb['child'],
            'discount_code' => $discount,
            "price"=>$bb['price']
          );  
        }
      }
      $ins['detail'] = $this->multiInsert('reserve_detail',$list); 
   

      #insert contact
      $table = "reserve_contact";
      $field = "contact_name,contact_lastname,contact_tel,contact_email,contact_line,contact_address,contact_district,contact_subdistrict,contact_province,contact_postcode,contact_description,contact_otp,code";
      $param = ":contact_name,:contact_lastname,:contact_tel,:contact_email,:contact_line,:contact_address,:contact_district,:contact_subdistrict,:contact_province,:contact_postcode,:contact_description,:contact_otp,:code ";						 
      $value = array(	
        ":contact_name" => $getpost['name'],
        ":contact_lastname" => $getpost['lastname'],
        ":contact_tel" => $getpost['tel'],
        ":contact_email" => $getpost['email'],
        ":contact_line" => $getpost['line'],
        ":contact_address" => $getpost['address'],
        ":contact_district" => $getpost['district'],
        ":contact_subdistrict" => $getpost['subdistrict'],
        ":contact_province" => $getpost['province'],
        ":contact_postcode" => $getpost['postcode'],
        ":contact_description" => $getpost['message'],
        ":contact_otp" => $getpost['code'],
        ":code" => $code_order
      );
      $ins['contact'] = $this->insertPrepare($table, $field,$param, $value);

      #insert payment
      $table = "reserve_payment";
      $field = "code,payment_date,thumbnail,status";
      $param = ":code,:payment_date,:thumbnail,:status";						 
      $value = array(	 
      "code" => $code_order,
      "payment_date" => date("Y-m-d H:i:s"),
      "thumbnail" => "",
      "status" => "pending"
      );
      $ins['payment'] = $this->insertPrepare($table, $field,$param, $value);
      
      # decrease quota discount
      if(count($_SESSION['discount']) > 0 && $ins['detail']['message'] == "OK"){
          $table = "reserve_promotion";
          $set = "quota = quota - :value";
          $where = 'pro_status = "publish"  AND ('. $setDiscount .' ) ';
          $value = array(
              ":value" => 1
          ); 
        $upd['promotion'] = $this->update_prepare($table, $set, $where,$value);		
      }
    }
    
    if($ins['order']['message'] == "OK"){
      $_SESSION['payment_id'] = md5($code_order.$_SESSION['encode_id']);
      unset($_SESSION['my_order']);
      unset($_SESSION['cart']);
      unset($_SESSSION['discount']);
      $ret['message'] = "success";
      $navig = $this->fetchObject("SELECT url FROM category WHERE cate_id = 4",[]);
      $ret['page'] =  ROOT_URL.$navig->url;
      
      #ส่งข้อความตรงนี้
      // $send_message = $this->send_mail_google();

    }else{
      $ret['page'] =  ROOT_URL;
      $ret['message'] = "error";
    }
    echo json_encode($ret);
  }
 
  public function remove_order_by_room_position(){
    $position = FILTER_VAR($_POST['position'],FILTER_SANITIZE_NUMBER_INT);
    $room = FILTER_VAR($_POST['room'],FILTER_SANITIZE_MAGIC_QUOTES);
    if(isset($_SESSION['my_order'][$room][$position])){
      if(count($_SESSION['my_order'][$room]) < 2){
        unset($_SESSION['my_order'][$room]);
      }else{
        unset($_SESSION['my_order'][$room][$position]);
      }
      $ret['message'] = "remove_order";
      $ret['status'] = "success";
      $ret['cart'] = $this->calculate_cost();
      $ret['result'] = $_SESSION['room_result'];
    }else{
      $ret['message'] = "not_found";
      $ret['status'] = "error";
    }
    echo json_encode($ret);
  }
 
  public function reservation_cancel(){
      $id = FILTER_VAR($_POST['order_id'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
      $sql ="SELECT * FROM reserve_order WHERE resv_action = :code ";
      $table = "reserve_order";
      $set = "resv_status = :resv_status";
      $where = ' resv_action = :code ';
      $value = array(
          ":resv_status" => 'fail',
          ":code" =>  $id
      ); 
      $update = $this->update_prepare($table, $set, $where,$value);		
      if($update['status'] == 200){
        echo json_encode([
          "message"=>"OK"
        ]);
        exit();
      } else {
        echo json_encode([
          "message"=>"error"
        ]);
        exit();
      }
  }
  
  public function upload_payment(){
      $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
      $date = FILTER_VAR($_POST['date'],FILTER_SANITIZE_MAGIC_QUOTES);
      $bank_id = FILTER_VAR($_POST['bank'],FILTER_SANITIZE_NUMBER_INT);
      $price = FILTER_VAR($_POST['price'],FILTER_SANITIZE_NUMBER_FLOAT);
      $image = FILTER_VAR($_POST['image'],FILTER_SANITIZE_MAGIC_QUOTES);
      $code = FILTER_VAR($_POST['id'],FILTER_SANITIZE_MAGIC_QUOTES);
      $sqlBank = "SELECT id FROM bank_info WHERE id = :id";
      $resBank = $this->fetchObject($sqlBank,[":id"=> $bank_id]);
      if(empty($resBank)){
        echo json_encode([
          "message" => "not_found_bank",
          "status"=> "error"
        ]);
        exit();
      }    
      $sql ="SELECT resv_code FROM reserve_order WHERE resv_action = :code";
      $result = $this->fetchObject($sql,[":code"=>$code]);
      $table = "reserve_payment";
      $set = "payment_date =:date , status=:status, thumbnail =:thumbnail,description =:description,name=:name,bank_id=:bank,price=:price";
      $where = ' code = :code ';
      $value = array(
          ":code" => $result->resv_code,
          ":date"=> $date,
          ":status" =>"success",  
          ":thumbnail"=>$image,
          ":description"=> "", 
          ":name"=> $name,
          ":bank"=> $resBank->id,
          ":price"=> $price ); 
      $update = $this->update_prepare($table, $set, $where,$value);	
      if($update['message'] == "OK"){
          unset($_SESSION['my_order']);
          unset($_SESSION['payment_id']);
          unset($_SESSION['cart']);
      }
      echo json_encode($update);
  }

  public function uploadImage_payment(){
      
    //create folder year
    if(!file_exists(__DIR__ . '/../upload/' . date('Y'))){
        mkdir(__DIR__ . '/../upload/' . date('Y'));
    }
    
    //create folder month
    if(!file_exists(__DIR__ . '/../upload/' . date('Y') . '/' . date('m'))){
        mkdir(__DIR__ . '/../upload/' . date('Y') . '/' . date('m'));
    }
    
    //filename && full path
    $fileExt = pathinfo($_FILES['images']["name"][0], PATHINFO_EXTENSION);
    $filename = md5(time()) . '.' . $fileExt;
    // $file_path = __DIR__ . '/../upload/'.date('Y').'/'.date('m').'/'. $filename;
    // $file_full_path = BASE_URL . 'upload/'.date('Y').'/'.date('m').'/' . $filename;
    $path = 'upload/'.date('Y').'/'.date('m').'/'. $filename;
    $file_path = __DIR__ . '/../'.$path;
    $file_full_path = BASE_URL . $path;
    
    if (move_uploaded_file($_FILES['images']['tmp_name'][0], $file_path)) {
        $ret['src'] = $file_full_path;
        $ret['image'] = $path;
        $ret['message'] = "success";
        echo json_encode($ret);
    } else {
       echo json_encode(['message'=>'error']);
    }
    
  } 
  
  public function upload_images_thumbs($new_folder, $id)
  { 
      if (!class_exists('Upload')) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/class.upload.php';
      }
      $full_destionation = PATH_UPLOAD . $new_folder;
   
      $oldmask = umask(0);
      if (!file_exists($full_destionation)) {
          @mkdir($full_destionation, 0777, true);
      }
      umask($oldmask);
      $handle = new Upload($_FILES['images']);
      print_r($_FILES['images']);
      if ($handle->uploaded) {
          $newname = time() . "_" . date('Ymdhis') . "_" . $id;
          $ext = strchr($_FILES['images']['name'], ".");
          $handle->file_new_name_body = $newname;
          $handle->image_resize = true;
          $handle->image_ratio_y = true;
          $handle->image_x = 350;
          $handle->Process( $full_destionation );
          $images = $new_folder . $newname . strtolower($ext);
      }

      return $images;
    }

    public function check_otp(){
      $otp = FILTER_VAR($_POST['otp'],FILTER_SANITIZE_NUMBER_INT);
      $tel = FILTER_VAR($_POST['tel'],FILTER_SANITIZE_NUMBER_INT);
      $sql ="SELECT resv_code FROM reserve_contact  as rc 
              INNER JOIN  reserve_order  as rso ON rso.resv_code = rc.code 
              WHERE rc.contact_tel = :tel 
                AND rc.contact_otp = :otp 
                    GROUP BY rso.resv_code ";
      $result=$this->fetchAll($sql,[":tel"=>$tel,":otp"=>$otp]);
      if(!empty($result)){
          $html = $this->get_all_history($result);
          echo json_encode([
            "html"=> $html,
            "id" =>$tel,
            "message" => "OK",
            "status" => "success"
          ]);
          exit();
      }else{  
        echo json_encode([
          "message" => "รหัสยืนยันไม่ถูกต้อง!",
          "status" => "error"
        ]);
        exit();
      }
    }

    public function get_all_history($getpost){
      $set="";
      foreach($getpost as $key =>$val){
         $set .= ($set == "")?"":" OR ";
         $set .= " rso.resv_code = '".$val['resv_code']."' ";
      } 
      $sql ="SELECT * FROM reserve_order as rso  
              INNER JOIN reserve_detail as rsd ON rso.resv_code = rsd.code 
              INNER JOIN reserve_contact as rsc ON rso.resv_code = rsc.code 
              INNER JOIN reserve_payment as rsp ON rso.resv_code = rsp.code 
              INNER JOIN room_product as rp ON rsd.room_type = rp.room_code 
              LEFT JOIN reserve_promotion as dis ON rsd.discount_code = dis.pro_code 
              WHERE rso.resv_status != 'banned' AND rso.resv_status != 'cancel'  AND (".$set.") 
              ORDER BY rso.resv_id DESC ";
      $result = $this->fetchAll($sql,[]); 
      if(!empty($result)){
        $room=""; 
        $price="";
        $setArr = array();
        $loop = 0;
        $html ="";
        $getpost['netpay'] = 0;
        foreach($result as $key => $val){
          $items[$val['resv_code']][$val['room_type']]['code'] = $val['code'];
          $items[$val['resv_code']][$val['room_type']]['discount'] = (isset($val['discount_code']))?$val['discount']:0;
          $items[$val['resv_code']][$val['room_type']]['room_amount'] = count($items[$val['resv_code']][$val['room_type']]['room']) +1;
          $items[$val['resv_code']][$val['room_type']]['room'] = $val['room_type_name'];
          $items[$val['resv_code']][$val['room_type']]['adult'] += $val['adult'];
          $items[$val['resv_code']][$val['room_type']]['children'] += $val['children'];
          $items[$val['resv_code']][$val['room_type']]['price'] = $val['room_current_price'];
          $items[$val['resv_code']][$val['room_type']]['extra'] = $val['room_extra'];
          $items[$val['resv_code']]['result']['order_id'] = $val['resv_action'];
          $items[$val['resv_code']]['result']['status'] = $val['resv_status'];
          $items[$val['resv_code']]['result']['payment'] = $val['status'];
          $items[$val['resv_code']]['result']['date_in'] = $val['date_checkin'];
          $items[$val['resv_code']]['result']['date_out'] = $val['date_checkout'];
          $items[$val['resv_code']]['result']['netpay'] = $val['resv_netpay'];
          $items[$val['resv_code']]['result']['payment'] = $val['status'];
          $items[$val['resv_code']]['result']['order_room_amount'] =  count( $items[$val['resv_code']]) -1 ; 
        } 
        $html = $this->set_form_payment_detail($items);
        return $html;   
      }else{
        echo json_encode([
          "message" =>"",
          "status"=>"error"
        ]);
        exit();
      }
    } 

    public function set_form_payment_detail($getpost){
      if(!empty($getpost)){
        $html="";
        $order_status = array(
          "publish"=>"สำเร็จแล้ว",
          "payment"=>"รอการชำระ",
          "pending"=>"รอดำเนินการ",
          "fail"=>"การจองผิดพลาด",
          "banned"=>"ถูกระงับการจอง"
        );
        foreach($getpost as $key =>$val){
          $txt_discount ="";
          $room ="";
          $text_status="";
          $price ="";
          $netpay = 0;
          $date1=date_create($val['result']['date_in']);
          $date2=date_create($val['result']['date_out']);
          $diff = date_diff($date1,$date2);
          $amount_date = $diff->days+1; 
          foreach($val as $aa) {
            $discount = 0;
            $discount = ($aa['discount'] > 0)?($aa['discount'] * $aa['room_amount']) * $amount_date:0;
           
            if(isset($aa['payment'])){ 
              $datein = $this->date_convert($aa['date_in']);
              $dateout = $this->date_convert($aa['date_out']);
              $text_status = ($aa['status'] == "pending")? (($aa['payment'] == "pending")?$order_status["payment"] : $order_status[$aa['status']])  :$order_status[$aa['status']];
              $confirm = ($aa['status'] == "pending" && $aa['payment'] == "pending")?'<span class="confirm_payment" data-id="'.$aa['order_id'].'">ยืนยันการชำระ</span>':"";
              $type = ($aa['status'] != "publish" && $aa['payment'] != "publish")?'non-pay':'success';
              continue;
            }
            $txt_discount .= '<div>'.number_format($discount) .'</div>';
            $room .= '<span>'.($aa['room']). ' x ' .$aa['room_amount'].' ห้อง</span>';
            $price .= '<span>'.(number_format($aa['price'])).' / ห้อง</span>';
          }
          $html .=' <div class="item">
                      <div class="table-head">
                          <span>วัน</span>
                          <span>ประเภทห้อง</span>
                          <span class="discount">ส่วนลด</span>
                          <span class="group-price">ราคา</span>
                          <span class="status">สถานะ</span>
                      </div>
                      <div class="table-body">
                          <span>'.$datein.' - '.$dateout.'</span>
                          <div class="group-nameroom">  '.$room.' </div>
                          <span class="discount"> '.$txt_discount.' </span>
                          <div class="group-price"> '.$price.' </div>
                          <div class="status">
                              <span class="'.$type.'" data-id="'.$redirect.'">'.$text_status.'</span>
                          </div>
                          '.$confirm.'
                      </div>
                      <div class="table-end">
                          <span>'.number_format($amount_date).' คืน</span>
                          <span>'.number_format($val['result']['netpay']).'</span>
                      </div>
                  </div>';
        }
      }
      return $html;
    }

    public function update_reserve_payment(){
      $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_MAGIC_QUOTES);
      $sql ="SELECT resv_action FROM reserve_order WHERE resv_action = :id AND date_checkout > :date ";
      $result = $this->fetchObject($sql,[":id" => $id, ":date"=>date("Y-m-d H:i:s")]);
      if(!empty($result)){
        $_SESSION['payment_id'] = $result->resv_action;
        echo json_encode([
          "path"=> ROOT_URL."ยืนยันการชำระ",
          "message"=>"available",
          "status"=>"success"
        ]);
      }else{
        echo json_encode([
          "message"=>"order_is_not_available",
          "status"=>"error"
        ]);
      }
     
    }
    
    public function require_meeting_room(){
         $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
         $email = FILTER_VAR($_POST['email'],FILTER_SANITIZE_MAGIC_QUOTES);
         $tel = FILTER_VAR($_POST['tel'],FILTER_SANITIZE_MAGIC_QUOTES);
         $subject = FILTER_VAR($_POST['subject'],FILTER_SANITIZE_MAGIC_QUOTES);
         $message = FILTER_VAR($_POST['message'],FILTER_SANITIZE_MAGIC_QUOTES);
        #leave message meeting room
        $table = "reserve_meeting";
        $field = "fullname,email,phone,topic,message,submit_date";
        $param = ":fullname,:email,:phone,:topic,:message,:submit_date";						 
        $value = array(	 
          ":fullname"=> $name,
          ":email"=>$email,
          ":phone"=> $tel,
          ":topic"=>$subject,
          ":message"=> $message,
          ":submit_date" => date('Y-m-d H:i:s') 
        );
        $ret = $this->insertPrepare($table, $field,$param, $value);
        if($ret['status'] == 200){
          #ส่งข้อความส่วนนี้
          echo json_encode([
            "message" => "OK",
            "status" => "success",
            "detail" => "send_message"
          ]);
          exit();
        }else {
          echo json_encode([
            "message" => "fail",
            "status" => "error",
            "detail" => "error_send"
          ]);
          exit();
        }
  }
 

}

$_POST['action'] = FILTER_VAR($_POST['action'],FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING);
$action = isset($_POST['action']) ? $_POST['action'] : '';
if (!empty($action)){
  new myapi($action);
}
?>