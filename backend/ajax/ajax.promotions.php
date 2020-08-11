<?php
// use function GuzzleHttp\json_encode; 
session_start();
error_reporting(1);
ini_set('display_errors', 1);
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.upload.php';
require_once dirname(__DIR__) . '/classes/class.uploadimage.php';   
require_once dirname(__DIR__) . '/classes/class.promotions.php';

getData::init(); 
$dbcon = new DBconnect();
$mydata = new promotions();
$myupload = new uploadimage();

if(isset($_REQUEST['action'])){ 
	switch($_REQUEST['action']){ 
        case'get_promotion':
            $requestData = $_REQUEST;
            $columns = array(
                1 => 'pro_code',
                3 => 'discount',
                5 => 'pro_date_available',
                6 => 'pro_date_expire'
            );
            $roomSql = "SELECT * FROM room_product";
            $myroom = $dbcon->fetchAll($roomSql,[]);
            foreach($myroom as $key =>$val){
                $setRoom[$val['room_id']]['name'] = $val['room_type_name'];
            }
            $sql = "SELECT * FROM reserve_promotion "; 
            if (!empty($requestData['search']['value'])) {
                $sql .= " WHERE pro_code LIKE '%" . $requestData['search']['value'] . "%' ";
            } 
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;
            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
            $result = $dbcon->query($sql);
            $output = array();
            if ($result) {
                $ii = 0;
                foreach ($result as $value) { 
                    $ii++;
                    $display =  ($value['pro_status'] == "banned")?'<div style="text-align:center;color:red; font-weight:bold;">BANNED</div>':'<div class="col-md-12 btnPin"> 
                                    <div class="toggle-switch inTables '.(($value['pro_status'] == 'no')?"":"ts-active").'" style="margin: auto">
                                        <span class="switch" data-id="'.$value['pro_id'].'"></span>
                                    </div>
                                    <input type="hidden" class="form-control" id="reviews_status" value="'.(($value['pro_status'] == 'no')?"no":"yes").'">
                                </div>';
                    $img = ($value['thumbnail'] == "")?'ไม่มี': '<a target="__blank" class="fancybox reviewImgName" data-id="'.$value['pro_id'].'" href='.ROOT_URL.$value['thumbnail']. '> <img style="width:50px;"src="'.ROOT_URL.$value['thumbnail'].'"></a>'; 
                    $nestedData = array();
                    $nestedData[] = (($value['pro_status'] == "publish")?"<div class='pro-status'>เปิดการใช้งาน</div>":"<div class='pro-status off'>ปิดการใช้งาน</div>");
                    $nestedData[] = $value['pro_code'];
                    $nestedData[] = $value['pro_name'];
                    $nestedData[] = $value['discount'] . ' บาท';
                    $nestedData[] = $value['quota']. " ห้อง";
                    $nestedData[] = date("d-m-Y H:i",strtotime( $value['pro_date_available']));
                    $nestedData[] = date("d-m-Y H:i",strtotime( $value['pro_date_expire']));
                    $nestedData[] = $setRoom[$value['pro_roomtype_id']]['name'];
                    $nestedData[] = '<p class="btn-center btn-flex">
                                        <a class="btn kt:btn-warning" style="color:white;" onclick="editCategory(event,' . $value['pro_id'] . ')"><i class="fas fa-edit"></i> แก้ไข</a>
                                        <a class="btn kt:btn-danger del_catenumb" style="color:white;" data-id="'.$value['pro_id'].'" data-name="'.$value['pro_name'].'" onclick="delReviews(event,' . $value['pro_id'] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i> ลบ</a>
                                     </p>';
                    
                    $output[] = $nestedData;
                }
            }

            $json_data = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $output,
            );
            echo json_encode($json_data);

        break;
        case'prepare_edit':

            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM reserve_promotion WHERE pro_id = :id";
            $result = $dbcon->fetchAll($sql,[":id"=>$id]);
            $result = $result[0];
            if($result['thumbnail'] == ""){ 
                $camera_i = '<i class="fa fa-camera"></i>';
            }else{
                $thumbnail = '<div class="col-img-preview" id="col_img_preview_1" data-id="1"><img class="preview-img" id="preview_img_1" src="'.ROOT_URL.$result['thumbnail'].'"></div>';
            }

            $roomSql = "SELECT * FROM room_product WHERE room_status = 'active' ";
            $room = $dbcon->fetchAll($roomSql,[]);
            if(!empty($room)){
                $options='';
                foreach($room as $key =>$val){
                    $slc = ($val['room_id'] == $result['pro_roomtype_id'])?"SELECTED":"";
                    $options .= '<option '.$slc.' value="'.$val['room_id'].'">'.$val['room_type_name'].'</option>';
                }
            }
            $html = '<div class="cate-blog-icon">  
                  <div>
                    <label for=""> รูปภาพประกอบโปรโมชั่น <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
                    <div class="form-group form-add-images">
                        <div id="image-preview" style="margin:auto;">
                            <label for="image-upload" class="image-label"> '.$camera_i.' </label>
                            <div class="blog-preview-add">'.$thumbnail.'</div>
                            <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add"/>
                        </div>
                        <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" value="'.$result['thumbnail'].'" required>  
                        </div> 
                    </div>
                </div>
                <div class="title-numb">ชื่อโปรโมชั่น:</div>
                <input  class="swal2-input txt_title"  placeholder="เรื่อง" value="'.$result['pro_name'].'">
                <div class="title-numb">คำอธิบาย:</div>
                <textarea  class="swal2-input txt_desc" placeholder="คำอธิบาย" style="height:150px;">'.$result['pro_description'].'</textarea>
                <div class="title-numb">ราคาส่วนลด:</div>
                <input  class="swal2-input txt_discount" placeholder="ราคาส่วนลด" value="'.$result['discount'].'">
                <div class="title-numb">จำนวนจำกัด:</div>
                <input  class="swal2-input txt_quota" placeholder="จำนวนโควต้า" value="'.$result['quota'].'">
                <div class="title-numb">วันที่เริ่มโปรโมชั่น:</div>
                <input type="date" class="swal2-input txt_datestart" placeholder="วันที่เริ่ม" value="">
                <div class="title-numb">วันที่สิ้นสุด:</div>
                <input type="date" class="swal2-input txt_dateend" placeholder="วันที่สิ้นสุด" value="">
                <div class="title-numb">โปรโมชั่นสำหรับห้องพัก:</div>
                <select id="room_promotion" class="swal2-
                input">
                   '.$options.'
                </select>
                <div class="title-numb">สถานะการใช้งาน:</div>
                <select id="promotion" class="swal2-input">
                    <option '.(($result['pro_status'] =="publish")?"SELECTED":"").' value="publish">เปิดใช้งาน</option>
                    <option '.(($result['pro_status'] =="no")?"SELECTED":"").' value="no">ปิดใช้งาน</option>
                    <option '.(($result['pro_status'] =="banned")?"SELECTED":"").' value="banned">ห้ามใช้งาน</option>
                </select>
                 ';
            $result['html'] = $html; 
            $result['available'] = date("d-m-Y H:i",strtotime($result['pro_date_available']));
            $result['expire'] = date("d-m-Y H:i",strtotime($result['pro_date_expire']));
            echo json_encode($result);
 
        break;  
        case'uploadImage_promotion':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/'; 
            $thumbnail = $myupload->upload_image_thumb($new_folder);   
            echo json_encode($thumbnail);
        break;
        case'update_promotion':
            $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
            $desc = FILTER_VAR($_POST['desc'],FILTER_SANITIZE_MAGIC_QUOTES);
            $status = FILTER_VAR($_POST['status'],FILTER_SANITIZE_MAGIC_QUOTES);
            $room_id = FILTER_VAR($_POST['room_id'],FILTER_SANITIZE_MAGIC_QUOTES);
            $amount = FILTER_VAR($_POST['amount'],FILTER_SANITIZE_MAGIC_QUOTES);
            $thumbnail = FILTER_VAR($_POST['image'],FILTER_SANITIZE_MAGIC_QUOTES);
            $discount = FILTER_VAR($_POST['discount'],FILTER_SANITIZE_NUMBER_FLOAT);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);

            $available = FILTER_VAR($_POST['available'],FILTER_SANITIZE_MAGIC_QUOTES);
            $expire = FILTER_VAR($_POST['expire'],FILTER_SANITIZE_MAGIC_QUOTES);
            $available = date("Y-m-d H:i",strtotime($available)); 
            $expire = date("Y-m-d H:i",strtotime($expire));
          
            $table = "reserve_promotion";
            $set = "pro_status= :pro_status,
                    pro_name =:pro_name,
                    pro_description = :pro_description,
                    pro_date_available=:pro_date_available,
                    pro_date_expire=:pro_date_expire,
                    pro_roomtype_id=:pro_roomtype_id,
                    discount=:discount,
                    quota=:quota,
                    thumbnail =:thumbnail";
            $where = "pro_id = :pro_id";
            $value = array(
                ":pro_id" => ($id),      
                ":pro_status" => ($status),
                ":pro_name" => ($name),
                ":pro_description" => ($desc),
                ":pro_date_available" => ($available),
                ":pro_date_expire" => ($expire),
                ":pro_roomtype_id" => ($room_id),
                ":discount"=>$discount,
                ":quota"=>$amount,
                ":thumbnail" => ($thumbnail) 
            ); 
            $result = $dbcon->update_prepare($table, $set, $where,$value);	
            if($result['message']== "OK"){
                echo json_encode([
                    "message"=>"แก้ไขข้อมูลสำเร็จ",
                    "status" =>"success"
                ]);

            }else{
                echo json_encode([
                    "message"=>"แก้ไขข้อมูลไม่สำเร็จ",
                    "status" =>"error"
                ]);
            }
        break;
        case'delete_promotion_by_id':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "reserve_promotion";
            $where  = "pro_id = :id";
            $val = array(
                ':id' => $id
            );
            $result = $dbcon->deletePrepare($table, $where , $val);
            echo json_encode($result);
        break;
        case'update_pin_promotion':
            $pin = FILTER_VAR($_POST['pin'],FILTER_SANITIZE_MAGIC_QUOTES);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            if($pin != "no"){
                $pin = "publish";
            }
            $table = "reserve_promotion";
            $set = "pro_status = :pro_status";
            $where = "pro_id = :id";
            $value = array(
                ":id" => ($id),
                ":pro_status" => ($pin)
            ); 
            print_r($value);
            $result = $dbcon->update_prepare($table, $set, $where,$value);	
            echo json_encode($result);
        break;
        case'prepare__add_promotion':
            $roomSql = "SELECT * FROM room_product WHERE room_status = 'active' ";
            $room = $dbcon->fetchAll($roomSql,[]);
            if(!empty($room)){
                $options='';
                foreach($room as $key =>$val){
                    $options .= '<option value="'.$val['room_id'].'">'.$val['room_type_name'].'</option>';
                }
            }
            $camera_i = '<i class="fa fa-camera"></i>';  
            $html = '<div class="cate-blog-icon">  
                        <div>
                        <label for=""> รูปภาพประกอบโปรโมชั่น <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
                        <div class="form-group form-add-images">
                            <div id="image-preview" style="margin:auto;">
                                <label for="image-upload" class="image-label"> '.$camera_i.' </label>
                                <div class="blog-preview-add">'.$thumbnail.'</div>
                                <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add"/>
                            </div>
                            <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" value="" required>  
                            </div> 
                        </div>
                    </div>
                    <div class="title-numb">ชื่อโปรโมชั่น:</div>
                    <input  class="swal2-input txt_title"  placeholder="โปรโมชั่น" value="">
                    <div class="title-numb">คำอธิบาย:</div>
                    <textarea  class="swal2-input txt_desc" placeholder="คำอธิบาย" style="height:150px;"></textarea>
                    <div class="title-numb">ราคาส่วนลด:</div>
                    <input  class="swal2-input txt_discount" placeholder="0" value="">
                    <div class="title-numb">จำนวนจำกัด:</div>
                    <input  class="swal2-input txt_quota" placeholder="0" value="">
                    <div class="title-numb">วันที่เริ่มโปรโมชั่น:</div>
                    <input type="date" class="swal2-input txt_datestart" placeholder="วันที่เริ่ม" value="2020-07-02">
                    <div class="title-numb">วันที่สิ้นสุด:</div>
                    <input type="date" class="swal2-input txt_dateend" placeholder="วันที่สิ้นสุด" value="31-07-2020 13:00">
                    <div class="title-numb">โปรโมชั่นสำหรับห้องพัก:</div>
                    <select id="room_promotion" class="swal2-input">
                        '.$options.'
                    </select>
                    <div class="title-numb">สถานะการใช้งาน:</div>
                    <select id="promotion" class="swal2-input">
                        <option value="publish">เปิดใช้งาน</option>
                        <option value="no">ปิดใช้งาน</option>
                        <option value="banned">ห้ามใช้งาน</option>
                    </select>
                    ';
            $result = array();
            $result['html'] = $html;  
            echo json_encode($result);
   
        break;
        case'insert_promotion':
            $code = $mydata->generateRandomString(10);
            $date = date("Y-m-d H:i:s");
            $sql ="SELECT * FROM reserve_promotion WHERE pro_code = :code AND pro_date_expire > :adate";
            $result =$dbcon->fetchObject($sql,[":code"=> $code ,":adate"=>$date]);
            if(empty($result)){
                $name = FILTER_VAR($_POST['name'],FILTER_SANITIZE_MAGIC_QUOTES);
                $desc = FILTER_VAR($_POST['desc'],FILTER_SANITIZE_MAGIC_QUOTES);
                $status = FILTER_VAR($_POST['status'],FILTER_SANITIZE_MAGIC_QUOTES);
                $room_id = FILTER_VAR($_POST['room_id'],FILTER_SANITIZE_MAGIC_QUOTES);
                $quota = FILTER_VAR($_POST['amount'],FILTER_SANITIZE_MAGIC_QUOTES);
                $thumbnail = FILTER_VAR($_POST['image'],FILTER_SANITIZE_MAGIC_QUOTES);
                $discount = FILTER_VAR($_POST['discount'],FILTER_SANITIZE_NUMBER_FLOAT);
               
                $available = FILTER_VAR($_POST['available'],FILTER_SANITIZE_MAGIC_QUOTES);
                $expire = FILTER_VAR($_POST['expire'],FILTER_SANITIZE_MAGIC_QUOTES);
                $available = date("Y-m-d H:i",strtotime($available)); 
                $expire = date("Y-m-d H:i",strtotime($expire));
             

                $table = "reserve_promotion";
                $field =  "pro_code,pro_status,discount,pro_name,quota,pro_description,pro_date_available,pro_date_expire,pro_roomtype_id, thumbnail,date_create";
                $key = ":pro_code,:pro_status,:discount,:pro_name,:quota,:pro_description,:pro_date_available,:pro_date_expire,:pro_roomtype_id,:thumbnail,:date_create";
                $value = array( 
                    ":pro_code" => ($code),
                    ":pro_status" => ($status),
                    ":discount" => ($discount),
                    ":pro_name" => ($name),
                    ":quota" => ($quota),
                    ":pro_description" => ($desc),
                    ":pro_date_available" => ($available),
                    ":pro_date_expire" => ($expire),
                    ":pro_roomtype_id" => ($room_id),
                    ":thumbnail" => ($thumbnail),
                    ":date_create"=> date("Y-m-d H:i:s")
                ); 
                $result = $dbcon->insertPrepare($table, $field, $key , $value);
                if($result['message'] == "OK"){
                    echo json_encode([
                        'message'=>"เพิ่มโปรโมชั่นสำเร็จ",
                        'method'=>"insert",
                        'status'=>"success",
                    ]);
                }else{
                    echo json_encode([
                        'message'=>"เพิ่มโปรโมชั่นไม่สำเร็จ",
                        'method'=>"insert",
                        'status'=>"error",
                    ]);
                }
            
            }else{
                echo json_encode([
                    'message'=>"โค้ดส่วนลดนี้กำลังใช้งาน",
                    'method'=>"code",
                    'status'=>"error",
                ]);
            }

        break;
	   
	} 
}

?>