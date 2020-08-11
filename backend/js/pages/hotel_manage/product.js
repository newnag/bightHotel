/*
* this section for modal add or edit product
*/

function add_room_product(){

}

$(".action-btn").on("click",".edit-product",function(){
    edit_room_product($(this).data('id'));
});
function edit_room_product(_id){
    $.ajax({
        url: site_url + "ajax/ajax.hotel_manage.php",
       type: 'POST',
       dataType: 'json',
       data: {action: "edit_room_product" ,id: _id },
       success: function(response){
            prepare_form(response,_id,"update_room_product");
       },
       error: function(){
            console.log('test')
       }
    }); 
}

function prepare_form(param,_id,_action){
    Swal.fire({
        width: "900px",
        customClass: {
            popup: 'room-product',
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
            let display =  ($("#displayStatus").prop("checked"))?"active":"no";
            let myparam = {
              id: _id,
              name: $(".room-product .txt_name").val(),
              title: $(".room-product .txt_title").val(),
              desc: $(".room-product .txt_desc").val(),
              roomamount: $(".room-product .txt_room_amount").val(),
              price: $(".room-product .txt_price").val(),
              currentprice: $(".room-product .txt_current_price").val(),
              extra: $(".room-product .txt_extra").val(),
              timein: $(".room-product .txt_timein").val(),
              timeout: $(".room-product .txt_timeout").val(),
              thumbnail: $("#edit-images-thumbnail-hidden").val(),
              facility: ",",
              action: _action,
              display
            }
            if(myparam.thumbnail == ""){
              $(".room-product  .img_thumbnail").addClass("invalid");
              Swal.showValidationMessage(
                `กรุณาใส่รูปภาพ`
              )
            }else{
              $(".room-product  .img_thumbnail").removeClass("invalid");
            }
            $.each($(".modal-facility-list input.chk_fac:checked"),function(key,val){
                myparam.facility += $(this).val()+",";
            })
            $.each($(".room-product input.form-control"), function(key, val){ 
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
          update_room_product(result.value);  
        }
    
    }) 
}

function update_room_product(param){
    $.ajax({
      url: site_url+ "ajax/ajax.hotel_manage.php",
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
          action: "delete_room_product",
          id: $(this).data("id")
        }
          $.ajax({
            url: site_url + "ajax/ajax.hotel_manage.php",
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

$(".page_hotel_manager").on("click",".add-room-product",function(){
    $.ajax({
      url: site_url + "ajax/ajax.hotel_manage.php",
     type: 'POST',
     dataType: 'json',
     data: {action: "prepare_add_room_product" },
     success: function(response){
          prepare_form(response,0,"add_room_product");
     },
     error: function(){ 
          console.log('error')
     }
  }); 
});



$(".page_hotel_manager").on('click','.change_price',function(){
  let oldp = $(".page_hotel_manager .txt_change_price").data('old');
  let newp = $(".page_hotel_manager .txt_change_price").val();
  let param = {
    action: 'change_room_current_price',
    price: $(".page_hotel_manager .txt_change_price").val(),
    id: $(this).data('id')
  }
  if(oldp != newp){
    Swal.fire({
      title: $(this).data('name'),
      text: "คุณต้องการปรับราคาห้องปัจจุบันเป็น: "+$(".page_hotel_manager .txt_change_price").val()+' บาท',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'ยืนยัน!'
    }).then((result) => {
      if (result.value) {
        change_room_price(param);    
      }
    });
  }else{
    Swal.fire({
      icon: 'warning',
      title: 'ไม่พบการเปลี่ยนแปลงราคา!',
      showConfirmButton: false,
      timer: 1500
    })
  }
});

function change_room_price(param){
  $.ajax({
    url: site_url + "ajax/ajax.hotel_manage.php",
    type: 'POST',
    dataType: 'json',
    data: param,
    success: function(response){ 
      if(response['status'] == 'success'){
        console.log(param);
        $(".page_hotel_manager .txt_change_price[data-id='"+param.id+"']").data('old',param.price);
      }
      Swal.fire({
        position: 'top-center',
        icon: response['status'],
        title:  response['message'], 
        showConfirmButton: false,
        timer: 1500
      });
    },
    errror: function(error){
      console.log('error')
    }
  });
}


$(".page_hotel_manager").on('click','.increase_room',function(){
  let param = {
    action: 'room_increasing',
    id: $(this).data('id')
  }
  let numb =  $('.page_hotel_manager .room-current-amount[data-id="'+$(this).data('id')+'"]').html();
  numb = (Number(numb)) + 1;
  let timerInterval;
  Swal.fire({
    title: 'Incresing!',
    html: 'กำลังเพิ่มจำนวนห้องพัก.',
    timer: 1000,
    timerProgressBar: true,
    onBeforeOpen: () => {
      Swal.showLoading()
      timerInterval = setInterval(() => {
        const content = Swal.getContent()
        if (content) {
          const b = content.querySelector('b')
          if (b) {
            b.textContent = Swal.getTimerLeft()
          }
        }
      }, 100)
    },
    onClose: () => {
      clearInterval(timerInterval)
    }
  })

  $.ajax({
      url: site_url + "ajax/ajax.hotel_manage.php",
      type: 'POST',
      dataType: 'json',
      data: param,
      success: function(response){
        if(response['status'] == 'success'){
          $('.page_hotel_manager .room-current-amount[data-id="'+param.id+'"]').html(numb)
        }else{
            Swal.fire({
              position: 'top-center',
              icon: 'error',
              title: 'ผิดพลาดกรุณาลองใหม่',
              showConfirmButton: false,
              timer: 1500
            })
        }

      },
      error: function(error){
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'ผิดพลาดกรุณาลองใหม่',
          showConfirmButton: false,
          timer: 1500
        })
      }

    })
});

$(".page_hotel_manager").on('click','.decrease_room',function(){
  let param = {
    action: 'room_decreasing',
    id: $(this).data('id')
  }
  let timerInterval;
  Swal.fire({
    title: 'Decreasing!',
    html: 'กำลังลดจำนวนห้องพัก.',
    timer: 1500,
    timerProgressBar: true,
    onBeforeOpen: () => {
      Swal.showLoading()
      timerInterval = setInterval(() => {
        const content = Swal.getContent()
        if (content) {
          const b = content.querySelector('b')
          if (b) {
            b.textContent = Swal.getTimerLeft()
          }
        }
      }, 100)
    },
    onClose: () => {
      clearInterval(timerInterval)
    }
  })
  let numb =  $('.page_hotel_manager .room-current-amount[data-id="'+$(this).data('id')+'"]').html();
  numb = (Number(numb)) - 1
  $.ajax({
      url: site_url + "ajax/ajax.hotel_manage.php",
      type: 'POST',
      dataType: 'json',
      data: param,
      success: function(response){
          if(response['status'] == 'success'){
            $('.page_hotel_manager .room-current-amount[data-id="'+param.id+'"]').html(numb)
          }else{
            Swal.fire({
              position: 'top-center',
              icon: 'error',
              title: 'ผิดพลาดกรุณาลองใหม่',
              showConfirmButton: false,
              timer: 1500
            })
          }
      },
      error: function(error){
        Swal.fire({
          position: 'top-center',
          icon: 'error',
          title: 'ผิดพลาดกรุณาลองใหม่',
          showConfirmButton: false,
          timer: 1500
        })
      }

    })
});


