
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
                0 => 'date_receive',
                1 => 'name',
                2 => 'phone',
                3 => 'province', 
                4 => 'status'
            );

            $sql = "SELECT * FROM bookcar";
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            if (!empty($requestData['search']['value'])) {
                if ($_SESSION['role'] != 'superadmin') { 
                    if ($_SESSION['role'] == 'editor' || $_SESSION['role'] == 'user') {
                        $sql .= " WHERE member_type NOT IN(1) AND member_type NOT IN(3)";
                    } else {
                        $sql .= " WHERE member_type NOT IN(1) ";
                    }
                    $sql .= " AND (username LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR display_name LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR email LIKE '" . $requestData['search']['value'] . "%') ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);

                } else {
                    //superamin
                    $sql .= " WHERE phone LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR name LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR province LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                }

            } else {

                if ($_SESSION['role'] != 'superadmin') { 

                    if ($_SESSION['role'] == 'editor' || $_SESSION['role'] == 'user') {
                        $sql .= " WHERE member_type NOT IN(1) AND member_type NOT IN(3)";
                    } else {
                        $sql .= " WHERE member_type NOT IN(1) ";
                    }

                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                } else { 
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                }
            }

            $output = array();
            if ($result) {
                foreach ($result as $value) {

                    $label_status = '';
                    if ($value['status'] == '3') {
                        $label_status = 'label-warning';
                    } else if ($value['status'] == '1') {
                        $label_status = 'label-success';
                    } else if ($value['status'] == '2') {
                        $label_status = 'label-danger';
                    } else if ($value['status'] == '4') {
                        $label_status = 'label-default';
                    }

                    //  $type = getData::valuefromkey('user_type', 'user_type', 'id', $value['member_type']);
                    //  $status = getData::valuefromkey('status_user', 'user_status', 'id', $value['status']);

                    $nestedData = array();
                    $nestedData[] = '<center><img src="' . SITE_URL . 'classes/thumb-generator/thumb.php?src=' . ROOT_URL . $value['profile'] . '&size=30"></center>';
                    $nestedData[] = $value['title'] . $value["name"];
                    $nestedData[] = $value["phone"];
                    $nestedData[] = $value["line"];
                    $nestedData[] = $value["province_name"];
                    $nestedData[] = date_format(date_create($value["date_regis"]), "d/m/Y - H:i");
                    $nestedData[] = '<span class="label ' . $label_status . '">' . $value["status"] . '</span>';

                    $nestedData[] = '<div class="box-tools tdChild" style="text-align: center;">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-bars"></i></button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li><a href="#" class="bt-view"   data-id="' . $value['id'] . '"  data-sales="' . $value['title'] . $value["name"] . '"><i class="fa  fa-eye  text-black"></i> ดูรายละเอียด</a></li>
                                            <li><a href="#" class="bt-view"   data-id="' . $value['id'] . '"  data-sales="' . $value['title'] . $value["name"] . '" ><i class="fa fa-file-text-o  text-green"></i> แบบตอบกลับ</a></li>
                                            <li><a href="#" class="bt-edit"   data-id="' . $value['id'] . '" ><i class="fa fa-pencil  text-aqua"></i> แก้ไขข้อมูล</a></li>
                                            <li><a href="#" class="bt-delete" data-id="' . $value['id'] . '"><i class="fa fa-remove  text-red"></i> ลบ</a></li>
                                        </ul>
                                        </div>
                                    </div>';
                    $nestedData[] = '<center><a data-id="' . $value["id"] . '" data-toggle="modal" data-target="#modal-admin" class="btn btn-success btn-xs edit-admin"><i class="fa fa-pencil-square-o"></i></a></center>';
                    // $nestedData[] = '<center><a data-id="' . $value["id"] . '" class="btn btn-danger btn-xs delete-admin"><i class="fa fa-trash-o"></i></a></center>';

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

        //====== รายการตอบกลับลูกค้า ของ sales =======

        case 'get_customer_reply':

            $requestData = $_REQUEST;

            $columns = array(
                0 => 'customer_reply.date_create',
                1 => 'customer.name',
                2 => 'customer.phone',
                // 3 => 'customer.line',
                3 => 'customer.province',
                4 => 'car_model.car_model',
            );

            $sales_id = filter_var($_POST['id'], FILTER_SANITIZE_MAGIC_QUOTES);

            $sql = "SELECT customer_reply.id AS 'reply_id',customer.*,car_model.car_model FROM customer_reply
        INNER JOIN customer  ON customer_reply.customer_id = customer.id
        INNER JOIN car_model ON customer.car_model = car_model.car_model_id
         WHERE sales_id='{$sales_id}'";

            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            if (!empty($requestData['search']['value'])) {
                if ($_SESSION['role'] != 'superadmin') {
                    if ($_SESSION['role'] == 'editor' || $_SESSION['role'] == 'user') {
                        $sql .= " WHERE member_type NOT IN(1) AND member_type NOT IN(3)";
                    } else {
                        $sql .= " WHERE member_type NOT IN(1) ";
                    }
                    $sql .= " AND (username LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR display_name LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR email LIKE '" . $requestData['search']['value'] . "%') ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);

                } else {
                    //superamin
                    $sql .= " WHERE phone LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR name LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR province LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                }

            } else {
                $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                $result = $dbcon->query($sql);
            }

            $output = array();
            if ($result) {
                foreach ($result as $value) {
                    $nestedData = array();
                    $nestedData[] = date_format(date_create($value["date_create"]), "d/m/Y - H:i");
                    $nestedData[] = $value['titleName'] . $value["name"];
                    $nestedData[] = $value["phoneNumber"];
                    // $nestedData[] = $value["lineID"];
                    $nestedData[] = $value["province"];
                    $nestedData[] = $value["car_model"];

                    $nestedData[] = '<div class="box-tools tdChild" style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i></button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#" class="bt-view-reply"   data-id="' . $value['reply_id'] . '" ><i class="fa fa-file-text-o text-green"></i> ดูรายละเอียด</a></li>
                                        <li><a href="#" class="bt-edit"   data-id="' . $value['reply_id'] . '" ><i class="fa fa-print text-aqua"></i> พิมพ์</a></li>
                                        <li><a href="#" class="bt-delete" data-id="' . $value['reply_id'] . '"><i class="fa fa-remove text-red"></i> ลบ</a></li>
                                    </ul>
                                    </div>
                                </div>';
                    $nestedData[] = '<center><a data-id="' . $value["id"] . '" data-toggle="modal" data-target="#modal-admin" class="btn btn-success btn-xs edit-admin"><i class="fa fa-pencil-square-o"></i></a></center>';
                    // $nestedData[] = '<center><a data-id="' . $value["id"] . '" class="btn btn-danger btn-xs delete-admin"><i class="fa fa-trash-o"></i></a></center>';

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

        //====== จบรายการตอบกลับลูกค้า ของ sales =======

        case 'getalluser':
            $requestData = $_REQUEST;
            $columns = array(
                0 => 'display_name',
                1 => 'email',
                2 => 'member_type',
                3 => 'language',
                4 => 'date_regis',
                5 => 'status_user',
                6 => 'edit',
                7 => 'delete',
            );

            $sql = "SELECT * FROM user";
            $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

            if (!empty($requestData['search']['value'])) {
                if ($_SESSION['role'] != 'superadmin') {
                    $sql = "SELECT * ";
                    $sql .= " FROM user";
                    if ($_SESSION['role'] == 'editor' || $_SESSION['role'] == 'user') {
                        $sql .= " WHERE member_type NOT IN(1) AND member_type NOT IN(3)";
                    } else {
                        $sql .= " WHERE member_type NOT IN(1) ";
                    }
                    $sql .= " AND (username LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR display_name LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR email LIKE '" . $requestData['search']['value'] . "%') ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                } else {
                    $sql = "SELECT * ";
                    $sql .= " FROM user";
                    $sql .= " WHERE username LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR display_name LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " OR email LIKE '" . $requestData['search']['value'] . "%' ";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                }

            } else {
                if ($_SESSION['role'] != 'superadmin') {
                    $sql = "SELECT * ";
                    $sql .= " FROM user";
                    if ($_SESSION['role'] == 'editor' || $_SESSION['role'] == 'user') {
                        $sql .= " WHERE member_type NOT IN(1) AND member_type NOT IN(3)";
                    } else {
                        $sql .= " WHERE member_type NOT IN(1) ";
                    }
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                } else {
                    $sql = "SELECT * ";
                    $sql .= " FROM user";
                    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
                    $result = $dbcon->query($sql);
                }

            }

            $output = array();
            foreach ($result as $value) {
                if ($value['status_user'] == '3') {
                    $label_status = 'label-warning';
                } else if ($value['status_user'] == '1') {
                    $label_status = 'label-success';
                } else if ($value['status_user'] == '2') {
                    $label_status = 'label-danger';
                } else if ($value['status_user'] == '4') {
                    $label_status = 'label-default';
                }

                $type = getData::valuefromkey('user_type', 'user_type', 'id', $value['member_type']);
                $status = getData::valuefromkey('status_user', 'user_status', 'id', $value['status_user']);
                $nestedData = array();
                $nestedData[] = $value["display_name"];
                $nestedData[] = $value["email"];
                $nestedData[] = $lang_config[$type["user_type"]];
                $nestedData[] = $value["language"];
                $nestedData[] = date_format(date_create($value["date_regis"]), "d/m/Y - H:i");
                $nestedData[] = '<span class="label ' . $label_status . '">' . $lang_config[$status["status_user"]] . '</span>';

                $nestedData[] = '<center><a data-id="' . $value["member_id"] . '" data-toggle="modal" data-target="#modal-admin" class="btn btn-success btn-xs edit-admin"><i class="fa fa-pencil-square-o"></i></a></center>';

                $nestedData[] = '<center><a data-id="' . $value["member_id"] . '" class="btn btn-danger btn-xs delete-admin"><i class="fa fa-trash-o"></i></a></center>';

                $output[] = $nestedData;
            }

            $json_data = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $output,
            );
            echo json_encode($json_data);
            break;

        case 'get_sales':

            $memberId = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
            $sql = "SELECT * FROM sales WHERE id = '" . $memberId . "'";
            $result = $dbcon->query($sql);
            echo json_encode(current($result));
            break;

        case 'update_sales':

            $data_post = $_POST;
            $setUpdate = "phone='{$data_post['phoneSale']}',
                      title='{$data_post['titleNameSale']}',
                      name='{$data_post['nameSale']}',
                      line='{$data_post['lineSale']}',
                      brand='{$data_post['saleBrand']}',
                      workplace='{$data_post['nameWorkplaceSale']}',
                      branch='{$data_post['workplaceBranchSale']}',
                      province='{$data_post['workplaceProvinceSale']}'";

            $where = " id = {$data_post['sales_id_edit']} ";
            $result = $dbcon->update('sales', $setUpdate, $where);
            echo json_encode($result);

            break;

        case 'delete_sales':
            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $where = "id = '" . $id . "'";
            $result = $dbcon->delete('sales', $where);
            echo json_encode($result);
            break;

    }
}
