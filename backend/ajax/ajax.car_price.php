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

        case 'get_carList':
            $requestData = $_REQUEST;

            $columns = array(
                1 => 'car_model.car_model',
                2 => 'car_brand.car_brand',
                3 => 'car_type.car_type',
                4 => 'car_modal_status',
                5 => 'created_date'
            );

             $sql = "SELECT car_model.created_date,car_model.car_model_id,car_model.car_model,car_brand.car_brand,car_type.car_type,car_model.car_modal_status FROM car_model
                      INNER JOIN car_brand ON car_model.car_brand_id = car_brand.car_brand_id 
                      INNER JOIN car_type ON car_model.car_type_id = car_type.car_type_id ";

            if (!empty($requestData['search']['value'])) {
                $sql .= " WHERE car_model.car_model LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR  car_brand.car_brand LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR  car_type.car_type LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
            } else {
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir']; 
            }

            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            $sql .= "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
            $result = $dbcon->query($sql);
 
            $output = array();
            if ($result) {
                $i = $_POST['start']+1;
                foreach ($result as $value) {

                    $label_status = '';
                    if ($value['car_modal_status'] == 'no') {
                        $label_status = 'label-warning';
                        $value["status"] = 'ไม่เปิดใช้งาน';
                    } else if ($value['car_modal_status'] == 'yes') {
                        $label_status = 'label-success';
                        $value["status"] = 'เปิดใช้งาน';
                    }

                    $nestedData = array();
                    $nestedData[] = $i++;
                    $nestedData[] = $value['car_model'];
                    $nestedData[] = $value["car_brand"];
                    $nestedData[] = $value["car_type"];
                    $nestedData[] = '<span class="label ' . $label_status . '">' . $value["status"] . '</span>';
                    $nestedData[] = getData::DateThai($value["created_date"]);
                    $action = '<a data-id="' . $value["car_model_id"] . '"  class="btn btn-success btn-xs edit_car"><i class="fa fa-pencil-square-o"></i></a>';

                    
                    if (in_array($_SESSION['role'], array('superamin', 'admin'))) {
                        $action .=  ' | <a data-id="' . $value["car_model_id"] . '" class="btn btn-danger btn-xs delete-car"><i class="fa fa-trash-o"></i></a>';
                    }
                    $nestedData[] = $action;
                                                
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

        case 'get_car':
            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM car_model WHERE car_model_id = '" . $id . "'";
            $result = $dbcon->fetch($sql);
            echo json_encode($result);
        break;  
                    
        case 'add_car':
            $data_post = $_POST;
           // print_r($data_post);
            $field = "car_type_id,
                      car_brand_id,
                      car_model,
                      car_model_price,
                      car_modal_status";
            $value = "'{$data_post['cartype']}',
                      '{$data_post['car_brand']}',
                      '{$data_post['car_detail']}',
                      '{$data_post['car_price']}',
                      'yes'";
            $result = $dbcon->insert('car_model', $field, $value);
            echo json_encode($result);

        break;

        case 'update_car':
            $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);
            $setUpdate =    "car_type_id = '{$data_post['edit_car_type']}',
                        car_brand_id = '{$data_post['edit_car_brand']}',
                        car_model = '{$data_post['edit_car_detail']}',
                        car_model_price = '{$data_post['edit_car_price']}',
                        car_modal_status = '{$data_post['edit_car_status']}'";
                        
            $where = "car_model_id = {$data_post['edit_car_id']} ";
            $result = $dbcon->update('car_model', $setUpdate, $where);
            echo json_encode($result);
        
        break;

        
        case 'delete_car':
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                $where = "car_model_id = '" . $id . "'";
                $result = $dbcon->delete('car_model', $where);
                echo json_encode($result);
        break; 

    }
}
