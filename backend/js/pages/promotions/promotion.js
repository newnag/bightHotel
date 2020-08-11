function reloadTable(){
    dataTableForm.ajax.reload(null, false);
}
$(function(){
    dataTableForm = $('#admin-grid').DataTable({    
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: site_url +"ajax/ajax.promotions.php",
            data: {action:"get_promotion"},
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
            [1, "asc"]
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

 
function editCategory(event,_id){
    let param = {
        action: 'prepare_edit',
        id: _id
    }
    $.ajax({
        url: site_url+"ajax/ajax.promotions.php",
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
// async function prepareEdit_category(response){
//         const { value: accept } = await Swal.fire({ 
//             customClass: {
//                 container: 'swal-cate-approve',
//                 header: 'my-header-style',
//             },
//             title: 'แก้ไขรีวิว', 
//             inputPlaceholder: 'Type of System',
//             showCancelButton: true,
//             confirmButtonText:'แก้ไข', 
//             cancelButtonText:'ยกเลิก', 
//             html: response['html'],
//             focusConfirm: false,
//             input: 'checkbox',
//             inputValue: 1,
//             inputValidator: (result) => { 
//                 if($(".page_promotions .txt_title").val().length < 1){
//                    return 'ระบุชื่อรีวิว'
//                 }
//                 let param = { 
//                     action:"update_promotion" 
//                    ,id: response['id'] 
//                    ,image: $(".page_promotions #add-images-content-hidden").val()
//                    ,title: $(".page_promotions .txt_title").val()
//                    ,desc: $(".page_promotions .txt_desc").val()
//                    ,priority: $(".page_promotions .txt_priority").val()
//                  }  
//                  swalUpdateNumbCategory(param);  
//             },   
//         }); 
// }

function swalUpdateNumbCategory(param){
    $.ajax({
        url: 'ajax/ajax.promotions.php',
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
    let details = $(".page_promotions .del_catenumb[data-id='"+_id+"']").data('name');
    Swal.fire({
        title: 'Are you sure?',
        text: "คุณต้องการลบรีวิว "+details+"!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยันการลบ!',
        cancelButtonText: 'ยกเลิก',
        
      }).then((result) => {
        if (result.value) {
            $.ajax({
                url: site_url + "ajax/ajax.promotions.php",
                type: "POST",
                dataType: 'json',
                data: { action: 'delete_promotion_by_id',id: _id },
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

$(".page_promotions").on("click",".btnPin .switch",function(event){
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
        url: site_url +"ajax/ajax.promotions.php",
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

$(".page_promotions ").on("change",'#add-images-content',function () { 
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
      url:  "ajax/ajax.promotions.php",
      type: 'POST',
      data: formdata,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (response) { 
        let thumbnail = '<div class="col-img-preview" id="col_img_preview_1" data-id="1"><img class="preview-img" id="preview_img_1" src="https://'+location.host+'/'+response[0]+'"></div>';
        $(".page_promotions .image-label").css("display","none")
        $(".page_promotions .blog-preview-add").html(thumbnail);
        $(".page_promotions #add-images-content-hidden").val(response[0]);
      }
    });
  }
 
  function prepare_promotion(){
    $.ajax({
        url: site_url+'ajax/ajax.promotions.php',
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

  
function add_promotion(param){
    Swal.fire({
        width: "700px",
        customClass: {
            popup: 'promotions',
            container: 'swal-category-numb',
            header: 'my-header-style' 
        },
        title: 'เพิ่มโปรโมชั่น',
        html: param['html'],
        showCancelButton: true,
        confirmButtonText: 'สร้างโปรโมชั่น',
        cancelButtonText: 'ยกเลิก',
        showLoaderOnConfirm: true,
        preConfirm: (_otp) => { 
            let myparam = { 
                action:"insert_promotion" 
                ,image: $(".page_promotions #add-images-content-hidden").val()
                ,name: $(".page_promotions .txt_title").val()
                ,desc: $(".page_promotions .txt_desc").val()
                ,discount: $(".page_promotions .txt_discount").val()
                ,amount: $(".page_promotions .txt_quota").val()
                ,available: $(".page_promotions .txt_datestart").val()
                ,expire: $(".page_promotions .txt_dateend").val()
                ,room_id: $(".page_promotions #room_promotion").val()
                ,status: $(".page_promotions #promotion").val()
            }  
            return myparam;
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => { 
        if(result.value){
           swalUpdateNumbCategory(result.value); 
        }
    });
}

function prepareEdit_category(param){
    Swal.fire({
        width: "700px",
        customClass: {
            popup: 'promotions',
            container: 'swal-cate-approve',
            header: 'my-header-style',
        },
        title: 'แก้ไขโปรโมชั่น', 
        html: param['html'],
        showCancelButton: true,
        confirmButtonText: 'บันทึก',
        cancelButtonText: 'ยกเลิก',
        showLoaderOnConfirm: true,
        onOpen: (toast) => {
             $(".txt_datestart").val(param['available'])
             $(".txt_dateend").val(param['expire'])
          },
        preConfirm: (_otp) => { 
            let myparam = { 
                action: "update_promotion" 
                ,id: param['pro_id']
                ,image: $(".page_promotions #add-images-content-hidden").val()
                ,name: $(".page_promotions .txt_title").val()
                ,desc: $(".page_promotions .txt_desc").val()
                ,discount: $(".page_promotions .txt_discount").val()
                ,amount: $(".page_promotions .txt_quota").val()
                ,available: $(".page_promotions .txt_datestart").val()
                ,expire: $(".page_promotions .txt_dateend").val()
                ,room_id: $(".page_promotions #room_promotion").val()
                ,status: $(".page_promotions #promotion").val()
            }  
        
        
            return myparam;
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => { 
        if(result.value){
            swalUpdateNumbCategory(result.value);   
        }
    
       }) 

}