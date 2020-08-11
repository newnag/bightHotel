<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user"></i> รายชื่อลูกค้า
            <small>( <?php echo $language_fullname['display_name']; ?> )</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก         ?></a></li>
            <li class="active"><?php echo $LANG_LABEL['customer']; //ผู้ดูแลระบบ        ?></li>
        </ol>
    </section>

    <section class="content">

        <div class="row">
          <div class=" col-xs-12 col-sm-12 col-md-10">
                <div class="box box-primary">
                    <div class="box-body">
                        <table id="customer-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                            <thead>
                                <tr>
                                    <th>วันที่</th>
                                    <th>ชื่อ</th>
                                    <th>รุ่นรถ</th>
                                    <th>เบอร์โทร</th>
                                    <th>จังหวัด</th>
                                    <th>สถานะรถ</th>
                                    <th>สถานะ</th>
                                    <th><?php echo 'Action'; ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
  
 
<div class="modal fade" id="modal-view-link">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa  fa-chain"></i> Link สำหรับส่งให้ฝ่ายขาย</h4>
            </div>
            <div class="modal-body">
                <div class='row'>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="text" class="form-control" id="link_copy" name="link_copy" disabled>
                            <p></p>
                            <span class="label label-success" id='copy_complete' style='display:none;'>คัดลอกลิงค์เรียบร้อย</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="bt_copy_link"><i class="fa  fa-clipboard"></i> คัดลอกลิงค์</button>
            </div>
        </div>
    </div>
</div>
 
<!-- POPUP แสดงรายละเอียดข้อเสนอลูกค้า & พิมพ์ -->
<div class="modal fade" id="modal-view">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-word-o text-aqua"></i> <?php echo 'ข้อมูลลูกค้า';?></h4>
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

<!-- แก้ไขข้อมูลลูกค้า -->
<form class="" id="form-edit-customer">

<div class="modal fade" id="modal-editCustomer">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> <?php echo 'แก้ไขข้อมูลลูกค้า'; ?></h4>
            </div>
            <div class="modal-body">
              
                    <div class="box-body">


                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label"><?php echo $LANG_LABEL['titlename']; //คำนำหน้าชื่อ     ?></label>
                                <input type="text" class="form-control" id="titleName" name="titleName" required>
                                <span class="help-block titleName-error">กรุณาระบุคำนำหน้าชื่อ</span>
                            </div>

                            <div class="form-group">
                                <label class="control-label">ชื่อ - นามสกุล</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <span class="help-block name-error">กรุณาระบุชื่อ - นามสกุล</span>
                            </div>


                            <div class="form-group">
                                <label class="control-label">เบอร์โทร</label>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
                                <span class="help-block phoneNumber-error">กรุณาระบุเบอร์โทรศัพท์</span>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Line ID</label>
                                <input type="text" class="form-control" id="lineID" name="lineID" required>
                                <span class="help-block lineID-error"></span>
                            </div> 

                            <div class="form-group">
                                <label class="control-label"><?php echo $LANG_LABEL['province']; //จังหวัด     ?></label>
                                <select class="form-control" id="province" name="province" required>
                                    <?php echo getData::option('province', 'province_name', '', '', 'id'); ?>
                                </select>
                                <span class="help-block province-error">กรุณาเลือกจังหวัด</span>
                            </div>
                        </div> 

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label">ประเภทรถยนต์</label>
                                <select class="form-control" id="categoryCar" name="categoryCar" required>
                                    <option value=''>ประเภทรถยนต์</option>
                                    <?php echo getData::option('car_type', 'car_type', '', '', 'car_type_id'); ?>
                                </select>
                                <span class="help-block categoryCar-error">กรุณาเลือกประเภทรถยนต์</span>
                            </div>

                            <div class="form-group">
                                <label class="control-label">ยี่ห้อรถยนต์</label>
                                <select class="form-control" id="brandCar" name="brandCar" required>
                                    <option value=''>ยี่ห้อรถยนต์</option>
                                    <?php echo getData::option('car_brand', 'car_brand', '', '', 'car_brand_id'); ?>
                                </select>
                                <span class="help-block brandCar-error">กรุณาเลือกยี่ห้อรถยนต์</span>
                            </div> 


                            <div class="form-group">
                                <label class="control-label">รุ่นย่อย</label>
                                <select class="form-control" id="subbrandCar" name="subbrandCar" required>
                                    <option value=''>รุ่นย่อย</option>
                                </select>
                                <span class="help-block subbrandCar-error">กรุณาเลือกรุ่นย่อย</span>
                            </div>



                            <div class="form-group">
                                <label class="control-label">สี</label>
                                <select class="form-control" id="colorCar" name="colorCar" required>
                                    <option value=''>สี</option>
                                    <?php echo getData::option('car_color', 'car_color', '', '', 'car_color_id'); ?>
                                </select>
                                <span class="help-block colorCar-error">กรุณาเลือกสีรถยนต์</span>
                            </div> 

                            <div class="form-group">
                                <label class="control-label">ดาวน์ กี่ %</label>
                                <input type="text" class="form-control" id="downPaymentPercent" name="downPaymentPercent" required>
                                <span class="help-block downPaymentPercent-error">กรุณาระบุดาวน์กี่ %</span>
                            </div>

                            <div class="form-group">
                                <label class="control-label">จำนวนเงินดาวน์</label>
                                <input type="text" class="form-control" id="downPayment" name="downPayment" required>
                                <span class="help-block downPayment-error">กรุณาระบุจำนวนเงินดาวน์</span>
                            </div>

                            <div class="form-group">
                                <label class="control-label">จำนวนงวด</label>
                                <input type="text" class="form-control" id="installment" name="installment" required>
                                <span class="help-block installment-error">กรุณาระบุผ่อนชำระกี่งวด</span>
                            </div>

                            <div class="form-group">
                                <label class="control-label">สิ่งที่ลูกค้าต้องการมากที่สุด</label>
                                <input type="text" class="form-control" id="customerRequire" name="customerRequire">
                                <span class="help-block customerRequire-error">กรุณาสิ่งที่ลูกค้าต้องการมากที่สุด</span>
                            </div>
                        </div>  

                        <input type="hidden"  name="action" value="update_customer">
                        <input type="hidden"  id="customer_id_edit" name="customer_id_edit"  value="">
                    </div>
               
            </div>
            <div class="modal-footer form-inline" style="left: 0;border-top: 2px solid #99854a;"> 
                   
                    <span>สถานะรถ: </span>
                    <select id="carStatus" name="carStatus" style="height: 40px;min-width: 150px;padding: 0 10px;border: 1px solid #dddddd;border-radius: 3px;">
                        <option value='ยังไม่จอง'>ยังไม่จอง</option>
                        <option value='กำลังดำเนินการ'>กำลังดำเนินการ</option>
                        <option value='รับรถแล้ว'>รับรถแล้ว</option>
                    </select>
                    <button type="button" class="btn btn-success pull-right" id="save-edit-customer" style="margin-left: 25px;"><i class="fa fa-floppy-o"></i> บันทึก</button>
                </div>
        </div>
    </div>
</div>
</form>
<!-- จบแก้ไขข้อมูล customer --> 
<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">

<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/car/customer_list.js?v=<?php echo date('s');?>"></script>