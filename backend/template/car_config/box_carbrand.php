<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"> ยี่ห้อรถยนต์</h3>

    <div class="box-tools pull-right">
        <button type="button" class="btn btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-add-brandtype1"><i class="fa fa-plus"></i> เพิ่มยี่ห้อรถยนต์</button>
    </div>
  </div>

  <div class="box-body no-padding">
    <div class="box-body box-category table-responsive">
   
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th width="10">#</th>
            <th>ยี่ห้อรถยนต์</th>
            <th width="180">action</th>
          </tr>
        </thead>
        <tbody>
        <?php 
                $carList = $mydata->get_brandList();
                $i=0;
                foreach ($carList as $key => $value) {
                    echo '<tr>
                            <td>'.++$i.'</td>
                            <td>'.$value['car_brand'].'</td>
                            <td><span class="edit_car_brand" data-id="'.$value['car_brand_id'].'"><i class="fa fa-pencil-square-o text-green" aria-hidden="true"></i> แก้ไข</span>';
                              if (in_array($_SESSION['role'], array('superamin', 'admin'))) {
                                  echo ' | <span class="delete_car_brand" data-id="'.$value['car_brand_id'].'"><i class="fa fa-trash-o text-red" aria-hidden="true"></i> ลบ</span>';
                              }
                        echo '</td></tr>';
                }  
        ?>
        
        </tbody>
      </table>
    </div> 
  </div>
</div> 

<div class="modal fade in" id="modal-add-brandtype1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> เพิ่มยี่ห้อรถยนต์</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-add-brandtype">
          <div class="box-body">

            <div class="form-group">
              <label class="col-sm-4 control-label">ยี่ห้อรถยนต์</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="add-brandtype">
              </div>
            </div> 

            <div class="form-group">
              <label class="col-sm-4 control-label">Website</label>
              <div class="col-sm-8">
                <input type="text" class="form-control disableEnter" id="add-website">
              </div>
            </div> 

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="save-add-brandtype"><i class="fa fa-floppy-o"></i> บันทึก</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade in" id="modal-edit-brandtype">
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
                <input type="text" class="form-control" id="edit-brandtype" required>
                <input type="hidden"id="edit-brandtype-id">
              </div>
            </div> 

            <div class="form-group">
              <label class="col-sm-4 control-label">เว็บไซต์</label>
              <div class="col-sm-8">
                <input type="text" class="form-control disableEnter" id="edit-website" required>
              </div>
            </div> 

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="save-edit-brandtype"><i class="fa fa-floppy-o"></i> อัพเดต</button>
      </div>
    </div>
  </div>
</div>