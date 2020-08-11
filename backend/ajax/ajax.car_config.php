
 <?php
session_start();
require_once dirname(__DIR__) . '/classes/class.protected_web.php';
ProtectedWeb::methodPostOnly();
ProtectedWeb::login_only();

require_once dirname(__DIR__) . '/classes/dbquery.php';
require_once dirname(__DIR__) . '/classes/preheader.php';
$dbcon = new DBconnect();
getData::init();

if (isset($_REQUEST['action'])) {

    $lang_config = getData::lang_config();

    switch ($_REQUEST['action']) {

        //======== car type ===========
        case 'get_cartype':
            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM car_type WHERE car_type_id = '" . $id . "'";
            $result = $dbcon->fetch($sql);
            echo json_encode($result);
        break;
        
        case 'add_cartype':
            $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);
            $field = "car_type";
            $value = "'{$data_post['cartype']}'";
            $result = $dbcon->insert('car_type', $field, $value);
            echo json_encode($result);

        break;

        case 'update_cartype':
            $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);
            $setUpdate = "car_type = '{$data_post['cartype']}'";
            $where = "car_type_id = {$data_post['id']} ";
            $result = $dbcon->update('car_type', $setUpdate, $where);
            echo json_encode($result);
        
        break;

        case 'delete_car_type':
            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $where = "car_type_id = '" . $id . "'";
            $result = $dbcon->delete('car_type', $where);
            echo json_encode($result);
        break;


        //======== car brand ===========
        case 'get_carbrand':
                $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
                $sql = "SELECT * FROM car_brand WHERE car_brand_id = '" . $id . "'";
                $result = $dbcon->fetch($sql);
                echo json_encode($result);
        break;
            
        case 'add_carbrand':
                $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);
                $field = "car_brand,car_brand_link";
                $value = "'{$data_post['car_brand']}',
                          '{$data_post['car_brand_link']}'
                        ";
                $result = $dbcon->insert('car_brand', $field, $value);
                echo json_encode($result);
    
        break;
    
        case 'update_carbrand':
                $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);
                $setUpdate = "car_brand = '{$data_post['car_brand']}',
                              car_brand_link = '{$data_post['car_brand_link']}'";
                $where = "car_brand_id = {$data_post['id']} ";
                $result = $dbcon->update('car_brand', $setUpdate, $where);
                echo json_encode($result);
            
        break;
    
        case 'delete_carbrand':
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                $where = "car_brand_id = '" . $id . "'";
                $result = $dbcon->delete('car_brand', $where);
                echo json_encode($result);
        break;

         //======== car color ===========
        case 'get_color':
                $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
                $sql = "SELECT * FROM car_color WHERE car_color_id = '" . $id . "'";
                $result = $dbcon->fetch($sql);
                echo json_encode($result);
        break;
            
        case 'add_color':
                $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);
                $field = "car_color";
                $value = "'{$data_post['color']}'";
                $result = $dbcon->insert('car_color', $field, $value);
                echo json_encode($result);

        break;

        case 'update_color':
                $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);
                $setUpdate = "car_color = '{$data_post['color']}'";
                $where = "car_color_id = {$data_post['id']} ";
                $result = $dbcon->update('car_color', $setUpdate, $where);
                echo json_encode($result);
            
        break;

        case 'delete_color':
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                $where = "car_color_id = '" . $id . "'";
                $result = $dbcon->delete('car_color', $where);
                echo json_encode($result);
        break; 

    }
}
