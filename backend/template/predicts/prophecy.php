<div class="content-wrapper page-prophecy">
    <section class="content-header">
      <h1>
        <i class="fas fa-yin-yang"></i> ทำนายเบอร์ 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>

        <li class="active">ทำนายเบอร์</li>
      </ol>
    </section>  
  
    <section class="content newForm ">
      <div class="row"> 
        <div class="col-lg-9 col-md-11 col-xs-12">			
          <div class="box box-primary">
            <div class="box-body"> 
			        <div class="box-head-action">							 
			          <span>ข้อมูลการทำนายเบอร์</span>
			          <button  type="button" onclick="add_prophecy()" class="btn btn-table-add"><i class="fa fa-fw fa-plus"></i> เพิ่มหมายเลข</button>
			         </div>
			        <hr>
		         
              <div id="prophecy-tables">
                <table id="prophecy-grid" class="table table-striped table-bordered table-hover no-footer "  width="100%"> 
                  <thead>  
                  <tr>
                    <th class="">หมายเลข</th>
                    <th class="">ความหมาย</th>  
                    <th class="">รายละเอียด</th>  
                    <th class="text-center">เปอร์เซ็นต์</th>  
                    <th class="text-center">จัดการ</th>   
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
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/predicts.css?v=<?=date('dhis')?>">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/meStyle.css?v=<?=date('dhis')?>"> 

  <!-- script -->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>	 
  <script src="<?php echo SITE_URL; ?>js/pages/predicts/prophecy.js?v=<?=date('dhis')?>"></script>

 