<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);


use \Firebase\JWT\JWT;

require_once DOC_ROOT . '/classes/FrontEnd.php';
require_once DOC_ROOT . '/classes/WebSecurity.class.php';
// require_once './../src/autoload.php';      // ReCaptcha


class Booking extends FrontEnd
{
  public function __construct()
  {
    parent::__construct();
    //ตรวจสอบว่ามีฟังก์ชั่นในคลาสไหมถ้ามีให้เรียกใช้งาน
  }

  /**
   * ดึงรอบเวลาของ ซุ้มที่นั่ง
   * 11.00 น - 13.00น และอื่นๆ รอบละ 2 ชั่วโมง
   * @param mixed $type arch/table
   * @return html
   */
  public function getTimeRound($type)
  {
    $sql = "SELECT id,round,time_start,time_end FROM time_round WHERE type=:type ORDER BY id ASC";
    $response = $this->fetchAll($sql, [":type" => $type]);
    $html = "";

    $html .= '<option value="">เลือกรอบ/เวลา</option>';
    foreach ($response as $key => $res) {
      $html .= "" . Component::TimeRound_item(["res" => $res]) . "";
    }

    return $html;
  }

  /**
   * ดึงข้อมูลการจอง ซุ้มที่นั่ง ด้วย วันที่และรอบเวลา ว่าซุ้มไหนโดนจองไปแล้วบ้าง
   * @param mixed $date ปีเดือนวัน
   * @param mixed $timeround รอบเวลา
   * @param mixed $type "arch"
   * @return fetchAll
   */
  public function getPositionArchWithDateAndTimeround($date, $timeround, $type = "arch")
  {
    $sql = "SELECT p.position_num , bk.status 
            FROM booking as bk
            LEFT JOIN time_round as tr ON bk.time_round_id = tr.id
            INNER JOIN position_table as p ON bk.position_id = p.id
            WHERE DATE_FORMAT(book_date,'%Y-%m-%d') = DATE_FORMAT(:mydate ,'%Y-%m-%d') AND 
                  time_round_id=:time_round_id AND bk.type=:type AND bk.status <> 'checkout'
            ";
    $response = $this->fetchAll($sql, [
      ":mydate" => date('Y-m-d', strtotime($date)),
      ":time_round_id" => $timeround,
      ":type" => $type
    ]);

    if (!$response) {
      return json_encode([
        "status_" => false,
        "message" => "fail"
      ]);
    } else {
      return  json_encode([
        "status_" => true,
        "message" => "success",
        "res" => $response
      ]);
    }
  }


  /**
   * ดึงข้อมูลการจอง โต๊ะ ด้วย วันที่และรอบเวลา ว่าโต๊ะไหนถูกจองไปแล้วบ้าง
   * @param mixed $date ปีเดือนวัน
   * @param mixed $timeround ไม่ได้ใช้
   * @param mixed $type "table"
   * @return fetchAll
   */
  public function getPositionTableWithDateAndTimeround($date, $timeround, $type = "table")
  {
    $sql = "SELECT p.position_num , bk.status 
            FROM booking as bk
            LEFT JOIN time_round as tr ON bk.time_round_id = tr.id
            INNER JOIN position_table as p ON bk.position_id = p.id
            WHERE DATE_FORMAT(book_date,'%Y-%m-%d') = DATE_FORMAT(:mydate ,'%Y-%m-%d') AND 
                  bk.type=:type AND bk.status <> 'checkout'
            ";
    $response = $this->fetchAll($sql, [
      ":mydate" => date('Y-m-d', strtotime($date)),
      // ":time_round_id" => $timeround,
      ":type" => $type
    ]);

    if (!$response) {
      return json_encode([
        "status_" => false,
        "message" => "fail"
      ]);
    } else {
      return  json_encode([
        "status_" => true,
        "message" => "success",
        "res" => $response,
        "type" => $type
      ]);
    }
  }


