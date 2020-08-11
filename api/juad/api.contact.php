<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);


use \Firebase\JWT\JWT;

require_once DOC_ROOT . '/classes/FrontEnd.php';
require_once DOC_ROOT . '/classes/WebSecurity.class.php';
// require_once './../src/autoload.php';      // ReCaptcha


class Contact extends FrontEnd
{
  public function __construct()
  {
    parent::__construct();
    //ตรวจสอบว่ามีฟังก์ชั่นในคลาสไหมถ้ามีให้เรียกใช้งาน
  }

  /**
   * ฟังชั่นบันทึก ติดต่อสอบถาม
   * @param mixed $input ข้อมูลที่ส่งมาจาก user
   * @return json 
   */
  public function saveContact($input)
  {

    #ตรวจสอบ null
    if(empty($input->fname) || empty($input->lname) || empty($input->phone) || empty($input->line) || empty($input->topic) || empty($input->description)){
      return json_encode(["status_" => false,"message" => "data_not_null","input" => $input]);
    }

    #ตรวจสอบ length 65535
    if( 
      strlen($input->fname." ".$input->lname) > 255 ||
      strlen($input->phone) > 20 ||
      strlen($input->line) > 50 ||
      strlen($input->topic) > 255 ||
      strlen($input->description) > 65535
      )
    {
      return json_encode(["status_" => false,"message" => "data_length_invalid"]);
    }


    #insert contact_advertise
    $sql = "INSERT INTO contact_advertise(fullname,phone,line,topic,detail,date_create,date_update)
            VALUES (:fullname,:phone,:line,:topic,:detail,NOW(),NOW())
          ";
    $values = [
      ":fullname" => $input->fname." ".$input->lname,
      ":phone" => $input->phone,
      ":line" => $input->line,
      ":topic" => $input->topic,
      ":detail" => $input->description
    ];
    $res = $this->insertValue($sql,$values);
    if($res['message'] == "OK"){
      return json_encode(["status_" => true,"message" => "success"]);
    }else{
      return json_encode(["status_" => false,"message" => "fail","res" => $res]);
    }
    
  }
}
