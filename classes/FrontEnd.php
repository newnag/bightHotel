<?php
session_start();
// require_once dirname(__DIR__) . '/classes/dbquery.php';
// require_once dirname(__DIR__) . '/classes/Route.class.php';
include 'PHPMailer/src/Exception.php';
include 'PHPMailer/src/PHPMailer.php';
include 'PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class FrontEnd extends DBconnect
{
  // public $dbcon = null;
  public  $site_url = SITE_URL;
  private $upload;
  private $pagePer = 12;

  public function __construct()
  {
    parent::__construct();
  }

  #Code Old
  #ดึงข้อมูล fab icon ด้วย id
  public function get_icon_fab_logo($_id)
  {
    $sql = "SELECT * FROM ads WHERE ad_id = '" . $_id . "' ";
    $res = $this->fetchObject($sql, []);
    return $res->ad_image;
  }

  #Code Old
  public function get_slug()
  {
    $url = explode('/', $_SERVER['REQUEST_URI']);
    $sss = explode('?', $url[1]);
    $slug = urldecode($sss[0]);
    return filter_var($slug, FILTER_SANITIZE_STRING);
  }

  #Code Old
  //ดึงข้อมูลฟังก์ชั่นของเว็บ
  public function feature()
  {
    $feat = "SELECT * FROM feature";
    return $this->query($feat);
  }

  #Code Old
  //ดึงข้อมูลเว็บ
  public function get_web_info($type = '')
  {
    $info_type = '';
    if (!empty($type)) {
      $info_type = ' AND info_type="' . $type . '"';
    }
    $sql = "SELECT * FROM web_info_type WHERE (defaults = 'yes ' OR language = '" . $_SESSION['language'] . "') " . $info_type . " ORDER BY FIELD(defaults,'yes') DESC";
    $result_infoType = $this->query($sql);
    $output = array();
    if ($result_infoType != false) {
      $dataInfoType = $this->translateQuery($result_infoType, 'id');
      foreach ($dataInfoType as $infoType) {
        $sql = "SELECT * FROM web_info WHERE (defaults = 'yes ' OR language = '" . $_SESSION['language'] . "')  AND info_type ='" . $infoType['info_type'] . "' AND info_display = 'yes' ORDER BY priority ASC, FIELD(defaults,'yes') DESC";
        $result_info = $this->query($sql);
        if ($result_info != false) {
          $output[$infoType['info_type']] = array(
            'title' => $infoType['info_title'],
            'data' => $this->translateQuery($result_info, 'info_title')
          );
        }
      }
    }
    return $output;
  }

  #Code Old
  /** อัพโหลด image
   * $FILE_NAME = ชื่อไฟล์ที่ส่งมาจา FORM HTML
   * $destination = ที่อยู่ไฟล์ปลายทาง
   */
  public function uploadImage($FILE_NAME, $destination)
  {
  
    if (!class_exists('Upload')) {
      require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/class.upload.php';
    }

    //PATH_UPLOAD ถูกกำหนดไว้ที่ config.php
    $full_destionation = '';
    if (empty($destination)) {
      $destination = date('Y') . '/' . date('m') . '/';
    }
    $destination = date('Y') . '/123/';
    $full_destionation = PATH_UPLOAD . $destination;
    $oldmask = umask(0);
    if (!file_exists("../upload/". $destination)) { 
      @mkdir("../upload/". $destination , 0777, true );
    }
    umask($oldmask);
    $image_return = '';
    $handle = new Upload($_FILES[$FILE_NAME]);
    if ($handle->uploaded) {
      //ตั้งชื่อใหม่
      $newname = date('Ymdhis') . "_" . $this->randomString(3);
      $ext = strchr($_FILES[$FILE_NAME]['name'][0], ".");
      $handle->file_new_name_body     = $newname;
      //$handle->image_resize           = true;
      // $handle->image_ratio_y          = true;
      //$handle->image_x                = 350;
      $handle->Process($full_destionation); 
      $handle->Clean();
      $image_return = 'upload/' . $destination . $newname . strtolower($ext);
    }
    return $image_return;
  }

  #Code Old
  public function get_content_by_cate_id($getpost)
  {
    $pagi =  isset($getpost['pagi']) ? filter_var($getpost['pagi'], FILTER_SANITIZE_MAGIC_QUOTES) : "";
    $amount = isset($getpost['amount']) ? filter_var($getpost['amount'], FILTER_SANITIZE_NUMBER_INT) : "";
    $slug = isset($getpost['slug']) ? filter_var($getpost['slug'], FILTER_SANITIZE_MAGIC_QUOTES) : "";
    $cate = isset($getpost['key_value']['cate_id']) ? $getpost['key_value']['cate_id'] : "";
    $pin  = isset($getpost['pin']) ?  $getpost['pin'] : "";
    $sort  = isset($getpost['sort']) ?  $getpost['sort'] : "";
    $order = isset($getpost['order']) ?  $getpost['order'] : "";
    $condition = "post.category IN ( " . $cate . " )" . $pin;
    if ($pagi == '' || $pagi <= 1) {
      $lim = "0," . $amount;
    } else {
      $begin = ((($pagi - 1) * $amount));
      $lim = $begin . ',' . ($amount);
    }
    if ($sort == '') {
      $sql = "SELECT * from post inner join(
                    SELECT  id FROM post WHERE (" . $condition . ")
                     AND ( defaults='yes' OR language='" . $_SESSION['language'] . "')
                     AND (date_display < '" . date('Y-m-d') . "' OR date_display = '0000-00-00 00:00:00') 
                     group by id  ORDER BY field(pin, 'yes') DESC,date_created DESC ,id DESC, FIELD(defaults,'yes') DESC LIMIT " . $lim . ")pos 
                    on pos.id = post.id 
                        ORDER BY field(post.pin, 'yes') DESC,post.date_created DESC ,post.id DESC, FIELD(post.defaults,'yes') DESC ";
    } else {
      $sql = "SELECT * from post inner join
                (SELECT  id FROM post WHERE (" . $condition . ") 
                    AND ( defaults='yes' OR language='" . $_SESSION['language'] . "')
                    AND (date_display < '" . date('Y-m-d H:i:s') . "' OR date_display = '0000-00-00 00:00:00')
                    group by id  
                    ORDER BY pin DESC, " . $sort . " " . $order . " ,id DESC, FIELD(defaults,'yes') DESC LIMIT " . $lim . ")pos 
                    on pos.id = post.id 
                        ORDER BY post.pin DESC, post." . $sort . " " . $order . ",post.id DESC, FIELD(post.defaults,'yes') DESC ";
    }
    $res = $this->query($sql);
    $postList = array();
    if ($res != false) {
      foreach ($res as $a) {
        if ($a['defaults'] == 'yes') {
          $return[$a['id']] = $a;
        }
        if ($a['language'] == $_SESSION['language']) {
          $return[$a['id']] = $a;
        }
        if ($pagi == '' || $pagi <= 1) {
          $lim_img = "0,15";
        } else {
          $begin = ((($pagi - 1) * 15));
          $lim_img = $begin . ',15';
        }
        //ดึงข้อมูลรูปภาพ
        $sql = "SELECT * FROM post_image WHERE post_id = '" . $a['id'] . "' ORDER BY position LIMIT " . $lim_img . "";
        $img = $this->query($sql);
        if ($img != false) {
          foreach ($img as $b) {
            $return[$a['id']]['images'][$b['position']] = $b;
          }
          $sql = "SELECT  count(*) from post_image WHERE post_id = '" . $a['id'] . "'";
          $cnt_img = $this->runQuery($sql);
          $cnt_img->execute();
          $return[$a['id']]['total_img'] = $cnt_img->fetchColumn();
        }
      }
      $postList['data'] = $return;
      $sql = "SELECT * FROM category WHERE (defaults = 'yes' OR language='" . $_SESSION['language'] . "') AND  cate_id = '" . $cate . "' ORDER BY FIELD(defaults,'yes') DESC";
      $ret = $this->query($sql);
      foreach ($ret as $a) {
        if ($a['defaults'] == 'yes') {
          $postList['title'] = $a['title'];
          $postList['keyword'] = $a['keyword'];
          $postList['description'] = $a['description'];
          $postList['url'] = $a['url'];
          $postList['thumbnail'] = $a['thumbnail'];
        }
        if ($a['language'] == $_SESSION['language']) {
          $postList['title'] = $a['title'];
          $postList['keyword'] = $a['keyword'];
          $postList['description'] = $a['description'];
          $postList['thumbnail'] = $a['thumbnail'];
        }
      }
      $sql = "SELECT  count(*) from (select * FROM post WHERE (" . $condition . ") AND post.display =  'yes' AND(post.date_display < '" . date('Y-m-d H:i:s') . "' OR post.date_display = '0000-00-00 00:00:00') GROUP BY post.id )ps";
      $cnt = $this->runQuery($sql);
      $cnt->execute();
      $postList['total'] = $cnt->fetchColumn();
    } else {
      $postList[0] = 'no_result';
    }
    return $postList;
  }

  #Code Old
  /* ดึงข้อมูลแปลภาษาของ interface */
  public function lang_config()
  {
    $listLang = $this->query('SELECT * FROM lang_config');
    $langActive = $_SESSION["language"];
    $contentLang = array();
    foreach ($listLang as $word) {
      if ($word[$langActive] === "" || $word[$langActive] === null) {
        $contentLang[$word['param']] = $word['defaults'];
      } else {
        $contentLang[$word['param']] = $word[$langActive];
      }
    }
    return $contentLang;
  }

  #Code Old
  //@get_language_array ฟังก์ชั่นดึงข้อมูลภาษาเป็นอาร์เรย์
  //ผลลัพธ์ return  :  Array ( [0] => TH [1] => EN [2] => CH )
  public function get_language_array()
  {
  
    $sql = "SELECT GROUP_CONCAT(language) AS 'language' FROM language";
    $result = $this->fetch($sql);
 
    return explode(',', $result['language']);
  }

  #Code Old
  #จะสุ่มข้อความตามความยาวที่เราต้องการ default = 5ตัว
  public function randomString($length = 5)
  { //กำหนดความยาวข้อความที่ต้องการ
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
  }

  #Code Old
  /* @translateQuery   ฟังก์ชั่งนี้ใช้เพื่อนำค่าจากตารางโพสต์ แล้วจัดเรียงข้อมูลให้เป็นภาษาปัจุบัน
    @result  ค่าที่ได้การการดึงข้อมุลในฐานข้อมุลด้วยคำสั่ง$this->query($sql); ต้องเปลี่ยนชื่อคอมลัมให้เป็น id ในคำนั่ง sql ด้วย
     */
  public function translateQuery($result, $defaulColumtId = 'id')
  {
    $post_all = array();
    if (!empty($result)) {
      $langActive = $_SESSION['language'];
      foreach ($result as $post) {
        $post_id = $post[$defaulColumtId];
        /* เก็บโพสต์ default เอาไว้ */
        if ($post['defaults'] == 'yes') {
          $post_all[$post_id] = $post;
        }
        /* เก็บโพสต์ที่เป็นภาษาปัจจุบัน โดยต้องมีในค่าภาษาที่ระบบได้เพิ่มเอาไว้ */
        if ($post['language'] == $langActive) {
          $post_all[$post_id] = $post;
        }
      }
    }
    return $post_all;
  }

  #Code Old
  public function option($table, $column, $key, $id = '', $default = '')
  {
    $sql = "SELECT * FROM " . $table;
    $result = $this->query($sql);
    $return = '';
    foreach ($result as $value) {
      $return .= '<option value=\'' . $value[$key] . '\' id=\'' . $value[$id] . '\'>' . $value[$column] . '</option>';
    }
    return  $return;
  }

  #Code Old
  /**
   * ฟังก์ชั่นแสดง dropdown รองรับหลายภาษา
   * @tatle ตาราง
   * @colum คอลัม
   * @op_id
   * @key
   */
  public function option_multilingual($table, $column, $op_id, $key)
  {
    $sql = "SELECT * FROM lang_config";
    $con = $this->dbcon->query($sql);
    $output = $_SESSION['backend_language'];
    foreach ($con as $a) {
      foreach ($a as $b => $c) {
        if ($b == 'param') {
          if ($a[$output] != '') {
            $$c = $a[$output];
          } else {
            $$c = $a['defaults'];
          }
        }
      }
    }
    $sql = "SELECT * FROM " . $table;
    $result = $this->dbcon->query($sql);
    foreach ($result as $value) {
      $return .= "<option value=\"$value[$key]\" id=\"$op_id$value[$key]\">" . $$value[$column] . "</option>";
    }
    return ($return);
  }

  public function send_email_google($option){ 

    $mail = new PHPMailer;
    $mail->isSMTP();	     
    $mail->CharSet = "utf-8";
    $mail->IsHTML(true);
    $mail->SMTPDebug = 3;
    $mail->Host =  $option['SMTP_HOST'];
    $mail->Port =  $option['SMTP_PORT'];
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $option['SMTP_USER'];
    $mail->Password = $option['SMTP_PASSWORD'];
    $mail->setFrom($option['mail_system'],$option['sendFromName']);														

    if(is_array($option['addAddress'])){ 			
      foreach ($option['addAddress'] as $key => $value) {
        $mail->AddAddress($value['email'], $value['name']);
      }
    }
    if(is_array($option['addBcc'])){   				
      foreach ($option['addBcc'] as $key => $value) {
        $mail->addBcc($value['email'], $value['name']); 
      }
    }
    $mail->Subject = $option['subject'];
    $mail->msgHTML($option['content']);
    if (!$mail->send()) { 
      //echo 'Mailer Error: ' . $mail->ErrorInfo;
      return "false"; 
    } else { 
      return "true"; 
    }
  }

 
 

  



  /**
   * $_perpage    = จำนวน item ที่จะโชว์ ในแต่ละหน้า
   * $_page       = $_GET['page']
   * $_cateid     = post.category
   * $_productcateid = Product Category Id มีเฉพาะ ที่เป็น สินค้า
   */
  // private function getPagination($_perpage, $_page, $_cateid, $_productcateid, $_Wherecondition = "")
  // {
  //   $Num_Rows = $this->getPostCountByCateId($_cateid, $_productcateid, $_Wherecondition); // Get ใน Post where cate 4 มีกี่ item
  //   $Per_Page = $_perpage;   // จำนวน item ที่จะโชว์ ในแต่ละหน้า
  //   $Page = $_page;
  //   if ($Page == '') { //ถ้าไม่มี $_GET['page']
  //     $Page = 1;
  //   }
  //   $Prev_Page = $Page - 1;
  //   $Next_Page = $Page + 1;
  //   $Page_Start = (($Per_Page * $Page) - $Per_Page);
  //   if ($Num_Rows <= $Per_Page) {
  //     $Num_Pages = 1;
  //   } else if (($Num_Rows % $Per_Page) == 0) {
  //     $Num_Pages = ($Num_Rows / $Per_Page);
  //   } else {
  //     $Num_Pages = ($Num_Rows / $Per_Page) + 1;
  //     $Num_Pages = (int) $Num_Pages;
  //   }
  //   return [$Page_Start, $Num_Pages];
  // }

  /**
   * สร้าง Pagination HTML
   * $_numpage = จำนวนหน้าทั้งหมด
   * $page = หน้าที่แสดงปัจจุบัน
   * $_catename = category name
   */
  // private function createPagination($_numpage, $_page, $_catename)
  // {
  //   if ($_numpage == 1) {
  //     return "";
  //     exit();
  //   }
  //   $out = "<div class=\"pagination\">";
  //   if ($_page != "" && $_page != "1") {
  //     $out .= "<span class=\"pagi-item\"><a href='/" . $_catename . "?page=" . ($_page - 1) . "'> < </a></span>";
  //   }
  //   for ($i = 1; $i <= $_numpage; $i++) {
  //     if ($_page == $i || ($_page == "" && $i == 1)) {
  //       $out .= "<span class=\"pagi-item pagi-active\"><a href='/" . $_catename . "?page=" . $i . "'> " . $i . " </a></span>";
  //     } else {
  //       $out .= "<span class=\"pagi-item\"><a href='/" . $_catename . "?page=" . $i . "'> " . $i . " </a></span>";
  //     }
  //   }
  //   if ($_page != $_numpage && $_numpage > 1) {
  //     if ($_page == "") $_page = 1;
  //     $out .= "<span class=\"pagi-item\"><a href='/" . $_catename . "?page=" . ($_page + 1) . "'> > </a></span>";
  //   }
  //   $out .= "</div>";
  //   return $out;
  // }

  // public function DateThai($strDate, $type = false,$yearth = false)
  // {
  //   if ($_SESSION['language'] == 'TH' || $_SESSION['language'] == "") {
  //     # code...
  //     $strYear = !empty($yearth)?date("Y", strtotime($strDate)) + 543:date("Y", strtotime($strDate));
  //     $strMonth = date("n", strtotime($strDate));
  //     $strDay = date("d", strtotime($strDate));
  //     $strHour = date("H", strtotime($strDate));
  //     $strMinute = date("i", strtotime($strDate));
  //     $strSeconds = date("s", strtotime($strDate));
  //     $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
  //     if ($type) {
  //       $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
  //     }
  //   } elseif ($_SESSION['language'] == 'LA') {
  //     # code...
  //     $strYear = date("Y", strtotime($strDate));
  //     $strMonth = date("n", strtotime($strDate));
  //     $strDay = date("d", strtotime($strDate));
  //     $strHour = date("H", strtotime($strDate));
  //     $strMinute = date("i", strtotime($strDate));
  //     $strSeconds = date("s", strtotime($strDate));
  //     $strMonthCut = array("", "ມັງກອນ", "ກຸມພາ", "ມີນາ", "ເມສາ", "ພຶດສະພາ", "ມິຖຸນາ", "ກໍລະກົດ", "ສິງຫາ", "ກັນຍາ", "ຕຸລາ", "ພະຈິກ", "ທັນວາ");
  //   } else {
  //     $strYear = date("Y", strtotime($strDate));
  //     $strMonth = date("n", strtotime($strDate));
  //     $strDay = date("d", strtotime($strDate));
  //     $strHour = date("H", strtotime($strDate));
  //     $strMinute = date("i", strtotime($strDate));
  //     $strSeconds = date("s", strtotime($strDate));
  //     $strMonthCut = array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
  //   }
  //   $strMonthThai = $strMonthCut[$strMonth];
  //   return "$strDay $strMonthThai $strYear";
  // }
}
