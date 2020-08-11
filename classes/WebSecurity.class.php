<?php

/**
 * Create By Kotbass
 * 
   #HTTP STATUS
   200 OK
   400 Bad Request
   401 Unauthorized loginไม่ถูกต้อง , ไม่มี Email หรือ user
   403 Forbidden ต้องห้าม
   404 Not Found
   405 Method Not Allowed
   408 Request Timeout
   411 Length Required
   500 Internet Server Error
 */

class WebSec
{
  public function __construct()
  {
  }

  /**
   * function เช็คว่าใช่ Method POST หรือไม่ ถ้าใช่ จะให้ผ่าน
   * $_SERVER['REQUEST_METHOD']
   * @return event 'methodPostOnly'
   * @return message 'ExceptionMethod'
   * @return status 405
   */
  public static function methodPostOnly()
  {
    /**
     * ถ้า Method ไม่ใช่ Post จะ Error
     */
    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
      echo json_encode([
        'event' => 'methodPostOnly',
        'message' => 'ExceptionMethod',
        'status' => 405
      ]);
      exit();
    }
  }


  /**
   * ตรวจสอบว่าใช่ HTTP Method POST ที่กำหนดหรือไม่
   * @return true/false
   */
  public static function isMethodPost()
  {
    return ($_SERVER['REQUEST_METHOD'] == "POST")?true:false;
  }


  /**
   * ตรวจสอบว่าใช่ HTTP Method GET ที่กำหนดหรือไม่
   * @return true/false
   */
  public static function isMethodGet()
  {
    return ($_SERVER['REQUEST_METHOD'] == "GET")?true:false;
  }


  /**
   * ตรวจสอบว่าใช่ HTTP Method PUT ที่กำหนดหรือไม่
   * @return true/false
   */
  public static function isMethodPut()
  {
    return ($_SERVER['REQUEST_METHOD'] == "PUT")?true:false;
  }


  /**
   * ตรวจสอบว่าใช่ HTTP Method PATCH ที่กำหนดหรือไม่
   * @return true/false
   */
  public static function isMethodPatch()
  {
    return ($_SERVER['REQUEST_METHOD'] == "PATCH")?true:false;
  }


  /**
   * ตรวจสอบว่าใช่ HTTP Method DELETE ที่กำหนดหรือไม่
   * @return true/false
   */
  public static function isMethodDelete()
  {
    return ($_SERVER['REQUEST_METHOD'] == "DELETE")?true:false;
  }


  /**
   * ตรวจสอบว่าใช่ HTTP Method ที่กำหนดหรือไม่
   * @param mixed $method methodที่กำหนด
   * @return true/false
   */
  public static function isMethod($method)
  {
    return ($_SERVER['REQUEST_METHOD'] == $method)?true:false;
  }


  /**
   * ตรวจสอบว่ามีการ Login เข้ามาหรือไม่ ถ้าไม่มีก็ Error 
   * $_SESSION['member']['islogin']
   * @return event 'checkLogin'
   * @return message 'ExceptionLogin'
   * @return status 401
   */
  public static function checkLogin()
  {
    if (!isset($_SESSION['member']['islogin'])) {
      echo json_encode([
        'event' => 'checkLogin',
        'message' => 'ExceptionLogin',
        'status' => 401
      ]);
      exit();
    }
  }


  /**
   * ตรวจสอบว่ามีการ Login เข้ามาหรือไม่
   * $_SESSION['member']['islogin']
   * @return event 'isLogin'
   * @return message 'ExceptionLogin'
   * @return status 401
   */
  public static function isLogin()
  {
    return (isset($_SESSION['member']['islogin']) && $_SESSION['member']['islogin'] == true)?true:false;
  }


  /**
   * ตรวจสอบ member type $_SESSION['member']['type']
   * @param mixed $type ประเภทของ member
   * @return event 'checkMemberType'
   * @return event 'ExceptionMemberType'
   * @return event 403
   */
  public static function checkMemberType($type)
  {
    if ($_SESSION['member']['type'] !== $type) {
      echo json_encode([
        'event' => 'checkMemberType',
        'message' => 'ExceptionMemberType',
        'status' => 403
      ]);
      exit();
    }
  } // End function checkMemberType


  /**
   * filter number int only ถ้าไม่ใช่ จะreturn null
   * @param mixed $num input ที่ใส่เข้ามา
   * @return number/0
   */
  public static function filterInt($num)
  {
    return (!empty($num))?filter_var($num,FILTER_SANITIZE_NUMBER_INT):0;
  }


  /**
   * ฟังก์ชั่น ตรวจสอบว่าเป็นตัวเลขหรือไม่
   * @param mixed $num input ที่ใส่เข้ามา
   * @return true/false
   */
  public static function isInt($num)
  {
    return (filter_var($num,FILTER_VALIDATE_INT)?true:false);
  }


  /**
   * ตรวจสอบ ค่าว่างของ array
   * @param mixed $data input ที่เป็น array
   * @return true/false
   */
  public static function isEmptyArray($data = [])
  {
    $empty = 0;
    foreach ($data as $val) {
      if ($val === null) $empty++;
    }
    return ($empty >= 1)?true:false;
  }


  /**
   * ฟังก์ชั่น magic_quotes
   * @param mixed $magic ค่าinputที่ส่งเข้ามา จะเป็น value หรือ array ก็ได้
   * @return FILTER_SANITIZE_ADD_SLASHES
   */
  public static function magic_quotes($magic)
  { #protect sqlinjection
    if (is_array($magic)) {
      foreach ($magic as $key => $value) {
        $magic[$key] = filter_var($magic[$key], FILTER_SANITIZE_ADD_SLASHES);
      }
      return $magic;
    } else if (!is_array($magic)) {
      return filter_var($magic, FILTER_SANITIZE_ADD_SLASHES);
    }
  }


  /**
   * ฟังก์ชั่น special_chars
   * @param mixed $chars ค่าinputที่ส่งเข้ามา จะเป็น value หรือ array ก็ได้
   * @return FILTER_SANITIZE_SPECIAL_CHARS
   */
  public static function special_chars($chars)
  { #protect xss
    if (is_array($chars)) {
      foreach ($chars as $key => $value) {
        $chars[$key] = filter_var($chars[$key], FILTER_SANITIZE_SPECIAL_CHARS);
      }
      return $chars;
    } else if (!is_array($chars)) {
      return filter_var($chars, FILTER_SANITIZE_SPECIAL_CHARS);
    }
  }


  /**
   * ฟังก์ชั่น string
   * @param mixed $string ค่าinputที่ส่งเข้ามา จะเป็น value หรือ array ก็ได้
   * @return FILTER_SANITIZE_STRING
   */
  public static function string($string)
  { #ตัด tags html ออก
    if (is_array($string)) {
      foreach ($string as $key => $value) {
        $string[$key] = filter_var($string[$key], FILTER_SANITIZE_STRING);
      }
      return $string;
    } else if (!is_array($string)) {
      return filter_var($string, FILTER_SANITIZE_STRING);
    }
  }


  /**
   * ฟังก์ชั่น number_int
   * @param mixed $int ค่าinputที่ส่งเข้ามา จะเป็น value หรือ array ก็ได้
   * @return FILTER_SANITIZE_NUMBER_INT
   */
  public static function number_int($int)
  { #กรอกเฉพาะตัวเลข

    if (is_array($int)) {
      foreach ($int as $key => $value) {
        $int[$key] = filter_var($int[$key], FILTER_SANITIZE_NUMBER_INT);
      }
      return $int;
    } else if (!is_array($int)) {
      return filter_var($int, FILTER_SANITIZE_NUMBER_INT);
    }
  }


  /**
   * ฟังก์ชั่น number_float
   * @param mixed $float ค่าinputที่ส่งเข้ามา จะเป็น value หรือ array ก็ได้
   * @return FILTER_SANITIZE_NUMBER_FLOAT
   */
  public static function number_float($float)
  { #กรอกเฉพาะตัวเลขทศนิยม
    if (is_array($float)) {
      foreach ($float as $key => $value) {
        $float[$key] = filter_var($float[$key], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      }
      return $float;
    } else if (!is_array($float)) {
      return filter_var($float, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
  }


  /**
   * ฟังก์ชั่น สร้าง csrf
   * @param mixed $id เป็น id ที่จะเอาไว้เป็นชื่อของ $_SESSION[$id] และ ชื่อ id ของ <input type="hidden" id="$id">
   * @return '<input type="hidden" id="'.$id.'" value="'.$csrf.'">'
   */
  public static function generateCSRF($id)
  {
    $csrf = sha1(uniqid(rand(), TRUE));
    $_SESSION[$id] = $csrf;
    return '<input type="hidden" id="' . $id . '" value="' . $csrf . '">';
  }


  /**
   * ฟังก์ชั่น ตรวจสอบ csrf ว่าถูกต้องหรือไม่ 
   * @param mixed $sessionCSRF คือ $_SESSION ที่เก็บไว้ใน server
   * @param mixed $inputCSRF คือ input ที่มาจาก client
   * @return true,false
   */
  public static function verifyCSRF($sessionCSRF, $inputCSRF)
  {
    return ($sessionCSRF == $inputCSRF) ? true : false;
  }


  /**
   * ฟังก์ชั่น ที่เอาไว้ดึงค่าจาก http request header
   * @param mixed $option เช่น Authorization
   * @param mixed $explode เช่น Bearer
   * @return เลขToken ของ Authorization Bearer
   */
  public static function getHTTPRequestHeaders($option, $explode)
  {
    // Server ใช้ ฟังชั่น apache_request_headers นี้ไม่ได้
    $headers = apache_request_headers();
    $headers_explode = explode($explode . " ", $headers[$option]);
    return $headers_explode[1];
  }


  /**
   * ฟังก์ชั่นที่เอาไว้ ดึงค่า token จาก body
   * @param mixed $data เช่น $input->token-csrf
   * @param mixed $explode เช่น csrf
   * @return เลขToken ของ csrf
   */
  public static function getHTTPRequestBody($data,$explode){
    $headers_explode = explode($explode." ",$data);
    return $headers_explode[1];
  }


  /**
   * ตรวจสอบ ค่า Referer HTTP_REFERER = SERVER_NAME
   * @return true/false
   */
  public static function isReferer()
  {
    if (stripos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME'])) {
      return true;
    } else {
      return false;
    }
  }
  /**
   * ตรวจสอบว่า content length มีข้อมูลหรือไม่
   * @return true/false
   */
  public static function isContentLength()
  {
    if ($_SERVER['CONTENT_LENGTH'] == "") {
      return false;
    } else {
      return true;
    }
  }
  /**
   * ตรวจสอบว่า user agent มีข้อมูลหรือไม่
   * @return true/false
   */
  public static function isUserAgent()
  {
    if ($_SERVER['HTTP_USER_AGENT'] == "") {
      return false;
    } else {
      return true;
    }
  }
  /**
   * ตรวจสอบว่า HTTP Accept มีข้อมูลหรือไม่
   * @return true/false
   */
  public static function isHTTPAccept(){
    if ($_SERVER['HTTP_ACCEPT'] == "") {
      //application/json
      return false;
    } else {
      return true;
    }
  }
  /**
   * ตรวจสอบว่า Http Accept Encoding = gzip
   * @return true/false
   */
  public static function isAcceptEncoding(){
    if ($_SERVER['HTTP_ACCEPT_ENCODING'] != "gzip") {
      return false;
    } else {
      return true;
    }
  }
  /**
   * ตรวจสอบว่า Http accept Language มีข้อมูลหรือไม่
   * @return true/false
   */
  public static function isAcceptLanguage(){
    if ($_SERVER['HTTP_ACCEPT_LANGUAGE'] == "") {
      //th-TH
      return false;
    } else {
      return true;
    }
  }
  /**
   * ตรวจสอบว่า Http Contention = Keep-Alive
   * @return true/false
   */
  public static function isHTTPConnection(){
    if (strtolower($_SERVER['HTTP_CONNECTION']) != "keep-alive") {
      //Keep-Alive
      return false;
    } else {
      return true;
    }
  }

  /**
   * เป็นฟังก์ชั่นที่เอาไว้ตรวจสอบว่า มีการส่ง HTTP Request Header แบบถูกต้องหรือไม่
   * @return true,false
   */
  public static function isHTTPRequestHeaders()
  {
    if (
      !WebSec::isReferer() ||
      !WebSec::isContentLength() ||
      !WebSec::isUserAgent() ||
      !WebSec::isHTTPAccept() ||
      !WebSec::isAcceptEncoding() ||
      !WebSec::isAcceptLanguage() ||
      !WebSec::isHTTPConnection()
    ) {
      return false;
    } else {
      return true;
    }
  }
}
