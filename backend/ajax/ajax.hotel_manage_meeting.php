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
                $where = " id =  ".$result->id ." AND type ='meeting' ";
                $ret = $dbcon->delete($table, $where);
                echo json_encode($ret);
            }else{
                echo json_encode([
                    "message" => "not_found",
                    "status"=> "error" 
                ]);
            } 
        break;

        case'delete_room_meeting':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql ="SELECT id FROM room_meeting WHERE id =:id  ";
            $result = $dbcon->fetchObject($sql,[":id"=>$id]);
            if(!empty($result)){
                $table = "room_meeting";
                $where = " id =  ".$result->id ." ";
                $ret = $dbcon->delete($table, $where);
                
                $table = "room_images";
                $where = " room_type_id =  ".$result->id ."  AND type = 'meeting' ";
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

        case'add_room_meeting':
            if(!empty($_POST)){
                foreach($_POST as $key => $val){
                    $getpost[$key] = FILTER_VAR(trim($val),FILTER_SANITIZE_MAGIC_QUOTES);
                }
            }
        
            $table = "room_meeting";
            $field = "title,description,facility,amount,display,thumbnail";
            $key = ":title,:description,:facility,:amount,:display,:thumbnail";
            $value = array(
                ":title" => $getpost['title'],
                ":description" => $getpost['desc'],
                ":facility" => $getpost['facility'],
                ":amount" => $getpost['roomamount'],
                ":display" =>$getpost['display'],
                ":thumbnail" =>$getpost['thumbnail']
            );

            $result = $dbcon->insertPrepare($table, $field, $key , $value);
            if($result['message'] == "OK"){
                $table = "room_images";
                $set = "display = 'yes' ";
                $where = " room_type_id = ".$result['insert_id']." AND type  = 'meeting' AND user_id = '" . $_SESSION['user_id'] . "' ";
                $ret = $dbcon->update($table, $set, $where);
                echo json_encode([
                    "update" => "success",
                    "message" => "เพิ่มห้องประชุมสำเร็จ" 
                ]);
            }else{
                $table = "room_images";
                $where = " user_id =  ".$_SESSION['user_id'] ." AND type ='meeting' AND display = 'no' ";
                $ret = $dbcon->delete($table, $where);
                echo json_encode([
                    "update" => "error",
                    "message" => "การเพิ่มห้องประชุมล้มเหลว" 
                ]);
            }
          

        break;
        case'prepare_add_room_meeting':
            $table = "room_images";
            $where = " user_id =  ".$_SESSION['user_id'] ." AND type = 'meeting' AND display = 'no' ";
            $ret = $dbcon->delete($table, $where);
            $sql ="SELECT max(id) max FROM room_meeting";
            $result = $dbcon->fetchObject($sql,[]);
            $result->id = $result->max + 1;
            
            $form = getData::get_form_meeting($result,""); ###
            echo json_encode([ 
                "html" => $form,
                "modal_btn" => "เพิ่ม",
                "modatl_title"=> "เพิ่มข้อมูลห้องประชุม" 
            ]); 
        break;
    
        case'edit_room_meeting':
            $table = "room_images";
            $where = " user_id =  ".$_SESSION['user_id'] ." AND type ='meeting' AND display = 'no' ";
            $ret = $dbcon->delete($table, $where);

            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM room_meeting WHERE id = :id";
            $result = $dbcon->fetchObject($sql,[":id"=>$id]);
            $getImg = "SELECT * FROM room_images WHERE room_type_id = :id AND type = 'meeting' ORDER BY image_id ASC ";
            $images = $dbcon->fetchAll($getImg,[":id"=> $result->id]);
            $form = getData::get_form_meeting($result,$images); ####
            echo json_encode([ 
                "html" => $form,
                "modal_btn" => "แก้ไข",
                "modatl_title"=> "แก้ไขข้อมูล" 
            ]); 
        break;
        case'uploadthumbnail':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            $images = getData::upload_images_thumb($new_folder);
            $table = "room_meeting";
            $set = "thumbnail = '" . $images['0'] . "'";
            $where = "id = '" . $_REQUEST['id'] . "' ";
            $result = $dbcon->update($table, $set, $where);
            $ret['url'] = $images['0'];
            $ret['image'] = '<div class="col-img-preview">
                                <img class="preview-img" src="'.ROOT_URL.$images['0'].'">
                            </div>';
            $ret['result'] = array('message' => $result['message'], 'image_link' => $images['0']);
            echo json_encode($ret);
        break;

        case 'uploadmoreimgmeeting':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            $images = getData::upload_images_thumb($new_folder);
            $id = FILTER_VAR($_REQUEST['id'],FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT MAX(image_id) max FROM room_images  WHERE room_type_id = '" .$id. "' AND type = 'meeting'";
            $max = $dbcon->fetch_assoc($sql);
            $max++;
            $ret = array();
            foreach ($images as $key => $img_link) {
                $table = "room_images";
                $field = "room_type_id, url, type ,image_id, user_id";
                $value = "	'" . $id . "',
                            '" . $img_link . "',
                            'meeting',
                            '" . ($key + $max) . "' ,
                            '" . $_SESSION['user_id'] . "' 
                        ";
                $res = $dbcon->insert($table, $field, $value);
                $ret[] = array('image_id' => $res['insert_id'], 'image_link' => $img_link);
            }
            echo json_encode($ret);
        break;

        case'update_room_meeting':
            if(!empty($_POST)){
                foreach($_POST as $key => $val){
                    $getpost[$key] = FILTER_VAR(trim($val),FILTER_SANITIZE_MAGIC_QUOTES);
                }
            }

            $table ="room_meeting";
            $set = "title = :title, 
                   description =  :desc,
                   facility =  :facility ,
                   amount = :amount ,
                   display = :display ";
            $where = "id = :id ";
            $value = array(    
                        ":title" => $getpost["title"], 
                        ":desc" =>  $getpost["desc"],
                        ":facility" =>  $getpost["facility"],
                        ":amount" =>  $getpost["roomamount"],
                        ":id" =>  $getpost["id"],
                        ":display" => $getpost["display"] 
                    );   
            $updates = $dbcon->update_prepare($table, $set, $where, $value);  
            if($updates['status'] == 200){
                $table = "room_images";
                $set = " display = 'yes' ";
                $where = " room_type_id = ".$getpost["id"]." AND type = 'meeting' AND user_id = '" . $_SESSION['user_id'] . "' ";
                $result = $dbcon->update($table, $set, $where);

                echo json_encode([
                    "message" => "บันทึกการแก้ไข",
                    "update" => "success"
                ]);
            }else{
                $table = "room_images";
                $where = " room_type_id = ".$getpost['id']." AND type = 'meeting' AND user_id =  ".$_SESSION['user_id'] ." AND display = 'no' ";
                $ret = $dbcon->delete($table, $where);

                echo json_encode([
                    "message" => "บันทึกไม่สำเร็จ",
                    "update" => "error"
                ]);
            }

        break;
         
	}
}

?>