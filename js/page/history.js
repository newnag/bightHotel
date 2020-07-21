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
        width: '600px',
        title: 'ตรวจสอบการจอง',
        text: 'กรุณากรอกเลขบัตรประชาชน 4 หลักสุดท้ายของท่าน',
        input: 'text',
        inputPlaceholder: 'รหัส 4 หลัก',
        inputAttributes: {
          autocapitalize: 'off'
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
                data: { action:"check_otp",otp: _otp, tel: '0123456789' },
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
            get_history(result.value.id)
            $(".history-page-zone .grid-item").html(result.value.html)
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
            title: 'ยืนยันตนสำเร็จ'
          });
          
        }else{
            location.href = hostname;
        }

    })
}

function get_history(_id){
//    $.ajax({
//         url: hostname+'api/myapi.php',
//         type: 'post',
//         dataType: 'json',
//         data: {action: 'get_all_history', code: _id },
//         success: function(response){
//             console.log(response)
//         },
//         error: function(){

//         }
//    });
}