  /**
   * ตรวจสอบว่า หมา่ยเลข (ซุ้ม) ถูกจองไปหรือยัง
   * @param mixed $date วันเดือนปีที่ User ส่งเข้ามา
   * @param mixed $timeround รอบเวลาที่ user ส่งเข้ามา
   * @param mixed $position position_id ที่ user ส่งเข้ามา
   * @return true/false "trueคือจองแล้ว falseคือยังไม่จอง"
   */
  public function isPositionBooking($type, $date, $timeround, $position)
  {
    if ($type == "arch") {
      $sql = "SELECT id 
            FROM booking 
            WHERE position_id=:position_id 
                  AND DATE_FORMAT(book_date,'%Y-%m-%d') = DATE_FORMAT(:date,'%Y-%m-%d')
                  AND time_round_id = :time_round_id
                  AND type='arch'
          ";
      $res = $this->fetchObject($sql, [
        ":date" => $date,
        ":position_id" => $position,
        ":time_round_id" => $timeround,
      ]);
    } else if ($type == "table") {
      $sql = "SELECT id 
            FROM booking 
            WHERE position_id=:position_id 
                  AND DATE_FORMAT(book_date,'%Y-%m-%d') = DATE_FORMAT(:date,'%Y-%m-%d')
                  AND type='table'
          ";
      $res = $this->fetchObject($sql, [
        ":date" => $date,
        ":position_id" => $position,
      ]);
    }


    return !empty($res) ? true : false;
  }


