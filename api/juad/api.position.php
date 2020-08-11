<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);


use \Firebase\JWT\JWT;

require_once DOC_ROOT . '/classes/FrontEnd.php';
require_once DOC_ROOT . '/classes/WebSecurity.class.php';
// require_once './../src/autoload.php';      // ReCaptcha


class Position extends FrontEnd
{
  public function __construct()
  {
    parent::__construct();
    //ตรวจสอบว่ามีฟังก์ชั่นในคลาสไหมถ้ามีให้เรียกใช้งาน
  }

  /**
   * ดึงตำแหน่งของซุ้ม หรือ โต๊ะ
   * @param mixed $type ประเภทของที่นั่ง (arch/table)
   * @return fetchAll Array
   */
  public function getPositionAll()
  {
    $sql = "SELECT * FROM position_table ORDER BY position_num";
    $res = $this->fetchAll($sql, []);
    return $res;
  }

  /**
   * ดึงตำแหน่งของซุ้ม หรือ โต๊ะ
   * @param mixed $type ประเภทของที่นั่ง (arch/table)
   * @return fetchAll Array
   */
  public function getPosition($type)
  {
    $sql = "SELECT * FROM position_table WHERE type=:type ORDER BY position_num";
    $res = $this->fetchAll($sql, [":type" => $type]);
    return $res;
  }


  /**
   * render ตำแหน่งของซุ้ม หรือโต๊ะ
   * @param mixed $data ข้อมูลที่ได้จากการ queryในฐานข้อมูล
   * @return html
   */
  public function render_position($dataPosition)
  {
    $html = '';
    foreach ($dataPosition as $key => $res) {
      $html .= "" . Component::position_item(["res" => $res]) . "";
    }
    return $html;
  }


  /**
   * render ตำแหน่งของซุ้ม หรือโต๊ะ เพื่อให้ผู้ใช้คลิ๊กเลือก
   * @param mixed $data ข้อมูลที่ได้จากการ queryในฐานข้อมูล
   * @return html
   */
  public function render_handleclick_position($dataPosition,$type)
  {
    $html = '';
    foreach ($dataPosition as $key => $res) {
      if($type == "arch"){
        $html .= "" . Component::arch_handle_click_position_item(["res" => $res,"count" => ($key+1)]) . "";
      }else{
        $html .= "" . Component::table_handle_click_position_item(["res" => $res,"count" => ($key+1)]) . "";
      }
    }
    return $html;
  }
}
