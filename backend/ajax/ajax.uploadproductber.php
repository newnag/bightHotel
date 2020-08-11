<?php
session_start();  
   
//  ProtectWeb::admin_only();
//  ProtectWeb::method_post_only();
//  error_reporting(E_ALL);
//  ini_set('display_errors', 1);

include '../config/database.php';
require_once('../classes/dbquery.php');
require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.productber.php');
 
$site_url = ROOT_URL;  
$dbcon = new DBconnect();
$data = new getData();
$dataClass = new productber();

if(isset($_REQUEST['action'])){ 
	switch($_REQUEST['action']){ 
		#อัพไฟล์ csv .. xlsx .. เพื่อแปลงแล้วเก็บข้อมูลลง db 
		case'uploadExcelFile':  
				//print_r($_FILES['file_upload']);  
				$target_dir = PATH_UPLOAD."excel/".date('Y-m').'/'; 
				if (!file_exists($target_dir)) {
					@mkdir($target_dir, 0777, true);
				} 
				$target_file = $target_dir. date('d-His').'-'. basename($_FILES["file_upload"]['name']);    
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); 
				// Check if image file is a actual image or fake image
				if(isset($_POST["submit"])) {
					$check = getimagesize($_FILES["file_upload"]["tmp_name"]);
					if($check !== false) {
						echo "File is an image - " . $check["mime"] . ".";
						$uploadOk = 1;
					} else {
						echo "ประเภทไฟล์ไม่ถูกต้อง";
						$uploadOk = 0;
					}
				}
				// Check if file already exists
				if (file_exists($target_file)) {
					echo "Sorry, file already exists.";
					$uploadOk = 0;
				} 
				// Check file size
				if ($_FILES["file_upload"]["size"] > 20000000) {
					echo "Sorry, your file is too large. please contact wynnsoft";
					$uploadOk = 0;
				} 
				// Allow certain file formats 
				$fileType = explode('.',$imageFileType);	 
				if($fileType[0] != "xlsx" && $fileType[0] != "xls") {
					echo "Sorry,this files is not allowed.";
					$uploadOk = 0;
				} 
				// Check if $uploadOk is set to 0 by an error 
				if ($uploadOk == 0) {
					echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file 
				} else {
					if (move_uploaded_file($_FILES["file_upload"]["tmp_name"], $target_file)) {
						// echo "The file ". basename( $_FILES["file_upload"]["name"]). " has been uploaded.";
					} else {
						echo "Sorry, there was an error uploading your file.";
					}
				} 
				#Delete data before new upload  
				$table ='berproduct'; 
				$where ='display != :display';
				$value = [ ':display' => 'test' ];			 
				$ret['res'] = $dbcon->deletePrepare($table, $where, $value);	
				#Insert ข้อมูลจาก excel และจัดหมวดหมู่ product_improve
				$extract = $dataClass->import_by_excel_productber($target_file); 
				
		break; 
		case'getExcelExport':
				global $site_url;
				$sql =' SELECT * FROM berproduct   ';
				$result = $dbcon->query($sql);   
				if(!empty($result)){ 
					$itemArr = array(); 
					foreach($result as $key =>$value){
						$itemArr[$key]['number'] =  $value['product_phone'];
						$itemArr[$key]['sum'] =  $value['product_sumber'];
						$itemArr[$key]['price']= $value['product_price'];
						$itemArr[$key]['network']  =   $value['product_network'];
						$itemArr[$key]['category'] =  substr($value['default_cate'],1,-1);
						$itemArr[$key]['mood'] =  $value['product_pin'];
						$itemArr[$key]['sold'] =  $value['product_sold'];
						$itemArr[$key]['new'] =  $value['product_hot'];
						$itemArr[$key]['comment'] =  $value['product_comment'];
						$itemArr[$key]['grade'] =  $value['product_grade'];
						$itemArr[$key]['discount'] = $value['product_discount'];
					}
				} 
			
				$link = $dataClass->exportExcelAllProducts($itemArr);  
				$ret['src'] = $site_url.'/backend/classes/'.$link;
				echo json_encode($ret); 
		break;
		case'updateDataUpload':  
			$getpost['order'] = '';  
			#จัดหมวดหมู่ที่มาจาก excel #Manual
			$res['manual'] =  $dataClass->getProductByCategoryManual($getpost);
			#จัดหมวดหมู่ตามสูตร #automatic
			$res['auto'] =  $dataClass->getProductByCategory($getpost);  
			#จัดเกรดของเบอร์ กรณี(product_grade ยังเป็นค่าว่าง) #automatic
			$res['grade'] =  $dataClass->getProductByCategoryGrade();  
			#จัดหมวดหมู่ตามสูตร หมวดเบอร์คู่รัก และ หมวดเบอร์ห่าม - xxyy #automatic
			$res['set'] =  $dataClass->getProductByCategoryBySet($getpost);  
		
			$res['message'] = "OK";
			echo json_encode($res);  
	 	break;

		case'uploadImagesCategory':
			  $id = FILTER_VAR($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
			  #ยังไม่ได้ทดสอบ และ ทำ protect
			  $new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
			  // $images = $data->upload_images($new_folder);
				$images = $data->upload_images_thumb($new_folder);
				$table = "berproduct_category";
				$set = "thumbnail = '".$images['0']."' ";
				$where = "bercate_id = :id ";
				$value = array(
					":id" => ($id)							
				); 
				$result = $dbcon->update_prepare($table, $set, $where,$value);	
			  echo json_encode($result);
		break;
	   
	} 
}

?>