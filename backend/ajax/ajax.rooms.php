<?php	
session_start();

include '../config/database.php';
require_once('../classes/dbquery.php');
require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.rooms.php');

$dbcon = new DBconnect();
$data = new getData();
$mydata = new rooms();

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
		case'getcategorycontent':
			$category = $mydata->get_category();
            $category_right = $mydata->get_category_tree($category);
            echo json_encode($category_right);
		break;
		case'getpaginationcontent':

			$cate_src = '';
			if($_REQUEST['cate'] != ''){
				$cate_src = " AND category LIKE '%,".$_REQUEST['cate'].",%'";
			}

			$status = '';
			if($_REQUEST['display'] != ''){
				$status = " AND display = '".$_REQUEST['display']."'";
			}

			$search = '';
			if($_REQUEST['search'] != ''){
				$search = " AND title LIKE '%".$_REQUEST['search']."%'";
			}

        	$table = "rooms";
			$where = "defaults = 'yes'".$search.$cate_src.$status;
        	$result = $data->pagination($table,$where);
        	echo ($result);
        break;
        case'getcontent':	
			$lan_arr = $data->get_language_array();
			$sql = "SELECT * FROM rooms WHERE id = :id ORDER BY FIELD(defaults ,'yes')DESC";
			$stmt = $dbcon->runQuery($sql);
            $stmt->execute(array(':id'=>$_REQUEST['id']));
            $result=$stmt->fetchAll(PDO::FETCH_ASSOC);

			$content = array();
			$ret = array();
			$tag = array();

			foreach($result as $a){
				if($a['defaults']=='yes'){
					$content['defaults']=$a;
				}
				$content[$a['language']]=$a;
			}

			foreach($content as $b => $c){
				if($b != 'defaults')
					if(in_array($b,$lan_arr))
						$lang_info .= ','.$c['language'];
				if($b == 'defaults')
					$ret=$c;
				if($b == $_SESSION['backend_language'])
					$ret=$c;

			}
			$ret['lang_info'] = $lang_info;
			$lang_info = '';
	
			if ($ret['tag'] != '') {
				$alltag = explode(',',$ret['tag']);
				for($i=1;$i < count($alltag)-1 ;$i++){
					$sql = "SELECT tag_name,tag_id FROM rooms_amenities WHERE tag_id = '".$alltag[$i]."'";
					$tag[] = $dbcon->query($sql);
				}
			}

			$sql = "SELECT * FROM rooms_image WHERE post_id = '".$_REQUEST['id']."' ORDER BY position ASC";
			$res = $dbcon->query($sql);
			if ($res == false) {
				$res = 'no_image';
			}

			$return_arr = array("data" => [$ret], 'tag' => $tag, 'images' => $res);
			echo json_encode($return_arr);
		break;
        case'addcontent':
        	$sql = " SELECT COUNT(slug) AS 'count_url_post' FROM rooms WHERE slug = '".$_REQUEST['slug']."' ";
        	$count_url_post = $dbcon->fetch_assoc($sql);

        	if ($count_url_post == 0) {
				$sql = "SELECT MAX(id) FROM rooms";
				$imax = $dbcon->fetch_assoc($sql);
				$imax++;

				$table = "rooms";
		        $field = "id,title,keyword,description,slug,topic,freetag,thumbnail,h1,h2,video,category,tag,content,saleprice,specialprice,link_fb,link_tw,link_ig,display,date_created,date_edit,date_display,author,post_view,comment_allow,comment_count,pin,language,defaults";
		        $value = "	'".$imax."',
		        			'".$_REQUEST['title']."',
		        			'".$_REQUEST['keyword']."',
		        			'".$_REQUEST['description']."',
		        			'".$_REQUEST['slug']."',
		        			'".$_REQUEST['topic']."',
		        			'".$_REQUEST['freetag']."',
		        			'',
		        			'".$_REQUEST['h1']."',
		        			'".$_REQUEST['h2']."',
		        			'".$_REQUEST['video']."',
		        			'".$_REQUEST['cateid']."',
		        			'".$_REQUEST['tag']."',
		        			'".$_REQUEST['content']."',
		        			'0',
		        			'0',
		        			'',
		        			'',
		        			'',
		        			'".$_REQUEST['display']."',
		        			'".date('Y-m-d H:i:s')."',
		        			'".date('Y-m-d H:i:s')."',
		        			'".$_REQUEST['dateDisplay']."',
		        			'".$_SESSION['user_id']."',
		        			'0',
		        			'no',
		        			'0',
		        			'".$_REQUEST['pin']."',
		        			'".$_SESSION['backend_language']."',
		        			'yes'";
		        $res = $dbcon->insert($table, $field, $value);

		        $table = "rooms_image";
		        $set = "status = 'publish',
		        		post_id = '".$imax."'";
			    $where = "post_id = 0";
			    $dbcon->update($table, $set, $where);

		        $result = array('data' => $res, 'id' => $imax);

		    }else {
        		$result['data'] = array('message' => 'url_already_exists');
        	}
	        echo json_encode($result);
		break;
		case'editcontent':
			if ($_REQUEST['slug'] == $_REQUEST['currentUrl']) {
				$count_url_cate = 0;
				$count_url_post = 0;
			}else {
	        	$sql = " SELECT COUNT(slug) AS 'count_url_post' FROM rooms WHERE slug = '".$_REQUEST['slug']."' ";
	        	$count_url_post = $dbcon->fetch_assoc($sql);
			}

			if ($count_url_post == 0) {

				if ($_REQUEST['submitType'] == 'edit') {
					$table = "rooms";
			        $set = "title = '".$_REQUEST['title']."',
			        		keyword = '".$_REQUEST['keyword']."',
			        		description = '".$_REQUEST['description']."',
			        		slug = '".$_REQUEST['slug']."',
			        		topic = '".$_REQUEST['topic']."',
			        		freetag = '".$_REQUEST['freetag']."',
			        		h1 = '".$_REQUEST['h1']."',
			        		h2 = '".$_REQUEST['h2']."',
			        		video = '".$_REQUEST['video']."',
			        		category = '".$_REQUEST['cateid']."',
			        		tag = '".$_REQUEST['tag']."',
			        		content = '".$_REQUEST['content']."',
			        		link_fb = '".$_REQUEST['linkfb']."',
			        		link_tw = '".$_REQUEST['linktw']."',
			        		link_ig = '".$_REQUEST['linkig']."',
			        		display = '".$_REQUEST['display']."',
			        		date_edit = '".date('Y-m-d H:i:s')."',
			        		date_display = '".$_REQUEST['dateDisplay']."',
			        		author = '".$_SESSION['user_id']."',
			        		pin = '".$_REQUEST['pin']."'
			        		";
			        $where = "id = '".$_REQUEST['id']."'
			        		AND language = '".$_SESSION['backend_language']."'";
			        $res = $dbcon->update($table, $set, $where);

			    }else if ($_REQUEST['submitType'] == 'add') {
			    	$table = "rooms";
			        $field = "id,title,keyword,description,slug,topic,freetag,thumbnail,h1,h2,video,category,tag,content,saleprice,specialprice,link_fb,link_tw,link_ig,display,date_created,date_edit,date_display,author,post_view,comment_allow,comment_count,pin,language";
			        $value = "	'".$_REQUEST['id']."',
			        			'".$_REQUEST['title']."',
			        			'".$_REQUEST['keyword']."',
			        			'".$_REQUEST['description']."',
			        			'".$_REQUEST['slug']."',
			        			'".$_REQUEST['topic']."',
			        			'".$_REQUEST['freetag']."',
			        			'".$_REQUEST['images']."',
			        			'".$_REQUEST['h1']."',
			        			'".$_REQUEST['h2']."',
			        			'".$_REQUEST['video']."',
			        			'".$_REQUEST['cateid']."',
			        			'".$_REQUEST['tag']."',
			        			'".$_REQUEST['content']."',
			        			'0',
			        			'0',
			        			'".$_REQUEST['linkfb']."',
			        			'".$_REQUEST['linktw']."',
			        			'".$_REQUEST['linkig']."',
			        			'".$_REQUEST['display']."',
			        			'".$_REQUEST['created']."',
			        			'".date('Y-m-d H:i:s')."',
			        			'".$_REQUEST['dateDisplay']."',
			        			'".$_SESSION['user_id']."',
			        			'0',
			        			'no',
			        			'0',
			        			'".$_REQUEST['pin']."',
			        			'".$_SESSION['backend_language']."'";
			        $res = $dbcon->insert($table, $field, $value);
			    }

		        $table = "rooms_image";
		        $set = "status = 'publish'";
			    $where = "post_id = '".$_REQUEST['id']."'";
			    $dbcon->update($table, $set, $where);

			    $ret[] = array('image_id' => $res['insert_id'], 'image_link' => $img_link);

		        $result = array('data' => $res);
	    	}else {
        		$result['data'] = array('message' => 'url_already_exists');
        	}
	        echo json_encode($result);
		break;
		case 'uploadimgcontent':
			$new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
			// $images = $data->upload_images($new_folder);
			$images = $data->upload_images_thumb($new_folder);

	        $table = "rooms";
	        $set = "thumbnail = '".$images['0']."'";
	        $where = "id = '".$_REQUEST['id']."'
	        		AND language = '".$_SESSION['backend_language']."'";
	        $result = $dbcon->update($table, $set, $where);
	        echo json_encode($result);
		break;
        case 'uploadmoreimgcontent':
			$new_folder = '../../upload/'.date('Y').'/'.date('m').'/';
			// $images = $data->upload_images($new_folder);
			$images = $data->upload_images_thumb($new_folder);

	        $sql = "SELECT MAX(position) max FROM rooms_image WHERE post_id = '".$_REQUEST['id']."'";
			$max = $dbcon->fetch_assoc($sql);
			$max++;

	        $ret=array();
	        foreach ($images as $key => $img_link) {
		        $table = "rooms_image";
			    $field = "post_id, image_link, position, language, status";
			    $value = "	'".$_REQUEST['id']."',
			    			'".$img_link."',
			    			'".($key+$max)."',
			    			'',
			    			'draft'";
			    $res = $dbcon->insert($table, $field, $value);
			    $ret[] = array('image_id' => $res['insert_id'], 'image_link' => $img_link);
			}
			echo json_encode($ret);
		break;
		case 'deleteimagedraft':
			$sql = "SELECT * FROM rooms_image WHERE status = 'draft' ";
			$ret = $dbcon->query($sql);

			if ($ret != false) {
				foreach ($ret as $key => $value) {
					unlink('../../'.$value['image_link']);
				}
				$table = "post_image";
			    $where = "status = 'draft'";
			    $ret = $dbcon->delete($table, $where);
			}
		break;
		case 'deleteimagecontent':
			unlink('../../'.$_REQUEST['filename']);
			$table = "rooms_image";
		    $where = "image_id = '".$_REQUEST['id']."'";
		    $ret = $dbcon->delete($table, $where);

		    $sql = "SELECT * FROM rooms_image WHERE post_id = '".$_REQUEST['postId']."' ORDER BY position ASC";
			$res = $dbcon->query($sql);
			if ($res == false) {
				$res = 'no_image';
			}
			$return_arr = array('images' => $res);
			echo json_encode($return_arr);
		break;
		case'searchtag':
			$sql = "SELECT tag_id,tag_name FROM rooms_amenities WHERE tag_name LIKE '%".$_REQUEST['key']."%'";
			$result = $dbcon->query($sql);
			echo json_encode($result);
		break;
		case'addtag':
			$sql = "SELECT tag_name FROM rooms_amenities WHERE tag_name = '".$_REQUEST['key']."'";
			$stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $result=$stmt->fetch(PDO::FETCH_ASSOC);
			if($result){
				$result = array('data' => 'exist');
			}else{
				$table = "rooms_amenities";
		        $field = "tag_name,post_count,display,language";
		        $value = " '".$_REQUEST['key']."',0,'yes','".$_SESSION['backend_language']."'";
		        $res = $dbcon->insert($table, $field, $value);
		        $result = array('data' => $res);
			}
			echo json_encode($result);
		break;
		case'deletecontent':
	      $table = "rooms";
	      $where = "id = '".$_REQUEST['id']."'";
	      $result = $dbcon->delete($table, $where);
	      echo json_encode($result);
	    break;
	}
}
?>