  /**
   * ฟังชั่นในการบันทึกข้อมูลลงในฐานข้อมูล ของการจอง
   * @param mixed $type "arch/table"
   * @param mixed $input ข้อมูล ที่ส่งมาจาก user
   * @return json
   */
  public function store($type, $input)
  {
    global $App;

    #ตรวจสอบค่าว่าง
    if (empty($input->date) || empty($input->line) || empty($input->name) || empty($input->people) || empty($input->phone) || empty($input->province) || empty($input->table) || empty($input->time)) {
      return json_encode(["message" => "data_not_null", "status_" => false]);
      exit();
    }

    #ตรวจสอบวันเดือนปีว่าถูกต้องหรือไม่
    if (!checkdate(date('m', strtotime($input->date)), date('d', strtotime($input->date)), date('Y', strtotime($input->date)))) {
      return json_encode(["message" => "date_invalid", "status_" => false]);
      exit();
    }

    #ตรวจสอบว่า วันเดือนปีที่ส่งเข้ามานั้น น้อยกว่าหรือเท่ากับ วันที่ปัจจุบันหรือไม่ ถ้าใช่ return error
    if( strtotime($input->date) <= time() ){
      return json_encode(["message" => "date_invalid_less_than", "status_" => false]);
      exit();
    }


    #ตรวจสอบ รอบเวลาที่ส่งเข้ามาเป็นตัวเลขหรือไม่
    if (!is_numeric($input->time)) {
      return json_encode(["message" => "time_invalid", "status_" => false]);
      exit();
    }

    #ตรวจสอบเบอร์มือถือ ว่า วันนี้ได้จองไปหรือยัง
    $sqlfindphone = " SELECT phone 
                      FROM booking
                      WHERE DATE_FORMAT(book_date,'%Y-%m-%d') = DATE_FORMAT(:book_date,'%Y-%m-%d')
                            AND phone=:phone  
                    ";
    $resFindPhone = $this->fetchObject($sqlfindphone, [":book_date" => date('Y-m-d', strtotime($input->date)), ":phone" => $input->phone]);
    if ($resFindPhone) {
      return json_encode(["message" => "phone_booked", "status_" => false, "phone" => $resFindPhone->phone]);
      exit();
    }

    #ตรวจสอบ รอบเวลาที่ส่งเข้ามามีอยู่ในฐานข้อมูลหรือไม่
    $sqlCheckTime = "SELECT id FROM time_round WHERE id=:id AND type=:type LIMIT 1";
    $resCheckTime = $this->fetchObject($sqlCheckTime, [":id" => $input->time, ":type" => $type]);
    if (!$resCheckTime) {
      return json_encode(["message" => "time_fail", "status_" => false]);
      exit();
    }

    #ตรวจสอบว่ามีหมายเลข(ซุ้ม/โต๊ะ)นี้หรือไม่
    $sqlCheckPosition = "SELECT id FROM position_table WHERE position_num=:table AND type=:type LIMIT 1";
    $resCheckPosition = $this->fetchObject($sqlCheckPosition, [":table" => $input->table, ":type" => $type]);
    if (!$resCheckPosition) {
      return json_encode(["message" => "position_fail", "status_" => false]);
      exit();
    }


    #ตรวจสอบว่าหมายเลข(ซุ้ม/โต๊ะ)นี้มีคนจองหรือยัง
    if ($this->isPositionBooking($type, date('Y-m-d', strtotime($input->date)), $input->time, $resCheckPosition->id)) {
      return json_encode([
        "message" => "position_booking",
        "status_" => false,
        "detail" => 'ไม่สามารถจอง' . (($type == "arch") ? "ซุ้ม" : "โต๊ะ") . "ที่ " . $input->table . " นี้ได้เพราะคนอื่นจองไปก่อนแล้วครับ กรุณากด OK และทำการเลือกซุ้มอื่น",
        "type" => $type,
        "timeround" => $input->time,
        "date" => $input->date
      ]);
      exit();
    }


    #ตรวจสอบว่า จำนวนคนเกินที่กำหนดไว้หรือไม่
    if ($type == "arch") {
      if ($input->people > 15) {
        return json_encode(["message" => "people_invalid", "status_" => false]);
        exit();
      }
    } else if ($type == "table") {
      if ($input->people > 5) {
        return json_encode(["message" => "people_invalid", "status_" => false]);
        exit();
      }
    }


    #ตรวจสอบว่าข้อมูลที่ส่งเข้ามา มี length ยาวเกินกว่าที่ DB กำหมดไว้หรือไม่
    if( 
      strlen($input->line) > 50 || 
      strlen($input->name) > 255 || 
      strlen($input->people) > 3 || 
      strlen($input->phone) > 20 || 
      strlen($input->province) > 2 || 
      strlen($input->table) > 2 || 
      strlen($input->time) > 2
    ){
      return json_encode(["message" => "data_length_valid", "status_" => false]);
      exit();
    }


    #ค้นหา booking_id_name
    #จัดเลขเรียง
    $findBookingIdName = $this->fetchObject("SELECT book_id_name FROM booking WHERE type=:type ORDER BY id DESC", [":type" => $type]);
    $bookingIdName = "";
    if (!$findBookingIdName) {
      #ถ้าไม่มี
      $bookingIdName = ($type == "arch") ? "A00000001" : "T00000001";
    } else {
      if ($type == "arch") {
        $tmp = ltrim($findBookingIdName->book_id_name, "A");
        $bookingIdName = "A" . str_pad(($tmp + 1), 8, "0", STR_PAD_LEFT);
      } else {
        $tmp = ltrim($findBookingIdName->book_id_name, "T");
        $bookingIdName = "T" . str_pad(($tmp + 1), 8, "0", STR_PAD_LEFT);
      }
    }

    // echo "บันทึกข้อมูล"; exit();

    #บันทึกข้อมูล
    $bookingID = sha1(uniqid(rand(), true));
    $sql = "INSERT INTO booking(book_id,book_id_name,book_date,time_round_id,position_id,name,people_qty,phone,line_id,province_id,type,status,date_create,date_update)
            VALUES (:book_id,:book_id_name,:book_date,:time_round_id,:position_id,:name,:people_qty,:phone,:line_id,:province_id,:type,'pending',NOW(),NOW())";
    $values = [
      ":book_id"    => $bookingID,
      ":book_id_name"    => $bookingIdName,
      ":book_date"  => date('Y-m-d', strtotime($input->date)),
      ":time_round_id" => $input->time,
      ":position_id" => $resCheckPosition->id,
      ":name" => $input->name,
      ":people_qty" => $input->people,
      ":phone" => $input->phone,
      ":line_id" => $input->line,
      ":province_id" => $input->province,
      ":type" => $type,
    ];
    $resInsert = $this->insertValue($sql, $values);
    if ($resInsert['message'] == "OK") {

      #ดึงค่า url ของ cate ยืนยันการจอง
      $tranfer = $App->getCategoryFieldByCateID(4, "url");

      #ยังไม่ได้ใช้ ลบออกได้
      // $_SESSION['booking']['booking_id'] = $bookingID;

      return json_encode(["message" => "success", "status_" => true, "url" => SITE_URL . $tranfer->url]);
    } else {
      return json_encode(["message" => "fail", "status_" => false]);
    }
  }


