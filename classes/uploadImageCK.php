<?php 
  #create by kotbass

  
  require_once $_SERVER['DOCUMENT_ROOT']."/config/config.php";

  //file upload for ckeditor
  if(isset($_FILES['upload']['name'])){
    $file = $_FILES['upload']['tmp_name'];
    $file_type = $_FILES['upload']['type'];
    $file_name = $_FILES['upload']['name'];
    $file_extendsion = pathinfo($_FILES['upload']['name'],PATHINFO_EXTENSION);
    $new_image_name = md5(uniqid(rand(),TRUE))."".$file_extendsion;


    #ตรวจสอบ TYPE
    if(
      (strtolower($file_type) !== "image/jpeg") &&
      (strtolower($file_type) !== "image/png") &&
      (strtolower($file_type) !== "image/jpeg") &&
      (strtolower($file_type) !== "image/gif")
    ){
      echo "file type invalid";
      exit();
    }


    #ตรวจสอบ extendsion
    if(!in_array($file_extendsion,["jpg","jpeg","png","gif","webp"])){
      echo "file extension invalid";
      exit();
    }
    
    $documentRootUpload = $_SERVER['DOCUMENT_ROOT'].'/upload/';
    #เช็คว่ามี folder ของปีปัจจุบันหรือไม่
    if(is_dir($documentRootUpload.date('Y'))){
      #เช็คว่ามี folder ของเดือนปัจจุบันหรือไม่
      if(!is_dir($documentRootUpload.date('Y').'/'.date('m'))){
        @mkdir($documentRootUpload.date('Y').'/'.date('m'),0644);
      }
    }else{
      @mkdir($documentRootUpload.date('Y').'/'.date('m'),0644);
    }
    
    $filePath = $documentRootUpload.date('Y').'/'.date('m').'/'. $new_image_name;
    $img = 'upload/'.date('Y').'/'.date('m').'/'. $new_image_name;
    

    #upload
    if (move_uploaded_file($file, $filePath)){
      $url =  SITE_URL.$img;
      $response=array("uploaded"=>1,"fileName"=>"{$new_image_name}","url"=>"{$url}");
      echo json_encode($response);
    }

  }
?>