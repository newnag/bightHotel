
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

        //=========== รายชื่อ customer ================

        case 'get_buyList':

            $requestData = $_REQUEST;
            $columns = array(
                0 => 'bc_date',
                1 => 'customer.name',
                2 => 'customer.phoneNumber',
                3 => 'province.province_name',
                4 => 'sales.name',
                5 => 'sales.phone',
                6 => 'car_model.car_model',
            );

            $sql = "SELECT customer.id as 'customer_id',
                        sales.title,
                        sales.name,
                        sales.phone,
                        customer.titleName as 'titleCustomer',
                        customer.name as 'nameCustomer',
                        customer.phoneNumber as 'phoneCustomer',
                        province.province_name,
                        car_model.car_model,bc_date,
                        buy_customer.bc_status,bc_id
                FROM buy_customer
                LEFT JOIN customer ON buy_customer.bc_customerID = customer.id
                LEFT JOIN sales ON buy_customer.bc_salesID = sales.id
                INNER JOIN car_model ON car_model.car_model_id = customer.car_model
                INNER JOIN province ON customer.province = province.id";

            if (!empty($requestData['search']['value'])) {
                $sql .= "  AND ( sales.phone LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR sales.name LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR province_name LIKE '%" . $requestData['search']['value'] . "%' )";
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
                foreach ($result as $value) {

                    $label_status = '';
                    if ($value['bc_status'] == 'อ่านแล้ว') {
                        $label_status = 'label-success';
                    } else {
                        $label_status = 'label-warning';
                    }

                    $nestedData = array();
                    $nestedData[] = getData::DateThai($value["bc_date"]);
                    $nestedData[] = $value['titleCustomer'] . $value['nameCustomer'];
                    $nestedData[] = $value['titleCustomer'] . $value['phoneCustomer'];
                    $nestedData[] = $value["province_name"];
                    $nestedData[] = $value['title'] . $value['name'];
                    $nestedData[] = $value['phone'];
                    $nestedData[] = $value["car_model"];
                    $nestedData[] = '<span class="label ' . $label_status . '">' . $value["bc_status"] . '</span>';

                    $action = '<div class="box-tools tdChild" style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i></button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#" class="bt-view"   data-id="' . $value['bc_id'] . '" ><i class="fa fa-file-text-o text-green"></i> ดูรายละเอียด</a></li>';

                                if(in_array($_SESSION['role'],array('superamin','admin'))){
                                    $action .=' <li><a href="#" class="bt-delete" data-id="' . $value['bc_id'] . '"><i class="fa fa-remove text-red"></i> ลบ</a></li>';
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

        //=========== จบรายชื่อ customer ================
   
        case 'get_customer_print':
            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);

            //update view
            $where = " bc_id = '{$id}' ";
            $dbcon->update('buy_customer', " bc_status = 'อ่านแล้ว' ", $where);

            $sql = "SELECT customer.*,car_model.car_model,car_model.car_model_price,car_color.car_color,car_brand.car_brand,car_type.car_type,province_name,
                        CONCAT(sales.title,sales.NAME) AS 'salesName' , sales.phone AS 'salesPhone',sales.line AS 'salesLine'
                        FROM buy_customer
                    INNER JOIN customer ON customer.id = buy_customer.bc_customerID
                    INNER JOIN sales ON sales.id = buy_customer.bc_salesID
                    INNER JOIN car_model ON customer.car_model = car_model.car_model_id
                    INNER JOIN car_type ON car_model.car_type_id = car_type.car_type_id
                    INNER JOIN car_brand ON car_model.car_brand_id = car_brand.car_brand_id
                    INNER JOIN car_color ON customer.car_color = car_color.car_color_id
                    INNER JOIN province ON customer.province = province.id

            WHERE buy_customer.bc_id='{$id}'";
            $resultCustomer = $dbcon->fetch($sql);

            $table = ' <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2"><center>ข้อมูลลูกค้า</center></td>
                </tr>
                <tr>
                    <th style="width: 150px">ชื่อ - นามสกุล</th>
                    <td>'.$resultCustomer['titleName'].$resultCustomer['name'].'</td>
                </tr>
                <tr>
                    <th style="">เบอร์โทร</th>
                    <td>'.$resultCustomer['phoneNumber'].'</td>
                </tr>
                <tr>
                    <th style="">Line ID</th>
                    <td>'.$resultCustomer['lineID'].'</td>
                </tr>
                <tr>
                    <th style="">ประเภทรถยนต์</th>
                    <td>'.$resultCustomer['car_type'].' - '.$resultCustomer['car_brand'].' ( สี '.$resultCustomer['car_color'].' )</td>
                </tr>
                 <tr>
                 <th style="">รุ่น</th>
                     <td>'.$resultCustomer['car_model'].'  ( ราคา '.number_format($resultCustomer['car_model_price']).' บาท )</td>
                 </tr>
                 <tr>
                    <th style="">ดาวน์</th>
                    <td>'.$resultCustomer['downPaymentPercent'].' % </td>
                 </tr>
                 <tr>
                    <th style="">จำนวนเงินดาวน์</th>
                    <td>'.number_format($resultCustomer['downPayment']).' บาท</td>
                 </tr>
                 <tr>
                    <th style="">ผ่อนชำระ</th>
                    <td>'.$resultCustomer['installment'].' งวด</td>
                </tr>
                <tr>
                    <th style="">สิ่งที่ลูกค้าต้องการ</th>
                    <td>'.$resultCustomer['customerRequire'].' งวด</td>
                </tr>
                 
         </tbody>
       
        </table>
      
         <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2"><center>ข้อมูลพนักงานฝ่ายขาย</center></td>
                </tr>
                <tr>
                    <th style="width: 150px">ชื่อ - นามสกุล</th>
                    <td>'.$resultCustomer['salesName'].'</td>
                </tr>
                <tr>
                    <th style="">เบอร์โทร</th>
                    <td>'.$resultCustomer['salesPhone'].'</td>
                </tr>
                <tr>
                    <th style="">Line ID</th>
                    <td>'.$resultCustomer['salesLine'].'</td>
                </tr>
         </tbody>
        </table>';
        echo $table;
            

            break;

        case 'delete_buy':

            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $where = "bc_id = '" . $id . "'";
            $result = $dbcon->delete('buy_customer', $where);

            //ลบแบบตอบกลับลูกค้าด้วย
            echo json_encode($result);

            break;
    }
}