  /**
   * ฟังชั่นในการบันทึกข้อมูลลงในฐานข้อมูล ของการจอง แบบมากกว่า 1 (ซุ้ม/โต๊ะ)
   * @param mixed $type "arch/table"
   * @param mixed $input ข้อมูล ที่ส่งมาจาก user
   * @return json
   */
  public function storeMulti($type, $input)
  {
    global $App;

    $tables = explode(",", $input->table);

    #ตรวจสอบค่าว่าง
    if (empty($input->date) || empty($input->line) || empty($input->name) || empty($input->people) || empty($input->phone) || empty($input->province) || empty($input->table) || empty($input->time)) {
      return json_encode(["message" => "data_not_null", "status_" => false]);
      exit();
    }

    #ตรวจสอบวันเดือนปีว่าถูกต้องหรือไม่
    if (!checkdate(date('m', strtotime($input->date)), date('d', strtotime($input->date)), date('Y', strtotime($input->date)))) {
      return json_encode(["message" => "date_invalid", "status_" => false]);
      exit();
    }

    #ตรวจสอบว่า วันเดือนปีที่ส่งเข้ามานั้น น้อยกว่าหรือเท่ากับ วันที่ปัจจุบันหรือไม่ ถ้าใช่ return error
    if( strtotime($input->date) <= time() ){
      return json_encode(["message" => "date_invalid_less_than", "status_" => false]);
      exit();
    }

    #ตรวจสอบ รอบเวลาที่ส่งเข้ามาเป็นตัวเลขหรือไม่
    if (!is_numeric($input->time)) {
      return json_encode(["message" => "time_invalid", "status_" => false]);
      exit();
    }

    #ตรวจสอบเบอร์มือถือ ว่า วันนี้ได้จองไปหรือยัง
    $sqlfindphone = " SELECT phone
                      FROM booking
                      WHERE DATE_FORMAT(book_date,'%Y-%m-%d') = DATE_FORMAT(:book_date,'%Y-%m-%d')
                            AND phone=:phone  
                    ";
    $resFindPhone = $this->fetchObject($sqlfindphone, [":book_date" => date('Y-m-d', strtotime($input->date)), ":phone" => $input->phone]);
    if ($resFindPhone) {
      return json_encode(["message" => "phone_booked", "status_" => false, "phone" => $resFindPhone->phone]);
      exit();
    }

    #ตรวจสอบ รอบเวลาที่ส่งเข้ามามีอยู่ในฐานข้อมูลหรือไม่
    $sqlCheckTime = "SELECT id FROM time_round WHERE id=:id AND type=:type LIMIT 1";
    $resCheckTime = $this->fetchObject($sqlCheckTime, [":id" => $input->time, ":type" => $type]);
    if (!$resCheckTime) {
      return json_encode(["message" => "time_fail", "status_" => false]);
      exit();
    }


    #ตรวจสอบ position ว่าเป็นตัวเลขทั้งหมดหรือไม่ ถ้าไม่ return error
    foreach ($tables as $key => $table) {
      if (!is_numeric($table)) {
        return json_encode(["message" => "position_number_invalid", "status_" => false]);
        exit();
      }
    }


    #ตรวจสอบว่ามีหมายเลข(ซุ้ม/โต๊ะ)นี้ในฐานข้อมูลหรือไม่
    $sqlCheckPosition = "SELECT id,position_num FROM position_table WHERE position_num IN(" . implode(',', $tables) . ") AND type=:type";
    $resCheckPosition = $this->fetchAll($sqlCheckPosition, [":type" => $type]);
    if (!$resCheckPosition) {
      return json_encode(["message" => "position_fail", "status_" => false]);
      exit();
    }

    #ถ้าค่าไม่เท่ากัน แสดงว่า มีตัวเลขตัวไหนตัวหนึ่งที่เป็นค่าไม่ตรงกันฐานข้อมูล
    if (count($resCheckPosition) != count($tables)) {
      return json_encode(["message" => "position_fail", "status_" => false]);
      exit();
    }


    #ตรวจสอบว่าหมายเลข(ซุ้ม/โต๊ะ)นี้มีคนจองหรือยัง
    $positionArr = [];
    foreach ($tables as $key => $table) {
      $sqlCheckPosition_ = "SELECT id FROM position_table WHERE position_num=:table AND type=:type LIMIT 1";
      $resCheckPosition_ = $this->fetchObject($sqlCheckPosition_, [":table" => $table, ":type" => $type]);
      array_push($positionArr, $resCheckPosition_->id);
      if ($this->isPositionBooking($type, date('Y-m-d', strtotime($input->date)), $input->time, $resCheckPosition_->id)) {
        return json_encode([
          "message" => "position_booking",
          "status_" => false,
          "detail" => 'ไม่สามารถจอง' . (($type == "arch") ? "ซุ้ม" : "โต๊ะ") . "ที่ " . $table . " นี้ได้เพราะคนอื่นจองไปก่อนแล้วครับ กรุณากด OK และทำการเลือกซุ้มอื่น",
          "type" => $type,
          "timeround" => $input->time,
          "date" => $input->date
        ]);
        exit();
      }
    }


    #ตรวจสอบว่า จำนวนคนเกินที่กำหนดไว้หรือไม่
    if ($type == "arch") {
      if ($input->people > (count($tables) * 15)) {
        return json_encode(["message" => "people_invalid", "status_" => false]);
        exit();
      }
    } else if ($type == "table") {
      if ($input->people > (count($tables) * 5)) {
        return json_encode(["message" => "people_invalid", "status_" => false]);
        exit();
      }
    }


    #ตรวจสอบว่าข้อมูลที่ส่งเข้ามา มี length ยาวเกินกว่าที่ DB กำหมดไว้หรือไม่
    if( 
      strlen($input->line) > 50 || 
      strlen($input->name) > 255 || 
      strlen($input->people) > 3 || 
      strlen($input->phone) > 20 || 
      strlen($input->province) > 2 || 
      strlen($input->time) > 2
    ){
      return json_encode(["message" => "data_length_valid", "status_" => false]);
      exit();
    }


    #ค้นหา booking_id_name
    #จัดเลขเรียง
    $findBookingIdName = $this->fetchObject("SELECT book_id_name FROM booking WHERE type=:type ORDER BY id DESC", [":type" => $type]);
    $bookingIdName = "";
    if (!$findBookingIdName) {
      #ถ้าไม่มี
      $bookingIdName = ($type == "arch") ? "A00000001" : "T00000001";
    } else {
      if ($type == "arch") {
        $tmp = ltrim($findBookingIdName->book_id_name, "A");
        $bookingIdName = "A" . str_pad(($tmp + 1), 8, "0", STR_PAD_LEFT);
      } else {
        $tmp = ltrim($findBookingIdName->book_id_name, "T");
        $bookingIdName = "T" . str_pad(($tmp + 1), 8, "0", STR_PAD_LEFT);
      }
    }


    #บันทึกข้อมูล
    $bookingID = sha1(uniqid(rand(), true));

    foreach ($positionArr as $key => $position_id) {
      $sql = "INSERT INTO booking(book_id,book_id_name,book_date,time_round_id,position_id,name,people_qty,phone,line_id,province_id,type,status,date_create,date_update)
              VALUES (:book_id,:book_id_name,:book_date,:time_round_id,:position_id,:name,:people_qty,:phone,:line_id,:province_id,:type,'pending',NOW(),NOW())";
      $values = [
        ":book_id"    => $bookingID,
        ":book_id_name"    => $bookingIdName,
        ":book_date"  => date('Y-m-d', strtotime($input->date)),
        ":time_round_id" => $input->time,
        ":position_id" => $position_id,
        ":name" => $input->name,
        ":people_qty" => $input->people,
        ":phone" => $input->phone,
        ":line_id" => $input->line,
        ":province_id" => $input->province,
        ":type" => $type,
      ];
      $resInsert = $this->insertValue($sql, $values);
    }
    // if($resInsert['message'] == "OK"){
    if (true) {
      #ดึงค่า url ของ cate ยืนยันการจอง
      $tranfer = $App->getCategoryFieldByCateID(4, "url");

      return json_encode(["message" => "success", "status_" => true, "url" => SITE_URL . $tranfer->url]);
    } else {
      return json_encode(["message" => "fail", "status_" => false]);
    }
  }


