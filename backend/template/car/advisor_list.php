<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/print/style-car-print.css">

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-users text-aqua"></i> รายการผู้แนะนำ
        <small>( <?php echo $language_fullname['display_name']; ?> )</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     ?></a></li>
        <li class="active">รายการผู้แนะนำ</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class=" col-xs-12 col-sm-12 col-md-10">

          <div class="box box-primary">
            <div class="box-body">
              <table id="advisor-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                  <thead>
                    <tr>
                    <th>วันที่</th>
                    <th>ชื่อผู้แนะนำ</th>
                    <th>เบอร์ผู้แนะนำ</th>
                    <th>ลูกค้าแนะนำ</th>
                    <th>เบอร์ลูกค้าแนะนำ</th>
                    <th>จังหวัด</th>
                    <th>รุ่นรถ</th>
                    <th>สถานะ</th>
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


<!-- POPUP แสดงรายละเอียดข้อเสนอลูกค้า & พิมพ์ -->
<div class="modal fade" id="modal-view">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">รายละเอียดข้อมูลผู้แนะนำ</h4>
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

 <!-- POPUP แสดงรายการตอบกลับลูกค้า -->

<!--  
  <div class="modal fade" id="modal-view">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-file-text text-green"></i> รายละเอียดข้อมูลผู้แนะนำ</h4>
        </div>
        <div class="modal-body">
          <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <h3>ผู้แนะนำ</h3>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_10 maginRight_15 lineheight_30">ชื่อ - นามสกุล</label>
                            <span class="lineheight_30" id='text_adivisorName'></span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_10 maginRight_15 lineheight_30">เบอร์มือถือ</label>
                            <span class="lineheight_30" id='text_adivisorPhone'></span>
                        </div>
                    </div>
                    
                       <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_10 maginRight_15 lineheight_30">Line ID</label>
                            <span class="lineheight_30" id='text_adivisorLine'></span>
                        </div>
                    </div>
          </div>

          <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 box-border">
                      <h3>ลูกค้าแนะนำ</h3>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_10 maginRight_15 lineheight_30">ชื่อ - นามสกุล</label>
                            <span class="lineheight_30" id='text_customerName'></span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_10 maginRight_15 lineheight_30">เบอร์มือถือ</label>
                            <span class="lineheight_30" id='text_customerPhone'>d</span>
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_10 maginRight_15 lineheight_30">Line ID</label>
                            <span class="lineheight_30" id='text_customerLine'></span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_10 maginRight_15 lineheight_30">ยี่ห้อรถ</label>
                            <span class="lineheight_30" id='text_customerCar'></span>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="wrap">
                            <label class="padding_10 maginRight_15 lineheight_30">จังหวัด</label>
                            <span class="lineheight_30" id='text_customerProvince'></span>
                        </div>
                    </div>
          </div>
        </div>
        <div class="modal-footer">           
                <button type="button" class="btn btn-primary" id="printOut"><i class="fa fa-printer"></i> พิมพ์</button>
            </div>
      </div>
    </div>
  </div>  -->
  <!-- css -->

  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">

  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/car/advisor_list.js?v=<?php echo date('s');?>"></script>