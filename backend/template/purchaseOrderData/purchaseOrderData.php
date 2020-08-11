<div class="content-wrapper berddpage purchasepage">
    <section class="content-header">
      <h1>
        <i class="fa fa-shopping-cart"></i> บันทึกการสั่งซื้อ 

      </h1> 
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
        <li class="active">ข้อมูลการสั่งซื้อสินค้า</li>
      </ol>
	</section>
	<section class="content-header btn-action">
		<div class="btnp-order unselect-txt">
		  <span class="btn btn-order success " data-type="publish">รายการที่สำเร็จ</span>
		  <span class="btn btn-order unsuccess active" data-type="pending">รอดำเนินการ</span>
	
		 </div>
		 <div class="add-more unselect-txt">
		 	<span class="btn add-more-list" onclick="insert_order_list()">เพิ่มรายการ</span>
		</div>
	 </section>
    <section class="content newForm ">
        <div class="row"> 
            <div class="col-xs-12">			 
                <div class="box box-primary"> 
                    <div class="box-body"> 
                        <div class="box-head-action">	 						 
                	        <span>ตารางการสั่งสินค้า</span>
						
							<div class="date_purchase" >  
								<div class="input-group date"> 
									<div class="input-group-addon"> 
										<i class="fa fa-calendar"></i> 
									</div>
									<input type="text" class="form-control pull-right" id="add-date-display" style=" margin-bottom: 0px;"placeholder="กรองข้อมูลโดยวันที่">
									<input type="hidden" class="form-control pull-right" id="add-date-display-hidden">
								</div>
							 </div>  
                        </div>
			            <hr>
			            <table id="purchase-grid" class="table table-striped table-bordered table-hover no-footer" width="100%"> 
                            <thead>  
                               <tr>
							   	 <th>เลขที่สั่งซื้อ</th> 
                                 <th>ชื่อผู้สั่งซื้อ</th>    
								 <th>เบอร์ติดต่อ</th>
								 <th><div class="text-center">วันที่สั่งซื้อ</div></th> 
								 <th><div class="text-center">สถานะ</div></th>  
								 <th><div class="text-center">แก้ไข</div></th>  
								 <th><div class="text-center">ลบ</div></th>                        
                               </tr>
                             </thead>
                        </table> 
                     </div> 
                 </div>
		     </div>  
			 
			 
         </div>
     </section>  
 </div> 
 
 
 
 <!-- css -->
 <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
 <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/purchase.css?v=<?=date('ymdHis')?>"">
<!-- script -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
 <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
 
<script type='module' >
        import {allField} from '<?php echo SITE_URL; ?>js/complete_province.js?v=<?=time()?>'
        window.allFiled = allField;
</script>";
<script src="<?php echo SITE_URL; ?>js/pages/purchase/purchase.js?v=<?=date('ymdHis')?>"></script>
<script src="<?php echo SITE_URL; ?>plugins/jquery-confirm/js/jquery-confirm.min.js"></script>


