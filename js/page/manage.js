$(".form-contact").on('click',"button",function(){
   
    let param = validate_contact_data(); 
    if(param){
        $.ajax({
            url: hostname+'api/myapi.php',
            type: 'POST',
            dataType: 'json',
            data: param,
            success: function(response){
                    console.log(response)
            },
            error: function(_error){
                console.log('error');
            }
        }); 
    }
  
});
 

function validate_contact_data(){
    let param = {
        action: 'submit_contact', 
        name: $(".form-contact .txt_name").val(),
        lastname: $(".form-contact .txt_lastname").val(),
        tel: $(".form-contact .txt_tel").val(),
        email: $(".form-contact .txt_email").val(),
        subject: $(".form-contact .txt_subject").val(),
        message: $(".form-contact .txt_message").val(),
        token: ''
    }
 
    if(param.name.length < 1 ){
        console.log("required name");
        $(".form-contact .txt_name").addClass('error')
    } else{
        $(".form-contact .txt_name").removeClass('error')
    }  
    if(param.lastname.length < 1 ){
        console.log("required lastname");
        $(".form-contact .txt_lastname").addClass('error')
    } else{
        $(".form-contact .txt_lastname").removeClass('error')
    }  
    if(param.tel.length !==  10 ){
        console.log("required tel");
        $(".form-contact .txt_tel").addClass('error')
    } else{
        $(".form-contact .txt_tel").removeClass('error')
    }  
    if(!validateEmail(param.email)){
        console.log("required email");
        $(".form-contact .txt_email").addClass('error')
    }else{
        $(".form-contact .txt_email").removeClass('error')
    }

    if(param.subject.length < 1 ){
        console.log("required subject");
        $(".form-contact .txt_subject").addClass('error')
    } else{
        $(".form-contact .txt_subject").removeClass('error')
    }  

    if(param.message.length < 1 ){
        console.log("required message");
        $(".form-contact .txt_message").addClass('error')
    } else{
        $(".form-contact .txt_message").removeClass('error')
    }
    
    if(  $(".form-contact input").hasClass('error')){
        console.log('contact is required ')
        return false;
    }
    if( $(".form-contact  textarea").hasClass('error')){
        console.log('message is required')
        return false;
    }

    return param;
 
}

mytest();
function mytest(){
    $(".txt_name").val('firstname');
    $(".txt_lastname").val('lastname');
    $(".txt_email").val('wynn@sf.sd');
    $(".txt_tel").val('0989999999');
    $(".txt_subject").val('ทดสอบหัวเรื่อง');
    $(".txt_message").val('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat '); 
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}