
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

        //=========== รายชื่อ sales ================

        case 'get_advisorList':

            $requestData = $_REQUEST;

            $columns = array(
                0 => 'date_create',
                1 => 'advisor_name',
                3 => 'customer_name',
                7 => 'status',
            );

            $sql = "SELECT * FROM advisor";
          

            if (!empty($requestData['search']['value'])) {
                $sql .= " WHERE advisor_name LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR advisor_phoneNumber LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR customer_name LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR customer_phone LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];

            } else {
                
            }

            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
            $result = $dbcon->query($sql);


            $output = array();
            if ($result) {
                foreach ($result as $value) {

                    $label_status = '';
                    if ($value['advisor_status'] == 'อ่านแล้ว') {
                        $label_status = 'label-success';
                    } else {
                        $label_status = 'label-warning';
                    }

                    $nestedData = array();
                    $nestedData[] = date_format(date_create($value["date_create"]), "d/m/Y - H:i");
                    $nestedData[] = $value['advisor_titleName'] . $value["advisor_name"];
                    $nestedData[] = $value["advisor_phoneNumber"];
                    $nestedData[] = $value['customer_titleName'] . $value["customer_name"];
                    $nestedData[] = $value["customer_phone"];
                    $nestedData[] = $value["customer_province"];
                    $nestedData[] = $value["customer_car"];
                    $nestedData[] = '<span class="label ' . $label_status . '">' . $value["advisor_status"] . '</span>';

                    $action = '<div class="box-tools tdChild" style="text-align: center;">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-bars"></i></button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li><a href="#" class="bt-view"   data-id="' . $value['advisor_id'] . '"><i class="fa  fa-eye  text-black"></i> ดูรายละเอียด</a></li>';

                                    if(in_array($_SESSION['role'],array('superamin','admin'))){
                                        $action .='<li><a href="#" class="bt-delete" data-id="' . $value['advisor_id'] . '"><i class="fa fa-remove  text-red"></i> ลบ</a></li>';
                                    }
            $nestedData[] = $action.'</ul>
                                    </div>
                                </div>';

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

        //=========== จบรายชื่อ advisor ================

        case 'get_advisor':

            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);

            $where = " advisor_id = '{$id}' ";
            $dbcon->update('advisor', " advisor_status = 'อ่านแล้ว' ", $where);

            $sql = "SELECT * FROM advisor WHERE advisor_id = '" . $id . "'";
            $result = $dbcon->fetch($sql);

            echo json_encode($result);
            break;

        case 'get_advisor_print':

            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM advisor WHERE advisor_id = '" . $id . "'";            

            $resultCustomer = $dbcon->fetch($sql);

            

            $table = ' <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2"><center>ข้อมูลผู้แนะนำ</center></td>
                </tr>
                <tr>
                    <th style="width: 150px">ชื่อ - นามสกุล</th>
                    <td>'.$resultCustomer['advisor_titleName'].$resultCustomer['advisor_name'].'</td>
                </tr>
                <tr>
                    <th style="">เบอร์โทร</th>
                    <td>'.$resultCustomer['advisor_phoneNumber'].'</td>
                </tr>
                <tr>
                    <th style="">Line ID</th>
                    <td>'.$resultCustomer['advisor_lineID'].'</td>
                </tr> 
         </tbody>
        </table> 
        <p></p>
         
         <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2"><center>ลูกค้าแนะนำ</center></td>
                </tr>
                <tr>
                    <th style="width: 150px">ชื่อ - นามสกุล</th>
                    <td>'.$resultCustomer['customer_titleName'].$resultCustomer['customer_name'].'</td>
                </tr>
                <tr>
                    <th style="">เบอร์โทร</th>
                    <td>'.$resultCustomer['customer_phone'].'</td>
                </tr>
                <tr>
                    <th style="">Line ID</th>
                    <td>'.$resultCustomer['customer_line'].'</td>
                </tr>
                <tr>
                    <th style="">ยี่ห้อรถยนต์ที่ต้องการ</th>
                    <td>'.$resultCustomer['customer_car'].'</td>
                </tr>
                 <tr>
                 <th style="">จังหวัด</th>
                     <td>'.$resultCustomer['customer_province'].'</td>
                 </tr>
                 
         </tbody>
        </table>';

        echo $table;

            break;

        case 'delete_advisor':
            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $where = "advisor_id = '" . $id . "'";
            $result = $dbcon->delete('advisor', $where);
            echo json_encode($result);
            break;

    }
}
