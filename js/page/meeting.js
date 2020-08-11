$(".meeting-page-zone .detial").on("click","button",function(){
    let param = {
        action: "require_meeting_room",
        name: $(".meeting-page-zone .txt_name").val(),
        email:$(".meeting-page-zone .txt_email").val(),
        tel:$(".meeting-page-zone .txt_tel").val(),
        subject:$(".meeting-page-zone .txt_subject").val(),
        message:$(".meeting-page-zone .txt_message").val()

    }

    Swal.fire({
        width: '400px',
        title: 'ส่งข้อความ!',
        text: "ตรวจสอบข้อการส่งแล้วหรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
          if(result.value){
            $.ajax({
                url: hostname+'api/myapi.php',
                type: 'POST',
                dataType: 'json',
                data: param,
                success: function(response){
                    if(response['status'] == "success"){ 
                    Swal.fire({
                        width: '400px',
                        text: 'ส่งข้อความเรียบร้อยแล้ว!',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        location.reload();
                    });
                   }else{
                    Swal.fire({
                        width: '400px',
                        text: 'ส่งข้อความไม่สำเร็จ!',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        location.reload();
                    });
                   }
                },
                error: function(error){
                    console.log(error)
                }
            });  
          }
      });
    
 


});