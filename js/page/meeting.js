$(".meeting-page-zone .detial").on("click","button",function(){
    let param = {
        action: "require_meeting_room",
        name: $("txt_name").val(),
        email:$("txt_email").val(),
        tel:$("txt_tel").val(),
        subject:$("txt_subject").val(),
        message:$("txt_message").val()
    }
    $.ajax({
        url: hostname+'api/myapi.php',
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
            console.log(response);
        },
        error: function(error){

        }
    });


});