<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa  fa-truck"></i> รุ่นรถ / ราคา
        <small>( <?php echo $language_fullname['display_name']; ?> )</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก?></a></li>
        <li class="active"> รุ่นรถ / ราคา</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-add-carprice"><i class="fa fa-plus"></i> เพิ่มข้อมูลรถยนต์</button>
                </div>
            </div>
   
            <div class="box-body">
              <table id="cars-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>รุ่นรถยนต์ / ราคา</th>
                      <th>ยี่ห้อรถยนต์</th>
                      <th>ประเภทรถยนต์</th>
                      <th>สถานะ</th>
                      <th>วันที่นำเข้า</th>
                      <th>action</th>
                    </tr>
                  </thead>
              </table>
            </div>
          </div>

        </div>
      </div>
      
    </section>
  </div>

 <!-- เพิ่มข้อมูลรถ -->
 <div class="modal fade" id="modal-add-carprice">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> ข้อมูลรุ่นรถยนต์ และราคา</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" id="form-add-car">
              <div class="box-body">

              <div class="form-group">
                  <label class="col-sm-3 control-label">ประเภทรถยนต์</label>
                  <div class="col-sm-9">
                  <select class="form-control" id="car_type" name="car_type" required>
                      <option value=''>เลือกประเภทรถยนต์</option>
                      <?php  echo getData::option('car_type', 'car_type', '', '', 'car_type_id') ;?>
                  </select>
                    <span class="help-block car_type-error">กรุณาเลือกประเภทรถยนต์</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">ยี่ห้อรถยนต์</label>
                  <div class="col-sm-9">
                   <select class="form-control" id="car_brand" name="car_brand" required>
                     <?php echo getData::option('car_brand', 'car_brand', '', '', 'car_brand_id'); ?>
                    </select>
                    <span class="help-block car_brand-error"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">รายละเอียดรุ่น</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="car_detail" name="car_detail" required>
                    <span class="help-block car_detail-error">กรุณาระบุรายละเอียดรุ่น</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">ราคา</label>
                  <div class="col-sm-9">
                    <input type="number" class="form-control" id="car_price1" name="car_price1" required>
                    <span class="help-block car_price1-error">กรุณาระบุราคารถยนต์</span>
                  </div>
                </div> 
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="save-add-car"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save']; //Save Changes ?></button>
          </div>
        </div>
      </div>
    </div>

  <!-- แก้ไขข้อมูลรถ -->
    <div class="modal fade" id="modal-editcar">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> แก้ไขข้อมูลรถยนต์ / ราคา</h4>
          </div>
          <div class="modal-body">
          <form class="form-horizontal" id="form-edit-car">
              <div class="box-body">

              <div class="form-group">
                  <label class="col-sm-3 control-label">ประเภทรถยนต์</label>
                  <div class="col-sm-9">
                  <select class="form-control" id="edit_car_type" name="edit_car_type" required>
                      <?php  echo getData::option('car_type', 'car_type', '', '', 'car_type_id') ;?>
                  </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">ยี่ห้อรถยนต์</label>
                  <div class="col-sm-9">
                   <select class="form-control" id="edit_car_brand" name="edit_car_brand" required>
                     <?php echo getData::option('car_brand', 'car_brand', '', '', 'car_brand_id'); ?>
                    </select>
                    <span class="help-block edit_car_brand-error"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">รายละเอียดรุ่น</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="edit_car_detail" name="edit_car_detail" required>
                    <span class="help-block edit_car_detail-error">กรุณาระบุรายละเอียดรุ่น</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">ราคา</label>
                  <div class="col-sm-9">
                    <input type="number" class="form-control" id="edit_car_price" name="edit_car_price" required>
                    <span class="help-block edit_car_price-error">กรุณาระบุราคารถยนต์</span>
                  </div>
                </div> 
              
              
              <div class="form-group">
                  <label class="col-sm-3 control-label">เปิดใช้งาน</label>
                  <div class="col-sm-9">
                    <select class="form-control" id="edit_car_status" name="edit_car_status">
                     <option value='yes'>ใช่</option>
                     <option value='no'>ไม่ใช่</option>
                    </select>
                  </div>
                </div> 
               
               </div>
              <input type='hidden' name='edit_car_id' id='edit_car_id'>
              <input type='hidden' name='action' value='update_car'>
            </form>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="save-edit-car"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save']; //Save Changes ?></button>
          </div>
        </div>
      </div>
    </div>
 
 
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">

  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/car/car_price.js?v=<?php echo date('s');?>"></script>