  /**
   * ดึง list ที่เราจอง
   * @param mixed $phone เบอร์มือถือของคนที่จอง
   * @return html
   */
  public function getBookingWithPhone($phone)
  {

    $sql = "SELECT bk.id,bk.name,bk.book_id,bk.book_id_name ,bk.book_date, bk.status,bk.phone,bk.line_id,bk.people_qty,bk.type
                    ,tr.round , pt.position_num , pv.province_name , bp.status as bp_status
            FROM booking as bk
            INNER JOIN time_round as tr ON bk.time_round_id = tr.id
            INNER JOIN position_table as pt ON bk.position_id = pt.id
            INNER JOIN province as pv ON bk.province_id = pv.id
            LEFT JOIN booking_payment as bp ON bk.book_id = bp.book_id
            WHERE bk.phone=:phone
                  AND DATE_FORMAT(book_date,'%Y-%m-%d') > DATE_FORMAT(NOW(),'%Y-%m-%d')
                  
            GROUP BY book_id
            ORDER BY bk.date_create ASC
            "; //--AND bp.status <> 'success'
    $response = $this->fetchAll($sql, [":phone" => $phone]);

    if (empty($response)) {
      // ไม่มีข้อมูล
      $html = "ไม่มีข้อมูล";
      $message = "nodata";
      $status_ = false;
    } else {
      $message = "success";
      $status_ = true;

      $html = "";
      foreach ($response as $key => $res) {

        // หาซุ้ม หรือ โต๊ะที่จอง
        $sql = "SELECT pt.position_num
            FROM booking as bk
            INNER JOIN position_table as pt ON bk.position_id = pt.id
            WHERE bk.phone=:phone AND bk.book_id =:book_id
            ";
        $positionNum = $this->fetchAll($sql, [":phone" => $phone, ":book_id" => $res['book_id']]);
        $positionNumStr = "";
        foreach ($positionNum as $k => $r) {
          $positionNumStr .= $r['position_num'] . ",";
        }

        $html .= "" . Component::list_my_booking(["res" => $res, "price" => number_format(count($positionNum) * 200), "position" => rtrim($positionNumStr, ",")]) . "";
      }
    }

    echo json_encode([
      "html" => $html,
      "message" => $message,
      "status_" => $status_
    ]);
  }

