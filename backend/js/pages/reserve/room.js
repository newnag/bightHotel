function reloadTable(){
    dataTableForm.ajax.reload(null, false);
}
$(function(){
    dataTableForm = $('#admin-grid').DataTable({    
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: site_url +"ajax/ajax.reserve_room.php",
            data: function(d){   
                d.action = "get_reserveroom";
                d.status = $("#slc_status_order").val();
                d.datein = $(".date-checkin").val();
            }, 
            type: "post",
            error: function(){					
              $(".employee-grid-error").html("");
              $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none"); 
            } 
        }, 
        "columnDefs": [{ 
            targets: [2],
            orderable: false,
        }], 
        "order": [
            [0, "desc"]
        ],
        // "columns": [
        //     { "width": "10%", "targets": 0 },
        //     { "width": "40%", "targets": 1 },
        //     { "width": "20%", "targets": 2 },
        //     { "width": "10%", "targets": 3 },
        //     { "width": "20%", "targets": 4 },
        //   ],
        "pageLength": 50,
    });   
});

$(".reviewspage").on('change','.date-checkin',function(){
    reloadTable();
});
 
$(".reviewspage").on('change','#slc_status_order',function(){
    reloadTable();
});
 
function confirmpayment(event,_id){
    let param = {
        action: 'prepare_edit',
        id: _id
    }
    $.ajax({
        url: site_url+"ajax/ajax.reserve_room.php",
        type: 'POST',
        dataType: 'json',
        data: { action: 'prepare_edit',
                id: _id },
        success: function(response){
            prepareEdit_category(response);

            let proStart = $('.txt_datestart').flatpickr({
                enableTime: true,
                dateFormat: "d-m-Y H:i",
                disableMobile: "true",
                minDate: "today",
                onChange: function(dateStr){
                    ProEnd.set('minDate',(dateStr[0].fp_incr(1)))
                }
            });
            let ProEnd = $('.txt_dateend').flatpickr({
                enableTime: true,
                minDate: new Date().fp_incr(1),
                dateFormat: "d-m-Y H:i",
                disableMobile: "true",
            });
        }
    });
}
 

function swalUpdateNumbCategory(param){
    $.ajax({
        url: 'ajax/ajax.reserve_room.php',
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
           
            if(response['status'] == "success"){
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'ทำรายการสำเร็จ',
                    showConfirmButton: false,
                    timer: 1000
                  })
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'ทำรายการไม่สำเร็จ',
                    showConfirmButton: false,
                    timer: 1000
                  })
            }

           reloadTable();

        },
        error: function(){
            console.log('error')
        }
    })
}
function delReviews(event,_id){
    let details = $(".page_reserve_room .del_catenumb[data-id='"+_id+"']").data('name');
    Swal.fire({
        title: 'Are you sure?',
        text: "คุณต้องการลบการจอง: "+details+"!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยันการลบ!',
        cancelButtonText: 'ยกเลิก',
        
      }).then((result) => {
        if (result.value) {
            $.ajax({
                url: site_url + "ajax/ajax.reserve_room.php",
                type: "POST",
                dataType: 'json',
                data: { action: 'delete_order_by_id',id: _id },
                success: function(response){
                    if(response['message'] == "OK"){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'ลบรายการสำเร็จ',
                            showConfirmButton: false,
                            timer: 1000
                          })
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'ลบรายการไม่สำเร็จ',
                            showConfirmButton: false,
                            timer: 1000
                          })
                    }
                   reloadTable();
                }
            })
        }
      }) 
}   

//Toggle Switch
$('.switch').on('click', (event) => {
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('.reviewspage').val('yes')
    } else {
        $('.reviewspage').val('no')
    }
}) 

$(".page_reserve_room").on("click",".btnPin .switch",function(event){
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('.reviews_status').val('yes')
        _pin = 'yes';
    } else {
        $('.reviews_status').val('no')
        _pin = 'no';
    }
    let _id = $(this).data('id');
    switchPin_promotion(_id,_pin);
})

