<?php
  $tour_detail = $mydata->get_tour_by_id($_GET['tour_id']);
  $program_tour = $mydata->get_programtour($_GET['tour_id']);
?>
<style>
  .profile-user-img {
    margin: 0 auto;
    width: 150px;
    height: 120px;
    padding: 3px;
    border: 0;
  }
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-calendar"></i> โปรแกรมทัวร์
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
      <li><a href="<?php echo SITE_URL.'?page=tour'; ?>">แพ็คเกจทัวร์</a></li>
      <li class="active">โปรแกรมทัวร์</li>
    </ol>
  </section>
  <section class="content">

    <div class="row">

      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <img class="profile-user-img img-responsive " src="<?php echo SITE_URL.'classes/thumb-generator/thumb.php?src='.ROOT_URL.$tour_detail[$_GET['tour_id']]['thumbnail'].'&size=150x120'; ?>" alt="User profile picture">

            <h3 class="profile-username text-center"><?= $tour_detail[$_GET['tour_id']]['title'] ?></h3>
            <hr>

            <strong>คำอธิบาย</strong>
            <p class="text-muted">
              <?= $tour_detail[$_GET['tour_id']]['description'] ?>
            </p>
            <hr>

            <strong>ระยะเวลา</strong>
            <p class="text-muted">
              <?= $tour_detail[$_GET['tour_id']]['h1'].' วัน '.$tour_detail[$_GET['tour_id']]['h2'].' คืน' ?>
            </p>
            <hr>

          </div>
        </div>
      </div>

      <div class="col-md-9">

        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">โปรแกรมทัวร์</h3>
            </div>
            <div class="box-body no-padding">

              <div class="box-body no-padding">
                <div class="categorybox-controls category-box">
                  <div class="pull-right">
                    <button type="button" class="btn btn-attree" data-toggle="modal" data-target="#modalAddProgramTour"><i class="fa fa-plus"></i> เพิ่มโปรแกรมทัวร์</button>
                  </div>
                </div>
              </div>

              <?php
              if (!empty($program_tour)) {
              ?>
              <div class="table-responsive box-body">
                <table class="table table-hover table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>รายละเอียด</th>
                      <th style="width: 136px;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($program_tour as $key => $value) {
                    ?>
                    <tr>
                      <td><?= $value['position'] ?></td>
                      <td><?= $value['program_title'] ?></td>
                      <td>
                        <?php
                          if(strpos($value['lang_info'],$_SESSION['backend_language'])){
                        ?>
                        <a data-id="<?= $value['program_id'] ?>" data-type="edit" data-toggle="modal" data-target="#modalEditProgramTour" class="btn btn-success btn-xs edit-program-tour" style="margin-right: 7px;">
                          <i class="fa fa-pencil-square-o"></i> แก้ไข
                        </a> |
                        <a data-id="<?= $value['program_id'] ?>" class="btn btn-danger btn-xs delete-program-tour" style="margin-left: 7px;">
                          <i class="fa fa-trash-o"></i> ลบ
                        </a>
                        <?php
                          }else {
                        ?>
                          <a data-id="<?= $value['program_id'] ?>" data-type="add" data-toggle="modal" data-target="#modalEditProgramTour" class="btn btn-primary btn-xs edit-program-tour" style="margin-right: 7px;">
                            <i class="fa fa-plus"></i> เพิ่ม
                          </a>
                        <?php
                          }
                        ?>
                      </td>
                    </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <?php
              }else {
                echo '
                <div class="box-body">
                  <div class="search-found">
                    <i class="fa fa-warning" aria-hidden"true"=""></i> ยังไม่มีข้อมูล
                  </div>
                </div>';
              }
              ?>

            </div>
          </div>

      </div>

    </div>
    <input type="hidden" id="tour-id" value="<?= $_GET['tour_id'] ?>">
  </section>
</div>

<!-- Modal Add Program Tour -->      
<div id="modalAddProgramTour" class="modal fade blog-content blog-content-sm" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> เพิ่มโปรแกรมทัวร์</h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">

          <div class="col-content col-md-12 scrollbar" id="scrollbar-add">
            <form id="form-add-content">

              <div class="col-md-12">
                <div class="form-group form-add-title">
                  <label>หัวเรื่อง</label>
                  <input type="text" class="form-control" id="add-title" name="add-title" placeholder="Title">
                  <span class="help-block add-title-error">Please fill out this field.</span>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-add-position">
                  <label>ลำดับ</label>
                  <input type="number" class="form-control" id="add-position" name="add-position" placeholder="Position" min="0">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>รายละเอียด</label>
                  <textarea class="form-input" id="add-content" name="add-content"></textarea>
                </div>  
              </div>
            </form>
          </div>

        </div>
      </div>
      <div class="modal-footer "> 
        <button type="submit" class="btn btn-success pull-right" id="save-add">
          <i class="fa fa-floppy-o"></i> บันทึก
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Program Tour -->      
<div id="modalEditProgramTour" class="modal fade blog-content blog-content-sm" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> แก้ไขโปรแกรมทัวร์</h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">

          <div class="col-content col-md-12 scrollbar" id="scrollbar-add">
            <form id="form-edit-content">

              <div class="col-md-12">
                <div class="form-group form-edit-title">
                  <label>หัวเรื่อง</label>
                  <input type="text" class="form-control" id="edit-title" name="edit-title" placeholder="Title">
                  <span class="help-block edit-title-error">Please fill out this field.</span>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group form-edit-position">
                  <label>ลำดับ</label>
                  <input type="number" class="form-control" id="edit-position" name="edit-position" placeholder="Position" min="0">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>รายละเอียด</label>
                  <textarea class="form-input" id="edit-content" name="edit-content"></textarea>
                </div>  
              </div>
            </form>
          </div>

        </div>
      </div>
      <div class="modal-footer "> 
        <input type="hidden" id="program-tour-id" value="">
        <input type="hidden" id="submit-type" value="">
        <button type="submit" class="btn btn-success pull-right" id="save-edit">
          <i class="fa fa-floppy-o"></i> บันทึก
        </button>
      </div>
    </div>
  </div>
