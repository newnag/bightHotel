window.onload = ()=>{
    deleteHistory()
}

function deleteHistory(){
    let element = document.querySelectorAll('.history-page-zone .grid-item .item .table-body .delete')
    element.forEach(ele=>{
        ele.addEventListener('click',(event)=>{
            event.preventDefault()
            ele.closest('.item').classList.add('hide')
            setTimeout(()=>{ele.closest('a').remove()},500)
        })
    })
}


confirm_payment_by_otp();
function confirm_payment_by_otp(){
    Swal.fire({
        customClass: {
            popup: 'history-popup',
        },
        width: '600px',
        title: 'ตรวจสอบการจอง',
        text: 'กรุณากรอกเลขบัตรประชาชน 4 หลักสุดท้ายของท่าน',
        input: 'text',
        inputPlaceholder: 'รหัส 4 หลัก',
        inputAttributes: {
          autocapitalize: 'off',
          maxlength: 4
        },
        showCancelButton: true,
        confirmButtonText: 'ตรวจสอบ',
        cancelButtonText: 'ยกเลิก',
        showLoaderOnConfirm: true,
        preConfirm: (_otp) => {
            return $.ajax({
                url: hostname+'api/myapi.php',
                type: 'POST',
                dataType: 'json',
                data: { action:"check_otp",otp: _otp, tel: $(".reservation_search").val() },
                success: function(response){
                    if(response['status'] === "success"){
                        return response
                    }else{
                        Swal.showValidationMessage(
                           response['message']
                        ) 
                    }
                },
                error: function(_error){
                    Swal.showValidationMessage(
                        `ร้องขอไม่สำเร็จ กรุณาติดต่อลองใหม่อีกครั้ง`
                    )
                }
            })
         
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        if(result.value){
             
            $(".history-page-zone .grid-item").html(result.value.html)
             const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            onOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })
          Toast.fire({
            icon: 'success',
            title: 'ยืนยันตนสำเร็จ'
          });
          
        }else{
            location.href = hostname;
        }

    })
}

 
$(".history-page").on("click",".confirm_payment",function(){
    $.ajax({
        url: hostname+"api/myapi.php",
        type: "POST",
        dataType: "json",
        data: {action: "update_reserve_payment", id: $(this).data('id') },
        success: function(response){
            if(response['status'] == "success"){
                location.href = response['path'];
            }else{
                Swal.fire({
                    width: '400px',
                    text: 'การจองล้มเหลว!',
                    icon: 'error',
                    confirmButtonText: 'ตกลง'
                });
                return false;
            }
        },
        error: function(error){

        }
    }); 
});

