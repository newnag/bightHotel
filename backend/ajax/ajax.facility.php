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
require_once dirname(__DIR__) . '/classes/class.facility.php';

getData::init(); 
$dbcon = new DBconnect();
$mydata = new facility();
$myupload = new uploadimage();

if(isset($_REQUEST['action'])){ 
	switch($_REQUEST['action']){ 
        case'get_reviews':
            $requestData = $_REQUEST;
            $columns = array(
                0 => 'priority', 
                1 => 'fac_title'
            );
            $sql = "SELECT * FROM facility_list "; 
            if (!empty($requestData['search']['value'])) {
                $sql .= " WHERE fac_title LIKE '%" . $requestData['search']['value'] . "%' ";
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
                foreach ($result as $value) { 
                    $display =  '<div class="col-md-12 btnPin"> 
                                    <div class="toggle-switch inTables '.(($value['fac_pin'] == 'no')?"":"ts-active").'" style="margin: auto">
                                        <span class="switch" data-id="'.$value['fac_id'].'"></span>
                                    </div>
                                    <input type="hidden" class="form-control" id="reviews_status" value="'.(($value['fac_pin'] == 'no')?"no":"yes").'">
                                </div>';
                    $img = ($value['fac_thumbnail'] == "")?'ไม่มี': '<a target="__blank" class="fancybox reviewImgName" data-id="'.$value['fac_id'].'" href='.ROOT_URL.$value['fac_thumbnail']. '> <img style="width:50px;"src="'.ROOT_URL.$value['fac_thumbnail'].'"></a>'; 
                    $nestedData = array();
                    $nestedData[] = $value['priority'];
                    $nestedData[] = $value['fac_title'];
                    $nestedData[] = '<div class="agentImages"><center>'.$img.'</center></div>';	
                    $nestedData[] = $display;
                    $nestedData[] = '<p class="btn-center btn-flex">
                                        <a class="btn kt:btn-warning" style="color:white;" onclick="editCategory(event,' . $value['fac_id'] . ')"><i class="fas fa-edit"></i> แก้ไข</a>
                                        <a class="btn kt:btn-danger del_catenumb" style="color:white;" data-id="'.$value['fac_id'].'" data-name="'.$value['fac_title'].'" onclick="delReviews(event,' . $value['fac_id'] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i> ลบ</a>
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
            $sql = "SELECT * FROM facility_list WHERE fac_id = :id";
            $result = $dbcon->fetchAll($sql,[":id"=>$id]);
            $result = $result[0];
            if($result['fac_thumbnail'] == ""){ 
                $camera_i = '<i class="fa fa-camera"></i>';
            }else{
                $thumbnail = '<div class="col-img-preview" id="col_img_preview_1" data-id="1"><img class="preview-img" id="preview_img_1" src="'.ROOT_URL.$result['fac_thumbnail'].'"></div>';
            }

            $html = '<div class="cate-blog-icon">  
                  <div>
                    <label for="">Category icon [SVG,PNG,JPEG] <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
                    <div class="form-group form-add-images">
                        <div id="image-preview" style="margin:auto;">
                            <label for="image-upload" class="image-label"> '.$camera_i.' </label>
                            <div class="blog-preview-add">'.$thumbnail.'</div>
                            <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add"/>
                        </div>
                        <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" value="'.$result['fac_thumbnail'].'" required>  
                        </div> 
                    </div>
                </div>
                <div class="title-numb">เรื่อง:</div>
                <input  class="swal2-input txt_title" placeholder="เรื่อง" value="'.$result['fac_title'].'">
                <div class="title-numb">คำอธิบาย:</div>
                <textarea  class="swal2-input txt_desc" placeholder="คำอธิบาย" style="height:150px;">'.$result['fac_description'].'</textarea>
                <div class="title-numb">ลำดับการแสดงผล:</div>
                <input  class="swal2-input txt_priority " value="'.($result['priority']).'" placeholder="กรุณาใส่ตัวเลข"> ';
            $result['html'] = $html; 
            echo json_encode($result);

        break;  
        case'uploadImage_reviews':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/'; 
            $thumbnail = $myupload->upload_image_thumb($new_folder);   
            echo json_encode($thumbnail);
        break;
        case'update_reviews':
            $title = FILTER_VAR($_POST['title'],FILTER_SANITIZE_MAGIC_QUOTES);
            $desc = FILTER_VAR($_POST['desc'],FILTER_SANITIZE_MAGIC_QUOTES);
            $thumbnail = FILTER_VAR($_POST['image'],FILTER_SANITIZE_MAGIC_QUOTES);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = FILTER_VAR($_POST['priority'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = ($priorityNew == 0)?1:$priorityNew;

            $sql = "SELECT priority FROM facility_list WHERE fac_id = :id LIMIT 1";
            $result = $dbcon->fetchAll($sql,[':id' => $id]);
            $priorityOld = $result[0]['priority']; 
            if($priorityNew != $priorityOld){ 
                $sql = "SELECT MAX(priority) as max FROM facility_list ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max']+1;
                }  
                $set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
                $where = "fac_id <> :id AND 
                (CASE 
                    WHEN :old < :new THEN priority > :old AND priority <= :new 
                    WHEN :old > :new THEN priority >= :new AND priority < :old 
                END)  ";
                $value = array(
                    ":id" => $id,
                    ":old" => $priorityOld,
                    ":new" => $priorityNew
                );
                $prio['set'] = $dbcon->update_prepare("facility_list",$set,$where,$value);
                $set = "priority = :new";
                $where = "fac_id = :id";
                $value = array(
                    ":id" => $id,
                    ":new" => $priorityNew
                );
                $prio['update'] = $dbcon->update_prepare("facility_list",$set,$where,$value);
            }
            $table = "facility_list";
            $set = "fac_name=:fac_name,fac_title=:fac_title,fac_description = :fac_description,fac_thumbnail =:fac_thumbnail";
            $where = "fac_id = :fac_id";
            $value = array(
                ":fac_id" => ($id),
                ":fac_name" => ($title),
                ":fac_title" => ($title),
                ":fac_description" => ($desc),
                ":fac_thumbnail" => ($thumbnail) 
            ); 
            $result = $dbcon->update_prepare($table, $set, $where,$value);	

            echo json_encode($result);
        break;
        case'delete_reviews_by_id':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "facility_list";
            $where  = "fac_id = :id";
            $val = array(
                ':id' => $id
            );
            $result = $dbcon->deletePrepare($table, $where , $val);
            echo json_encode($result);
        break;
        case'update_pin_reviews':
            $pin = FILTER_VAR($_POST['pin'],FILTER_SANITIZE_MAGIC_QUOTES);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "facility_list";
            $set = "fac_pin = :fac_pin";
            $where = "fac_id = :id";
            $value = array(
                ":id" => ($id),
                ":fac_pin" => ($pin)
            ); 
            print_r($value);
            $result = $dbcon->update_prepare($table, $set, $where,$value);	
            echo json_encode($result);
        break;

        case'prepare__add_reviews':
            $sql ="SELECT max(priority) as numb FROM facility_list";
            $result = $dbcon->fetchObject($sql,[]);
            $camera_i = '<i class="fa fa-camera"></i>';  
            $html = '<div class="cate-blog-icon">  
                    <div>
                        <label for="">Category icon [SVG,PNG,JPEG] <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
                        <div class="form-group form-add-images">
                            <div id="image-preview" style="margin:auto;">
                                <label for="image-upload" class="image-label"> '.$camera_i.' </label>
                                <div class="blog-preview-add"></div>
                                <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add"/>
                            </div>
                            <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" value="" required>  
                        </div> 
                    </div>
                </div>
                <div class="title-numb">เรื่อง:</div>
                <input  class="swal2-input txt_title" placeholder="เรื่อง" value="">
                <div class="title-numb">คำอธิบาย:</div>
                <textarea  class="swal2-input txt_desc" placeholder="คำอธิบาย" style="height:150px;"></textarea>
                <div class="title-numb">ลำดับการแสดงผล:</div>
                <input  class="swal2-input txt_priority " value="'.($result->numb+1).'" placeholder="กรุณาใส่ตัวเลข"> ';
            $result = array();
            $result['html'] = $html; 
            echo json_encode($result);
        break;
        case'insert_reviews':
            $title = FILTER_VAR($_POST['title'],FILTER_SANITIZE_MAGIC_QUOTES);
            $desc = FILTER_VAR($_POST['desc'],FILTER_SANITIZE_MAGIC_QUOTES);
            $image = FILTER_VAR($_POST['image'],FILTER_SANITIZE_MAGIC_QUOTES);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = FILTER_VAR($_POST['priority'],FILTER_SANITIZE_NUMBER_INT);
            $priorityNew = ($priorityNew == 0)?1:$priorityNew;

            $sql = "SELECT priority FROM facility_list WHERE priority = :prioritynew LIMIT 1";
            $result = $dbcon->fetchObject($sql,[':prioritynew' => $priorityNew]);
            $priorityOld = $result->priority; 
            if(!empty($priorityOld)){ 
                $sql = "SELECT MAX(priority) as max FROM facility_list ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max'];
                }  
                $set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
                $where = "fac_id <> :id AND 
                (CASE 
                    WHEN :old < :new THEN priority > :old AND priority <= :new 
                    WHEN :old > :new THEN priority >= :new AND priority < :old 
                END)
                ";
                $value = array(
                    ":id" => $id,
                    ":old" => $priorityOld,
                    ":new" => $priorityNew
                );
                $prio['set'] = $dbcon->update_prepare("facility_list",$set,$where,$value);
            }

            $table = "facility_list";
            $field = "fac_description,fac_name,fac_title, fac_thumbnail,priority";
            $key = ":fac_description ,:fac_name,:fac_title,:fac_thumbnail,:priority";
            $value = array(
                ":fac_description" => ($desc),
                ":fac_name" => ($title),
                ":fac_title" => ($title),
                ":fac_thumbnail" => ($image),
                ":priority" => $priorityNew 
            );
            $result = $dbcon->insertPrepare($table, $field, $key , $value);
            echo json_encode($result);
        break;
	   
	} 
}

?>