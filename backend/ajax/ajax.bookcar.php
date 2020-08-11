
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

        case 'get_bookcarList':

            $requestData = $_REQUEST;

            $columns = array(
                0 => 'date_create',
                1 => 'name',
                2 => 'phoneNumber',
                3 => 'province_name',
                4 => 'status',
            );

            $sql = "SELECT bookcar.*,DATEDIFF(CONCAT(bookcar.date_receive,' 00:00:00'),NOW()) AS 'expire',province_name FROM bookcar
                        INNER JOIN province on bookcar.province = province.id";
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            if (!empty($requestData['search']['value'])) {

                $sql .= " WHERE phoneNumber LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " OR name LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " OR province_name LIKE '" . $requestData['search']['value'] . "%' ";
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                $result = $dbcon->query($sql);

            } else {
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                $result = $dbcon->query($sql);
            }

            $output = array();
            if ($result) {
                foreach ($result as $value) {

                    $label_status = '';
                    if ($value['status'] == 'อ่านแล้ว') {
                        $label_status = 'label-success';
                    } else {
                        $label_status = 'label-warning';
                    }

                    if ($value['expire'] < 0) {
                        $statusExpire = '<span class="label label-default">ครบกำหนด</span>';
                    } else {
                        $statusExpire = '<span class="label label-success">อีก ' . $value['expire'] . ' วัน</span>';
                    }

                    $nestedData = array();

                    $nestedData[] = getData::DateThai($value["date_receive"]) . ' ' . $statusExpire;
                    $nestedData[] = $value['titleName'] . $value["name"];
                    $nestedData[] = $value["phoneNumber"];
                    $nestedData[] = $value["province_name"];
                    $nestedData[] = '<span class="label ' . $label_status . '">' . $value["status"] . '</span>';
                    $nestedData[] = $value["car_status"];

                    $action = '<div class="box-tools tdChild" style="text-align: center;">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-bars"></i></button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li><a href="#" class="bt-view"   data-id="' . $value['id'] . '"  data-customer="' . $value['titleName'] . $value["name"] . '"><i class="fa  fa-eye  text-black"></i> ดูรายละเอียด</a></li>
                                            <li><a href="#" class="bt-edit"   data-id="' . $value['id'] . '"  data-customer="' . $value['titleName'] . $value["name"] . '"><i class="fa fa-pencil  text-aqua"></i> แก้ไขข้อมูล</a></li>';
                                            if(in_array($_SESSION['role'],array('superamin','admin'))){
                                                $action .='<li><a href="#" class="bt-delete" data-id="' . $value['id'] . '"  data-customer="' . $value['titleName'] . $value["name"] . '"><i class="fa fa-remove  text-red"></i> ลบ</a></li>';
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

        //=========== จบรายชื่อ sales ================

        case 'get_bookcar':

            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);

            $where = " id = {$id} ";
            $result = $dbcon->update('bookcar', ' status="อ่านแล้ว" ', $where);

            $sql = "SELECT bookcar.*,province_name,district_name,subdistrict_name,info_title,text_title,attribute  FROM bookcar
                    INNER JOIN province ON province.id = bookcar.province
                    INNER JOIN district ON district.district_id =bookcar.district
                    INNER JOIN subdistrict ON subdistrict.subdistrict_id = bookcar.subDistrict
                    INNER JOIN web_info on web_info.info_id = bookcar.bank
                    WHERE bookcar.id = '" . $id . "' AND info_type='bank'";
            $result = $dbcon->fetch($sql);
            $result['date_receive_value'] = getData::DateThai($result['date_receive']);
            echo json_encode($result);
            break;

        case 'get_bookcar_print':

            $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT bookcar.*,province_name,district_name,subdistrict_name,info_title,text_title,attribute  FROM bookcar
                INNER JOIN province ON province.id = bookcar.province
                INNER JOIN district ON district.district_id =bookcar.district
                INNER JOIN subdistrict ON subdistrict.subdistrict_id = bookcar.subDistrict
                INNER JOIN web_info on web_info.info_id = bookcar.bank
                WHERE bookcar.id = '" . $id . "' AND info_type='bank'";
                $result = $dbcon->fetch($sql); 

            $table = ' <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><center>ข้อมูลการจอง</center></td>
                                    </tr>
                                    <tr>
                                    <th style="">เงื่อนไขที่</th>
                                        <td>' . $result['conditionCar'] . '</td>
                                    </tr>
                                    <tr>
                                         <th style="">วันที่รับรถ</th>
                                        <td>' . getData::DateThai($result['date_receive']) . '</td>
                                    </tr>
                                    <tr>
                                         <th style="">การชำระเงิน</th>
                                        <td>' . $result['info_title'].'<br>เลขบัญชี'.$result['attribute'] . '</td>
                                    </tr>
                                    
                                    <tr>
                                        <th style="width: 150px">ชื่อ - นามสกุล</th>
                                        <td>' . $result['titleName'] . $result['name'] . '</td>
                                    </tr>
                                    <tr>
                                        <th style="">ที่อยู่</th>
                                        <td>' . $result['address'] .' '.$result['subdistrict_name']. '  '.$result['district_name'].' '.$result['province_name'] . ' '.$result['postcode'].'</td>
                                    </tr>
                                    <tr>
                                        <th style="">เบอร์โทร</th>
                                        <td>' . $result['phoneNumber'].'</td>
                                    </tr>
                                    <tr>
                                        <th style="">สถานะการจอง</th>
                                        <td>' . $result['car_status'].'</td>
                                </tr>
                             </tbody>

                            </table>
                            ';
            echo $table;

            break;

        case 'update_bookcar':

            $data_post = ProtectedWeb::magic_quotes_special_chars_array($_POST);
            list($auspicious, $day) = explode(':', $data_post['day']);
            $date_receive = date('Y') . '/' . $data_post['month'] . '/' . $day;

            $setUpdate = "conditionCar = '{$data_post['conditionCar']}',
                bank = '{$data_post['bank_destination']}',
                auspicious = '{$auspicious}',
                date_receive = '{$date_receive}',
                titleName = '{$data_post['titleName']}',
                name = '{$data_post['name']}',
                address = '{$data_post['address']}',
                subDistrict = '{$data_post['subDistrict']}',
                district =  '{$data_post['district']}',
                province = '{$data_post['province']}',
                postcode = '{$data_post['postID']}',
                car_status = '{$data_post['carStatus']}',
                phoneNumber = '{$data_post['phone']}'";

            $where = " id = {$data_post['bookcar_id_edit']} ";
            $result = $dbcon->update('bookcar', $setUpdate, $where);
            echo json_encode($result);

            break;

        case 'delete_bookcar':
            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $where = "id = '" . $id . "'";
            $result = $dbcon->delete('bookcar', $where);
            echo json_encode($result);
            break;

        case 'get_auspicious':

            $month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT * FROM auspicious WHERE month_select='{$month}'  ORDER BY month_select DESC";
            $result = $dbcon->query($sql);

            if (!$result) {
                echo json_encode('no_result');
            } else {
                echo json_encode($result);
            }
            break;

        case 'get_district':

            $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_NUMBER_INT);

            $table = " district WHERE province_id='{$province}' ";
            echo getData::option($table, 'district_name', '', '', 'district_id');

            break;

        case 'get_subdistrict':

            $district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_NUMBER_INT);

            $table = " subdistrict WHERE district_id='{$district}' ";
            echo getData::option($table, 'subdistrict_name', '', 'postcode', 'subdistrict_id');
            break;

    }
}
