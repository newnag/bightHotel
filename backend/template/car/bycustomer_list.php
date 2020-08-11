<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/print/style-car-print.css">
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
            <div class="col-xs-12">
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

<!-- ดูข้อมูลลูกค้า -->
<div class="modal fade" id="modal-viewCustomer">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> ข้อมูลลูกค้า ( ลูกค้า : <span id='customer_name_view'></span>)</h4>
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">ประเภทรถยนต์</label>
                            <span class="lineheight_20" id='text_categoryCar'></span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">ยี่ห้อ</label>
                            <span class="lineheight_20" id='text_brandCar'></span>
                        </div>
                    </div>
                    
                       <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">สี</label>
                            <span class="lineheight_20" id='text_colorCar'></span>
                        </div>
                    </div>
                </div>
                
                  <div class="row">

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">ราคา</label>
                            <span class="lineheight_20" id='text_priceCar'></span>
                        </div>
                    </div>
                      
                       <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">รุ่นย่อย</label>
                            <span class="lineheight_20" id='text_subCar'></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">ชื่อ - สกุล</label>
                            <span class="lineheight_20" id='text_nameCustomer'></span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">เบอร์โทร</label>
                            <span class="lineheight_20" id='text_phoneCustomer'></span>
                        </div>
                    </div>
                    
                       <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">Line ID</label>
                            <span class="lineheight_20" id='text_lineCustomer'></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">การดาวน์</label>
                            <span class="lineheight_20" id='text_downPaymentPercentCustomer'></span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">จำนวนเงินดาวน์</label>
                            <span class="lineheight_20" id='text_downPaymentCustomer'></span>
                        </div>
                    </div>
                    
                       <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">ผ่อนชำระ</label>
                            <span class="lineheight_20" id='text_installmentCustomer'></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">สิ่งที่ลูกค้าต้องการ</label>
                            <span class="lineheight_20" id='text_customerRequire'></span>
                        </div>
                    </div>                   
                </div>

            </div>
            <div class="modal-footer">           
                <button type="button" class="btn btn-primary" id="print-customer"><i class="fa fa-printer"></i> พิมพ์</button>
            </div>
        </div>
    </div>
</div>
<!-- จบดูข้อมูล customer -->

