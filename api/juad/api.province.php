<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);


use \Firebase\JWT\JWT;

require_once DOC_ROOT . '/classes/FrontEnd.php';
require_once DOC_ROOT . '/classes/WebSecurity.class.php';
// require_once './../src/autoload.php';      // ReCaptcha


class Province extends FrontEnd
{
  public function __construct()
  {
    parent::__construct();
    //ตรวจสอบว่ามีฟังก์ชั่นในคลาสไหมถ้ามีให้เรียกใช้งาน
  }

  /**
   * ดึงจังหวัด มาทั้งหมด
   * @return fetchAll Array
   */
  public function getProvince(){
    $sql = "SELECT * FROM province ORDER BY id";
    $res = $this->fetchAll($sql,[]);
    return $res;
  }

  /**
   * ดึงข้อมูลจังหวัด ตาม id
   * @param mixed $id เลขไอดี
   * @return fetchObject Object
   */
  public function getProvinceByID($id){
    $sql = "SELECT * FROM province WHERE id=:id LIMIT 1";
    $res = $this->fetchObject($sql,[":id" => $id]);
    return $res;
  }

  /**
   * ดึงข้อมูลจังหวัด ตาม id
   * @param mixed $id เลขไอดีแบบ Array
   * @return fetchObject Object
   */
  public function getProvinceByIDS($id=[]){
    $sql = "SELECT * FROM province WHERE id=:id LIMIT 1";
    $res = $this->fetchObject($sql,[":id" => $id]);
    return $res;
  }

  /**
   * ดึงข้อมูลจังหวัด ตาม name
   * @param mixed $name ชื่อจังหวัด
   * @return fetchObject Object
   */
  public function getProvinceByName($name){
    $sql = "SELECT * FROM province WHERE province_name=:name LIMIT 1";
    $res = $this->fetchObject($sql,[":id" => $name]);
    return $res;
  }

  /**
   * ดึงข้อมูลจังหวัด ตาม name
   * @param mixed $name ชื่อจังหวัดแบบ Array
   * @return fetchObject Object
   */
  public function getProvinceByNames($name = []){
    $sql = "SELECT * FROM province WHERE province_name=:name LIMIT 1";
    $res = $this->fetchObject($sql,[":id" => $name]);
    return $res;
  }


  /**
   * ตรวจสอบว่ามีชื่อจังหวัดนี้หรือไม่
   * @param mixed $name ชื่อจังหวัด
   * @return true/false
   */
  public function isProvinceByName($name){
    $sql = "SELECT id FROM province WHERE province_name=:name LIMIT 1";
    $res = $this->fetchObject($sql,[":name" => $name]);
    return !empty($res)?true:false;
  }

  /**
   * render html จังหวัด
   * @param mixed $province ข้อมูลที่ได้จากการ query ในฐานข้อมูล
   * @return html
   */
  public function render_province($province){
    $html = '';
    foreach($province as $key => $res){
      $html .= "".Component::province_item(["res" => $res])."";
    }
    return $html;
  }
}
