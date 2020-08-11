<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);


use \Firebase\JWT\JWT;

require_once DOC_ROOT . '/classes/FrontEnd.php';
require_once DOC_ROOT . '/classes/WebSecurity.class.php';
// require_once './../src/autoload.php';      // ReCaptcha


class Member extends FrontEnd
{
  public function __construct()
  {
    parent::__construct();
    //ตรวจสอบว่ามีฟังก์ชั่นในคลาสไหมถ้ามีให้เรียกใช้งาน
  }

  #login 
  public function login($username, $password)
  {
    $username = filter_var($username, FILTER_SANITIZE_MAGIC_QUOTES);
    $password = filter_var($password, FILTER_SANITIZE_MAGIC_QUOTES);
    
    #update ถ้าการblock หมดอายุ
    $this->updateUnBlockedIP($_SERVER['HTTP_X_FORWARDED_FOR']);
    #ตรวจสอบว่า ip address โดนบล็อค หรือไม่
    if($this->checkBlockIP($_SERVER['HTTP_X_FORWARDED_FOR'])){
      return ["status" => 400,"status_login" => false,"message" => "ip_has_blocked"];                             
    }

    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
      #input => username
      $sqlMem = "SELECT m.* , p.id as province_id , p.province_name  
                  FROM members as m 
                  LEFT JOIN province as p ON m.province_id = p.id
                  WHERE m.username=:username";
      $resMem = $this->fetchObject($sqlMem, [":username" => $username]);
    }else{
      #input => email
      $sqlMem = "SELECT m.* , p.id as province_id , p.province_name  
                  FROM members as m 
                  LEFT JOIN province as p ON m.province_id = p.id
                  WHERE m.email=:email";
      $resMem = $this->fetchObject($sqlMem, [":email" => $username]);
    }

    

