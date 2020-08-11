<?php	
session_start();

include '../config/database.php';
require_once('../classes/dbquery.php');
require_once('../classes/class.upload.php');
require_once('../classes/preheader.php');
require_once('../classes/class.orders.php');

$dbcon = new DBconnect();
$data = new getData();
$mydata = new orders();

if(isset($_REQUEST['action'])) {
	$lang_config = $data->lang_config();
 /* $output = $_SESSION['backend_language'];
  foreach($lang_config as $a){
    foreach($a as $b => $c){
      if($b == 'param'){
        if($a[$output]!='')
          $$c = $a[$output];
        else
          $$c = $a['defaults'];
      }
    }
  }*/
  switch($_REQUEST['action']){
    case'getorder':
      $requestData= $_REQUEST;
      $columns = array( 
        0 => 'orders.slipimg',
        1 => 'orders.id',
        2 => 'orders.name',
        3 => 'orders.datetime_payed',
        4 => 'orders.phone',
        5 => 'orders.email',
        6 => 'orders.orders_status',
        7 => 'action'
      );

      $sql = "SELECT * FROM orders WHERE orders.slipimg != '' ";

      $stmt = $dbcon->runQuery($sql);
            $stmt->execute();
            $totalData = $stmt->rowCount();
            $totalFiltered = $totalData;

      if( !empty($requestData['search']['value']) ) {
        $sql = " SELECT *  FROM orders";
        $sql.= " INNER JOIN orders_status ON orders.orders_status = orders_status.status_id";
        $sql.=" WHERE (orders.id LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR orders.name LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR orders.datetime_payed LIKE binary '%".$requestData['search']['value']."%' ";
        $sql.=" OR orders.phone LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR orders.email LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR orders.orders_status LIKE '%".$requestData['search']['value']."%' ";
        $sql.=" OR orders_status.orders_desc LIKE '%".$requestData['search']['value']."%' )";
        $sql.=" AND orders.slipimg != '' ";
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; 
        $result = $dbcon->query($sql);
        //echo $sql;
      } else {  

        $sql =" SELECT *  FROM orders";
        $sql.= " INNER JOIN orders_status ON orders.orders_status = orders_status.status_id";
        $sql.=" WHERE orders.slipimg != '' ";
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $result = $dbcon->query($sql);
      }

      $output = array();

      if($result){
        foreach ($result as $value) {
          if ($value['new'] === 'new') {
            $status_new = ' <small id="status-new-'.$value["id"].'" class="label label-danger" style="margin-left: 10px;">ใหม่</small>';
          }else {
            $status_new = '';
          }

          if ($value['orders_status'] == '2') {
            $textColor = 'red';
          }else if($value['orders_status'] == '3') {
            $textColor = '#0096e0';
          }else if($value['orders_status'] == '4') {
            $textColor = '#00d876';
          }else{
            $textColor = 'black';
          }
          $nestedData=array(); 

          $nestedData[] ='<div class="content-imgPayed">
                  <a class="fancybox" href="'.ROOT_URL.$value["slipimg"].'" title="'.$value["name"].'">
                    <img src="'.ROOT_URL.$value["slipimg"].'" alt="">
                  </a>
                </div>';

          $nestedData[] = '<div class = "tdChild">'.$value["id"].'</td>'; 
          $nestedData[] = '<div class = "tdChild">'.$value["name"].'</td>';
          $nestedData[] = '<div class = "tdChild">'.date_format(date_create($value["datetime_payed"]),"d/m/Y - H:i:s").'</td>';
          $nestedData[] = '<div class = "tdChild">'.$value["phone"].'</td>';
          $nestedData[] = '<div class = "tdChild">'.$value["email"].'</td>';
          $nestedData[] = '<div class = "tdChild" style = "color:'.$textColor.';">'.$value["orders_desc"].'</div>';
          /*
          $nestedData[] = "<h1>".$value["id"]."</h1>".
                          "<div>".$value["name"]."</div>".
                          "<div>".date_format(date_create($value["datetime_payed"]),"d/m/Y - H:i:s")."</div>".
                          "<div>".$value["phone"]."</div>".
                          "<div>".$value["email"]."</div>".
                          "<div style = 'color:".$textColor.";' >".$value["orders_desc"]."</div>"
          ; 
          $nestedData[] = '';
          $nestedData[] = '';
          $nestedData[] = '';
          $nestedData[] = '';
          $nestedData[] = '';
          */



          if( $value['orders_status'] == '3' || $value['orders_status'] == '4' ){
            $nestedData[] = '<div class="box-tools tdChild" style="text-align: center;">
                              <div class="btn-group">
                                <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                  <i class="fa fa-bars"></i></button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                  <li><a href="#" class="getorderdetail" data-id="'.$value['id'].'" > ดูรายละเอียด</a></li>
                                </ul>
                              </div>
                            </div>';
          }else{
            $nestedData[] = '<div class="box-tools tdChild" style="text-align: center;">
                              <div class="btn-group">
                                <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown">
                                  <i class="fa fa-bars"></i></button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                  <li><a href="#" class="getorderdetail" data-id="'.$value['id'].'" > ดูรายละเอียด</a></li>
                                  <li><a href="javascript:void(0);" class="delete-order" data-id="'.$value['id'].'">ลบคำสั่งซื้อ</a></li>
                                </ul>
                              </div>
                            </div>';
          }
          $output[] = $nestedData;
        }
      }
      $json_data = array(
        "draw"            => intval( $requestData['draw'] ),
        "recordsTotal"    => intval( $totalData ),
        "recordsFiltered" => intval( $totalFiltered ),
        "data"            => $output
      );
      echo json_encode($json_data);
    break;

   /* case'getorderdetail':
      $table = "orders";
      $set = "status = ''";
      $where = "OrderID = '".$_REQUEST['id']."'";
      $result = $dbcon->update($table, $set, $where);

      $customer = $mydata->get_customer_detail($_REQUEST['id']);
      $order = $mydata->get_order_detail($_REQUEST['id']);

      $sql="SELECT OrderID FROM orders WHERE status = 'new'";
      $stmt = $dbcon->runQuery($sql);
      $stmt->execute();
      $totalData = $stmt->rowCount();
      $totalFiltered = $totalData;

      $output = array('customer' => $customer, 'order' => $order, 'total' => $totalFiltered);

      echo json_encode($output);
    break;*/

    case'getorderdetail':
      /*$table = "orders";
      $set = "new = ''";
      $where = "id = ".$_REQUEST['id'];
      $result = $dbcon->update($table, $set, $where);*/

      $sql = "SELECT *";
      $sql.=" FROM orders";
      $sql.=" WHERE orders.id = ".$_REQUEST['id'];
      $result = $dbcon->query($sql);
      $orders = $result;

      $datetime_order = date_format(date_create($orders[0]["datetime_order"]),"d/m/Y - H:i:s");

      $datetime_payed = date_format(date_create($orders[0]["datetime_payed"]),"d/m/Y - H:i:s");

      $slipImg = ROOT_URL.$orders[0]['slipimg'];

      $orders_status = '';

      echo json_encode([ $orders[0] , $orders_status , $datetime_order,$datetime_payed , $slipImg ,  json_decode($orders[0]["items"],true)]);
    break;

    case'getneworderlist':
      $output = '';
      $new_orders = $data->new_orders_msg();
      if ($new_orders) {
        $output .= '<li class="header">คุณมีรายการสั่งซื้อใหม่ '.count($new_orders).' รายการ</li>
                      <li>
                        <ul class="menu">';
                          foreach ($new_orders as $key => $value) {
                             $output .= '
                            <li>
                              <a href="'.SITE_URL.'?page=orders">
                                <i class="fa fa-shopping-cart text-green"></i> Order # '.$value['OrderID'].'
                              </a>
                            </li>';
                          }
                      $output .= ' 
                        </ul>
                      </li>
                    <li class="footer"><a href="'.SITE_URL.'?page=orders">ดูทั้งหมด</a></li>';
      }else {
        $output = ' <li class="header">คุณไม่มีรายการสั่งซื้อใหม่</li>
                    <li>&nbsp;</li>
                    <li class="footer"><a href="'.SITE_URL.'?page=orders">ดูทั้งหมด</a></li>';
      }
      echo $output;
    break;
    case'editorderstatus':
      $output = array();

      if($_REQUEST['orders_status'] == '3' || $_REQUEST['orders_status'] == '4') {
        $items = $data->get_name_by_id('orders','items','id',$_REQUEST['orderId']);
        $items = json_decode($items,true);
        $table = "product";
        $set = "topic = '3'";
        foreach ($items as $key => $value) {
          $where = "id = '".$value[id]."'";
          $status = $dbcon->update($table, $set, $where);
        }
      }
      
      $table = "orders";
      $set = "orders_status = '".$_REQUEST['orders_status']."'";
      $where = "id = '".$_REQUEST['orderId']."'";
      $res = $dbcon->update($table, $set, $where);

      if ($res['message'] == 'OK') {
        $output['message'] = "success";
      }else {
        $output['message'] = "not_success";
      }
      echo json_encode($output );
    break;

    /*case'deleteorder':
      $output = array();
      $table = "orders";
      $where = "OrderID = '".$_REQUEST['id']."'";
      $res = $dbcon->delete($table, $where);
      if ($res['message'] == 'OK') {
        $table = "orders_detail";
        $where = "OrderID = '".$_REQUEST['id']."'";
        $output = $dbcon->delete($table, $where);
      }else {
        $output = $res;
      }
      echo json_encode($output);
    break;*/

    case'deleteorder':

      $table = "orders";
      $where = "id = '".$_REQUEST['id']."'";
      $output = $dbcon->delete($table, $where);

      echo json_encode([ $_REQUEST['id'] ]);
    break;

	}
}
?>