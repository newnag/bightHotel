<?php
// use function GuzzleHttp\json_encode;

session_start();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
require_once dirname(__DIR__) . '/classes/class.contact_sel.php';
require_once dirname(__DIR__) . '/classes/class.upload.php';
require_once dirname(__DIR__) . '/classes/class.protected_web.php';

$dbcon = new DBconnect();
getData::init();

$mydata = new contact_sel();

if (isset($_REQUEST['action'])) {

    switch ($_REQUEST['action']) {

        case 'getContact':

            ProtectedWeb::methodPostOnly();
            ProtectedWeb::login_only();

            $sql = "SELECT * FROM contact_sel LIMIT 1";
            $result = $dbcon->fetch($sql);
            echo json_encode([
                'message' => 'OK',
                'result'   => $result
            ]);
        break;
        case 'saveContact':
            ProtectedWeb::methodPostOnly();
            ProtectedWeb::login_only();   

            $map = preg_replace("/ width=\"[0-9]*\" /"," width=\"100%\" ",$_POST['map']);

            $table = "contact_sel";
            $set = "name =:name , address =:address , phone =:phone , line =:line, email =:email , map =:map , title =:title , youtube =:youtube , facebook =:facebook ,  ig =:ig , manual_predict =:manual_predict , footer_title =:footer_title  , footer_desc =:footer_desc";
            $where = "id = 1";
            $value = array(
                ':name'    => $_POST['name'],
                ':address' => $_POST['address'],
                ':phone' => $_POST['phone'],
                ':line' => $_POST['line'],
                ':email' => $_POST['email'],
                ':map' => $map,
                ':title' => $_POST['log_text'],
                ':youtube' => $_POST['youtube'],
                ':facebook' => $_POST['facebook'],
                ':ig' => $_POST['ig'],
                ':manual_predict'=>$_POST['predict'],
                ':footer_title'=>$_POST['footer_title'],
                ':footer_desc'=>$_POST['footer_desc'],
            );
            $result = $dbcon->update_prepare($table, $set, $where, $value);
            echo json_encode($result);
        break;
        case 'uploadImageLogo':
            $new_folder = '../../upload/' . date('Y') . '/' . date('m') . '/';
            $images = $mydata->upload_images_thumb($new_folder);
            $table = "contact_sel";
            $set = "thumbnail = :image ";
            $where = "id > 0";
            $value = array(
                ':image' => $images['0']
            );
            $result = $dbcon->update_prepare($table, $set, $where, $value);
            echo json_encode($result);
        break;

    }
}