  /**
   * ดึง list ที่เราจอง สำหรับ หน้า ค้นหาการจอง
   * @param mixed $phone เบอร์มือถือของคนที่จอง
   * @return html
   */
  public function getBookingWithPhone_($phone)
  {

    $sql = "SELECT bk.id,bk.name,bk.book_id,bk.book_id_name ,bk.book_date, bk.status,bk.phone,bk.line_id,bk.people_qty,bk.type
                    ,tr.round , pt.position_num , pv.province_name , bp.status as bp_status
            FROM booking as bk
            INNER JOIN time_round as tr ON bk.time_round_id = tr.id
            INNER JOIN position_table as pt ON bk.position_id = pt.id
            INNER JOIN province as pv ON bk.province_id = pv.id
            LEFT JOIN booking_payment as bp ON bk.book_id = bp.book_id
            WHERE bk.phone=:phone
            GROUP BY book_id
            ORDER BY bk.date_create ASC
            "; //--AND bp.status <> 'success'
    $response = $this->fetchAll($sql, [":phone" => $phone]);


    if (empty($response)) {
      // ไม่มีข้อมูล
      $html = "ไม่มีข้อมูล";
      $message = "nodata";
      $status_ = false;
    } else {
      $message = "success";
      $status_ = true;

      $html = "";
      foreach ($response as $key => $res) {

        // หาซุ้ม หรือ โต๊ะที่จอง
        $sql = "SELECT pt.position_num
            FROM booking as bk
            INNER JOIN position_table as pt ON bk.position_id = pt.id
            WHERE bk.phone=:phone AND bk.book_id =:book_id
            ";
        $positionNum = $this->fetchAll($sql, [":phone" => $phone, ":book_id" => $res['book_id']]);
        $positionNumStr = "";
        foreach ($positionNum as $k => $r) {
          $positionNumStr .= $r['position_num'] . ",";
        }

        $html .= "" . Component::list_my_booking_search(["res" => $res, "price" => number_format(count($positionNum) * 200), "position" => rtrim($positionNumStr, ",")]) . "";
      }
    }

    echo json_encode([
      "html" => $html,
      "message" => $message,
      "status_" => $status_
    ]);
  }


  public function getBookingURLWithPhone($phone)
  {
    global $App;
    $sql = "SELECT bk.name,bk.book_id,bk.book_id_name ,bk.book_date, bk.status,bk.phone,bk.line_id,bk.people_qty,bk.type
                    ,tr.round , pt.position_num , pv.province_name , bp.status as bp_status
            FROM booking as bk
            INNER JOIN time_round as tr ON bk.time_round_id = tr.id
            INNER JOIN position_table as pt ON bk.position_id = pt.id
            INNER JOIN province as pv ON bk.province_id = pv.id
            LEFT JOIN booking_payment as bp ON bk.book_id = bp.book_id
            WHERE bk.phone=:phone
                  AND DATE_FORMAT(book_date,'%Y-%m-%d') > DATE_FORMAT(NOW(),'%Y-%m-%d')   
                  
            GROUP BY book_id
            "; 
            //--AND bp.status <> 'success'
    $response = $this->fetchAll($sql, [":phone" => $phone]);

    if ($response) {
      $cateSearchQ = $App->getCategoryFieldByCateID(11, "url");
      $_SESSION['phone'] = $phone;
      echo json_encode([
        "message" => "success",
        "status_" => true,
        "url_redirect" => SITE_URL . $cateSearchQ->url,
      ]);
    } else {
      echo json_encode([
        "message" => "fail",
        "status_" => false
      ]);
    }
  }

