<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/print/style-car-print.css">

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa  fa-calendar-check-o text-aqua"></i> รายการจองรถ
        <small>( <?php echo $language_fullname['display_name']; ?> ) จองรถยนต์  <a href="http://xn--b3cyig4ald4iqgc3e.com/bookcar" target='_blank'><span class="label label-success">http://โปรโมชั่นรถ.com/bookcar</span></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     ?></a></li>
        <li class="active">รายการจองรถ</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10">

          <div class="box box-primary">
            <div class="box-body">
              <table id="bookcar-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                  <thead>
                    <tr>
                      <th>วันรับรถ</th>
                      <th><?php echo $LANG_LABEL['name']; //ชื่อ    ?></th>
                      <th><?php echo $LANG_LABEL['phone']; //เบอร์โทร    ?></th>
                      <th><?php echo $LANG_LABEL['province'];?></th>
                      <th>สถานะ</th>
                      <th>สถานะการจอง</th>
                      <th><?php echo 'Action'; //แก้ไข    ?></th>
                    </tr>
                  </thead>
              </table>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

 <!-- POPUP แสดงข้อมูลจองรถ -->
 <!-- POPUP แสดงรายละเอียดข้อเสนอลูกค้า & พิมพ์ -->
<div class="modal fade" id="modal-view">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> ข้อมูลจองรถ ( ลูกค้า : <span id='bookcar_customer'></span>)</h4>
            </div>
            <div class="modal-body">
                <section class="">
                    <div class="row">
                         <div class="col-xs-12" id='table-view'>
                           
                         </div>
                    </div>
                </section>
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="printOut"><i class="fa fa-printer"></i> พิมพ์</button> 
            </div>
        </div>
    </div>
</div> 
 
  <!-- แก้ไขข้อมูล bookcar -->
  <form class="form-horizontal" id="form_bookcar">
    <div class="modal fade" id="modal-edit">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> แก้ไขข้อมูลจองรถ ( ลูกค้า : <span id='bookcar_customer_edit'></span>)</h4>
          </div>
          <div class="modal-body">
              <div class="box-body">

              <div class="form-group">
                  <label class="col-sm-3 control-label">เงื่อนไขที่</label>
                  <div class="col-sm-9">
                    <textarea class="form-control"  id="conditionCar" name="conditionCar" required></textarea>
                    <span class="help-block conditionCar-error">กรุณาระบุเงื่อนไข</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">ช่องชำระเงิน</label>
                  <div class="col-sm-9">
                
                   <?php
                    $bankinfo = current(getData::get_web_info('bank'));
                    echo '<select name="bank_destination" id="bank_destination" class="form-control" required>';
                    foreach ($bankinfo['data'] as $key => $bank) {
                       echo '<option value="'.$bank['info_id'].'">'.$bank['info_title'].' ('.$bank['attribute'].')</option>';
                    }
                    echo ' </select>';
                    ?> 

                    <span class="help-block bank_destination-error">กรุณาเลือกช่องทางชำระเงิน</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">เดือนกฤกษ์ดี</label>
                  <div class="col-sm-9">
                    <select name="month" id="month" class="form-control" required>
                            <option value="">เลือกเดือน</option>
                            <option value="01">มกราคม</option>
                            <option value="02">กุมภาพันธ์</option>
                            <option value="03">มีนาคม</option>
                            <option value="04">เมษายน</option>
                            <option value="05">พฤษภาคม</option>
                            <option value="06">มิถุนายน</option>
                            <option value="07">กรกฎาคม</option>
                            <option value="08">สิงหาคม</option>
                            <option value="09">กันยายน</option>
                            <option value="10">ตุลาคม</option>
                            <option value="11">พฤศจิกายน</option>
                            <option value="12">ธันวาคม</option>
                        </select>
                    <span class="help-block month-error">กรุณาเลือกเดือนที่รับรถ</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">วันที่รับรถ</label>
                  <div class="col-sm-9">
                  <select name="day" id="day" class="form-control" required>
                            <option value="">เลือกวัน</option>
                   </select>
                    <span class="help-block day-error">กรุณาเลือกวันที่รับรถ</span>
                  </div>
                </div>


              <div class="form-group">
                  <label class="col-sm-3 control-label"><?php echo $LANG_LABEL['titlename']; //คำนำหน้าชื่อ ?></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="titleName" name="titleName" required>
                    <span class="help-block titleName-error">กรุณาระบุคำนำหน้าชื่อ</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">ชื่อ - นามสกุล</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name" required>
                    <span class="help-block name-error">กรุณาระบุชื่อ - นามสกุล</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">เบอร์โทร</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="phone" name="phone" required>
                    <span class="help-block phone-error">กรุณาระบุเบอร์โทรศัพท์</span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">ที่อยู่</label>
                  <div class="col-sm-9">
                    <textarea class="form-control"  id="address" name="address" required></textarea>
                    <span class="help-block address-error">กรุณาระบุที่อยู่</span>
                  </div>
                </div>

          
                <div class="form-group">
                  <label class="col-sm-3 control-label"><?php echo $LANG_LABEL['province']; //จังหวัด ?></label>
                  <div class="col-sm-9">
                    <select class="form-control" id="province" name="province" required>
                     <?php echo getData::option('province', 'province_name', '', '', 'id'); ?>
                    </select>
                    <span class="help-block province-error">กรุณาเลือกจังหวัด</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">อำเภอ</label>
                  <div class="col-sm-9">
                    <select class="form-control" id="district" name="district" required>
                        <option value="">เลือกอำเภอ</option>
                    </select>
                    <span class="help-block district-error">กรุณาเลือกจังหวัด</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">ตำบล</label>
                  <div class="col-sm-9">
                    <select class="form-control" id="subDistrict" name="subDistrict" required>
                        <option value="">เลือกตำบล</option>
                    </select>
                    <span class="help-block subDistrict-error">กรุณาเลือกตำบล</span>
                  </div>
                </div>

                
              <div class="form-group">
                  <label class="col-sm-3 control-label">รหัสไปรษณีย์</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="postID" name="postID" required>
                    <span class="help-block postID-error">กรุณาระบุรหัสไปรษณีย์</span>
                  </div>
                </div>

                <input type="hidden"  name="action" value="update_bookcar">
                <input type="hidden"  id="bookcar_id_edit" name="bookcar_id_edit"  value="">
              </div>
           
          </div>

          <div class="modal-footer form-inline" style="left: 0;border-top: 2px solid #99854a;"> 
                   
                   <span>สถานะรถ: </span>
                   <select id="carStatus" name="carStatus" style="height: 40px;min-width: 150px;padding: 0 10px;border: 1px solid #dddddd;border-radius: 3px;">
                       <option value='ยังไม่จอง'>ยังไม่จอง</option>
                       <option value='กำลังดำเนินการ'>กำลังดำเนินการ</option>
                       <option value='รับรถแล้ว'>รับรถแล้ว</option>
                   </select>
                   <button type="button" class="btn btn-success pull-right" id="save-edit-bookcar" style="margin-left: 25px;"><i class="fa fa-floppy-o"></i> บันทึก</button>
               </div>

        </div>
      </div>
    </div>

    </form>
    <!-- จบแก้ไขข้อมูล bookcar -->
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">

  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/car/bookcar_list.js?v=<?php echo date('s');?>"></script>