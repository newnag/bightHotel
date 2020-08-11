<?php

/**
 * Create By Kotbass
 */

session_start();
error_reporting(1);
ini_set('display_errors', 1);


use \Firebase\JWT\JWT;

require_once DOC_ROOT . '/classes/FrontEnd.php';
require_once DOC_ROOT . '/classes/WebSecurity.class.php';
// require_once './../src/autoload.php';      // ReCaptcha

class Upload extends FrontEnd
{
  public $documentRootUpload;
  public function __construct()
  {
    parent::__construct();
    //ตรวจสอบว่ามีฟังก์ชั่นในคลาสไหมถ้ามีให้เรียกใช้งาน
    $this->documentRootUpload = $_SERVER['DOCUMENT_ROOT'].'/upload/';
  }

  /**
   * @param mixed $mid คือ $App->getMember('id') 
   * @param mixed $file คือ $_FILES 
   * @param mixed $token คือ เป็นเลข token ที่จะเอาไว้ไปเว็บใน upload_tmp
   * @return json
   */
  public function uploadImg($mid,$file,$token=null)
  {
    #file info
    $fileName = basename($file['name']);
    $fileExt = pathinfo($file["name"], PATHINFO_EXTENSION);
    $fileType = $file['type'];
    $fileTMP = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileErr = $file['error'];

    #create Name
    $fileNameNew = md5(uniqid() . $fileName) . '.' . $fileExt;

    #Check FILE TYPE
    if ((strtolower($fileType) !== "image/jpeg") && (strtolower($fileType) !== "image/png") && (strtolower($fileType) !== "image/webp") ) {
      return ["status" => 400,"status_upload" => false,"message" => "error_type" ,"error" => $fileType];
    }

    #Check File Extension 
    if (!in_array($fileExt, ["jpg", "jpeg", "png","webp"])) {
      return ["status" => 400,"status_upload" => false,"message" => "error_extension"];
    }    

    #เช็คว่ามี folder ของปีปัจจุบันหรือไม่
    if(is_dir($this->documentRootUpload.date('Y'))){
      #เช็คว่ามี folder ของเดือนปัจจุบันหรือไม่
      if(!is_dir($this->documentRootUpload.date('Y').'/'.date('m'))){
        @mkdir($this->documentRootUpload.date('Y').'/'.date('m'),0644);
      }
    }else{
      @mkdir($this->documentRootUpload.date('Y').'/'.date('m'),0644);
    }
    
    $filePath = $this->documentRootUpload.date('Y').'/'.date('m').'/'. $fileNameNew;
    $img = 'upload/'.date('Y').'/'.date('m').'/'. $fileNameNew;


    if (move_uploaded_file($fileTMP, $filePath)) {

      $sql = "INSERT INTO upload_tmp(mem_id,image,status,date_create,date_update,token)
              VALUES(:mem_id,:image,'pending',:date_create,:date_update,:token)";
      $values = [
        ":mem_id" => $mid,
        ":image"  => $img,
        ":date_create" => date('Y-m-d H:i:s'),
        ":date_update" => date('Y-m-d H:i:s'),
        ":token" => $token
      ];
      $res= $this->insertValue($sql,$values);
      return [
        "status" => 200,
        "status_upload" => true,
        "message" => "success",
        "img_src" => SITE_URL.$img,
        "img" => $img,
        "img_tmp_id" => $res['last_id']
      ];

    } else {
      return ["status" => 400,"status_upload" => false,"message" => "error_fail"];
    }
  }

  /**
   * @param $mid คือ $App->getMember('id') 
   * @param $file คือ $_FILES 
   * @param $token คือ เป็นเลข token ที่จะเอาไว้ไปเว็บใน upload_tmp
   */
  public function uploadImgMulti($mid,$files,$token)
  {
    $statusUpload = true;
    $status = array();
    $imgsrcArr = array();
    $imgArr = array();
    $lastidArr = array();
    $test = array();

    foreach($files['name'] as $key => $file){
      #file info
      $fileName = basename($files['name'][$key]);
      $fileExt = pathinfo($files["name"][$key], PATHINFO_EXTENSION);
      $fileType = $files['type'][$key];
      $fileTMP = $files['tmp_name'][$key];
      $fileSize = $files['size'][$key];
      $fileErr = $files['error'][$key];
      
      #create Name
      $fileNameNew = md5(uniqid() . $fileName) . '.' . $fileExt;
  
      #Check FILE TYPE
      if ((strtolower($fileType) !== "image/jpeg") && 
      (strtolower($fileType) !== "image/png") && (strtolower($fileType) !== "image/webp") ) {
        $statusUpload = false;
        array_push($status,'400');
        array_push($test,'error_type');
        break;
        // return ["status" => 400,"status_upload" => false,"message" => "error_type"];
      }
  
      #Check File Extension 
      if (!in_array($fileExt, ["jpg", "jpeg", "png","webp"])) {
        array_push($status,'400');
        $statusUpload = false;
        array_push($test,'error_extension');
        break;
        // return ["status" => 400,"status_upload" => false,"message" => "error_extension"];
      }    
  
      
      #เช็คว่ามี folder ของปีปัจจุบันหรือไม่
      if(is_dir($this->documentRootUpload.date('Y'))){
        #เช็คว่ามี folder ของเดือนปัจจุบันหรือไม่
        if(!is_dir($this->documentRootUpload.date('Y').'/'.date('m'))){
          @mkdir($this->documentRootUpload.date('Y').'/'.date('m'),0644);
        }
      }else{
        @mkdir($this->documentRootUpload.date('Y').'/'.date('m'),0644);
      }
      
      $filePath = $this->documentRootUpload.date('Y').'/'.date('m').'/'. $fileNameNew;
      $img = 'upload/'.date('Y').'/'.date('m').'/'. $fileNameNew;
      

      if (move_uploaded_file($fileTMP, $filePath)) {
  
        $sql = "INSERT INTO upload_tmp(mem_id,image,status,date_create,date_update,token)
                VALUES(:mem_id,:image,'pending',:date_create,:date_update,:token)";
        $values = [
          ":mem_id" => $mid,
          ":image"  => $img,
          ":date_create" => date('Y-m-d H:i:s'),
          ":date_update" => date('Y-m-d H:i:s'),
          ":token" => $token
        ];
        $res= $this->insertValue($sql,$values);
  
        $statusUpload = true;
        array_push($status,'200');
        array_push($imgsrcArr,SITE_URL.$img);
        array_push($imgArr,$img);
        array_push($lastidArr,$res['last_id']);


        // return [
        //   "status" => 200,
        //   "status_upload" => true,
        //   "message" => "success",
        //   "img_src" => SITE_URL.$img,
        //   "img" => $img,
        //   "img_tmp_id" => $res['last_id']
        // ];
      } else {
        array_push($status,'400');
        $statusUpload = false;
        array_push($test,'error_fail');
        break;
        // return ["status" => 400,"status_upload" => false,"message" => "error_fail"];
      }
      
    }//end foreach

    if($statusUpload){
      return [
        "status" => $status,
        "status_upload" => true,
        "message" => "success",
        "img_src" => $imgsrcArr,
        "img" => $imgArr,
        "img_tmp_id" => $lastidArr,
        "test" => $test
      ];
    }else{
      return [
        "status" => 400,
        "status_upload" => false,
        "message" => "error_fail",
        "test" => $test
      ];
    }
  }
}