    if ($resMem) {
      if (password_verify($password, $resMem->password)) {

        #set members_login count = 0
        $this->updateMemberLogin("set",$_SERVER['HTTP_X_FORWARDED_FOR'],0);
        
        #set session member
        $this->setMemberSession($resMem);

        return [
          "status" => 200,
          "status_login" => true,
          "message" => "success"
        ];
      } else {
        // ถ้ารหัสผ่านไม่ตรง
        $this->checkLoginBruteForce($_SERVER['HTTP_X_FORWARDED_FOR']);
        return [
          "status" => 401,
          "status_login" => false,
          "message" => "password_invalid"
        ];
      }
    } else {
      // ถ้าไม่มีข้อมูล
      $this->checkLoginBruteForce($_SERVER['HTTP_X_FORWARDED_FOR']);
      return [
        "status" => 401,
        "status_login" => false,
        "message" => "email_notmatch"
      ];
    }
  } //function login

  #ฟังก์ชั่นที่เอาไว้เช็คว่า user มีการ loginผิดซ้ำๆหรือไม่
  #ถ้าlogin ผิดซ้ำๆเกิน 5 ครั้งใน ip เดียวกัน จะ Block IP
  public function checkLoginBruteForce($ip){
    $sqlfindIP = "SELECT id,date_create FROM members_login WHERE ip=:ip";
    $resFindIP = $this->fetchObject($sqlfindIP,[":ip" => $ip]);
    if(!empty($resFindIP)){
      // ถ้ามีข้อมูล ให้ update
      $sqlCheckTime = "SELECT (CASE WHEN NOW() < ADDDATE(date_create,INTERVAL 1 MINUTE) THEN 'in' ELSE 'out' END) as checktime 
              FROM members_login 
              WHERE ip=:ip";
      $resCheckTime = $this->fetchObject($sqlCheckTime,[":ip" => $ip]);
      if($resCheckTime->checktime == "in"){
        #ถ้าอยู่ในช่วงเวลา
        $this->updateMemberLogin("add",$ip);
      }else{
        #ถ้าอยู่นอกช่วงเวลา
        $this->updateMemberLogin("set",$ip,1);
      }
    }else{
      //ถ้าไม่มีข้อมูล ให้ insert
      $sqlInsert = "INSERT INTO members_login(ip,count,date_create,date_expire)
                    VALUES (:ip,1,NOW(),NOW())";
      $this->insertValue($sqlInsert,[":ip" => $ip]);
    }
  }

  #ฟังก์ชั่นที่เอาไว้ update count (ถ้าloginผ่าน หรือพึ่งเข้าครั้งแรกและกรอกรหัสผ่านผิด จะset count = 0) 
  #ถ้าlogin false จะ set count + 1
  #$action คือตัวแปรที่เอาไว้ตรวจสอบว่า ค่าที่เข้ามานั้น เป็น set หรือ add
  public function updateMemberLogin($action,$ip,$count = null){
    if($action == "set"){
      $sql = "UPDATE members_login SET count=:count,date_create=NOW() WHERE ip=:ip";
      $res = $this->updateValue($sql,[":count" => $count , ":ip" => $ip]);
    }else if($action == "add"){
      $sql = "UPDATE members_login SET count=count+1,date_create=NOW() WHERE ip=:ip";
      $res = $this->updateValue($sql,[":ip" => $ip]);

      // ถ้า count > 5 ให้ update date_expire
      $this->updateValue("UPDATE members_login SET date_expire=ADDDATE(NOW(),INTERVAL 1 HOUR),date_create=NOW() WHERE ip=:ip AND count > 5",[":ip" => $ip]);
    }
    return (!empty($res))?true:false;
  }

  #ตรวจสอบว่า ip โดนบล็อคหรือไม่ (return true = block , false = not block)
  public function checkBlockIP($ip){
    $sql = "SELECT id FROM members_login WHERE ip=:ip AND count > 5 AND date_expire > NOW()";
    $res = $this->fetchObject($sql,[":ip" => $ip]);
    return (!empty($res))?true:false;
  }

  #อัพเดทข้อมูล ถ้าหมดเวลาการโดนบล็อค
  public function updateUnBlockedIP($ip){
    $sql = "UPDATE members_login SET count=0,date_create=NOW() WHERE count > 5 AND date_expire < NOW() AND ip=:ip";
    $this->updateValue($sql,[":ip" => $ip]);
  }

  #สมัครสามาชิก
  public function register($input)
  {
    // ตรวจสอบค่าว่าง
    if(empty($input->name) || empty($input->username) || empty($input->email) || empty($input->password) || empty($input->passCF) || empty($input->phone) || empty($input->line_id)){
      return ["status" => 400,"status_register" => false,"message" => "data not null"];
    }
    // ตรวจสอบ username Duplicate
    if($this->checkUsernameDuplicate($input->username)){
      return ["status" => 400,"status_register" => false,"message" => "username duplicate"];
    }
    // ตรวจสอบ email valid
    if(!$this->checkEmailValidate($input->email)){
      return ["status" => 400,"status_register" => false,"message" => "email invalid"];
    }
    // ตรวจสอบ email Duplication
    if($this->checkEmailDuplicate($input->email)){
      return ["status" => 400,"status_register" => false,"message" => "email duplicate"];
    }

    //ตรวจสอบรหัสผ่าน อย่างน้องต้องมี8ตัว
    if(strlen($input->password) < 8){
      return ["status" => 400,"status_register" => false,"message" => "password length lessthan 8"];
    }

    // ตรวจสอบ password equals password Confirm
    if($input->password != $input->passCF){
      return ["status" => 400,"status_register" => false,"message" => "password not equals"];
    }

    $memid = sha1(uniqid(rand(),TRUE));
    $sqlRegister = "INSERT INTO members(mem_id,name,phone,email,password,date_create,date_update,username,status,line_id) 
                    VALUES (:mem_id,:name,:phone,:email,:password,:date_create,:date_update,:username,:status,:line_id)";
    $values = [
      ":mem_id" => $memid,
      ":name" => htmlspecialchars($input->name),
      ":phone" => htmlspecialchars($input->phone),
      ":email" => $input->email,
      ":password" => password_hash($input->password,PASSWORD_BCRYPT),
      ":date_create" => date('Y-m-d H:i:s'),
      ":date_update" => date('Y-m-d H:i:s'),
      ":username" => htmlspecialchars($input->username),
      ":status" => 'active',
      ":line_id" => htmlspecialchars($input->line_id),
    ];
    $res = $this->insertValue($sqlRegister,$values);
    if($res['message'] == "OK"){
      return ["status" => 200,"status_register" => true,"message" => "success"];
    }else{
      return ["status" => 400,"status_register" => false,"message" => "error"];
    }
    return $res;
  }

  // ตั้งค่า SESSION['member']
  private function setMemberSession($member)
  {
    $_SESSION['member']['is_login']             = true;
    $_SESSION['member']['id']                   = $member->id;
    $_SESSION['member']['member_id']            = $member->mem_id;
    $_SESSION['member']['username']             = $member->username;
    $_SESSION['member']['address']              = $member->address;
    $_SESSION['member']['email']                = $member->email;
    $_SESSION['member']['name']                 = $member->name;
    $_SESSION['member']['image_profile']        = $member->	image_profile;
    $_SESSION['member']['image_cover']          = $member->image_cover;
    $_SESSION['member']['image_confirm']        = $member->image_confirm;
    $_SESSION['member']['clup_name']            = $member->clup_name;
    $_SESSION['member']['aboutus_detail']       = $member->aboutus_detail;
    $_SESSION['member']['province']             = $member->province_name;
    $_SESSION['member']['province_id']          = $member->province_id;
    $_SESSION['member']['phone']                = $member->phone;
    $_SESSION['member']['line_id']              = $member->line_id;
    $_SESSION['member']['fb_link']              = $member->fb_link;
    $_SESSION['member']['confirm_regis']        = $member->confirm_regis; //เวลา
    $_SESSION['member']['confirm_regis_status'] = $member->confirm_regis_status; //status
    $_SESSION['member']['premium_status']       = $member->premium_status;
    $_SESSION['member']['premium_type']         = $member->premium_type;
  }// function setMemberSession

  // ลบ SESSION['member']
  public function clearMemberSession(){
    unset($_SESSION['member']);
  }

  #เช็คว่า username ซ้ำหรือไม่
  #ถ้าซ้ำ return true
  public function checkUsernameDuplicate($username){
    $sql = "SELECT username FROM members WHERE username = :username LIMIT 1";
    $res = $this->fetchObject($sql,[":username" => $username]);
    return !$res?false:true;
  }

  #เช็คว่า username ซ้ำหรือไม่
  #ถ้าซ้ำ return true
  public function checkUsernameDuplicateWithMemberID($username,$id){
    $sql = "SELECT username FROM members WHERE username = :username AND id <> :id LIMIT 1";
    $res = $this->fetchObject($sql,[":username" => $username,":id" => $id]);
    return !$res?false:true;
  }

  #ตรวจสอบว่า email ถูกต้องหรือไม่
  public function checkEmailValidate($email){
    return filter_var($email,FILTER_VALIDATE_EMAIL);
  }

  #ตรวจสอบว่า email ซ้ำหรือไม่
  public function checkEmailDuplicate($email){
    $sql = "SELECT email FROM members WHERE email = :email LIMIT 1";
    $res = $this->fetchObject($sql,[":email" => $email]);
    return !$res?false:true;
  }

  #แก้ไขรหัสผ่าน
  public function editPassword($mid,$input){
    global $App;
    //ตรวจสอบว่าค่าว่างหรือไม่
    if(empty($input->passwordCurrent) || empty($input->passwordNew) || empty($input->passwordConf)){
      return ["status" => 400 , "status_edit" => false , "message" => "data not null"];
    }
    // ตรวจสอบว่า password new มีเกิน8ตัวหรือไม่
    if(strlen($input->passwordNew) < 8){
      return ["status" => 400 , "status_edit" => false , "message" => "password_length_lessthan_8"];
    }
    // ตรวจสอบว่ารหัสตรงกันหรือไม่
    if($input->passwordNew != $input->passwordConf){
      return ["status" => 400 , "status_edit" => false , "message" => "password not equals"];
    }
    // ตรวจสอบว่า password current ตรงกันใน db หรือไม่
    $sqlpassword = "SELECT password FROM members WHERE id=:id LIMIT 1";
    $resPassword = $this->fetchObject($sqlpassword,[":id" => $mid]);
    if( password_verify($input->passwordCurrent,$resPassword->password) ){
      //update password
      $sqlUpdate = "UPDATE members SET password=:password,date_update=:date_update WHERE id=:id";
      $resUpdate = $this->updateValue($sqlUpdate,[
        ":password" => password_hash($input->passwordNew,PASSWORD_BCRYPT),
        ":id" => $mid,
        ":date_update" => date('Y-m-d H:i:s')
      ]);
      if($resUpdate['message'] == "OK"){
        return ["status" => 200 , "status_edit" => true , "message" => "success"];
      }else{
        return ["status" => 400 , "status_edit" => false , "message" => "fail"];
      }
    }else{
      return ["status" => 400 , "status_edit" => false , "message" => "password_notequals"];
    }
  }

  #แก้ไขข้อมูลส่วนตัว
  public function editInformation($mid,$input){
    global $App;

    //ตรวจสอบว่าค่าว่างหรือไม่
    if(empty($input->name) || empty($input->clup) || empty($input->username) || empty($input->phone) || empty($input->lineid) || empty($input->address) || empty($input->aboutus) || empty($input->province)){
      return ["status" => 400 , "status_edit" => false , "message" => "data not null"];
    }
    
    // ตรวจสอบว่า มี usernameนี้ในระบบหรือยัง
    $checkUsernameDuplicate = $this->checkUsernameDuplicateWithMemberID($input->username,$mid);
    if($checkUsernameDuplicate){
      return ["status" => 400 , "status_edit" => false , "message" => "username duplicate"];
    }

    //update information
    $sqlUpdate = "UPDATE members 
                  SET name=:name ,clup_name=:clup, username=:username , province_id=:province_id,phone=:phone ,fb_link=:fb_link, line_id=:line_id , address=:address , aboutus_detail=:aboutus , date_update=:date_update
                  WHERE id=:id";
    $resUpdate = $this->updateValue($sqlUpdate,[
      ":name" => htmlspecialchars($input->name),
      ":clup" => htmlspecialchars($input->clup),
      ":username" => htmlspecialchars($input->username),
      ":province_id" => htmlspecialchars($input->province),
      ":phone" => htmlspecialchars($input->phone),
      ":line_id" => htmlspecialchars($input->lineid),
      ":fb_link" => htmlspecialchars($input->facebook),
      ":address" => htmlspecialchars($input->address),
      ":aboutus" => htmlspecialchars($input->aboutus),
      ":date_update" => date('Y-m-d H:i:s'),
      ":id" => $mid
    ]);
    if($resUpdate['message'] == "OK"){

      $resMyMember = $this->fetchObject(
        "SELECT m.* , p.id as province_id , p.province_name  
        FROM members as m 
        INNER JOIN province as p ON m.province_id = p.id
        WHERE m.id=:id
        ",
        [":id" => $mid]);
      $this->setMemberSession($resMyMember);

      return ["status" => 200 , "status_edit" => true , "message" => "success"];
    }else{
      return ["status" => 400 , "status_edit" => false , "message" => "fail"];
    }
  }

  #update image_profile
  public function editImageProfile($mid,$img,$token){
    $sql = "UPDATE members SET image_profile=:img,date_update=NOW() WHERE id=:id";
    $res = $this->updateValue($sql,[":img" => $img,":id" => $mid]);

    // delete upload_tmp by token
    $this->deletePrepare('upload_tmp','token=:token',[":token" => $token]);

    $_SESSION['member']['image_profile'] = $img;
    return $res;
  }

  #update image_cover
  public function editImageCover($mid,$img,$token){
    $sql = "UPDATE members SET image_cover=:img,date_update=NOW() WHERE id=:id";
    $res = $this->updateValue($sql,[":img" => $img,":id" => $mid]);

    // delete upload_tmp by token
    $this->deletePrepare('upload_tmp','token=:token',[":token" => $token]);

    $_SESSION['member']['image_cover'] = $img;
    return $res;
  }


  #update ยืนยันตัวตน
  public function updateConfirmInformation($mid,$img,$token){
    $sql = "UPDATE members SET image_confirm=:img,date_update=NOW() WHERE id=:id";
    $res = $this->updateValue($sql,[":img" => $img,":id" => $mid]);

    // delete upload_tmp by token
    $this->deletePrepare('upload_tmp','token=:token',[":token" => $token]);

    if($res['message'] == "OK"){
      $_SESSION['member']['image_confirm'] = $img;
      return ['status' => 200,'status_upload' => true,'message'=>'success' ];
    }else{
      return ['status' => 400,'status_upload' => false,'message'=>'fail' ];
    }
  }


  #สมัครสมาชิก premium
  public function RegisterPremium($mid,$img,$token){
    // premium_type หลังบ้านจะเป็นคนปรับให้ โดยดูจาก หลักฐานที่โอน
    // premium_upload idmember_id	image	status	date_create	date_update

    $sql = "INSERT INTO premium_upload(member_id,image,status,date_create,date_update)
            VALUES (:member_id,:image,:status,:date_create,:date_update)";
    $values = [
      ":member_id" => $mid,
      ":image" => $img,
      ":status" => 'pending',
      ":date_create" => date('Y-m-d H:i:s'),
      ":date_update" => date('Y-m-d H:i:s'),
    ];
    $res = $this->insertValue($sql,$values);

    // delete upload_tmp by token
    $this->deletePrepare('upload_tmp','token=:token',[":token" => $token]);

    if($res['message'] == "OK"){
      $_SESSION['member']['image_confirm'] = $img;
      return ['status' => 200,'status_upload' => true,'message'=>'success' ];
    }else{
      return ['status' => 400,'status_upload' => false,'message'=>'fail' ];
    }
  }
}
