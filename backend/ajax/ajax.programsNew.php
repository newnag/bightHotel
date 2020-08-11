<?php
// use function GuzzleHttp\json_encode;

session_start();
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.upload.php';
$dbcon = new DBconnect();
getData::init();

if (isset($_REQUEST['action'])) {

    $lang_config = getData::lang_config();

    switch ($_REQUEST['action']) {

        case 'get_programsTable':

            $requestData = $_REQUEST;
            $columns = array(
                0 => '',
                1 => 'id',
                2 => '',
                3 => 'title',
                4 => '',
                5 => '',
                6 => '',
                7 => '',

            );

            $sql = "SELECT * FROM post WHERE category = 6";

            if (!empty($requestData['search']['value'])) {

                $sql .= " AND title LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
            } else {
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
            }

            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
            $result = $dbcon->query($sql);

            $output = array();
            if ($result) {
                foreach ($result as $key => $value) {

                    $nestedData = array();
                    $nestedData[] = '';
                    $nestedData[] = ($key + 1);
                    $nestedData[] = '<center><img style="width:250px;max-height:150px;" src="' . SITE_URL . 'classes/thumb-generator/thumb.php?src=' . ROOT_URL . $value['thumbnail'] . '&size=x100"></center>';
                    $nestedData[] = $value['title'];
                    $nestedData[] = "<a href=\"".$value['short_url']."\">".$value['short_url']."</a>";
                    $nestedData[] = $value['date_created'];
                    $nestedData[] = $value['date_edit'];

                    $nestedData[] = '
                            <a class="btn kt:btn-warning" style="color:white;" onclick="editPrograms(event,' . $value['id'] . ')"><i class="fa fa-pencil-square" aria-hidden="true"></i> แก้ไข</a>
                            <a class="btn kt:btn-danger" onclick="delProgramsById(event,' . $value['id'] . ')" style="color:white;"  ><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a>
                    ';

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

        case 'uploadimgcontent':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            $images = getData::upload_images_thumb($new_folder);

            if (empty($images)) {
                echo json_encode([
                    'event' => 'update',
                    'status' => 200,
                    'message' => "OK",
                    'image' => 'null',
                ]);
            } else {
                $table = "post";
                $set = "thumbnail = '" . $images['0'] . "'";
                $where = "id = '" . $_REQUEST['id'] . "'";
                $result = $dbcon->update($table, $set, $where);
                echo json_encode($result);
            }

            break;

        case 'delProgramsById':

            $id = isset($_POST['id']) ? $_POST['id'] : null;
            if (empty($id)) {
                echo json_encode([
                    'message' => 'Error',
                    'detail' => 'empty',
                ]);
            }

            if (!is_numeric($id)) {
                echo json_encode([
                    'message' => 'Error',
                    'detail' => 'number_invalid',
                ]);exit();
            }

            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

            $table = "post";
            $where = "id = :id";
            $val = array(
                ':id' => $id,
            );
            $res = $dbcon->deletePrepare($table, $where, $val);
            echo json_encode($res);

            break;
        case 'getProgramsById':

            $id = isset($_POST['id']) ? $_POST['id'] : null;
            if (empty($id)) {
                echo json_encode([
                    'message' => 'Error',
                    'detail' => 'empty',
                ]);
            }

            if (!is_numeric($id)) {
                echo json_encode([
                    'message' => 'Error',
                    'detail' => 'number_invalid',
                ]);exit();
            }

            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT id , title , short_url , thumbnail FROM post WHERE id =:id LIMIT 1";
            $value = array(
                ":id" => $id,
            );
            $res = $dbcon->fetchObject($sql, $value);

            if ($res) {
                echo json_encode([
                    'message' => "OK",
                    'res' => $res,
                ]);exit();
            } else {
                echo json_encode([
                    'message' => 'Error',
                    'detail' => 'Nodata',
                ]);exit();
            }
            break;

        case 'add_programs':
            $name = isset($_POST['name']) ? $_POST['name'] : null;
            $url = isset($_POST['url']) ? $_POST['url'] : null;

            if (empty($name) || empty($url)) {
                echo json_encode([
                    'message' => 'Error',
                    'detail' => 'Empty',
                ]);exit();
            }

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                echo json_encode([
                    'message' => 'Error',
                    'detail' => 'url_invalid',
                ]);exit();
            }

            $maxId = $dbcon->fetchObject("SELECT MAX(id)+1 as maxID FROM post ", []);

            $sql = "INSERT INTO post
                    (id,title,keyword,description,slug,freetag,h1,h2,short_url,thumbnail,video,category,topic,display,date_created,date_edit,author,defaults)
                    VALUES
                    (:id,:title,:keyword,:description,:slug,:freetag,:h1,:h2,:short_url,:thumbnail,:video,:category,:topic,:display,:date_created,:date_edit,:author,:defaults)";
            $value = array(
                ":id" => $maxId->maxID,
                ":title" => $name,
                ":keyword" => $name,
                ":description" => $name,
                ":slug" => $name . time(),
                ":freetag" => '-',
                ":h1" => '-',
                ":h2" => '-',
                ":short_url" => $url,
                ":thumbnail" => 'ready-img',
                ":video" => '',
                ":category" => 6,
                ":topic" => $name,
                ":display" => 'yes',
                ":date_created" => date('Y-m-d H:i:s'),
                ":date_edit" => date('Y-m-d H:i:s'),
                ":author" => $_SESSION['login_user_id'],
                ":defaults" => "yes",
            );
            $res = $dbcon->insertValue($sql, $value);
            echo json_encode([
                'res' => $res,
                'insert_id' => $maxId->maxID,
            ]);
            break;
        case 'edit_programs':
            $name = isset($_POST['name']) ? $_POST['name'] : null;
            $url = isset($_POST['url']) ? $_POST['url'] : null;
            $id = isset($_POST['id']) ? $_POST['id'] : null;

            if (empty($name) || empty($url) || empty($id)) {
                echo json_encode([
                    'message' => 'Error',
                    'detail' => 'Empty',
                ]);exit();
            }

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                echo json_encode([
                    'message' => 'Error',
                    'detail' => 'url_invalid',
                ]);exit();
            }

            if(!is_numeric($id)){
                echo json_encode([
                    'message' => 'Error',
                    'detail' => 'number_invalid',
                ]);exit();
            }

            $table = "post";
            $set = "title =:title , short_url =:url";
            $where = "id =:id ";
            $value = array(
                ":id" => $id,
                ":title" => $name,
                ":url" => $url
            );
            $res = $dbcon->update_prepare($table, $set, $where, $value);
            
            echo json_encode([
                'res' => $res,
                'insert_id' => $id,
            ]);
            break;
    }
}