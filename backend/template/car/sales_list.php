<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-user-secret"></i> <?php echo $LANG_LABEL['sales']; //ผู้ดูแลระบบ    ?>
        <small>( <?php echo $language_fullname['display_name']; ?> ) สมัครฝ่ายขาย  <a href="http://xn--b3cyig4ald4iqgc3e.com/registersales" target='_blank'><span class="label label-success">http://โปรโมชั่นรถ.com/registersales</span></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     ?></a></li>
        <li class="active"><?php echo $LANG_LABEL['sales']; //ผู้ดูแลระบบ    ?></li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
      <div class=" col-xs-12 col-sm-12 col-md-10">
          <div class="box box-primary">
            <div class="box-body">
              <table id="sales-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                  <thead>
                    <tr>
                      <th></th>
                      <th><?php echo $LANG_LABEL['name']; //ชื่อ    ?></th>
                      <th><?php echo $LANG_LABEL['phone']; //เบอร์โทร    ?></th>
                      <th><?php echo 'Line ID'; //หน้าที่    ?></th>
                      <th>ยี่ห้อ</th>
                      <th><?php echo $LANG_LABEL['province']; //ภาษา    ?></th>
                      <th><?php echo $LANG_LABEL['dateregis']; //วันที่ลงทะเบียน    ?></th>
                      <th><?php echo $LANG_LABEL['matchstatus']; //สถานะ    ?></th>
                      <th><?php echo 'Action';?></th>
                    </tr>
                  </thead>
              </table>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

   <!-- POPUP แสดงรายละเอียดข้อเสนอลูกค้า & พิมพ์ -->
<div class="modal fade" id="modal-view">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-word-o text-aqua"></i> ข้อมูลฝ่ายขาย</h4>
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

  <!-- แก้ไขข้อมูล Sales -->
    <div class="modal fade" id="modal-editSale">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> <?php echo 'แก้ไขข้อมูล Sales'; ?></h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" id="form-edit-sales">
              <div class="box-body">

              <div class="form-group">
                  <label class="col-sm-3 control-label"><?php echo $LANG_LABEL['titlename']; //คำนำหน้าชื่อ ?></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="titleNameSale" name="titleNameSale" required>
                    <span class="help-block titleNameSale-error">กรุณาระบุคำนำหน้าชื่อ</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">ชื่อ - นามสกุล</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="nameSale" name="nameSale" required>
                    <span class="help-block nameSale-error">กรุณาระบุชื่อ - นามสกุล</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">เบอร์โทร</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="phoneSale" name="phoneSale" required>
                    <span class="help-block phoneSale-error">กรุณาระบุเบอร์โทรศัพท์</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Line ID</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="lineSale" name="lineSale" required>
                    <span class="help-block lineSale-error"></span>
                  </div>
                </div>


                <div class="form-group">
                  <label class="col-sm-3 control-label">ขายรถยนต์ยี่ห้อ</label>
                  <div class="col-sm-9">
                    <select class="form-control" id="saleBrand" name="saleBrand" required>
                     <?php echo getData::option('car_brand', 'car_brand', '', '', 'car_brand_id'); ?>
                    </select>
                    <span class="help-block saleBrand-error"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">ชื่อโชว์รูมที่ทำงาน (บริษัท)</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="nameWorkplaceSale" name="nameWorkplaceSale" required>
                    <span class="help-block nameWorkplaceSale-error">กรุณาระบุชื่อโชว์รูมที่ทำงาน</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">สาขา</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="workplaceBranchSale" name="workplaceBranchSale">
                    <span class="help-block workplaceBranchSale-error"></span>
                  </div>
                </div>


                <div class="form-group">
                  <label class="col-sm-3 control-label"><?php echo $LANG_LABEL['province']; //จังหวัด ?></label>
                  <div class="col-sm-9">
                    <select class="form-control" id="workplaceProvinceSale" name="workplaceProvinceSale" required>
                     <?php echo getData::option('province', 'province_name', '', '', 'id'); ?>
                    </select>
                    <span class="help-block workplaceProvinceSale-error">กรุณาเลือกจังหวัด</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">สถานะ</label>
                  <div class="col-sm-9">
                    <select class="form-control" id="statusSale" name="statusSale" required>
                      <option value='active'>อนุมัติแล้ว</option>
                      <option value='pending'>รออนุมัติ</option>
                    </select>
                    <span class="help-block statusSale-error">กรุณาเลือกสถานะ</span>
                  </div>
                </div>

                <div class="col-md-6">
                    <figure id="img-profile" class="" style=" background: url('./images/image134x134.jpg'); 
                                        background-size:cover; width: 165px; height: 165px;">
                    </figure>
                </div>

                <div class="col-md-6">
                  <figure id="img-card" class="" style=" background: url('./images/image134x134.jpg'); 
                                          background-size:cover; width: 165px; height: 165px;">
                      </figure>
                </div>
                <input type="hidden"  name="action" value="update_sales">
                <input type="hidden"  id="sales_id_edit" name="sales_id_edit"  value="">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="save-edit-sales"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save']; //Save Changes ?></button>
          </div>
        </div>
      </div>
    </div>
    <!-- จบแก้ไขข้อมูล Sales -->



  <input type='hidden' value='' id='sales_id' />
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">

  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/car/sales_list.js"></script>