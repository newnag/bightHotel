<div class="content-wrapper reviewspage">
    <section class="content-header">
      <h1>
        <i class="fas fa-list-alt"></i> ข้อมูลการจองห้องพัก 
      </h1>
      <ol class="breadcrumb"> 
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
        <li class="active">ข้อมูลการจองห้องพัก</li>
      </ol>
    </section>  
    <section class="content newForm ">
      <div class="row"> 
        <div class="col-lg-12">			
          <div class="box box-primary">
            <div class="box-body"> 
                <div class="box-head-action">							 
                <span>ข้อมูลโปรโมชั่น</span>
                <div style="display:flex;">
                  <input type="date" class="date-checkin" value=""  placeholder="เลือกวันที่" style="margin-right:10px;">
                  <select id="slc_status_order" class="form-control" style="max-width:200px">
                    <option SELECTED DISABLED value="">เลือกสถานะ</option>
                    <option value="publish">สำเร็จ</option>
                    <option value="pending">รอดำเนินการ</option>
                    <option value="payment">รอชำระเงิน</option>
                    <option value="fail">ผิดพลาด</option>
                  </select>
                </div>
        
			          <!-- <button  type="button" onclick="prepare_promotion()" class="btn btn-table-add"><i class="fa fa-fw fa-plus"></i> เพิ่มโปรโมชั่น </button> -->
			     </div>
			    <hr>
		        <table id="admin-grid" class="table table-striped table-bordered table-hover no-footer" width="100%"> 
                 <thead> s
                    <tr>
                      <th>เลขที่</th>
                      <th>ห้องพัก</th>            
                      <th>วันที่เข้า-ออก</th>  
                      <th>การเข้าพัก</th>       
                      <th>ส่วนลดทั้งหมด</th>       
                      <th>ราคา</th>      
                      <th>สถานะการจอง</th>    
                      <th>ข้อมูลติดต่อ</th>  
                      <th>วันที่ทำรายการ</th>  
                      <th>จัดการ</th>
                    </tr> 
                  </thead>
              </table>
            </div>
          </div>
	    </div> 
			 
      </div>
		</section>
  </div>
 </div>
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/reviews.css?v=<?=rand(1,1000)?>">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/meStyle.css?v=<?=rand(1,1000)?>">

 <!-- script -->
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.3/flatpickr.min.js"></script>
 <script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
 <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
 <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
 <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script> 
 <script src="<?php echo SITE_URL; ?>js/pages/reserve/room.js?v=<?=date('ymd-his')?>"></script>
 <script src="<?php echo SITE_URL; ?>plugins/jquery-confirm/js/jquery-confirm.min.js"></script>


  
 