<!-- POPUP แสดงรายการตอบกลับลูกค้า -->
<div class="modal fade" id="modal-view-reply">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-text text-green"></i> <?php echo ' รายการเสนอเงื่อนไข'; //แก้ไขข้อมูลของผู้ดูแลระบบ        ?> ( ลูกค้า : <span id='customer_name'></span>)</h4>
            </div>
            <div class="modal-body">
                <table id="custoemrReply" class="table table-striped table-bordered table-hover no-footer" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;">วันที่ส่ง</th>
                            <th style="text-align: center;">ชื่อฝ่ายขาย</th>
                            <th style="text-align: center;">เบอร์โทร</th>
                            <th style="text-align: center;">ที่ทำงาน</th>
                            <th style="text-align: center;">#</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
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
<div class="modal fade" id="modal-customerReply">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-word-o text-aqua"></i> <?php echo 'เสนอเงื่อนไขลูกค้า'; //        ?></h4>
            </div>
            <div class="modal-body">
              <section class="invoice">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">ชื่อลูกค้า</label>
                            <span class="lineheight_20" id='reply_nameCustomer'></span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">เบอร์โทร</label>
                            <span class="lineheight_20" id='reply_phoneCustomer'></span>
                        </div>
                    </div>
                    
                       <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">Line ID</label>
                            <span class="lineheight_20" id='reply_lineCustomer'></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">ประเภทรถยนต์</label>
                            <span class="lineheight_20" id='reply_categoryCar'></span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">ยี่ห้อ</label>
                            <span class="lineheight_20" id='reply_brandCar'></span>
                        </div>
                    </div>
                    
                       <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">สี</label>
                            <span class="lineheight_20" id='reply_colorCar'></span>
                        </div>
                    </div>
                </div>
                
                  <div class="row">

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">ราคา</label>
                            <span class="lineheight_20" id='reply_priceCar'></span>
                        </div>
                    </div>
                      
                       <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">รุ่นย่อย</label>
                            <span class="lineheight_20" id='reply_subCar'></span>
                        </div>
                    </div>
                </div> 

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">การดาวน์</label>
                            <span class="lineheight_20" id='reply_downPaymentPercentCustomer'></span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">จำนวนเงินดาวน์</label>
                            <span class="lineheight_20" id='reply_downPaymentCustomer'></span>
                        </div>
                    </div>
                    
                       <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">ผ่อนชำระ</label>
                            <span class="lineheight_20" id='reply_installmentCustomer'></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_5 maginRight_15 lineheight_20">สิ่งที่ลูกค้าต้องการ</label>
                            <span class="lineheight_20" id='reply_customerRequire'></span>
                        </div>
                    </div>                   
                </div>


                </section>

                <section class="invoice">
                    
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ชื่อฝ่ายขาย</label>
                                <span class="lineheight_20" id='reply_nameSales'></span>
                        </div>
    
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">เบอร์โทร</label>
                                <span class="lineheight_20" id='reply_phoneSales'></span>
                        </div>
                        
                           <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="wrap">
                                <label class="padding_5 maginRight_15 lineheight_20">Line ID</label>
                                <span class="lineheight_20" id='reply_lineCustomer'></span>
                            </div>
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ดาวน์</label>
                                <span class="lineheight_20" id='reply_downPaymentPercentSales'></span>
                        </div>
    
    
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">จำนวนเงินดาวน์</label>
                                <span class="lineheight_20" id='reply_downPaymentSales'></span>
                        </div>
                    </div>   
                    
    
                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">48งวด</label>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ผ่อนงวดละ</label>
                                <span class="lineheight_20" id='reply_installMent48'></span>
                        </div>  
                        
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ดอกเบี้ย</label>
                                <span class="lineheight_20" id='reply_interest48'></span>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">60งวด</label>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ผ่อนงวดละ</label>
                                <span class="lineheight_20" id='reply_installMent60'></span>
                        </div>  
                        
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ดอกเบี้ย</label>
                                <span class="lineheight_20" id='reply_interest60'></span>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">72งวด</label>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ผ่อนงวดละ</label>
                                <span class="lineheight_20" id='reply_installMent72'></span>
                        </div>  
                        
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ดอกเบี้ย</label>
                                <span class="lineheight_20" id='reply_interest72'></span>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-md-2 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">84งวด</label>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ผ่อนงวดละ</label>
                                <span class="lineheight_20" id='reply_installMent84'></span>
                        </div>  
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ดอกเบี้ย</label>
                                <span class="lineheight_20" id='reply_interest84'></span>
                        </div> 
                    </div>

                    <div class="row installMentOther" style="display:none">
                        <div class="col-md-2 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20" id='reply_installMentOther'></label>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ผ่อนงวดละ</label>
                                <span class="lineheight_20" id='reply_installMentOtherValue'></span>
                        </div>  
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ดอกเบี้ย</label>
                                <span class="lineheight_20" id='reply_interestOtherValue'></span>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ส่วนลด</label>
                                <span class="lineheight_20" id='reply_discountMoney'></span>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-12">
                                <label class="padding_5 maginRight_15 lineheight_20">ของแถม</label>
                                <span class="lineheight_20" id='reply_bonusFree'></span>
                        </div>  
                    </div>
                 
                 <div class="row" id='reply_listPrice' style='display:none'></div>
    
    
    
                    </section>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<!-- แก้ไขข้อมูลลูกค้า -->
<div class="modal fade" id="modal-editCustomer">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> <?php echo 'แก้ไขข้อมูลลูกค้า'; ?></h4>
            </div>
            <div class="modal-body">
                <form class="" id="form-edit-customer">
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
                </form>
            </div>
            <div class="modal-footer">           
                <button type="button" class="btn btn-primary" id="save-edit-customer"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save']; //Save Changes     ?></button>
            </div>
        </div>
    </div>
</div>
<!-- จบแก้ไขข้อมูล customer --> 

<input type='hidden' value='' id='customer_id' />
<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">

<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/car/customer_list.js?v=<?php echo date('s');?>"></script>