$(".list-room").on('click','.virwFull',function(){
    let room = $(this).data('room');
    let param = {
        room,
        action: 'get_more_room_detail'
    }
    document.querySelector(".dialog-fullview .inner-dialog .nameroom h2").innerHTML = "";   
    document.querySelector(".dialog-fullview .inner-dialog .price h2").innerHTML = "";
    document.querySelector(".dialog-fullview .inner-dialog .description p").innerHTML = "";
    document.querySelector(".dialog-fullview .inner-dialog .facilities .inroom").innerHTML = "";
    document.querySelector(".dialog-fullview .inner-dialog .carousel .list-img").innerHTML = "";
    document.querySelector(".dialog-fullview .inner-dialog .img-bigbox figure img").src = "";

    $.ajax({
        url: hostname+'api/myapi.php',
        type: 'POST',
        dataType: 'json',
        data: param ,
        success: function(response){
            if(response['message'] == "success"){
                document.querySelector(".dialog-fullview .inner-dialog .nameroom h2").innerHTML = response['type_name'];
                document.querySelector(".dialog-fullview .inner-dialog .price h2").innerHTML = response['curprice'];
                document.querySelector(".dialog-fullview .inner-dialog .description p").innerHTML = response['description'];
                document.querySelector(".dialog-fullview .inner-dialog .facilities .inroom").innerHTML = response['facility'];
                document.querySelector(".dialog-fullview .inner-dialog .carousel .list-img").innerHTML = response['images'];
                document.querySelector(".dialog-fullview .inner-dialog .img-bigbox figure img").src = response['thumbnail'];
                clickImgDialogChangeURL();
                openDialogRoom();
                DragImg('.dialog-fullview .inner-dialog .carousel .list-img')
            }
        },
        error: function(res){
            console.log('error')
        }
    }); 
});

$(".room-page").on('click','.plus',function(){
    console.log('increase room');
    let _id = $(this).closest(".list-item").data('id');
    reserve_by_room_id(_id);
});
$(".room-page ").on('click','.minus',function(){
    console.log('decrease room');
    let _id = $(this).closest(".list-item").data('id');
    $.ajax({
        url: hostname+'api/myapi.php',
        type: 'POST',
        dataType: 'json',
        data: { action: "decrease_room", id: _id} ,
        success: function(response){
            if(response['html'] == null){
                $(".detail-order .detail-list").css("display","none");
            }else{
                $(".detail-order .detail-list").css("display","block");
                $(".detail-order .detail-list").html(response['html']); 

                let result = response['cart']['result'];
                reset_cart_detail(result);
            }
        },
        error: function(_error){
            console.log('error')
        }
    })
});
 
