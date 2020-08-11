<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);


use \Firebase\JWT\JWT;

require_once __DIR__.'/../classes/FrontEnd.php';
require_once __DIR__.'/../classes/WebSecurity.class.php';
// require_once './../src/autoload.php';      // ReCaptcha


class Test extends FrontEnd
{
  /*
    public function __construct($method)
    {
      parent::__construct();
      //ตรวจสอบว่ามีฟังก์ชั่นในคลาสไหมถ้ามีให้เรียกใช้งาน
      echo "test";
      if (method_exists($this, $method)) {
        $this->frontEnt = new FrontEnd();
        $this->secret = "6Lf24NUUAAAAAAHeqYYExorg0cDxpKAm3Nedh_op";
        $this->$method();
      } else {
        echo 'Permission denied';
        exit;
      }
    }
  */
  public function __construct()
  {
    parent::__construct();
    //ตรวจสอบว่ามีฟังก์ชั่นในคลาสไหมถ้ามีให้เรียกใช้งาน
  }

  public function test(){
    
    print_r($_POST);
  }
}

// $action = isset($_POST['action']) ? $_POST['action'] : '';
// if (!empty($action)) {
//   new Test($action);
// }
?>