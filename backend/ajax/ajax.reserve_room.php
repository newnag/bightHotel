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
require_once dirname(__DIR__) . '/classes/class.reserve_room.php';

getData::init(); 
$dbcon = new DBconnect();
$mydata = new reserve_room();
$myupload = new uploadimage();

if(isset($_REQUEST['action'])){ 
	switch($_REQUEST['action']){ 
        case'get_reserveroom':
            $requestData = $_REQUEST;
            $columns = array( 0 => ' rso.resv_id ' ); 
            $sql = "    SELECT  rso.resv_id,
                                rso.resv_code, 
                                rso.date_checkin, 
                                rso.date_checkout, 
                                rso.resv_price, 
                                rso.resv_discount, 
                                rso.resv_extra, 
                                rso.resv_netpay, 
                                rso.resv_status, 
                                rso.resv_datecreated, 
                                rsd.price, 
                                rsd.adult, 
                                rsd.children, 
                                rsd.discount_code, 
                                rsd.room_type, 
                                rsc.contact_name, 
                                rsc.contact_lastname, 
                                rsc.contact_tel, 
                                rsc.contact_address, 
                                rsc.contact_district, 
                                rsc.contact_subdistrict, 
                                rsc.contact_province, 
                                rsc.contact_postcode, 
                                rsc.contact_otp,
                                rsp.status as payment 
                        FROM reserve_order  as rso 
                        INNER JOIN reserve_detail as rsd 
                        INNER JOIN reserve_contact as rsc
                        INNER JOIN reserve_payment  as rsp 
                            ON  rso.resv_code = rsd.code 
                                AND rso.resv_code = rsc.code 
                                AND rso.resv_code = rsd.code 
                                AND rso.resv_code = rsp.code 
                        INNER JOIN room_product as rp ON rp.room_code = rsd.room_type 
                        WHERE ( rso.resv_status = 'publish'  OR  rso.resv_status = 'pending' )  "; 

            if($_POST['status'] != ""){
                if($_POST['status'] == "payment"){
                    $sql .= " AND rsp.status != 'success' ";
                }else{
                    $sql .= " AND rso.resv_status = '".$_POST['status']."' ";
                }
            }
            if($_POST['datein'] != ""){
                $sql .= " AND rso.resv_datecheckin >= '".$_POST['date_in']."' ";
            }
            if (!empty($requestData['search']['value'])) {
                $sql .= " AND (  rso.resv_code LIKE '%" . $requestData['search']['value'] . "%' 
                            OR rso.resv_code LIKE '%" . $requestData['search']['value'] . "%'  
                            OR rsc.contact_name LIKE '%" . $requestData['search']['value'] . "%'  
                            OR rp.room_type_name LIKE '%" . $requestData['search']['value'] . "%'  
                        ) ";
            } 
            $sql .= " ORDER BY  " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;
            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
            $result = $dbcon->query($sql);
            $output = array();

            $status['fail'] = "<div class='action-display action-fail'>ล้มเหลว</div>";
            $status['banned'] = "<div class='action-display action-banned'>ปิดกั้น</div>";
            $status['publish'] = "<div class='action-display action-success'>สำเร็จ</div>";
            $status['pending'] = "<div class='action-display action-pending'>รอการอนุมัติ</div>";
            $status['payment'] = "<div class='action-display action-payment'>รอการชำระ</div>";
            $status['fail_payment'] = "<div class='action-display  action-fail-payment'>ชำระเงินไม่สำเร็จ</div>";

            $sql ="SELECT * FROM room_product";
            $room = $dbcon->fetchAll($sql,[]); 
            foreach($room as $key =>$val){
                $setRoom[$val['room_code']] = $val['room_type_name'];
            }
            
            if ($result) {
                $room ="";
                $discount = 0;
                foreach ($result as $key => $value) { 
                    $room .= '<p>'.$setRoom[$value['room_type']].' ( '.number_format($value['price']).'฿)</p>';
                    $nextKey = $key+1;
                    if($value['resv_code'] == $result[$nextKey]['resv_code']){ 
                        continue;
                    }
                    if($value['payment'] != 'success' ){
                        if($value['payment'] == 'pending'){
                            $value['resv_status'] = 'payment'; 
                        } else {
                            $value['resv_status'] = 'fail_payment'; 
                        }
                    }

                    $contact =  '  <p>ชื่อ: '.$value['contact_name'].' '.$value['contact_lastname'].'</p>
                                    <p>เบอร์: '.$value['contact_tel'].' </p>
                                    <p>ที่อยู่: '.$value['contact_address'].' '.$value['contact_district'].'  '.$value['contact_subdistrict'].'  '.$value['contact_province'].'  '.$value['contact_postcode'].' </p>
                                    <p>รหัสยืนยัน: '.$value['contact_otp'].'</p> ';
    
                    $datein = $mydata->date_convert($value['date_checkin']);
                    $dateout = $mydata->date_convert($value['date_checkout']);
                    $dateCreated = $mydata->date_convert($value['resv_datecreated']);
                    $date1= date_create($value['date_checkin']);
                    $date2= date_create($value['date_checkout']);
                    $diff = date_diff($date1,$date2);
                    $duration = $diff->days+1;
                    $nestedData = array();
                    $nestedData[] = $value['resv_id'];
                    $nestedData[] = "<div style='display:grid'>".$room."</div>";
                    $nestedData[] = $datein ." - " .$dateout;
                    $nestedData[] = $duration." คืน";
                    $nestedData[] = $value['resv_discount'];
                    $nestedData[] = $value['resv_netpay'];
                    $nestedData[] =  $status[$value['resv_status']];
                    $nestedData[] = "<div style='display:grid;'>".$contact."</div>";
                    $nestedData[] = "<div style='display:grid;'><p>". $dateCreated . " </p><p>เวลา: ".date('H:i:s',strtotime($value['resv_datecreated'])) ."</p></div> ";
                    $nestedData[] =  '<p class="btn-center btn-flex">
                                        <a class="btn kt:btn-warning" style="color:white;" onclick="confirmpayment(event,' . $value['resv_id'] . ')"><i class="fas fa-edit"></i> เพิ่มเติม </a>
                                        <a class="btn kt:btn-danger del_catenumb" style="color:white;" data-id="'.$value['resv_id'].'" data-name="'.$value['resv_code'].'" onclick="delReviews(event,' . $value['resv_id'] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i> Remove</a>
                                     </p>';
                    $output[] = $nestedData;
                    $discount = 0;
                    $txt = "";
                    $room= ""; 
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
            $sql = "    SELECT  rso.resv_id,
                                rso.resv_code, 
                                rso.date_checkin, 
                                rso.date_checkout, 
                                rso.resv_price, 
                                rso.resv_discount, 
                                rso.resv_extra, 
                                rso.resv_netpay, 
                                rso.resv_status, 
                                rso.resv_datecreated, 
                                rsd.guest_name, 
                                rsd.guest_lastname, 
                                rsd.price, 
                                rsd.adult, 
                                rsd.children, 
                                rsd.discount_code, 
                                rsd.room_type, 
                                rsc.contact_name, 
                                rsc.contact_lastname, 
                                rsc.contact_email, 
                                rsc.contact_line, 
                                rsc.contact_tel,    
                                rsc.contact_address, 
                                rsc.contact_district, 
                                rsc.contact_subdistrict, 
                                rsc.contact_province, 
                                rsc.contact_postcode, 
                                rsc.contact_otp,
                                rsp.status as payment_status,
                                rsp.thumbnail as payment_thumbnail ,
                                rsp.payment_date as payment_date ,
                                rsp.name as payment_name ,
                                rsp.price as payment_price ,
                                rsp.bank_id 
                        FROM reserve_order  as rso 
                        INNER JOIN reserve_detail as rsd 
                        INNER JOIN reserve_contact as rsc
                        INNER JOIN reserve_payment  as rsp 
                            ON  rso.resv_code = rsd.code 
                                AND rso.resv_code = rsc.code 
                                AND rso.resv_code = rsd.code 
                                AND rso.resv_code = rsp.code 
                        INNER JOIN room_product as rp ON rp.room_code = rsd.room_type 
                        WHERE ( rso.resv_status = 'publish'  OR  rso.resv_status = 'pending' ) AND rso.resv_id = :id "; 
            $result = $dbcon->fetchAll($sql,[":id"=>$id]);
            $param = $result[0]; 

            $roomSql = "SELECT * FROM room_product";
            $room = $dbcon->fetchAll($roomSql,[]);
            foreach($room as $key =>$val){
                $setRoom[$val['room_code']] = $val['room_type_name'];
            }
     
            $getBank = "SELECT * FROM bank_info WHERE id = :id ";
            $resBank = $dbcon->fetchObject($getBank,[":id"=>$param['bank_id']]);
            $bank_info =  $resBank->name." ( ".$resBank->account . ":  ". $resBank->number ." )";

            $status['failed'] = "<span class='action-fail'>ล้มเหลว</span>";
            $status['success'] = "<span class='action-success'>ชำระแล้ว</span>";
            $status['pending'] = "<span class='action-pending'>รอการชำระ</span>";

            if($param['payment'] != 'success' ){
                if($param['payment'] == 'pending'){
                    $param['resv_status'] = 'payment'; 
                } else {
                    $param['resv_status'] = 'fail_payment'; 
                }
            }

            $datein = $mydata->date_convert($param['date_checkin']);
            $dateout = $mydata->date_convert($param['date_checkout']);
            $dateCreated = $mydata->date_convert($param['resv_datecreated']);
            $payment_date = $mydata->date_convert($param['payment_date']);
            $date1= date_create($param['date_checkin']);
            $date2= date_create($param['date_checkout']);
            $diff = date_diff($date1,$date2);
            $duration = $diff->days+1;
            $roomd ="";
            foreach($result as $key =>$val ){
                $roomd .= '<div class="list">
                                <p>'.($key+1).'</p>
                                <p>'.$setRoom[$val['room_type']].'</p>
                                <p>'.number_format($val['price']).'</p>
                                <p>'.$val['guest_name'].' '.$val['guest_lastname'].'</p>
                                <p>'.$val['adult'].'</p>
                                <p>'.$val['children'].'</p>
                                <p>'.$val['discount'].'</p>
                            </div>';
            }

        
            $images = ($param['payment_thumbnail'] != "")? ' <img src="../../'.$param['payment_thumbnail'].'" alt="images">':"";
            $html = '  <div class="modal-body">
                        <div class="room-blog">
                            <h3>Room Detail</h3>
                            <div class="ss1">
                                <div class="list header-list">
                                    <h3>No.</h3>
                                    <h3>Room</h3>
                                    <h3>Price</h3>
                                    <h3>Name</h3>
                                    <h3>Adult</h3>
                                    <h3>Children</h3>
                                    <h3>Discount</h3>
                                </div>
                                '.$roomd.'
                                <div class="modal-detail">
                                    <div class="detail-blog" >
                                        <h3>Contact</h3>
                                        <div class="db-1">
                                            <p>ชื่อ: '.$param['contact_name'].' '.$param['contact_lastname'].'</p>
                                            <p>โทรศัพท์: '.$param['contact_tel'].'</p>
                                            <p>Email: '.$param['contact_email'].'</p>
                                            <p>Line: '.$param['contact_line'].'</p>
                                            <p>ที่อยู่: '.$param['contact_address'].' '.$param['contact_district'].' '.$param['contact_subdistrict'].' '.$param['contact_province'].' '.$param['contact_postcode'].'</p>
                                            <p>รหัสยืนยัน 4 หลัก: '.$param['contact_otp'].'</p>
                                        </div>
                                    </div>
                                    <div class="detail-blog" >
                                        <h3>Information</h3>
                                        <div class="db-1">
                                            <p>วันที่เข้าพัก: '.$datein.' </p>   
                                            <p>วันที่ออก: '.$dateout.' </p>   
                                            <p>จำนวน: '.$duration.' คืน</p>    
                                            <p>ค่าห้องพัก: '.number_format($param['resv_price']).' บาท </p>
                                            <p>ค่าบริการเพิ่มเติม: '.number_format($param['resv_extra']).' บาท </p>
                                            <p>ส่วนลด: '.number_format($param['resv_discount']).' บาท</p>
                                            <p>ค่าใช้จ่ายทั้งหมด: '.number_format($param['resv_netpay']).' บาท</p>
                                        </div>
                                    </div>
                                    <div class="detail-blog" >
                                        <h3>Payment</h3>
                                        <div class="db-1">
                                            <p>สถานะ: '.$status[$param['payment_status']].'</p>
                                            <p>ชื่อผู้โอน: '.(($param['payment_status']=="success")?$param['payment_name']:"").'</p>
                                            <p>จำนวน: '.(($param['payment_status'] =="success")?number_format($param['payment_price']):"0").' บาท</p>
                                            <p>วันที่: '.(($param['payment_status'] =="success")?$payment_date." ".date("H:i",strtotime($param['payment_date']))." น.":"").'</p>
                                            <p>เข้าบัญชี: '.(($param['payment_status'] =="success")?($bank_info):"").'</p>
                                        </div>
                                        <div class="payment-image">
                                            '.$images.'
                                        </div>
                                    </div>
                                    <div class="full-payment-image">
                                        '.$images.'
                                        <div class="close">X</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>   ';
            $result['html'] = $html; 
            $result['thumbnail'] = ROOT_URL.$result[0]['payment_thumbnail']; 
            $result['available'] = date("d-m-Y H:i",strtotime($result['pro_date_available']));
            $result['expire'] = date("d-m-Y H:i",strtotime($result['pro_date_expire']));
            $result['code'] = $param['resv_code'];
            $result['txt_title'] = "ข้อมูลการจอง";
            $result['txt_confirm'] = "อนุมัติการจอง";
            $result['approve'] = ($result[0]['resv_status'] == "publish")?false:true;

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
        case'delete_order_by_id':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT resv_code FROM reserve_order WHERE resv_id = :id";
            $result = $dbcon->fetchObject($sql,[":id" => $id]);
            if(!empty($result)){
                $table = "reserve_order";
                $where  = "resv_code = :id";
                $val = array(
                    ':id' => $result->resv_code
                );
                $del['order'] = $dbcon->deletePrepare($table, $where , $val);
                if($del['order']['message'] == "OK"){
                    $table = "reserve_contact";
                    $where  = "code = :id";
                    $val = array(
                        ':id' => $result->resv_code
                    );
                    $del['contact'] = $dbcon->deletePrepare($table, $where , $val);
                    $table = "reserve_payment";
                    $where  = "code = :id";
                    $val = array(
                        ':id' => $result->resv_code
                    );
                    $del['payment'] = $dbcon->deletePrepare($table, $where , $val);
                    $table = "reserve_detail";
                    $where  = "code = :id";
                    $val = array(
                        ':id' => $result->resv_code
                    );
                    $del['detail'] = $dbcon->deletePrepare($table, $where , $val);
                }
                echo json_encode([
                    'message' => 'OK',
                    'status' => 'success'
                ]);
            }else{
                echo json_encode([
                    'message' => 'not_found',
                    'status' => 'fail'
                ]);
            }
            
        break;
        case'update_order':
            $code = FILTER_VAR($_POST['code'],FILTER_SANITIZE_MAGIC_QUOTES);
            
            $table = "reserve_order";
            $set = "resv_status = :state";
            $where = "resv_code = :code";
            $value = array(
                ":state" => "publish",
                ":code" =>  $code ,
            ); 
            $ups['order'] = $dbcon->update_prepare($table, $set, $where,$value);	

            $table = "reserve_payment";
            $set = "status = :state";
            $where = " code = :code ";
            $value = array(
                ":state" => "success",
                ":code" =>  $code ,
            ); 
            $ups['payment'] = $dbcon->update_prepare($table, $set, $where,$value);	

            if($ups['order']['message'] == "OK" && $ups['payment']['message'] == "OK" ){
                echo json_encode([
                    "message" => "ปรับสถานะสำเร็จ",
                    "status" =>"success"
                ]);

            }else{
                echo json_encode([
                    "message" => "ปรับสถานะไม่สำเร็จ",
                    "status" =>"error"
                ]);
            }
        break;
        
	   
	} 
}

?>