<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);


use \Firebase\JWT\JWT;

require_once DOC_ROOT . '/classes/FrontEnd.php';
require_once DOC_ROOT . '/classes/WebSecurity.class.php';
// require_once './../src/autoload.php';      // ReCaptcha


class Gallery extends FrontEnd
{
  public function __construct()
  {
    parent::__construct();
    //ตรวจสอบว่ามีฟังก์ชั่นในคลาสไหมถ้ามีให้เรียกใช้งาน
  }


  /**
   * ดึงรูปภาพ บรรยากาศ อาหาร รีวิวลูกค้า
   * @param mixed $cateID เลขid ของ category
   * @return fetchAll
   */
  public function getPostImagesWithCateID($cateID = null)
  {
    $where = "";
    if (!empty($cateID)) {
      $where = "WHERE p.category = '" . $cateID . "'";
    }
    $sql = "SELECT pi.image_link , p.title
            FROM post_image as pi
            INNER JOIN post as p ON pi.post_id = p.id
            INNER JOIN category as c ON p.category = c.cate_id 
            $where
            ORDER BY pi.image_id DESC
          ";
    $response = $this->fetchAll($sql, []);
    return $response;
  }


  /**
   * ดึงข้อมูล image_link,title ของ post_image
   * @param mixed $cateID เลขid ของ category
   * @param mixed $start เลขหน้าเริ่มต้นของแต่ละ page
   * @param mixed $perpage จำนวน item ที่จะแสดงต่อหนึ่งหน้า 
   * @return fetchAll
   */
  public function getDataPostImageWidthCateID($cateID = null,$start,$perpage)
  {
    $where = "";
    if (!empty($cateID)) {
      $where = "WHERE p.category = '" . $cateID . "'";
    }
    $sql = "SELECT pi.image_link , p.title
            FROM post_image as pi
            INNER JOIN post as p ON pi.post_id = p.id
            INNER JOIN category as c ON p.category = c.cate_id 
            $where
            ORDER BY pi.image_id DESC
            LIMIT {$start},{$perpage}
          ";
    $response = $this->fetchAll($sql, []);
    return $response;
  }


  /**
   * ดึงจำนวนข้อมูล item ของ post image ว่ามีกี่จำนวน
   * @param mixed $cateID เลขid ของ category
   * @return จำนวน(ตัวเลข) $response->count
   */
  public function getCountPostImageWidthCateID($cateID = null)
  {
    $where = "";
    if (!empty($cateID)) {
      $where = "WHERE p.category = '" . $cateID . "'";
    }
    $sql = "SELECT COUNT(pi.image_link) as count
            FROM post_image as pi
            INNER JOIN post as p ON pi.post_id = p.id
            INNER JOIN category as c ON p.category = c.cate_id 
            $where
            ORDER BY pi.image_id DESC
          ";
    $response = $this->fetchObject($sql, []);
    return $response->count;
  }


  /**
   * render รูปภาพ บรรยากาศ อาหาร รีวิวลูกค้า
   * @param moxed $response เป็นข้อมูลที่ได้จากการ query ที่ฟังชั่น getPostImagesWithCateID($cateID=null)
   * @return html
   */
  public function renderPostImages($response)
  {
    $html = "";
    foreach($response as $key => $res){
      $html .= '<figure onclick="handleClickShowImage(event)" data-image="'.SITE_URL.$res['image_link'].'">
                  <img src="'.SITE_URL.$res['image_link'].'?v=1" alt="'.$res['title'].'">
                </figure>';
    }
    return $html;  
  }

  /**
   * ฟังก์ชั่น Pagination
   * @param mixed $cateID เลขid ของ category
   * @param mixed $_perpage จำนวน item ที่จะแสดงต่อหนึ่งหน้า
   * @param mixed $_page หน้าที่ user กำลังดู
   * @return json
   */
  public function pagination($cateID,$_perpage,$_page)
  {
    #จำนวน item ที่จะแสดงต่อหนึ่งหน้า
    $perpage = $_perpage; 
    #หน้าที่ user กำลังดู
    $page = !empty($_page)?$_page:1;
    #หน้าเริ่มต้นของแต่ละ page
    $start = ($page - 1) * $perpage;

    #หาจำนวนของ item
    $totalRecord = $this->getCountPostImageWidthCateID($cateID);
    #จำนวนหน้าทั้งหมดที่มี
    $totalPage = ceil($totalRecord/$perpage);

    return [
      "totalRecord"=> $totalRecord, #หาจำนวนของ item
      "totalPage" => $totalPage,    #จำนวนหน้าทั้งหมดที่มี
      "start" => $start,            #หน้าเริ่มต้นของแต่ละ page
      "perpage" => $perpage,        #จำนวน item ที่จะแสดงต่อหนึ่งหน้า
      "page" => $page               #หน้าที่ user กำลังดู
    ];    
  }
}
