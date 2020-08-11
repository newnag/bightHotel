<!-- <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/print/style-car-print.css"> -->
<div class="content-wrapper">
<section class="content-header">
    <h1>
        <i class="fa fa-user"></i> รายการตอบกลับลูกค้า
        <small>( <?php echo $language_fullname['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก         ?></a></li>
        <li class="active">รายการตอบกลับลูกค้า</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class=" col-xs-12 col-sm-12 col-md-10">
            <div class="box box-primary">
                <div class="box-body">
                    <table id="reply-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>ชื่อลูกค้า</th>
                                <th>เบอร์ลูกค้า</th>
                                <th>จังหวัด</th>
                                <th>ชื่อฝ่ายขาย</th>
                                <th>เบอร์ฝ่ายขาย</th>
                                <th>รุ่นรถ</th>
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


<!-- POPUP แสดงรายละเอียดข้อเสนอลูกค้า & พิมพ์ -->
<div class="modal fade" id="modal-customerReply">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-file-word-o text-aqua"></i> <?php echo 'เสนอเงื่อนไขลูกค้า';?></h4>
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
<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">

<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/car/reply_list.js?v=<?php echo date('ss');?>"></script>