</div>


<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/ckeditor/ckeditor.js"></script>
<script>
// Add
// editor content
CKEDITOR.replace('add-content', {
  filebrowserUploadUrl  :"/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=File",
  filebrowserImageUploadUrl : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Image",
  filebrowserFlashUploadUrl : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Flash",
  height: 400,
  language: 'th'
});

$('#modalAddProgramTour').on('hide.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    document.getElementById("form-add-content").reset();
    CKEDITOR.instances['add-content'].setData('');

    $(".form-add-title").removeClass("has-error");
    $(".add-title-error").css("display","none");
  }
});

function validate_add_content(data) {
  var tour_id = $("#tour-id"),
      title = $("#add-title"),
      position = $("#add-position"),
      content = CKEDITOR.instances["add-content"].getData()
      ;

  //validate title
  if (title.val().length < 1) {
    title.focus();
    $(".form-add-title").addClass("has-error");
    $(".add-title-error").css("display","block");
    return false;
  } else {
    $(".form-add-title").removeClass("has-error");
    $(".add-title-error").css("display","none");
  }

  var data = {
      action: "addprogramtour",
      tourid: tour_id.val(),
      title: title.val(),
      position: position.val(),
      content: content,
  };
  add_programtour(data);
  // console.log(data);
}

function add_programtour(data) {
  var url = url_ajax_request + "ajax/ajax.tour.php",
            dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      if(obj.data['message'] === "OK"){
        location.reload();
      }else {
        console.log(obj);
      }
    }
  });
}

$("#save-add").on("click", function(){ 
  validate_add_content();
});

// Edit
// editor content
CKEDITOR.replace('edit-content', {
  filebrowserUploadUrl  :"/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=File",
  filebrowserImageUploadUrl : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Image",
  filebrowserFlashUploadUrl : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Flash",
  height: 400,
  language: 'th'
});

$('#modalEditProgramTour').on('hide.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    document.getElementById("form-edit-content").reset();
    CKEDITOR.instances['edit-content'].setData('');

    $('#submit-type').val("");
    $(".form-edit-title").removeClass("has-error");
    $(".edit-title-error").css("display","none");
  }
});

$(".edit-program-tour").on("click", function(){  
  var id = $(this).data("id"),
      submitType = $(this).data("type");
  $.ajax({
    type:"POST",
    url:"ajax/ajax.tour.php",
    data:{action:"getprogramtour",
          id:id},
    beforeSend: function() {
      document.getElementById("form-edit-content").reset();
      CKEDITOR.instances['edit-content'].setData('');
      $('#program-tour-id').val('');
      $('#edit-title').val('');
      $('#edit-position').val('');
      $('#submit-type').val('');
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);

      $('#submit-type').val(submitType);
      $('#program-tour-id').val(obj.data["0"].program_id);
      $('#edit-title').val(obj.data["0"].program_title);
      $('#edit-position').val(obj.data["0"].position);
      CKEDITOR.instances['edit-content'].setData(obj.data["0"].program_detail);

    }
  });
});

function validate_edit_content(data) {
  var tour_id = $("#tour-id"),
      id = $('#program-tour-id'),
      title = $("#edit-title"),
      position = $("#edit-position"),
      content = CKEDITOR.instances["edit-content"].getData()
      ;

  //validate title
  if (title.val().length < 1) {
    title.focus();
    $(".form-edit-title").addClass("has-error");
    $(".edit-title-error").css("display","block");
    return false;
  } else {
    $(".form-editd-title").removeClass("has-error");
    $(".edit-title-error").css("display","none");
  }

  var data = {
      action: "editprogramtour",
      id: id.val(),
      tourid: tour_id.val(),
      title: title.val(),
      position: position.val(),
      content: content,
      submitType: $("#submit-type").val()
  };
  edit_programtour(data);
  // console.log(data);
}

function edit_programtour(data) {
  var url = "ajax/ajax.tour.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if(obj.data['message'] === "OK"){
        location.reload();

      }else {
        console.log(obj);
      }
    }
  });
}

$("#save-edit").on("click", function(){ 
  validate_edit_content();
});

//Delete
$(".delete-program-tour").on("click", function(){
  var data = {
    action: "deleteprogramtour",
    id: $(this).data("id")
  };
  $.confirm({
    title: 'Are you sure?',
    content: 'you want to delete this content.',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'Yes, delete it!',
        btnClass: 'btn-red',
        action: function(){
          delete_content(data);
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});

function delete_content(data) {
  var url = "ajax/ajax.tour.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      console.log(obj);
      if (obj['message'] === "OK") {
        $.confirm({
          title: 'Deleted!',
          content: 'Successfully Deleted!',
          theme: 'modern',
          icon: 'fa fa-check',
          type: 'darkgreen',
          draggable: false,
          backgroundDismiss: true,
          buttons: {
            confirm:  {
              text: 'OK',
              btnClass: 'btn-darkgreen',
              action: function(){
                location.reload();
              }
            }
          },
          backgroundDismiss: function(){
            location.reload();
          }
        });
      }
    }
  });
}
</script>