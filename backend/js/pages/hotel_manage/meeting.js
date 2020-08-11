
 
  //ใส่ event เมื่อมีการเปลี่ยนรูป
  $(".page_hotel_manager").on("change",".room-meeting #edit-images-thumbnail", function (event) {
    files = event.target.files;
    var data = new FormData();
    data.delete("images[]");
    $.each(files, function (key, value) {
      data.append("images[]", ("images" + (key + 1), value));
    });
 
    data.append('action', 'uploadthumbnail');
    data.append('id', $(this).data('id'));
    $.ajax({
      url: site_url+ "ajax/ajax.hotel_manage_meeting.php",
      type: "POST",
      data: data,
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false, 
      success: function (response) { 
        $(".room-meeting #edit-images-thumbnail-hidden").val(response['url']);
        $(".img_thumbnail .blog-preview-edit").html(response['image']);
      }  
    });
});


$(".page_hotel_manager").on("change",".room-meeting #edit-more-images",function (event) {
   
    files = event.target.files;
    var data = new FormData();
    data.delete("images[]");
    $.each(files, function (key, value) {
      data.append("images[]", ("images" + (key + 1), value));
    });

    data.append('action', 'uploadmoreimgmeeting');
    data.append('id', $(this).data('id'));
    $.ajax({
      url: site_url+ "ajax/ajax.hotel_manage_meeting.php",
      type: "POST",
      data: data,
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function (event) {
        $('#prog-edit').progressbar({ value: 0 });
        $('#overlay-edit-more-img').css('display', 'block');
        // console.log(event);
      },
      progress: function (e) {
        if (e.lengthComputable) {
          var pct = (e.loaded / e.total) * 100;
          // console.log(pct);
          $('#prog-edit')
            .progressbar('option', 'value', pct)
            .children('.ui-progressbar-value')
            .html(pct.toPrecision(3) + '%');
        } else {
          // console.warn('Content Length not reported!');
        }
      },
      success: function (msg) {
        var obj = jQuery.parseJSON(msg),
          img_list = '';
        //not fix 
        for (i = 0; i < obj.length; i++) {
          img_list += '\
          <div class="blog-show-image" data-id="'+ obj[i].image_id + '">\
            <div class="iconimg id_imgmore-edit" id="img-delete" data-id="'+ obj[i].image_id + '" data-name="' + obj[i].image_link + '">\
              <i class="fa fa-times" alt="delete"></i>\
            </div>\
            <div id="image-preview">\
              <div class="col-img-preview">\
                <img class="preview-img" src="'+ root_url + obj[i].image_link + '">\
              </div>\
            </div>\
          </div>';
        }
        $("#show-img-more").append(img_list);
      },
      complete: function () {
        $('#prog-edit').progressbar({ value: 0 });
        $('#overlay-edit-more-img').css('display', 'none');
      }
    });
  });


$(".page_hotel_manager").on('click','.room-meeting #img-delete', function(){ 

  let param = {
    action: "delete_images_by_id",
    id: $(this).data('id')
  }
  $.ajax({
    url: url_ajax_request + "ajax/ajax.hotel_manage_meeting.php",
    type: 'POST',
    dataType: 'json',
    data: param,
    success: function(response){
      console.log(response)
      if(response['message'] == "OK"){
        $(".page_hotel_manager .room-meeting #show-img-more .blog-show-image[data-id='"+param.id+"']").remove();
      }
    },
    error:function(error){
      console.log('error')
      }
  })

});
 


/*
* this section for modal add or edit product
*/

function add_room_meeting(){

}

$(".action-btn").on("click",".edit-product",function(){
    edit_room_meeting($(this).data('id'));
});
function edit_room_meeting(_id){
    $.ajax({
        url: site_url + "ajax/ajax.hotel_manage_meeting.php",
       type: 'POST',
       dataType: 'json',
       data: {action: "edit_room_meeting" ,id: _id },
       success: function(response){
            prepare_form(response,_id,"update_room_meeting");
       },
       error: function(){
            console.log('error')
       }
    }); 
}

