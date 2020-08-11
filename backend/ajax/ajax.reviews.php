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
require_once dirname(__DIR__) . '/classes/class.reviews.php';

getData::init(); 
$dbcon = new DBconnect();
$mydata = new reviews();
$myupload = new uploadimage();

if(isset($_REQUEST['action'])){ 
	switch($_REQUEST['action']){ 
        case'get_reviews':
            $requestData = $_REQUEST;
            $columns = array(
                0 => 'priority', 
                1 => 'title'
            );
            $sql = "SELECT * FROM gallery_image "; 
            if (!empty($requestData['search']['value'])) {
                $sql .= " WHERE title LIKE '%" . $requestData['search']['value'] . "%' ";
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
                                    <div class="toggle-switch inTables '.(($value['display'] == 'no')?"":"ts-active").'" style="margin: auto">
                                        <span class="switch" data-id="'.$value['id'].'"></span>
                                    </div>
                                    <input type="hidden" class="form-control" id="reviews_status" value="'.(($value['display'] == 'no')?"no":"yes").'">
                                </div>';
                    $img = ($value['thumbnail'] == "")?'ไม่มี': '<a target="__blank" class="fancybox reviewImgName" data-id="'.$value['id'].'" href='.ROOT_URL.$value['thumbnail']. '> <img style="width:50px;"src="'.ROOT_URL.$value['thumbnail'].'"></a>'; 
                    $nestedData = array();
                    $nestedData[] = $value['priority'];
                    $nestedData[] = $value['title'];
                    $nestedData[] = '<div class="agentImages"><center>'.$img.'</center></div>';	
                    $nestedData[] = $display;
                    $nestedData[] = '<p class="btn-center btn-flex">
                                        <a class="btn kt:btn-warning" style="color:white;" onclick="editCategory(event,' . $value['id'] . ')"><i class="fas fa-edit"></i> แก้ไข</a>
                                        <a class="btn kt:btn-danger del_catenumb" style="color:white;" data-id="'.$value['id'].'" data-name="'.$value['title'].'" onclick="delReviews(event,' . $value['id'] . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i> ลบ</a>
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
            $sql = "SELECT * FROM gallery_image WHERE id = :id";
            $result = $dbcon->fetchAll($sql,[":id"=>$id]);
            $result = $result[0];
            if($result['thumbnail'] == ""){ 
                $camera_i = '<i class="fa fa-camera"></i>';
            }else{
                $thumbnail = '<div class="col-img-preview" id="col_img_preview_1" data-id="1"><img class="preview-img" id="preview_img_1" src="'.ROOT_URL.$result['thumbnail'].'"></div>';
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
                        <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden" value="'.$result['thumbnail'].'" required>  
                        </div> 
                    </div>
                </div>
                <div class="title-numb">เรื่อง:</div>
                <input  class="swal2-input txt_title" placeholder="เรื่อง" value="'.$result['title'].'">
                <div class="title-numb">คำอธิบาย:</div>
                <textarea  class="swal2-input txt_desc" placeholder="คำอธิบาย" style="height:150px;">'.$result['description'].'</textarea>
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

            $sql = "SELECT priority FROM gallery_image WHERE id = :id LIMIT 1";
            $result = $dbcon->fetchAll($sql,[':id' => $id]);
            $priorityOld = $result[0]['priority']; 
            if($priorityNew != $priorityOld){ 
                $sql = "SELECT MAX(priority) as max FROM gallery_image ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max']+1;
                }  
                $set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
                $where = "id <> :id AND 
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
                $prio['set'] = $dbcon->update_prepare("gallery_image",$set,$where,$value);
                $set = "priority = :new";
                $where = "id = :id";
                $value = array(
                    ":id" => $id,
                    ":new" => $priorityNew
                );
                $prio['update'] = $dbcon->update_prepare("gallery_image",$set,$where,$value);
            }
            $table = "gallery_image";
            $set = "description = :description,title=:title,thumbnail =:thumbnail";
            $where = "id = :id";
            $value = array(
                ":id" => ($id),
                ":title" => ($title),
                ":description" => ($desc),
                ":thumbnail" => ($thumbnail) 
            ); 
            $result = $dbcon->update_prepare($table, $set, $where,$value);	

            echo json_encode($result);
        break;
        case'delete_reviews_by_id':
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "gallery_image";
            $where  = "id = :id";
            $val = array(
                ':id' => $id
            );
            $result = $dbcon->deletePrepare($table, $where , $val);
            echo json_encode($result);
        break;
        case'update_pin_reviews':
            $pin = FILTER_VAR($_POST['pin'],FILTER_SANITIZE_MAGIC_QUOTES);
            $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
            $table = "gallery_image";
            $set = "display = :display";
            $where = "id = :id";
            $value = array(
                ":id" => ($id),
                ":display" => ($pin)
            ); 
            print_r($value);
            $result = $dbcon->update_prepare($table, $set, $where,$value);	
            echo json_encode($result);
        break;

        case'prepare__add_reviews':
            $sql ="SELECT max(priority) as numb FROM gallery_image";
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

            $sql = "SELECT priority FROM gallery_image WHERE priority = :prioritynew LIMIT 1";
            $result = $dbcon->fetchObject($sql,[':prioritynew' => $priorityNew]);
            $priorityOld = $result->priority; 
            if(!empty($priorityOld)){ 
                $sql = "SELECT MAX(priority) as max FROM gallery_image ";
                $PriorityMax = $dbcon->fetch($sql);
                if($priorityNew > $PriorityMax['max']){
                    $priorityNew = $PriorityMax['max'];
                }  
                $set = "priority = (CASE WHEN :old < :new THEN priority-1 WHEN :old > :new THEN priority+1 END)";
                $where = "id <> :id AND 
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
                $prio['set'] = $dbcon->update_prepare("gallery_image",$set,$where,$value);
            }

            $table = "gallery_image";
            $field = "description,title, thumbnail,priority,date_create";
            $key = ":description,:title,:thumbnail,:priority,:date_create";
            $value = array(
                ":description" => ($desc),
                ":title" => ($title),
                ":thumbnail" => ($image),
                ":priority" => $priorityNew,
                ":date_create"=> date("Y-m-d H:i:s")
            );
            $result = $dbcon->insertPrepare($table, $field, $key , $value);
            echo json_encode($result);
        break;
	   
	} 
}

?>