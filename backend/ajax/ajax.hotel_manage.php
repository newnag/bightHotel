<?php
session_start();  

include '../config/database.php';
require_once('../classes/dbquery.php');
require_once('../classes/class.upload.php');
require_once('../classes/preheader.php'); 
$site_url = ROOT_URL;
$thumbgenerator =''.$site_url.'backend/classes/thumb-generator/thumb.php?src='.$site_url.'';  
$dbcon = new DBconnect();
getData::init();




if(isset($_REQUEST['action'])) {
	
	switch($_REQUEST['action']){
        case'delete_images_by_id':
            
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql ="SELECT url,id FROM room_images WHERE id =:id ";
            $result = $dbcon->fetchObject($sql,[":id"=>$id]);
            if(!empty($result)){
                unlink('../../' . $result->url);
                $table = "room_images";
                $where = " id =  ".$result->id ." AND type='product' ";
                $ret = $dbcon->delete($table, $where);
                echo json_encode($ret);
            }else{
                echo json_encode([
                    "message" => "not_found",
                    "status"=> "error" 
                ]);
            } 
        break;

        case'delete_room_product':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql ="SELECT room_id FROM room_product WHERE room_id =:id ";
            $result = $dbcon->fetchObject($sql,[":id"=>$id]);
            if(!empty($result)){
                $table = "room_product";
                $where = " room_id =  ".$result->room_id ." ";
                $ret = $dbcon->delete($table, $where);
                
                $table = "room_images";
                $where = " room_type_id =  ".$result->room_id ."  AND type ='product' ";
                $ret = $dbcon->delete($table, $where);

                echo json_encode([
                    "message" => "ลบรายการสำเร็จ",
                    "status"=> "success"   
                ]);
            }else{
                echo json_encode([
                    "message" => "ลบรายการไม่สำเร็จ",
                    "status"=> "error" 
                ]);
            }

        break;

        case'add_room_product':
            if(!empty($_POST)){
                foreach($_POST as $key => $val){
                    $getpost[$key] = FILTER_VAR(trim($val),FILTER_SANITIZE_MAGIC_QUOTES);
                }
            }
        
            $table = "room_product";
            $field = "room_type_name,room_title,room_description,room_price,room_current_price,room_extra,time_checkin,time_checkout,room_facility,room_amount,room_status,room_thumbnail";
            $key = ":room_type_name,:room_title,:room_description,:room_price,:room_current_price,:room_extra,:time_checkin,:time_checkout,:room_facility,:room_amount,:room_status,:room_thumbnail";
            $value = array(
                ":room_type_name" => $getpost['name'],
                ":room_title" => $getpost['title'],
                ":room_description" => $getpost['desc'],
                ":room_price" => $getpost['price'],
                ":room_current_price" => $getpost['currentprice'],
                ":room_extra" => $getpost['extra'],
                ":time_checkin" => $getpost['timein'],
                ":time_checkout" => $getpost['timeout'],
                ":room_facility" => $getpost['facility'],
                ":room_amount" => $getpost['roomamount'],
                ":room_status" =>$getpost['display'],
                ":room_thumbnail" =>$getpost['thumbnail']
            );

            $result = $dbcon->insertPrepare($table, $field, $key , $value);
            if($result['message'] == "OK"){
                $table = "room_images";
                $set = "display = 'yes' ";
                $where = " room_type_id = ".$result['insert_id']." AND type = 'product' AND user_id = '" . $_SESSION['user_id'] . "' ";
                $ret = $dbcon->update($table, $set, $where);
                echo json_encode([
                    "update" => "success",
                    "message" => "เพิ่มห้องพักสำเร็จ" 
                ]);
            }else{
                $table = "room_images";
                $where = " user_id =  ".$_SESSION['user_id'] ." AND type = 'product' AND display = 'no' ";
                $ret = $dbcon->delete($table, $where);
                echo json_encode([
                    "update" => "error",
                    "message" => "การเพิ่มห้องพักล้มเหลว" 
                ]);
            }
          

        break;
        case'prepare_add_room_product':
            $table = "room_images";
            $where = " user_id =  ".$_SESSION['user_id'] ." AND type = 'product' AND display = 'no' ";
            $ret = $dbcon->delete($table, $where);


            $sql ="SELECT max(room_id) max FROM room_product";
            $result = $dbcon->fetchObject($sql,[]);
            $result->room_id = $result->max + 1;
            $form = getData::get_form_product($result,"");
            echo json_encode([ 
                "html" => $form,
                "modal_btn" => "เพิ่ม",
                "modatl_title"=> "เพิ่มข้อมูลห้องพัก" 
            ]); 
        break;
    
        case'edit_room_product':
            $table = "room_images";
            $where = " user_id =  ".$_SESSION['user_id'] ." AND type='product' AND display = 'no' ";
            $ret = $dbcon->delete($table, $where);

            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM room_product WHERE room_id = :id";
            $result = $dbcon->fetchObject($sql,[":id"=>$id]);
            $getImg = "SELECT * FROM room_images WHERE room_type_id = :id AND type='product' ORDER BY image_id ASC ";
            $images = $dbcon->fetchAll($getImg,[":id"=> $result->room_id]);
            $form = getData::get_form_product($result,$images);
            echo json_encode([ 
                "html" => $form,
                "modal_btn" => "แก้ไข",
                "modatl_title"=> "แก้ไขข้อมูล" 
            ]); 
        break;
        case'uploadthumbnail':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            $images = getData::upload_images_thumb($new_folder);
            $table = "room_product";
            $set = "room_thumbnail = '" . $images['0'] . "'";
            $where = "room_id = '" . $_REQUEST['id'] . "' ";
            $result = $dbcon->update($table, $set, $where);
            $ret['url'] = $images['0'];
            $ret['image'] = '<div class="col-img-preview">
                                <img class="preview-img" src="'.ROOT_URL.$images['0'].'">
                            </div>';
            $ret['result'] = array('message' => $result['message'], 'image_link' => $images['0']);
            echo json_encode($ret);
        break;

        case 'uploadmoreimgproduct':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            $images = getData::upload_images_thumb($new_folder);
            $id = FILTER_VAR($_REQUEST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT MAX(image_id) max FROM room_images  WHERE room_type_id = '" .$id. "' AND type='product' ";
            $max = $dbcon->fetch_assoc($sql);
            $max++;
            $ret = array();
            foreach ($images as $key => $img_link) {
                $table = "room_images";
                $field = "room_type_id, url, image_id, user_id";
                $value = "	'" . $id . "',
			    			'" . $img_link . "',
                            '" . ($key + $max) . "' ,
                            '" . $_SESSION['user_id'] . "' 
                        ";
                $res = $dbcon->insert($table, $field, $value);
                $ret[] = array('image_id' => $res['insert_id'], 'image_link' => $img_link);
            }
            echo json_encode($ret);
        break;

        case'update_room_product':
            if(!empty($_POST)){
                foreach($_POST as $key => $val){
                    $getpost[$key] = FILTER_VAR(trim($val),FILTER_SANITIZE_MAGIC_QUOTES);
                }
            }

            $table ="room_product";
            $set = " room_type_name = :name,
                    room_title = :title, 
                    room_description =  :desc,
                    room_price =  :price,
                    room_current_price = :currentprice,
                    room_extra =  :extra,
                    time_checkin =  :timein,
                    time_checkout =  :timeout,
                    room_facility =  :facility ,
                    room_amount = :roomamount ,
                    room_status = :display ";
            $where = "room_id = :id ";
            $value = array(    
                        ":name" => $getpost["name"],
                        ":title" => $getpost["title"], 
                        ":desc" =>  $getpost["desc"],
                        ":price" =>  $getpost["price"],
                        ":currentprice" => $getpost["currentprice"],
                        ":extra" =>  $getpost["extra"],
                        ":timein" =>  $getpost["timein"],
                        ":timeout" =>  $getpost["timeout"],
                        ":facility" =>  $getpost["facility"],
                        ":roomamount" =>  $getpost["roomamount"],
                        ":id" =>  $getpost["id"],
                        ":display" => $getpost["display"] 
                    );  
            $updates = $dbcon->update_prepare($table, $set, $where, $value);  

            if($updates['status'] == 200){
                $table = "room_images";
                $set = " display = 'yes' ";
                $where = " room_type_id = ".$getpost["id"]." AND type = 'product' AND user_id = '" . $_SESSION['user_id'] . "' ";
                $result = $dbcon->update($table, $set, $where);

                echo json_encode([
                    "message" => "บันทึกการแก้ไข",
                    "update" => "success"
                ]);
            }else{
                $table = "room_images";
                $where = " room_type_id = ".$getpost['id']." AND type = 'product' AND user_id =  ".$_SESSION['user_id'] ." AND display = 'no' ";
                $ret = $dbcon->delete($table, $where);

                echo json_encode([
                    "message" => "บันทึกไม่สำเร็จ",
                    "update" => "error"
                ]);
            }

        break;
        case'change_room_current_price': 
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $curprice = FILTER_VAR($_POST['price'],FILTER_SANITIZE_NUMBER_FLOAT);
            $table = "room_product";
            $set = "room_current_price =  ".$curprice." ";
            $where = " room_id = ".$id." ";
            $ups = $dbcon->update($table, $set, $where);
            if($ups['message'] == "OK"){
                echo json_encode([
                    "message" =>"ปรับราคาเรียบร้อยแล้ว",
                    "status" => "success"
                ]); 
            }else{
                echo json_encode([
                    "message" =>"failed",
                    "status" => "error"
                ]); 
            }
        break;
        case'room_decreasing':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "room_product";
            $set = "room_amount = room_amount - 1 ";
            $where = " room_id = ".$id." ";
            $ups = $dbcon->update($table, $set, $where);

            if($ups['message'] == "OK"){
                echo json_encode([
                    "message" =>"ปรับจำนวนเรียบร้อยแล้ว",
                    "status" => "success"
                ]); 
            }else{
                echo json_encode([
                    "message" =>"failed",
                    "status" => "error"
                ]); 
            }
        break;
        case'room_increasing':   
                $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
                $table = "room_product";
                $set = "room_amount = room_amount + 1 ";
                $where = " room_id = ".$id." ";
                $ups = $dbcon->update($table, $set, $where);
                if($ups['message'] == "OK"){
                    echo json_encode([
                        "message" =>"ปรับจำนวนเรียบร้อยแล้ว",
                        "status" => "success"
                    ]); 
                }else{
                    echo json_encode([
                        "message" =>"failed",
                        "status" => "error"
                    ]); 
                }
        break;
	}
}

?>