function switchPin_promotion(_id,_pin){
    let param = { 
        action: 'update_pin_promotion',
        id: _id,
        pin: _pin
    }
    $.ajax({
        url: site_url +"ajax/ajax.reserve_room.php",
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
            Swal.fire({ 
                title: 'Waiting!',
                timer: 700,
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
            });
        }
    });
}

$(".page_reserve_room ").on("change",'#add-images-content',function () { 
    let file = this.files[0];
    if (file.length !== 0) { 
      var img = file.name;
      $('#add-images-content-hidden').val(img);
      $(".form-add-images").removeClass("has-error");
      $(".add-images-error").css("display", "none"); 
      editPreviewImage(file,'uploadImage_promotion');
    }
});

function editPreviewImage(file,action) {
    let formdata = new FormData();
    formdata.append("action", action);
    formdata.append("images[]", file);
    $.ajax({
      url:  "ajax/ajax.reserve_room.php",
      type: 'POST',
      data: formdata,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (response) { 
        let thumbnail = '<div class="col-img-preview" id="col_img_preview_1" data-id="1"><img class="preview-img" id="preview_img_1" src="https://'+location.host+'/'+response[0]+'"></div>';
        $(".page_reserve_room .image-label").css("display","none")
        $(".page_reserve_room .blog-preview-add").html(thumbnail);
        $(".page_reserve_room #add-images-content-hidden").val(response[0]);
      }
    });
  }
 
  function prepare_promotion(){
    $.ajax({
        url: site_url+'ajax/ajax.reserve_room.php',
        type: 'POST',
        dataType: 'json',
        data: {action: 'prepare__add_promotion' },
        success: function(response){
            add_promotion(response);
            
            let proStart = $('.txt_datestart').flatpickr({
                enableTime: true,
                dateFormat: "d-m-Y H:i",
                disableMobile: "true",
                minDate: "today",
                onChange: function(dateStr){
                    ProEnd.set('minDate',(dateStr[0].fp_incr(1)))
                }
            });
            let ProEnd = $('.txt_dateend').flatpickr({
                enableTime: true,
                minDate: new Date().fp_incr(1),
                dateFormat: "d-m-Y H:i",
                disableMobile: "true",
            });
        }
    });
  }

  
function prepareEdit_category(param){
    Swal.fire({
        width: "90%",
        customClass: {
            popup: 'promotions',
            container: 'swal-cate-approve',
            header: 'my-header-style',
        },
        title: param['txt_title'], 
        html: param['html'],
        showConfirmButton: param['approve'],
        showCancelButton: true,
        confirmButtonText: param['txt_confirm'],
        cancelButtonText: 'ปิด',
        showLoaderOnConfirm: true,
        onOpen: (toast) => {
             $(".txt_datestart").val(param['available'])
             $(".txt_dateend").val(param['expire'])
          },
        preConfirm: (_otp) => { 
            Swal.fire({
                title: 'ยืนยัน?',
                text: "อนุมัติการจองห้องพัก!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'อนุมัติ',
                cancelButtonText: 'ยกเลิก'
              }).then((result) => {
                if (result.value) {
                    let myparam = { 
                        action:"update_order" 
                        ,code: param['code']
                    }  
                    approve_room(myparam); 
                }
              })
        },
        allowOutsideClick: () => !Swal.isLoading()
      })

}

function approve_room(param){
        $.ajax({    
            url: site_url + "ajax/ajax.reserve_room.php",
            type: 'POST',
            dataType: 'json',
            data: param,
            success: function(response){
                console.log(response)
            },
            error: function(error){
                console.log('error')
            }
        })

}

$('.page_reserve_room').on('click','.promotions .modal-detail .payment-image img',function(){
    $('.full-payment-image').show()
});
$('.page_reserve_room').on('click','.full-payment-image .close',function(){
    $('.full-payment-image').hide()
});

$('.date-checkin').flatpickr({
    dateFormat: "d-m-Y",
    disableMobile: "true",
})