function prepare_form(param,_id,_action){
    Swal.fire({
        width: "900px",
        customClass: {
            popup: 'room-meeting',
        },
        title: param['modatl_title'],
        html: param['html'],
        input: 'checkbox',
        inputValue: 1,
        showCancelButton: true,
        confirmButtonText: param['modal_btn'],
        cancelButtonText: 'ยกเลิก',
        showLoaderOnConfirm: true,
        preConfirm: (_otp) => { 
            let display =  ($("#displayStatus").prop("checked"))?"yes":"no";
            let myparam = {
              id: _id,
              title: $(".room-meeting .txt_title").val(),
              desc: $(".room-meeting .txt_desc").val(),
              roomamount: $(".room-meeting .txt_room_amount").val(),
              thumbnail: $("#edit-images-thumbnail-hidden").val(),
              facility: ",",
              action: _action,
              display
            }
            if(myparam.thumbnail == ""){
              $(".room-meeting  .img_thumbnail").addClass("invalid");
              Swal.showValidationMessage(
                `กรุณาใส่รูปภาพ`
              )
            }else{
              $(".room-meeting  .img_thumbnail").removeClass("invalid");
            }
            $.each($(".modal-facility-list input.chk_fac:checked"),function(key,val){
                myparam.facility += $(this).val()+",";
            })
            $.each($(".room-meeting input.form-control"), function(key, val){ 
              if($(this).val() == ""){
                  Swal.showValidationMessage(
                    `กรุณาระบุข้อมูล ${$(this).data('name')}`
                  )
              }
            });
        
            return myparam;
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => { 
        if(result.value){
          update_room_meeting(result.value);  
        }
    
    }) 
}

function update_room_meeting(param){
    $.ajax({
      url: site_url+ "ajax/ajax.hotel_manage_meeting.php",
      type: 'POST',
      dataType: 'json',
      data: param,
      success: function(response){
          Swal.fire({
            position: 'top-center',
            icon: response['update'],
            title:  response['message'], 
            showConfirmButton: false,
            timer: 1500
          }).then((result) => {
              location.reload();
          });
     
      },
      error:function(error){

      }
    });
}

$(".page_hotel_manager ").on("change",'#add-images-content',function () { 
    let file = this.files[0];
    if (file.length !== 0) { 
      var img = file.name;
      $('#add-images-content-hidden').val(img);
      $(".form-add-images").removeClass("has-error");
      $(".add-images-error").css("display", "none"); 
      editPreviewImage(file,'uploadImage_reviews');
    }
});



$(".page_hotel_manager").on('click','.delete-product',function(){
  Swal.fire({
    title: 'ยืนยันการลบ?',
    text: "คุณต้องการลบห้องพักนี้!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'ยืนยัน',
    cancelButtonText: 'ยกเลิก'
  }).then((result) => {
    if(result.value){
        let param  =  {
          action: "delete_room_meeting",
          id: $(this).data("id")
        }
          $.ajax({
            url: site_url + "ajax/ajax.hotel_manage_meeting.php",
            type: 'POST',
            data: param,
            dataType: 'json',
            success: function (response) { 
              Swal.fire({
                position: 'top-center',
                icon: response['status'],
                title:  response['message'], 
                showConfirmButton: false,
                timer: 1500
              }).then((result) => {
                  location.reload();
              });
            }
          });
      }
    });
});

$(".page_hotel_manager").on("click",".add-room-meeting",function(){
    $.ajax({
      url: site_url + "ajax/ajax.hotel_manage_meeting.php",
     type: 'POST',
     dataType: 'json',
     data: {action: "prepare_add_room_meeting" },
     success: function(response){
          prepare_form(response,0,"add_room_meeting");
     },
     error: function(){
          console.log('error')
     }
  }); 
});