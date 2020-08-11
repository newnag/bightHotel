<?php
/*
Class Route Create By Kotbass
*/

class Route
{
  public function __construct(){}

  /**
   * ฟังชั่น route
   * จะทำการ ตัด slug url ว่าอยู่ level ไหน
   * return Array
   */
  public static function slug($opt = null)
  {
    #อ่านค่า URI จาก URL และ ทำการ Explode / ให้เป็น Array
    $uri_full = !empty($opt) ? $opt : urldecode($_SERVER['REQUEST_URI']);
    $uri_array = explode("/", $uri_full);
    $uri_level = [];

    #เช็คดู Uri Level 1 จะเก็บไว้ในตัวแปร $pageView
    $uri_level['level_1'] = trim($uri_array[1]);
    if ($uri_level['level_1'] == "" || $uri_level['level_1'] == "หน้าหลัก" || $uri_level['level_1'] == "หน้าหลัก" || $uri_level['level_1'] == "หน้าแรก") {
      $uri_level['level_1'] = "หน้าหลัก";
    } else {
      $match = preg_match("/\?+/", $uri_level['level_1']);
      if ($match > 0) { #ถ้ามี Query String
        $param_slug = explode('?', $uri_level['level_1']);
        $uri_level['level_1'] = empty($param_slug[0]) ? "หน้าหลัก" : $param_slug[0];
      }
    }

    #วงลูปสร้าง uri_level 2 ถึง (count($uri_array) - 1)
    $loop_i = count($uri_array) - 1;
    for ($i = 2; $i <= $loop_i; $i++) {
      if (isset($uri_array[$i]) && !empty($uri_array[$i])) {
        $match = preg_match("/\?+/", $uri_array[$i]);
        if ($match > 0) { //ถ้ามี Query String
          $uri_level['level_' . $i] = explode('?', $uri_array[$i])[0];
        } else {
          $uri_level['level_' . $i] = trim($uri_array[$i]);
        }
      }
    }

    return $uri_level;
  }

  #ฟังชั่นที่เอาไว้ Route
  public static function route($opt,$method)
  {
    global $cateID, $Route , $isRoute;
    // print_r($opt);
    if (is_numeric($opt)) {
      // echo "CateID: ".$cateID." | ".$opt."<br>";
      if ($cateID == $opt) {
        // echo "is_numeric";
        $isRoute = true;
        Route::execute($method);
      }
    }else if(is_array($opt)){
      if(in_array($cateID,$opt)){
        // echo "is_array";
        Route::execute($method);
      }
    } else {
      #ถ้าเป็นตัวอักษร
      #create instance FrontEnd
      $app = new Application();
      #split slug $opt
      $route_arr = Route::slug($opt);
      #หา category id จาก array level_1
      $cate_id = $app->getCateIDByURL($route_arr['level_1']);
      
      #เช็คว่า เลข category id ตรงกับ $cateID หรือไม่ และเช็คว่า Route level เท่ากันไหม
      if(strtolower($opt) == 'other' || strtolower($opt) == '/other'){
        if(empty($isRoute)){ Route::execute($method);}
      }
      else if (count($Route) === count($route_arr) && $Route['level_1'] === $route_arr['level_1']) {
        if (count($route_arr) === 1) {
          #มี level === 1
          Route::execute($method);
        } else {
          #ถ้ามี Level มากกว่า 1
          // print_r($Route);
          $isOn = true;
          $isParam = false; #ตัวแปรเอาไว้เช็คว่า มี param หรือไม่ true คือ มี , false คือ ไม่มี
          $arrParam = []; #ตัวแปรเอาไว้เก็บ ค่า param
          for ($i = 2; $i <= count($route_arr); $i++) {
            #เช็คว่ามีการส่ง Param เข้ามาด้วยหรือไม่
            if (isset($route_arr['level_' . $i]) && Route::isParam($route_arr['level_' . $i])) {
              $isParam = true;
              array_push($arrParam,$Route['level_' . $i] );
            }else{
              if($route_arr['level_' . $i] !== $Route['level_' . $i]){
                $isOn = false;
              }
            }
          } //endfor
          
          #ถ้าเข้าเงื่อนไขการ route
          if($isOn){
            #เช็คตัวแปร $isParam ว่าเป็น True หรือ False
            if (!$isParam) {
              #False ถ้าไม่มี Param
              Route::execute($method);
            } else {
              #True ถ้ามี Param
              if (count($arrParam) > 1) {

                #good
                echo eval('print_r($method("' . implode('","', $arrParam) . '"));');

              } else {
                #ถ้ามี param แค่ 1 ตัว
                Route::execute($method,$arrParam[0]);
              }
            }
          }
        }
      }
      
      
    }
  }

  #ฟังชั่น execute method => Route::execute($method)
  public static function execute($method,$param = false){
    if( gettype($method) === "object" ){
      if(!$param){
        print_r($method());
      }else{
        print_r($method($param));
      }
    }
    else if( gettype($method) === "string" ){
      list($myClass,$myMethod) = explode('@',$method);
      require_once __DIR__.'/../api/api.'.$myClass.'.php';
      $instance = new $myClass();
      $instance->$myMethod();
    }
  }

  #ฟังก์ชั่น get
  public static function get($opt, $method)
  {
    if ($_SERVER['REQUEST_METHOD'] === "GET") { Route::route($opt,$method); }
  }

  #ฟังก์ชั่น post
  public static function post($opt, $method)
  {
    if ($_SERVER['REQUEST_METHOD'] === "POST") Route::route($opt,$method);
  }

  #ฟังก์ชั่น patch
  public static function patch($opt, $method)
  {
    if ($_SERVER['REQUEST_METHOD'] === "PATCH") Route::route($opt,$method);
  }

  #ฟังก์ชั่น put
  public static function put($opt, $method)
  {
    if ($_SERVER['REQUEST_METHOD'] === "PUT") Route::route($opt,$method);
  }

  #ฟังก์ชั่น delete
  public static function delete($opt, $method)
  {
    if ($_SERVER['REQUEST_METHOD'] === "DELETE") Route::route($opt,$method);
  }

  #ฟังก์ชั่น options
  public static function options($opt, $method)
  {
    if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") Route::route($opt,$method);
  }

  #ฟังก์ชั่น resource
  public static function resource($opt, $method)
  {
    Route::route($opt,$method);
  }

  #เช็คว่ามี Param หรือไม่
  public static function isParam($opt = null)
  {
    if (empty($opt)) return false;
    $param = explode(":", $opt);
    return !empty($param[1]) ? true : false;
  }
}
