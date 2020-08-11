<?php	
session_start();

include '../config/database.php';
require_once('../classes/dbquery.php');
require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.car_brand.php');

$dbcon = new DBconnect();
$data = new getData();
$mydata = new car_brand();

if(isset($_REQUEST['action'])) {
	$lang_config = $data->lang_config();
    $output = $_SESSION['backend_language'];
    foreach($lang_config as $a){
      foreach($a as $b => $c){
        if($b == 'param'){
          if($a[$output]!='')
            $$c = $a[$output];
          else
            $$c = $a['defaults'];
        }
      }
    }
    switch($_REQUEST['action']){
		case'getallcategory':
			$getpost = array('amount' => 10, 'pagi' => $_REQUEST['pagi'], 'search' => $_REQUEST['search']);
			$result = $mydata->get_all_category($getpost, $_REQUEST['status']);
			$category = $mydata->get_category();

			$table = "category";
			if ($_REQUEST['status'] == 'show') {
				$where = "main_page = 'no' AND menu = 'no' AND display = 'yes' AND defaults = 'yes'";
			}else if ($_REQUEST['status'] == 'hidden') {
				$where = "main_page = 'no' AND display = 'no' AND defaults = 'yes'";
			}else {
				$where = "main_page = 'no' AND menu = 'yes' AND display = 'yes' AND defaults = 'yes'";
			}

			$output = array();
			$i = 0;
			foreach ($result as $value) {
				$output['data'][$i] = $value;
				$output['data'][$i]['parent_name'] = ($category[$value['parent_id']]['cate_name'] == '') ? 'หน้าหลัก' : $category[$value['parent_id']]['cate_name'];
				$i++;
			}

			$output['rows'] = $data->pagination($table,$where);
			echo json_encode($output);
		break;
		case'getcategoryedit':
			$lan_arr = $data->get_language_array();
			$sql = "SELECT * FROM car_brand WHERE cate_id = '".$_REQUEST['id']."' ORDER BY FIELD( defaults,  'yes')DESC";
			$result = $dbcon->query($sql);

			$category = array();
			$ret = array();

			foreach($result as $a){
				if($a['defaults']=='yes'){
					$category[$a['cate_id']]['defaults']=$a;
				}
				$category[$a['cate_id']][$a['language']]=$a;
			}

			foreach($category as $a){
				foreach($a as $b => $c){
					if($b != 'defaults')
						if(in_array($b,$lan_arr))
							$lang_info .= ','.$c['language'];
					if($b == 'defaults')
						$ret[$c['cate_id']]=$c;
					if($b == $_SESSION['backend_language'])
						$ret[$c['cate_id']]=$c;
				}
				$ret[$c['cate_id']]['lang_info'] = $lang_info;
				$lang_info = '';
			}
			echo json_encode($ret[$_REQUEST['id']]);
		break;
		case'getpaginationcategory':
        	$table = "car_brand";
        	$where = "defaults = 'yes'";
        	$result = $data->pagination($table,$where);
        	echo ($result);
        break;
        case 'addcategory':
			$sql = " SELECT COUNT(url) AS 'count_url' FROM car_brand WHERE url = '".$_REQUEST['slug']."' ";
        	$count_url = $dbcon->fetch_assoc($sql);

        	if ($count_url == 0) {
				if ($_REQUEST['parentId']==0) {
					$level=0;
				}else {
					$sql = "SELECT level FROM car_brand WHERE cate_id='".$_REQUEST['parentId']."'";
					$level = $dbcon->fetch_assoc($sql);
					$level++;
				}

				$sql = "SELECT MAX(priority) pmax FROM car_brand WHERE parent_id='".$_REQUEST['parentId']."'";
				$priority = $dbcon->fetch_assoc($sql);

				if ($_REQUEST['priority'] == 0 || $_REQUEST['priority'] > $priority) {
					$data_priority = $priority+1;
				}else {
					$data_priority = $_REQUEST['priority'];
				}

				$sql = "SELECT MAX(cate_id) imax FROM car_brand";
				$imax = $dbcon->fetch_assoc($sql);
				$imax++;

				$table = "car_brand";
		        $field = "cate_id,priority,cate_name,url,topic,title,keyword,description,freetag,h1,h2,parent_id,level,display,menu,position,language,defaults";
		        $value = "	'".$imax."',
		        			'".$data_priority."',
		        			'".$_REQUEST['name']."',
		        			'".$_REQUEST['slug']."',
		        			'".$_REQUEST['topic']."',
		        			'".$_REQUEST['title']."',
		        			'".$_REQUEST['keyword']."',
		        			'".$_REQUEST['description']."',
		        			'".$_REQUEST['freetag']."',
		        			'".$_REQUEST['h1']."',
		        			'".$_REQUEST['h2']."',
		        			'".$_REQUEST['parentId']."',
		        			'".$level."',
		        			'".$_REQUEST['display']."',
		        			'no',
		        			'0',
		        			'".$_SESSION['backend_language']."',
		        			'yes'";
		        $res = $dbcon->insert($table, $field, $value);

		        if ($_REQUEST['priority'] <= $priority && $_REQUEST['priority'] != 0) {
					$table = "car_brand";
			        $set = "priority = priority+1";
			        $where = "parent_id = '".$_REQUEST['parentId']."'
			        		AND priority >= '".$_REQUEST['priority']."'
			        		AND cate_id NOT IN (".$imax.") ";
			        $dbcon->update($table, $set, $where);
		        }

		        $result = array('data' => $res, 'id' => $imax);
        	}else if ($count_url > 0) {
        		$result['data'] = array('message' => 'url_already_exists');
        	}
	        echo json_encode($result);
		break;
		case 'editcategory':
			if ($_REQUEST['slug'] == $_REQUEST['currentUrl']) {
				$count_url = 0;
			}else {
				$sql = " SELECT COUNT(url) AS 'count_url' FROM car_brand WHERE url = '".$_REQUEST['slug']."' ";
        		$count_url = $dbcon->fetch_assoc($sql);
			}

			if ($count_url == 0) {
				if ($_REQUEST['parentId']==0) {
					$level=0;
				}else {
					$sql = "SELECT level FROM car_brand WHERE cate_id='".$_REQUEST['parentId']."'";
					$level = $dbcon->fetch_assoc($sql);
					$level++;
				}

				$sql = "SELECT MAX(priority) pmax FROM car_brand WHERE parent_id='".$_REQUEST['parentId']."'";
				$priority = $dbcon->fetch_assoc($sql);

				if ($_REQUEST['submitType'] == 'edit') {
					$table = "car_brand";
			        $set = "cate_name = '".$_REQUEST['name']."',
			        		title = '".$_REQUEST['title']."',
			        		keyword = '".$_REQUEST['keyword']."',
			        		description = '".$_REQUEST['description']."',
			        		topic = '".$_REQUEST['topic']."',
			        		freetag = '".$_REQUEST['freetag']."',
			        		h1 = '".$_REQUEST['h1']."',
			        		h2 = '".$_REQUEST['h2']."',
			        		url = '".$_REQUEST['slug']."',
			        		display = '".$_REQUEST['display']."',
			        		menu = 'no',
			        		position = '0',
			        		parent_id = '".$_REQUEST['parentId']."',
			        		level = '".$level."'
			        		";
			        $where = "cate_id = '".$_REQUEST['cateId']."'
			        		AND language = '".$_SESSION['backend_language']."'";
			        $res = $dbcon->update($table, $set, $where);

		    	}else if ($_REQUEST['submitType'] == 'add') {
		    		$table = "car_brand";
			        $field = "cate_id,cate_name,thumbnail,url,topic,title,keyword,description,freetag,h1,h2,parent_id,level,display,menu,position,language";
			        $value = "	'".$_REQUEST['cateId']."',
			        			'".$_REQUEST['name']."',
			        			'".$_REQUEST['images']."',
			        			'".$_REQUEST['slug']."',
			        			'".$_REQUEST['topic']."',
			        			'".$_REQUEST['title']."',
			        			'".$_REQUEST['keyword']."',
			        			'".$_REQUEST['description']."',
			        			'".$_REQUEST['freetag']."',
			        			'".$_REQUEST['h1']."',
			        			'".$_REQUEST['h2']."',
			        			'".$_REQUEST['parentId']."',
			        			'".$level."',
			        			'".$_REQUEST['display']."',
			        			'no',
			        			'0',
			        			'".$_SESSION['backend_language']."'";
			        $res = $dbcon->insert($table, $field, $value);
		    	}

		    	$table = "car_brand";
		        $set = "display = '".$_REQUEST['display']."',
			        	menu = '".$_REQUEST['menu']."',
			        	position = '".$_REQUEST['position']."',
		        		parent_id = '".$_REQUEST['parentId']."',
		        		level = '".$level."'
		        		";
		        $where = "cate_id = '".$_REQUEST['cateId']."'";
		        $dbcon->update($table, $set, $where);

		        if ($_REQUEST['parentId'] == $_REQUEST['currentParentId']) {

			        if ($_REQUEST['priority'] < $_REQUEST['currentPriority']) {
			        	$table = "car_brand";
				        $set = "priority = priority+1";
				        $where = "parent_id = '".$_REQUEST['parentId']."'
				        		AND priority >= '".$_REQUEST['priority']."' 
				        		AND priority < '".$_REQUEST['currentPriority']."' 
				        		";
				        $dbcon->update($table, $set, $where);

			        }else if ($_REQUEST['priority'] > $_REQUEST['currentPriority']) {
			        	$table = "car_brand";
				        $set = "priority = priority-1";
				        $where = "parent_id = '".$_REQUEST['parentId']."'
				        		AND priority <= '".$_REQUEST['priority']."' 
				        		AND priority > '".$_REQUEST['currentPriority']."' 
				        		";
				        $dbcon->update($table, $set, $where);
			        }

			        if ($_REQUEST['priority'] == 0) {
						$table = "car_brand";
				        $set = "priority = '".$_REQUEST['currentPriority']."'";
				        $where = "cate_id = '".$_REQUEST['cateId']."'";
				        $dbcon->update($table, $set, $where);
					}
					else if ($_REQUEST['priority'] > $priority) {
						$table = "car_brand";
				        $set = "priority = '".$priority."'";
				        $where = "cate_id = '".$_REQUEST['cateId']."'";
				        $dbcon->update($table, $set, $where);
					}else {
						$table = "car_brand";
				        $set = "priority = '".$_REQUEST['priority']."'";
				        $where = "cate_id = '".$_REQUEST['cateId']."'";
				        $dbcon->update($table, $set, $where);
					}

			    }else {			    	
					if ($priority != null) {

						$table = "car_brand";
				        $set = "priority = priority-1";
				        $where = "parent_id = '".$_REQUEST['currentParentId']."' 
				        		AND priority > '".$_REQUEST['currentPriority']."' 
				        		";
				        $dbcon->update($table, $set, $where);

						$data_priority = $priority+1;
						$table = "car_brand";
				        $set = "priority = '".$data_priority."'";
				        $where = "cate_id = '".$_REQUEST['cateId']."'";
				        $dbcon->update($table, $set, $where);
						
					}else {
						$table = "car_brand";
				        $set = "priority = priority-1";
				        $where = "parent_id = '".$_REQUEST['currentParentId']."' 
				        		AND priority > '".$_REQUEST['currentPriority']."' 
				        		";
				        $dbcon->update($table, $set, $where);

						$table = "car_brand";
				        $set = "priority = '1'";
				        $where = "cate_id = '".$_REQUEST['cateId']."'";
				        $dbcon->update($table, $set, $where);
					}

			    }

		        $result = array('data' => $res);
        	}else if ($count_url > 0) {
        		$result['data'] = array('message' => 'url_already_exists');
        	}
			echo json_encode($result);
        break;
        case 'uploadimgcate':
			$new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
			$images = $data->upload_images($new_folder);

	        $table = "car_brand";
	        $set = "thumbnail = '".$images['0']."'";
	        $where = "cate_id = '".$_REQUEST['id']."'
	        		AND language = '".$_SESSION['backend_language']."'";
	        $result = $dbcon->update($table, $set, $where);
	        echo json_encode($result);
		break;
	}
}
?>