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

        case 'get_bookList':
            $requestData = $_REQUEST;

            $columns = array(
                1 => '_date',
                2 => 'detail'
            );

             $sql = "SELECT *,CONCAT(YEAR(NOW()),'-',auspicious.month_select,'-',auspicious.day_select) AS '_date' FROM auspicious ";

            if (!empty($requestData['search']['value'])) {

                $sql .= " WHERE detail LIKE '%" . $requestData['search']['value'] . "%' ";
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
                  
                    $nestedData = array();
                    $nestedData[] = $i++;
                    $nestedData[] = getData::DateThai($value['_date']);
                    $nestedData[] = $value["detail"];
                    $action =  '<a data-id="' . $value["id"] . '"  class="btn btn-success btn-xs edit_book"><i class="fa fa-pencil-square-o"></i></a> ';
                    
                    
                    if (in_array($_SESSION['role'], array('superamin', 'admin'))) {
                        $action.= ' | <a data-id="' . $value["id"] . '" class="btn btn-danger btn-xs delete_book"><i class="fa fa-trash-o"></i></a>';
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

        case 'get_book':
            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM auspicious WHERE id = '" . $id . "'";
            $result = $dbcon->fetch($sql);
            $result['_date'] = $result['day_select'].'/'.$result['month_select'].'/'.date('Y');
            echo json_encode($result);
        break;  
                    
        case 'add_book':
            $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);
            list($day,$month) = explode('/',$data_post['date']);
            $field = "month_select,
                      day_select,
                      detail";
            $value = "'{$month}',
                      '{$day}',
                      '{$data_post['detail']}'";
            $result = $dbcon->insert('auspicious', $field, $value);
            echo json_encode($result);

        break;

        case 'update_book':
            $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);
            list($day,$month) = explode('/',$data_post['edit-date-book']);

            $setUpdate = "month_select = '{$month}',
                          day_select = '{$day}',
                          detail = '{$data_post['edit_detail']}'";
            $where = " id = {$data_post['edit_id_book']} ";
            $result = $dbcon->update('auspicious', $setUpdate, $where);
            echo json_encode($result);
        
        break;

        
        case 'delete_book':
                $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
                $where = " id = '" . $id . "'";
                $result = $dbcon->delete('auspicious', $where);
                echo json_encode($result);
        break; 

    }
}
