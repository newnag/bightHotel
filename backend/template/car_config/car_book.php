<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa  fa-calendar"></i> ฤกษ์ดีวันรับรถ
        <small>( <?php echo $language_fullname['display_name']; ?> )</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก?></a></li>
        <li class="active"> ฤกษ์ดีวันรับรถ</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class=" col-xs-12 col-sm-12 col-md-7">

          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-add-book"><i class="fa fa-plus"></i> เพิ่มวันรับรถ</button>
                </div>
            </div>
   
            <div class="box-body">
              <table id="book-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>วันที่</th>
                      <th>รายละเอียด</th>
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
 <div class="modal fade" id="modal-add-book">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> ข้อมูลฤกษ์ดีวันรับรถยนต์</h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" id="form-add-book">
              <div class="box-body">

              <div class="form-group">
                  <label class="col-sm-3 control-label">วันที่รับรถ</label>
                  <div class="col-sm-9">
                    <input type='text' name='add-date-book' id='add-date-book' class="form-control" required>
                    <span class="help-block 'add-date-book-error">กรุณาเลือกวันรับรถยนต์</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">รายละเอียด</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" id='detail_book' name='detail_book' required></textarea>
                    <span class="help-block detail_book-error">กรุณาระบุรายละเอียดวันรับรถ</span>
                  </div>
                </div>
                
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="save-add-book"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save']; //Save Changes ?></button>
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
            <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> แก้ไขข้อมูลวันรับรถ</h4>
          </div>
          <div class="modal-body">

          <form class="form-horizontal" id="form-edit-book">
              <div class="box-body">

              <div class="form-group">
                  <label class="col-sm-3 control-label">วันที่รับรถ</label>
                  <div class="col-sm-9">
                    <input type='text' name='edit-date-book' id='edit-date-book' class="form-control" required>
                    <span class="help-block 'edit-date-book-error">กรุณาเลือกวันรับรถยนต์</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">รายละเอียด</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" id='edit_detail' name='edit_detail' required></textarea>
                    <span class="help-block edit_detail-error">กรุณาระบุรายละเอียดวันรับรถ</span>
                  </div>
                </div>
              </div>
              <input type='hidden' id='edit_id_book'  name='edit_id_book'>
              <input type='hidden' name='action' value='update_book' >
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
  <script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/car/car_book.js?v=<?php echo date('s');?>"></script>