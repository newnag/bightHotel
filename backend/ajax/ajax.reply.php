
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

        //====== รายการตอบกลับลูกค้า ของ customer =======

        case 'get_replyList':

            $requestData = $_REQUEST;

            $columns = array(
                0 => 'date_create',
                1 => 'nameCustomer',
                3 => 'province.province_name',
                4 => 'sales.name',
                6 => 'car_model.car_model',
                7 => 'reply_status',
            );

            $sql = "SELECT customer.id as 'customer_id',customer_reply.id,customer_reply.date_create,
                                `customer_reply`.`titleNameSales` AS `titleNameSales`,
                                `customer_reply`.`nameSales` AS `nameSales`,
                                `customer_reply`.`phoneSales` AS `phoneSales`,
                                `customer_reply`.`sales_id` AS `sales_id`,
                                reply_status,
                                sales.title,
                                sales.name,
                                sales.phone,
                                customer.titleName as 'titleCustomer',
                                customer.name as 'nameCustomer',
                                customer.phoneNumber as 'phoneCustomer',
                                province.province_name,
                                car_model.car_model
                        FROM customer_reply
                        LEFT JOIN  customer ON customer.id = customer_reply.customer_id
                        LEFT JOIN car_model ON car_model.car_model_id = customer.car_model
                        LEFT JOIN  sales ON sales.id =  customer_reply.sales_id
                        LEFT JOIN province ON customer.province = province.id";

            if (!empty($requestData['search']['value'])) {
                $sql .= "  AND ( nameCustomer LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR sales.name LIKE '%" . $requestData['search']['value'] . "%' ";
                $sql .= " OR province_name LIKE '%" . $requestData['search']['value'] . "%' )";
            }

            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'];
            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
            $result = $dbcon->query($sql);

            $output = array();
            if ($result) {
                foreach ($result as $value) {
                    $nameSales = '';
                    $phone = '';
                    if ($value['sales_id'] == 0) {
                        $nameSales = $value['titleSales'] . $value['nameSales'];
                        $phone = $value['phoneSales'];
                    } else {
                        $nameSales = $value['title'] . $value['name'];
                        $phone = $value['phone'];
                    }

                    $label_status = '';
                    if ($value['reply_status'] == 'อ่านแล้ว') {
                        $label_status = 'label-success';
                    } else {
                        $label_status = 'label-warning';
                    }

                    $nestedData = array();
                    $nestedData[] = date_format(date_create($value["date_create"]), "d/m/Y - H:i");
                    $nestedData[] = $value['titleCustomer'] . $value['nameCustomer'];
                    $nestedData[] = $value['phoneCustomer'];
                    $nestedData[] = $value["province_name"];
                    $nestedData[] = $nameSales;
                    $nestedData[] = $phone;
                    $nestedData[] = $value["car_model"];
                    $nestedData[] = '<span class="label ' . $label_status . '">' . $value["reply_status"] . '</span>';

                    $action = '<div class="box-tools tdChild" style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i></button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                         <li><a href="#" class="bt-detail-reply"   data-customer-id="' . $value['customer_id'] . '" data-id="' . $value['id'] . '"><i class="fa fa-file-text-o text-green"></i> ดูรายละเอียด</a></li>';

                    if (in_array($_SESSION['role'], array('superamin', 'admin'))) {
                        $action .= '  <li><a href="#" class="bt-delete" data-id="' . $value['id'] . '"  data-customer="' . $value['titleCustomer'] . $value['nameCustomer'] . '"><i class="fa fa-remove text-red"></i> ลบ</a></li>';
                    }
                    $nestedData[] = $action . '</ul>
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

        //====== จบรายการตอบกลับลูกค้า ของ customer =======

        case 'get_reply_print':

            $customer_id = filter_var($_REQUEST['customer_id'], FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT customer.*,car_model.car_model,car_model.car_model_price,car_color.car_color,car_brand.car_brand,car_type.car_type,province_name FROM customer
                    INNER JOIN car_model ON customer.car_model = car_model.car_model_id
                    INNER JOIN car_type ON car_model.car_type_id = car_type.car_type_id
                    INNER JOIN car_brand ON car_model.car_brand_id = car_brand.car_brand_id
                    INNER JOIN car_color ON customer.car_color = car_color.car_color_id
                    INNER JOIN province ON customer.province = province.id
            WHERE customer.id='{$customer_id}'";
            $resultCustomer = $dbcon->fetch($sql);

            $reply_id = filter_var($_REQUEST['reply_id'], FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT
                        customer_reply.*,
                        sales.title,sales.name AS name , sales.phone,sales.line,
                        province.province_name
                FROM customer_reply
                INNER JOIN  customer ON customer_reply.customer_id = customer.id
                LEFT JOIN  sales ON sales.id =  customer_reply.sales_id
                LEFT JOIN province ON sales.province = province.id
                WHERE customer_reply.id = '{$reply_id}'";

            $resultReply = $dbcon->fetch($sql);
            $resultReply['listPrice'] = json_decode($resultReply['listPrice'], true);

            //update view
            $where = " id = '{$reply_id}' ";
            $dbcon->update('customer_reply', " reply_status = 'อ่านแล้ว' ", $where);

            if ($resultReply['sales_id'] == 0) {
                $nameSales = $resultReply['titleSales'] . $resultReply['nameSales'];
                $phoneSales = $resultReply['phoneSales'];
                $lineIdSales = $resultReply['lineSales'];
            } else {
                $nameSales = $resultReply['title'] . $resultReply['name'];
                $phoneSales = $resultReply['phone'];
                $lineIdSales = $resultReply['line'];
            }

            $installMentOther = '';
            if ($resultReply['installMentOther'] > 0) {
                $installMentOther = '<p><strong>' . $resultReply['installMentOther'] . ' งวด</strong>  งวดละ ' . $resultReply['installMentOtherVal'] . ' บาท  ดอกเบี้ย ' . $resultReply['interestOtherVal'] . ' %</p>';
            }

            $listPrice = '';
            $iprice = 1;
            if (count($resultReply['listPrice']) > 0) {
                $sumPrice = 0;
                foreach ($resultReply['listPrice'] as $value) {
                    $listPrice .= '<p>' . $iprice++ . '. ' . $value['menu'] . ' ' . $value['price'] . ' บาท</p>';
                    $sumPrice += $value['price'];
                }

                $listPrice .= '<p>สรุปค่าใช้จ่ายวันรับรถยนต์ ' . $sumPrice . ' บาท</p>';
            }

            $otherDetail = '';

            //carInStore
            if ($resultReply['carInStore'] != '') {
                $otherDetail .= '<p>'.$resultReply['carInStore'] .'</p>';
            }

            //car_come
            if ($resultReply['car_come'] != '0000-00-00') {
                $otherDetail .= '<p>รถจะเข้ามาวันที่ <span class="label label-warning">' . getData::DateThai($resultReply['car_come']) . '</span></p>';
            }

            //imageCar
            if ($resultReply['imageCar'] != '') {
                $imgCar = explode(',', $resultReply['imageCar']);
                $coutImg = count($imgCar);
                for ($i=0; $i < $coutImg ; $i++) { 
                    $otherDetail .= '<img style="width:150px;" src="'.ROOT_URL.$imgCar[$i].'"> ';
                }
            }

            $table_reply = ' <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><center>ข้อมูลลูกค้า</center></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 150px">ชื่อ - นามสกุล</th>
                                        <td>' . $resultCustomer['titleName'] . $resultCustomer['name'] . '</td>
                                    </tr>
                                    <tr>
                                        <th style="">เบอร์โทร</th>
                                        <td>' . $resultCustomer['phoneNumber'] . '</td>
                                    </tr>
                                    <tr>
                                        <th style="">Line ID</th>
                                        <td>' . $resultCustomer['lineID'] . '</td>
                                    </tr>
                                    <tr>
                                        <th style="">ประเภทรถยนต์</th>
                                        <td>' . $resultCustomer['car_type'] . ' - ' . $resultCustomer['car_brand'] . ' ( สี ' . $resultCustomer['car_color'] . ' )</td>
                                    </tr>
                                     <tr>
                                     <th style="">รุ่น</th>
                                         <td>' . $resultCustomer['car_model'] . '  ( ราคา ' . number_format($resultCustomer['car_model_price']) . ' บาท )</td>
                                     </tr>
                                     <tr>
                                        <th style="">ดาวน์</th>
                                        <td>' . $resultCustomer['downPaymentPercent'] . ' % </td>
                                     </tr>
                                     <tr>
                                        <th style="">จำนวนเงินดาวน์</th>
                                        <td>' . number_format($resultCustomer['downPayment']) . ' บาท</td>
                                     </tr>
                                     <tr>
                                        <th style="">ผ่อนชำระ</th>
                                        <td>' . $resultCustomer['installment'] . ' งวด</td>
                                    </tr>
                                    <tr>
                                        <th style="">สิ่งที่ลูกค้าต้องการ</th>
                                        <td>' . $resultCustomer['customerRequire'] . ' งวด</td>
                                    </tr>

                             </tbody>

                            </table>


                            <p></p>

                             <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><center>ข้อมูลพนักงานฝ่ายขาย</center></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 150px">ชื่อ - นามสกุล</th>
                                        <td>' . $nameSales . '</td>
                                    </tr>
                                    <tr>
                                        <th style="">เบอร์โทร</th>
                                        <td>' . $phoneSales . '</td>
                                    </tr>
                                    <tr>
                                        <th style="">Line ID</th>
                                        <td>' . $lineIdSales . '</td>
                                    </tr>
                                    <tr>
                                        <th style="">ดาวน์</th>
                                        <td>' . $resultReply['percentPayment'] . ' %</td>
                                    </tr>
                                     <tr>
                                     <th style="">จำนวนเงินดาวน์</th>
                                         <td>' . number_format($resultReply['downPayment']) . ' บาท </td>
                                     </tr>
                                     <tr>
                                        <th style="">งวดผ่อนชำระ</th>
                                        <td>
                                            <p><strong>48 งวด</strong> งวดละ ' . $resultReply['installMent48'] . ' บาท  ดอกเบี้ย ' . $resultReply['interest48'] . ' %</p>
                                            <p><strong>60 งวด</strong> งวดละ ' . $resultReply['installMent60'] . ' บาท  ดอกเบี้ย ' . $resultReply['interest60'] . ' %</p>
                                            <p><strong>72 งวด</strong> งวดละ ' . $resultReply['installMent72'] . ' บาท  ดอกเบี้ย ' . $resultReply['interest72'] . ' %</p>
                                            <p><strong>84 งวด</strong> งวดละ ' . $resultReply['installMent84'] . ' บาท  ดอกเบี้ย ' . $resultReply['interest84'] . ' %</p>
                                            ' . $installMentOther . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="">ส่วนลด</th>
                                        <td>' . number_format($resultReply['discountMoney']) . ' บาท</td>
                                     </tr>
                                     <tr>
                                        <th style="">ของแถม</th>
                                        <td>' . ($resultReply['bonusFree'] == "" ? '-' : $resultReply['bonusFree']). '</td>
                                    </tr>
                                    <tr>
                                        <th style="">ค่าใช้จ่าย</th>
                                        <td>' . ($listPrice == "" ? '-' : $listPrice) . '</td>
                                    </tr>

                                    <tr>
                                        <th style="">รายละเอียดเพิ่มเติม</th>
                                        <td>' . ($otherDetail == "" ? '-' : $otherDetail) . '</td>
                                    </tr>
                             </tbody>
                            </table>

                            ';
            echo $table_reply;

            break;

        case 'delete_reply':
            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $where = "id = '" . $id . "'";
            $result = $dbcon->delete('customer_reply', $where);
            echo json_encode($result);
            break;

    }
}