  /**
   * ฟังก์ชั่นแจ้งชำระเงิน
   * @param mixed $input ข้อมูลที่ user ส่งเข้ามา
   * @return json
   */
  public function savePayment($inputs)
  {

    #ตรวจสอบค่าว่าง
    foreach ($inputs as $input) {
      if (empty($input)) {
        return json_encode([
          "message" => "data_not_null", "status_" => false
        ]);
        exit();
      }
    }

    #ตรวจสอบ id ธนาคาร เป็น number หรือไม่ ถ้าไม่เป็น return error
    if (!is_numeric($inputs->bank)) {
      return json_encode([
        "message" => "data_bank_invalid", "status_" => false
      ]);
      exit();
    }

    #ตรวจสอบ id ธนาคาร
    $sqlCheckBank = "SELECT id FROM bank_info WHERE id=:id LIMIT 1";
    $resCheckBank = $this->fetchObject($sqlCheckBank, [":id" => $inputs->bank]);
    if (!$resCheckBank) {
      return json_encode([
        "message" => "bank_fail", "status_" => false
      ]);
      exit();
    }

    #ตรวจสอบ ยอดชำระ เป็น number หรือไม่ ถ้าไม่เป็น return error
    if (!is_numeric($inputs->price)) {
      return json_encode([
        "message" => "data_price_invalid", "status_" => false
      ]);
      exit();
    }

    #ตรวจสอบวันที่
    if (!checkdate(date('m', strtotime($inputs->date)), date('d', strtotime($inputs->date)), date('Y', strtotime($inputs->date)))) {
      return json_encode([
        "message" => "data_date_invalid", "status_" => false
      ]);exit();
    }

    #ตรวจสอบว่า เวลาผ่านไปนานเกิน 6 ชม หรือยัง หลังจากที่กดจอง
    if($this->checkTimeBooking6Hour($inputs->book_id)){
      return json_encode([
        "message" => "date_over6hour", "status_" => false
      ]);exit();
    }

    #ตรวจสอบ length ของ string
    if (
      strlen($inputs->book_id) > 40 ||
      strlen($inputs->bank_id) > 2 ||
      strlen($inputs->bank_name) > 255 ||
      strlen($inputs->price) > 11 ||
      strlen($inputs->img) > 255 ||
      strlen($inputs->position) > 100
    ) {
      return json_encode([
        "message" => "data_length_invalid", "status_" => false
      ]);
      exit();
    }

    #ตรวจสอบดูว่าทำการแจ้งชำระหรือยัง
    $sqlfindPayment = "SELECT book_id,id FROM booking_payment WHERE book_id=:book_id LIMIT 1";
    $resfindPayment = $this->fetchObject($sqlfindPayment, [":book_id" => $inputs->book_id]);

    // อัพเดท booking.status ให้เป็น  pending_payment เพื่อบ่งบอกว่า ได้ทำการอัพ slip payment
    $this->updateValue("UPDATE booking SET status='pending_payment',date_update=NOW() WHERE book_id=:book_id",[":book_id"=>$inputs->book_id]);

    if (!$resfindPayment) {
      // ถ้ายังไม่มีข้อมูล ให้ insert
      $sql = "INSERT INTO booking_payment(book_id,bank_id,bank_name,price,date,img,status,position,date_create,date_update)
                        VALUES (:book_id,:bank_id,:bank_name,:price,:date,:img,'pending',:position,NOW(),NOW())";
      $values = [
        ":book_id" => $inputs->book_id,
        ":bank_id" => $inputs->bank,
        ":bank_name" => $inputs->name,
        ":price" => $inputs->price,
        ":date" => date('Y-m-d H:i:s', strtotime($inputs->date)),
        ":img" => $inputs->image,
        ":position" => $inputs->position,
      ];
      $res = $this->insertValue($sql, $values);
      if ($res['message'] == "OK") {
        return json_encode([
          "message" => "success", "status_" => true
        ]);
        exit();
      } else {
        return json_encode([
          "message" => "fail", "status_" => false
        ]);
        exit();
      }
    } else {
      // ถ้ามีข้อมูล ให้ update
      $sql =  " UPDATE booking_payment SET bank_id=:bank_id,bank_name=:bank_name,price=:price,date=:date,img=:img,status='pending',
                      position=:position,date_update=NOW()
                WHERE book_id=:book_id
              ";
      $values = [
        ":book_id" => $inputs->book_id,
        ":bank_id" => $inputs->bank,
        ":bank_name" => $inputs->name,
        ":price" => $inputs->price,
        ":date" => date('Y-m-d H:i:s', strtotime($inputs->date)),
        ":img" => $inputs->image,
        ":position" => $inputs->position,
      ];
      $res = $this->updateValue($sql, $values);
      if ($res['message'] == "OK") {
        return json_encode([
          "message" => "success", "status_" => true
        ]);
        exit();
      } else {
        return json_encode([
          "message" => "fail", "status_" => false
        ]);
        exit();
      }
    }
  }

