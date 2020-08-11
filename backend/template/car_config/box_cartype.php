<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"> ประเภทรถยนต์</h3>

    <div class="box-tools pull-right">
        <button type="button" class="btn btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-add-cartype"><i class="fa fa-plus"></i> เพิ่มประเภทรถยนต์</button>
    </div>
  </div>

  <div class="box-body no-padding">
    <div class="box-body box-category table-responsive">
   
      <table class="table table-bordered table-striped" id="cartype-grid">
        <thead>
          <tr>
            <th width="10">#</th>
            <th>ประเภทรถยนต์</th>
            <th width="180">action</th>
          </tr>
        </thead>
        <tbody>
        <?php 
                $carList = $mydata->get_carTypeList();
                foreach ($carList as $key => $value) {
                    echo '<tr>
                            <td>'.++$i.'</td>
                            <td>'.$value['car_type'].'</td>
                            <td><span class="edit_car_type" data-id="'.$value['car_type_id'].'"><i class="fa fa-pencil-square-o text-green" aria-hidden="true"></i> แก้ไข</span>';
                        if (in_array($_SESSION['role'], array('superamin', 'admin'))) {
                            echo ' | <span class="delete_car_type" data-id="'.$value['car_type_id'].'"><i class="fa fa-trash-o text-red" aria-hidden="true"></i> ลบ</span>';
                        }
                  echo '</td></tr>';
                }  
        ?>
        
        </tbody>
      </table>
    </div> 
  </div>
</div> 

<div class="modal fade in" id="modal-add-cartype">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> เพิ่มประเภทรถยนต์</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-add-cartype">
          <div class="box-body">

            <div class="form-group">
              <label class="col-sm-4 control-label">ประเภทรถยนต์</label>
              <div class="col-sm-8">
                <input type="text" class="form-control disableEnter" id="add-cartype">
              </div>
            </div> 

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="save-add-cartype"><i class="fa fa-floppy-o"></i> บันทึก</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade in" id="modal-edit-cartype">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> แก้ไขประเภทรถยนต์</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="box-body">

            <div class="form-group">
              <label class="col-sm-4 control-label">ประเภทรถยนต์</label>
              <div class="col-sm-8">
                <input type="text" class="form-control disableEnter" id="edit-cartype">
                <input type="hidden"id="edit-cartype-id">
              </div>
            </div> 
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="save-edit-cartype"><i class="fa fa-floppy-o"></i> อัพเดต</button>
      </div>
    </div>
  </div>
</div>