$(".room-page-zone .list-room").on('click','.btn_reserve',function(){
    reserve_by_room_id($(this).data('room'));
});
$(".room-page-zone .detail-order").on('click','button',function(){
    if($(this).hasClass("let-reserve")){
        location.href = hostname+"ยืนยันการจอง";
    }else{
        Swal.fire({
            width: '400px',
            text: 'ท่านยังไม่ได้เลือกห้องพัก!',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
});

function reserve_by_room_id(_id){
    let date_in = document.querySelector(".room-page .detail-order .dateCheck.checkIn").value;
    let date_out = document.querySelector(".room-page .detail-order .dateCheck.checkOut").value; 
    let adult = document.querySelector(".booking #adult").value; 
    let child = document.querySelector(".booking #child").value; 
    if(date_in ===""){
         var element = document.getElementById("input_checkin");     
         //element.scrollIntoView();
         $(".room-page .detail-order .dateCheck.checkIn").addClass("input-invalid");
         Swal.fire({
             width: '400px',
             text: 'กรุณากำหนดวันที่เข้าพัก!',
             icon: 'error',
             confirmButtonText: 'OK'
         });
         return false;
 
    }else if(date_out ===""){
         var element = document.getElementById("input_checkout");
         //element.scrollIntoView();
         $(".room-page .detail-order .dateCheck.checkOut").addClass("input-invalid");
         Swal.fire({
             width: '400px',
             text: 'กรุณากำหนดวันที่ออกจากที่พัก!',
             icon: 'error',
             confirmButtonText: 'OK'
         })
         return false;
    } else {
  
     document.querySelector(".room-page .detail-order .dateCheck").classList.remove('input-invalid');
     let param = {
         action: "check_room_available_on_date",
         room: _id,
         date_in,
         date_out,
         adult,
         child
     }
     $.ajax({
         url: hostname+'api/myapi.php',
         type: 'POST',
         dataType: 'json',
         data: param, 
         success: function(response){

            if(response['message'] === 'error'){
                 Swal.fire({
                     width: '400px',
                     text: 'ขออภัยห้องพักเต็มแล้วค่ะ!',
                     icon: 'error',
                     confirmButtonText: 'OK'
                 })
            }else{
               $(".detail-order .booking-button button").addClass('let-reserve');
               let result = response['cart']['result'];
               reset_cart_detail(result);
               $(".detail-order .detail-list").css("display","block");
               $(".detail-order .detail-list").html(response['html']); 
               const Toast = Swal.mixin({
                   toast: true,
                   position: 'top-end',
                   showConfirmButton: false,
                   timer: 3000,
                   timerProgressBar: true,
                   onOpen: (toast) => {
                     toast.addEventListener('mouseenter', Swal.stopTimer)
                     toast.addEventListener('mouseleave', Swal.resumeTimer)
                   }
                }) 
                Toast.fire({
                   icon: 'success',
                   title: 'เพิ่มห้องสำเร็จแล้ว'
                })
            }
         }
     });
    }
}

$(".room-page .detail-order").on('change','.dateCheck',function(){
    if($(this).val() !== ""){
        $(this).removeClass('input-invalid');
    }
});

$(".booking .button").on("click","button",function(){
    let datein = $(".booking .formBook .header_checkin").val();
    let dateout = $(".booking .formBook .header_checkout").val();
    if(datein === ""){
        Swal.fire({
            width: '400px',
            text: 'กรุณากำหนดวันที่เข้าพัก!',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return false;
    }
    if(dateout === ""){
        Swal.fire({
            width: '400px',
            text: 'กรุณากำหนดวันที่ออกจากที่พัก!',
            icon: 'error',
            confirmButtonText: 'OK'
        })
        return false;
    }
    
    location.href = hostname+"ห้อง/ห้องพัก/"+datein+"/"+dateout;
}); 


// $(function(){
//     $.ajax({
//         url: hostname+"api/myapi.php",
//         type: 'POST',
//         dataType: 'json',
//         data: { action: 'test_function' },
//         success: function(response){
//             $(".detail-order .detail-list").html(response['html']);
//         },
//         error: function(err){
//             console.log('errror')
//         }
//     });
// });
    
$('.detail-order').on('click',".delete",function(){
    let param = {
       action: 'reserve_remove_room_id',
       id: $(this).data('id') 
    }
    $.ajax({
        url: hostname+"api/myapi.php",
        type: 'POST',
        dataType: 'json', 
        data: param,
        success: function(response){
            if(response['cart']){
                if(response['cart']['result']['amount'] == 0 ){
                    $(".detail-order .booking-button button").removeClass('let-reserve');
                }
            }

            let result = response['cart']['result'];
            reset_cart_detail(result);
            remove_by_room_id(response['id']);
        },
        error: function(_err){

        }
    });
});

function remove_by_room_id(_id){
    $(".list-item[data-id='"+_id+"']").remove();
}

$(".detail-order .discount").on('click',"button",function(){
    set_discount();
});

function set_discount(){
    let code = $(".discount input").val();
    if(code.length > 5){
        let param = {
            action: 'discount_code',
            code
        }
        $.ajax({
            url: hostname+"api/myapi.php",
            type: 'POST',
            dataType: 'json',
            data: param,
            success: function(response){
                let result = response['cart']['result'];
                 reset_cart_detail(result); 
                if(response['message'] === "available"){
                    Swal.fire({
                        width: '400px',
                        title: 'ใช้ส่วนลดแล้ว',
                        text: response['text'],
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }else{
                    Swal.fire({
                        width: '400px',
                        title: 'ไม่สามารถใช้ได้',
                        text: 'กรุณาตรวจสอบข้อมูลคูปอง และเงื่อนไขการใช้งาน!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            },
            error :function(error){
                console.log('error')
            }
        });
    }
}

function reset_cart_detail(result){
    $(".detail-order .room-price").html(result['price']);
    $(".detail-order .room-amount").html(result['amount']);
    $(".detail-order .room-vat").html(result['extra']);
    $(".detail-order .room-discount").html(result['discount']);
    $(".detail-order .room-netpay").html(result['netpay']); 
}


$(".bookingroom-page .box-bookingRoom .bookingRoom .form-grid .adult").on('click','.right',function(){
    let param = {
        action: 'increase_decrease_room',
        position: $(this).closest(".bookingRoom").data('position'),
        room: $(this).closest(".bookingRoom").data('room'),
        function: 'increase'
    }
    increaseAndDecreaseAdult(param);
});

$(".bookingroom-page .box-bookingRoom .bookingRoom .form-grid .adult").on('click','.left',function(){
    let param = {
        action: 'increase_decrease_room',
        position: $(this).closest(".bookingRoom").data('position'),
        room: $(this).closest(".bookingRoom").data('room'),
        function: 'decrease'
    }
    increaseAndDecreaseAdult(param);
});

 function increaseAndDecreaseAdult(_param){ 
    $.ajax({
        url: hostname+'api/myapi.php',
        type: 'POST',
        dataType: 'json',
        data: _param,
        success: function(response){
            console.log(response)
            let result = response['cart']['result'];
            reset_cart_detail(result);
            
        },
        error: function(){
            console.log('error')
        }
    });
}


$(".bookingroom-page .box-bookingRoom .bookingRoom .form-grid .child").on('click','.right',function(){
    let param = {
        action: 'increase_decrease_children',
        position: $(this).closest(".bookingRoom").data('position'),
        room: $(this).closest(".bookingRoom").data('room'),
        function: 'increase'
    }
    increaseAndDecreaseChild(param);
});

$(".bookingroom-page .box-bookingRoom .bookingRoom .form-grid .child").on('click','.left',function(){
    let param = {
        action: 'increase_decrease_children',
        position: $(this).closest(".bookingRoom").data('position'),
        room: $(this).closest(".bookingRoom").data('room'),
        function: 'decrease'
    }
    increaseAndDecreaseChild(param);    
});

function increaseAndDecreaseChild(_param){ 
    $.ajax({
        url: hostname+'api/myapi.php',
        type: 'POST',
        dataType: 'json',
        data: _param,
        success: function(response){
         
        },
        error: function(){
            console.log('error')
        }
    });
}

$(".bookingroom-page .booking").on("click","button",function(){
    let list = [];
    $.each($(".bookingroom-page .bookingRoom"), function(index, val){
        let list_arr = []; 
        let id = $(this).data('room');
        let position = $(this).data('position');
        let name = $(".bookingRoom[data-room='"+id+"'][data-position='"+position+"'] .txt_guest_name").val();
        let lastname = $(".bookingRoom[data-room='"+id+"'][data-position='"+position+"'] .txt_guest_lastname").val();
        let adult = $(".bookingRoom[data-room='"+id+"'][data-position='"+position+"'] .inputAdult").val(); 
        let child = $(".bookingRoom[data-room='"+id+"'][data-position='"+position+"'] .inputChild").val();
        let extra = $(".bookingRoom[data-room='"+id+"'][data-position='"+position+"'] .breakfast-check").prop('checked');
         list_arr = { 
            id,
            position,
            name,
            lastname,
            adult,
            child,
            extra,  
        }
        list.push(list_arr);
    }); 
    
    let param = {
        action:'reservation_confirm',
        name: $(".info-person .txt_name").val(),
        lastname: $(".info-person .txt_lastname").val(),
        tel: $(".info-person .txt_tel").val(),
        email:$(".info-person .txt_email").val(),
        line: $(".info-person .txt_line").val(),
        code: $(".info-person .txt_code").val(),
        address:  $(".info-person .txt_address").val(),
        district: $(".info-person .txt_district").val(),
        subdistrict: $(".info-person .txt_subdistrict").val(),
        province:  $(".info-person .txt_province").val(),
        postcode: $(".info-person .txt_postcode").val(),
        message:  $(".info-person .txt_message").val(),
        token: $('#g-recaptcha-response').val(),
        invoice_name: $(".taxinvoice .txt_invoice").val(),
        invoice_checked:$(".taxinvoice #taxinvoice-check").prop('checked'),
        list
    }

    validate_confirm_reservation(param);
});


$(".box-bookingRoom .bookingRoom").on("keyup","input",function(){
    let leng = $(this).val().length;
    if(leng  < 2){
        $(this).addClass('fill-int');
        $(this).addClass('input-invalid');
    }else{
        $(this).removeClass('fill-int');
        $(this).removeClass('input-invalid');
    }
});

$(".info-person").on("keyup","input.input-invalid",function(){
    $(this).removeClass('input-invalid');
})

function validate_confirm_reservation(param){
    $(".box-bookingRoom .bookingRoom input.fill-int").addClass('input-invalid')
    $(".info-person input").removeClass('input-invalid');
    if(param.name.length < 1){ 
        $(".info-person .txt_name").addClass('input-invalid')
    }
    if(param.lastname.length < 1){ 
        $(".info-person .txt_lastname").addClass('input-invalid')
    }
    if(param.tel.length != 10){ 
        $(".info-person .txt_tel").addClass('input-invalid')
    }
    if(param.email.length < 1){ 
        $(".info-person .txt_email").addClass('input-invalid')
    }
    if(param.code.length != 4){ 
        $(".info-person .txt_code").addClass('input-invalid')
    }
    if(param.address.length < 1){ 
        $(".info-person .txt_address").addClass('input-invalid')
    }
    if(param.subdistrict.length < 1){ 
        $(".info-person .txt_subdistrict").addClass('input-invalid')
    }
    if(param.district.length < 1){ 
        $(".info-person .txt_district").addClass('input-invalid')
    }
    if(param.postcode.length != 5){ 
        $(".info-person .txt_postcode").addClass('input-invalid')
    }
    if(param.province.length < 1){ 
        $(".info-person .txt_province").addClass('input-invalid')
    }
    if(param.message.length < 1){ 
        $(".info-person .txt_message").addClass('input-invalid')
    }

    if( $(".box-bookingRoom .bookingRoom input").hasClass('fill-int')){
        Swal.fire({
            width: '400px',
            text: 'กรุณาระบุชื่อ-นามสกุลผู้เข้าพัก!',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        }).then((result) => {
            $('html, body').animate({
                scrollTop: $('.fill-int').offset().top - 150 
                }, 1200);	
        });
        return false;
    }

    if($(".info-person input").hasClass('input-invalid') ){ 
        Swal.fire({
            width: '400px',
            text: 'กรุณากรอกข้อมูลติดต่อให้ครบถ้วน!',
            icon: 'error',
            confirmButtonText: 'ตกลง',
        }).then((result) => {
                $('html, body').animate({
                    scrollTop: $('.input-invalid').offset().top- 250
                    }, 1200);	
        });
        return false;
    }


    Swal.fire({
        title: 'ยืนยันการจองห้องพัก!',
        text: "ตรวจสอบข้อมูลของท่านแล้วหรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
          console.log(param)
        if (result.value) {
            $.ajax({
                url: hostname+'api/myapi.php',
                type: 'POST',
                dataType: 'json',
                data: param,
                success: function(response){
                    if(response['message'] === "success"){
                        $(".bookingroom-page .bookingRoom[data-room='"+param.room+"'][data-position='"+param.position+"']").remove();
                        Swal.fire({
                            width: '400px',
                            title: 'จองห้องพักสำเร็จแล้ว!',
                            icon: 'success',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            window.location.href = response['page'];
                        });
                       }else{
                        Swal.fire({
                            width: '400px',
                            title: 'การจองล้มเหลว!',
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            window.location.href = response['page'];
                        });
                       }
                },
                error: function(){ 
                    console.log('error')
                }
            });
        }
      })
}

$(".bookingroom-page .bookingRoom").on("click",".remove-order",function(){
   
    let param = {
        action: "remove_order_by_room_position",
        position: $(this).closest(".bookingRoom").data('position'),
        room: $(this).closest(".bookingRoom").data('room') 
    }
    $.ajax({
        url: hostname+"api/myapi.php",
        type: "POST",
        dataType: "json",
        data: param,
        success: function(response){
            $(".detail-order .detail-list").css("display","block");
            $(".detail-order .detail-list").html(response['result']);
            let result = response['cart']['result'];
            reset_cart_detail(result);
           if(response['status'] === "success"){
                $(".bookingroom-page .bookingRoom[data-room='"+param.room+"'][data-position='"+param.position+"']").remove();
                Swal.fire({
                    width: '400px',
                    text: 'ลบออกจากตะกร้าแล้ว!',
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    console.log(response)
                    if(response['cart']['result']['amount'] == 0){
                        console.log('tse')
                        location.reload();
                    }
                })
           }else{
            Swal.fire({
                width: '400px',
                text: 'ไม่สำเร็จ!',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            }); 
           }
        },
        error: function(error){
            console.log("_error")
        }
    });
}); 
 

$(".bookingroom-page-zone").on("click",".cancle-button",function(){
    Swal.fire({
        title: 'ยกเลิกการจอง?',
        text: "ต้องการยกเลิกการจองห้องพักหรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.value) {
            $.ajax({
                url: hostname+"api/myapi.php",
                type: 'POST',
                dataType: 'json',
                data: { action: "reservation_cancel",order_id: $(this).data('id')},
                success: function(response){
                    if(response['message'] == "OK"){
                        Swal.fire({
                            width: '400px',
                            text: 'ยกเลิกการจองแล้ว!',
                            icon: 'success',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            location.reload();
                        });
                    }else{
                        Swal.fire({
                            width: '400px',
                            text: 'ไม่สำเร็จ ไม่พบเลขที่การจอง!',
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            location.reload();
                        });
                    }
                },
                error: function(error){

                }
            })
        }
      })
}); 

$(".bookingroom-page-zone").on("click",".buttonFinalPay",function(){

    let param = {
        action: 'upload_payment',
        name: $(".txt_name").val(),
        price : $('.txt_price').val(),
        date: $(".date-box .dateCheck").val(),
        bank: $(".input-box #slc_bank").val(),
        image: $("#add-images-hidden").val(),
        id: $(this).data('id'),
        token: $('#g-recaptcha-response').val()
    }

    //validate input form

    if(param.name == ''){
        Swal.fire({
            icon: 'info',
            title: 'กรุณากรอก ชื่อผู้โอน'
        });
        return;
    }

    if(param.price == ''){
        Swal.fire({
            icon: 'info',
            title: 'กรุณากรอก จำนวนเงิน'
        });
        return;
    }

    if(param.date == ''){
        Swal.fire({
            icon: 'info',
            title: 'กรุณากรอก วัน/เวลา'
        });
        return;
    }

    if(param.image == ''){
        Swal.fire({
            icon: 'info',
            title: 'กรุณาอัพโหลดสลิป'
        });
        return;
    }
    
    Swal.fire({
        popup: 'title-style',
        title: 'ยืนยันการชำระเงิน?',
        text: "ท่านได้ตรวจสอบความถูกต้องแล้วหรือไม่?",
        icon: 'warning',
        width: "500px",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'

      }).then((result) => {
        if (result.value) {
             confirm_payment_action(param);
        }
      })
});

function confirm_payment_action(param){
    
    $.ajax({
        url: hostname + "api/myapi.php",
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
            if(response['message'] == "OK"){    
                Swal.fire({
                    width: '400px',
                    text: 'ยืนยันการชำระเงินเรียบร้อย!',
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                }).then((result) =>{
                    if(result){
                        location.href = hostname;
                    }
                })
            } else { 
                Swal.fire({
                    width: '400px',
                    text: 'ยืนยันการชำระเงินไม่สำเร็จ!',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                }).then((result) =>{
                    if(result){
                        location.href = hostname;
                    }
                })
            }



        },
        error: function(error){

        }

    });
}

$(".bookingroom-page").on("change",'.right-box #slip-upload',function () { 
    let file = this.files[0];
    if (file.length !== 0) { 
    //   var img = file.name;
    //   $('#add-images-hidden').val(img);
      update_payment_img(file,'uploadImage_payment');
    }
});

function update_payment_img(file,action) {
    let formdata = new FormData();
    formdata.append("action", action);
    formdata.append("images[]", file);
    $.ajax({
      url:  hostname+"api/myapi.php",
      type: 'POST',
      data: formdata,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (response) { 
          console.log(response)
        $("#add-images-hidden").val(response['image']);
        $('.review').addClass('addimg')
        $('.review img').attr('src',response['src']);
      },
      error: function(error){
        console.log('error')
      }
    });
  }
 

$(".room-page").on('change',"#input_checkin",function(){
    set_dateInOut();
});

$(".room-page").on('change',"#input_checkout",function(){
    set_dateInOut();
});

function set_dateInOut(){
    let datein = $("#input_checkin").val();
    let dateout = $("#input_checkout").val();
    if(datein != ""&& dateout != ""){
        let path = hostname+"ห้อง/ห้องพัก/"+datein+"/"+dateout;
        location.href= path;    
    }   
}