  /**
   * ตรวจสอบว่า booking ที่ทำรายการนั้น เกิน 6 ชั่วโมงไปแล้วหรือยัง
   * ถ้าเกินแล้วจะ return true
   * @param mixed $bookID เลข  booking id
   * @return (true/false)
   */
  public function checkTimeBooking6Hour($bookID){
    $sqlCheckTimeBooking = "SELECT date_create FROM booking WHERE book_id = :book_id AND DATE_ADD(date_create,INTERVAL 6 HOUR) < NOW()";
    $resCheckTimeBooking = $this->fetchObject($sqlCheckTimeBooking,[":book_id" => $bookID]);
    return ($resCheckTimeBooking)?true:false;
  }


  /**
   * ลบ booking
   */
  public function deleteBookingWithBookID($bookingID)
  {

    #ตรวจสอบค่าว่าง
    if(empty($bookingID)){
      return ["message" => "data_not_null","status_" => false];exit();
    }

    #ตรวจสอบว่ามี booking_id นี้ในระบบหรือไม่ (booking)
    $sqlFindBooking = "SELECT id FROM booking WHERE book_id=:book_id LIMIT 1";
    $resFindBooking = $this->fetchObject($sqlFindBooking,[":book_id" => $bookingID]);
    if(!$resFindBooking){
      return ["message" => "booking_is_null","status_" => false];exit();
    }
    
    #ตรวจสอบว่าอยู่ในช่วงเวลาที่ยังให้ลบได้หรือไม่ (book_date)
    $sqlFindBooking = " SELECT id 
                        FROM booking 
                        WHERE book_id=:book_id 
                              AND DATE_FORMAT(book_date,'%Y-%m-%d') < DATE_FORMAT(NOW(),'%Y-%m-%d')
                        LIMIT 1";
    $resFindBooking = $this->fetchObject($sqlFindBooking,[":book_id" => $bookingID]);
    if($resFindBooking){
      return ["message" => "booking_date_invalid","status_" => false];exit();
    }
    
    #กด Confirm ไปหรือยัง (INNER JOIN booking_payment)
    $sqlFindBooking = " SELECT b.id 
                        FROM booking as b
                        INNER JOIN booking_payment as bp ON b.book_id = bp.book_id
                        WHERE b.book_id=:book_id 
                        LIMIT 1";
    $resFindBooking = $this->fetchObject($sqlFindBooking,[":book_id" => $bookingID]);
    if($resFindBooking){
      return ["message" => "booking_is_confirm","status_" => false];exit();
    }

    #delete
    $resDelete = $this->deletePrepare("booking","book_id=:book_id",[":book_id" => $bookingID]);
    if($resDelete['message']=="OK"){
      return ["message" => "success","status_" => true];exit();
    }else{
      return ["message" => "fail","status_" => false];exit();
    }
  }


  /**
   * ตรวจสอบว่า ได้ทำการ register line แล้วหรือยัง
   */
  public function checkRegisterLine($phone){
    $sql = "SELECT phone FROM _line_register WHERE phone=:phone LIMIT 1";
    $res = $this->fetchObject($sql,[":phone" => $phone]);
    if(!$res){
      return json_encode([
        "message" => "fail", "status" => false
      ]);
    }else{
      return json_encode([
        "message" => "success", "status" => true
      ]);
    }
  }
}
